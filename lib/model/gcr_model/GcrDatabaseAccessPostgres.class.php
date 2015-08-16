<?php
// GcrDatabaseAccessPostgres class.
// Created by Ron Stewart 3/9/2011

// This class houses the schema specific database access 
// functions we use for access to schemas other than global
// (access to mdl_ and mhr_ tables). Eschool.class.php and
// Institution.class.php currently use these functions.

class GcrDatabaseAccessPostgres
{
    public static function beginTransaction()
    {
        $conn = self::getConnection();
        $conn->beginTransaction();
    }
    public static function commitTransaction()
    {
        $conn = self::getConnection();
        $conn->commit();
    }
    public static function getConnectionCountRatio()
    {
        $conn = self::getConnection();
        $sql = 'show max_connections';//
        $result = $conn->fetchAssoc($sql);
        $max_connections = $result[0]['max_connections'];
        $sql = 'select count(*) from pg_stat_activity';
        $result = $conn->fetchAssoc($sql);
        $current_connections = $result[0]['count'];
        return ($current_connections / $max_connections);
    }
    public static function createSchema($caller)
    {
        $moodledataDir = gcr::moodledataDir;
        $dbName = gcr::DBName;
        $schemaType = $caller->getAppType();
        $templateSchema = $schemaType->getTemplate();
        $schemaName = $caller->getShortName();
        $schemaDumpDir = gcr::moodledataDir . $schemaName . '/';

        try
        {
            if (!$schemaType->getTemplateObject())
            {
                throw new Exception("Template schema not found in global table!");
            }
            else if (!GcrEschoolTable::isShortNameValid($templateSchema))
            {
                throw new Exception("Template schema short_name is invalid!");
            }
            else if (!GcrEschoolTable::isShortNameValid($schemaName))
            {
                throw new Exception("New schema short_name is invalid!");
            }
            $conn = self::getConnection();

            // create a new database user for this eschool
            $conn->execute('create user gc4' . $schemaName . 'admin with password \'' . $caller->getAdminPassword() . '\'');
            // create the moodledata directory for the new eschool
            exec("mkdir {$moodledataDir}{$schemaName}");
            exec("cp -r {$moodledataDir}{$templateSchema}/* {$moodledataDir}{$schemaName}");
            exec("mkdir {$moodledataDir}{$schemaName}/gc_images");

            // Look for an existing template dump file to use
            if (file_exists(gcr::templateDumpDir . "$templateSchema.dump"))
            {
                // use sed to modify all self references in template dump to the new schema name
                exec("sed 's/{$templateSchema}/{$schemaName}/g' " . gcr::templateDumpDir . "$templateSchema.dump > {$schemaDumpDir}{$schemaName}.dump");
            }
            else // Make a new dump of the template and use that
            {
                // make a schema dump of the template used for the new eschool
                exec("pg_dump -U " . gcr::globalDBAdminName . " -h " . gcr::DBHostName . " -p " . gcr::DBPort . " --schema={$templateSchema} {$dbName} > {$schemaDumpDir}{$templateSchema}.dump");
                // use sed to modify all self references in template dump to the new schema name
                exec("sed 's/{$templateSchema}/{$schemaName}/g' {$schemaDumpDir}{$templateSchema}.dump > {$schemaDumpDir}{$schemaName}.dump");
            }
            // restore the modified dump in to a brand new schema which will act as the new eschool's database
            exec("psql -U " . gcr::globalDBAdminName . " -h " . gcr::DBHostName . " -p " . gcr::DBPort . " -f {$schemaDumpDir}{$schemaName}.dump {$dbName}");
            // delete the dump files
            exec("rm {$schemaDumpDir}{$schemaName}.dump");
            if (file_exists("{$schemaDumpDir}{$templateSchema}.dump"))
            {
                exec("rm {$schemaDumpDir}{$templateSchema}.dump");
            }
            // set the new database user's search path to the eschool's schema
            $conn->execute('ALTER USER gc4' . $schemaName . 'admin SET search_path TO ' . $schemaName . ',pg_catalog');
            if (preg_match('/^[a-zA-Z0-9\.]+$/', $caller->getLogo()))
            {
                if ($caller->isMoodle())
                {
                    $institutionMoodledataDir = gcr::moodledataDir . $caller->getInstitution()->getShortName() . '/';
                    exec("cp {$institutionMoodledataDir}gc_images/{$caller->getLogo()} {$schemaDumpDir}gc_images/{$caller->getLogo()}");
                }
                else
                {
                    exec("mv {$moodledataDir}{$caller->getLogo()} {$schemaDumpDir}gc_images/{$caller->getLogo()}");
                }
            }
        }
        catch (Exception $e)
        {
            global $CFG;
            $CFG->current_app->gcError("Schema creation of {$caller->getShortName()} error, {$e->getMessage()}", 'eschoolcreationfailed');
        }
    }
    public static function schemaExists($caller)
    {
        $sql = 'select exists (select * from pg_catalog.pg_namespace where nspname = ?) as created';
        $result = self::gcQuery($caller, $sql, array($caller->getShortName()), true);
        return $result->created;
    }
    public static function gcQuery($caller, $sql, $params = array(), $returnOneRecord = false, $failSilently = false)
    {
        try
        {
            $conn = self::getConnection();
            if ($returnOneRecord)
            {
                $sql .= ' limit 1';
            }
            if (!$resultSet = $conn->fetchAssoc($sql, $params))
            {
                return null;
            }
            foreach($resultSet as $record)
            {
                $objectArray[] = (object) $record;
            }
            if ($returnOneRecord)
            {
                return $objectArray[0];
            }
            return $objectArray;
        }
        catch (Exception $e)
        {
            global $CFG;
            $message = "SQL Query on " . get_class($caller)  . " {$caller->getShortName()} failed: $sql params: " .
                    implode(",", $params) . " Message: " . $e->getMessage();
            if ($failSilently)
            {
                $CFG->current_app->gcError($message);
            }
            else if ($conn->getTransactionLevel() > 0)
            {
                throw $e;
            }
            else
            {
                $CFG->current_app->gcError($message, 'gcdatabaseerror');
            }
            return -1;
        }
    }
    public static function countTableRecords($caller, $tableName)
    {
        $sql = 'select count (*) from ' . self::getTableName($caller, $tableName);
        $result = self::gcQuery($caller, $sql, array(), true);
        return $result->count;
    }
    public static function deleteFromTable($caller, $tableName, $columnName, $columnValue)
    {
        $sql = 'delete from ' . self::getTableName($caller, $tableName) . ' where ' . $columnName . ' = ?';
        self::gcQuery($caller, $sql, array($columnValue));
    }
    public static function deleteSchemaFromSystem($caller)
    {
        $conn = self::getConnection();
        $schema_name = $caller->getShortName();
        // make sure that shortname is not '' or '  ' which would result in deleting
        // the entire moodledata directory.
        if (!GcrEschoolTable::isShortNameValid($schema_name))
        {
            $CFG->current_app->gcError('Attempt to delete eschool with corrupt short_name', 'gcdatabaseerror');
            die();
        }
        try
        {
            $conn->execute('drop schema ' . $schema_name . ' cascade');
        }
        catch (Exception $e)
        {
            error_log("\n" . date('d/m/Y H:i:s', time()) . ": Delete schema {$schema_name} error, database schema not deleted", 3,
                    gcr::rootDir . 'debug/error.log');
        }
        try
        {
            $conn->execute('drop user gc4' . $schema_name . 'admin');
        }
        catch (Exception $e)
        {
            error_log("\n" . date('d/m/Y H:i:s', time()) . ": Delete schema {$schema_name} error, database user not deleted", 3,
                    gcr::rootDir . 'debug/error.log');
        }
    }
    public static function getConnection()
    {
        // get Symfony's persistent db connection
        if (!$conn = Doctrine_Manager::getInstance()->getCurrentConnection())
        {
                throw new Exception("Could not connect to Database.");
        }
        return $conn;
    }
    public static function getTableName($caller, $tableName)
    {
        return $caller->getShortName() . '.' . $caller->getDatabaseTablePrefix() . $tableName;
    }
    public static function insertIntoTable($caller, $tableName, $valueArray = array())
    {
        try
        {
            $params = array();
            $column_string = '';
            $string = '';
            $where_string = '';
            $table = self::getTableName($caller, $tableName);

            // Eschool::insertIntoMdlTable doesn't include the autonumber field because they all use
            // id (bigserial) as the pk. So, we insert it here.
            if ($caller->isMoodle())
            {
                $valueArray['id'] = gcr::autoNumber;
            }

            foreach ($valueArray as $column => $value)
            {
                $column_string .= $column . ",";
                $string .= '?,';
                $where_string .= "$column = ? AND ";
                if (!isset($pk) && ($value === gcr::autoNumber))
                {
                    $sql = "select nextval ('{$table}_{$column}_seq')";
                    $result = self::gcQuery($caller, $sql);
                    $value = $result[0]->nextval;
                    $pk = new stdClass();
                    $pk->column = $column;
                    $pk->value = $value;
                }
                $params[] = $value;
            }
            $string = substr($string, 0, -1);
            $column_string = substr($column_string, 0, -1);
            $where_string = substr($where_string, 0, -5);

            $sql = "insert into $table($column_string) values($string)";
            self::gcQuery($caller, $sql, $params);

            if (isset($pk))
            {
                $new_record = self::selectFromTable($caller, $tableName, $pk->column, $pk->value, true);
            }
            else
            {
                $sql = "select * from $table where $where_string";
                $new_record = self::gcQuery($caller, $sql, $params, true);
            }
            return $new_record;
        }
        catch (Exception $e)
        {
            global $CFG;
            $CFG->current_app->gcError("Error executing $sql on $table, {$e->getMessage()}: Params: " . implode(",", $valueArray), 'gcdatabaseerror');
        }
        return false;
    }
    public static function logQueryResult($sql, $result = array())
    {
        $result_string = date('d/m/Y H:i:s', time()) . " SQL: \"" . $sql . "\"\n\n";
        foreach($result as $record)
        {
            foreach ($record as $key => $value)
            {
                $result_string .= "$key => $value; ";
            }
            $result_string .= "\n";
        }
        error_log("\n\n" . $result_string, 3, gcr::rootDir . 'debug/sql.log');
    }
    public static function rollbackTransaction()
    {
        $conn = self::getConnection();
        $conn->rollback();
    }
    public static function selectFromTable($caller, $tableName, $columnName = false, $columnValue = false, $returnOne = false, $orderBy = false)
    {
        $table = self::getTableName($caller, $tableName);
        try
        {
            $returnValues = array();
            $sql = 'select * from ' . $table;
            if (($columnName !== false) && ($columnValue !== false))
            {
                $sql .= ' where "' . $columnName . '" = ?';
                if ($orderBy)
                {
                    $sql .= ' order by ' . $orderBy;
                }
                $resultSet = self::gcQuery($caller, $sql, array($columnValue), $returnOne);
            }
            else
            {
                if ($orderBy)
                {
                    $sql .= ' order by ' . $orderBy;
                }
                $resultSet = self::gcQuery($caller, $sql, array(), $returnOne);
            }
            return $resultSet;
        }
        catch (Exception $e)
        {
            global $CFG;
            $CFG->current_app->gcError("Error executing $sql on $table, {$e->getMessage()}", 'gcdatabaseerror');
        }
        return false;
    }
    public static function updateTable($caller, $tableName, $valueAssocArray, $whereAssocArray, $returnValues = false)
    {
        $table = self::getTableName($caller, $tableName);
        try
        {
            $setListString = '';
            foreach ($valueAssocArray as $key => $value)
            {
                $setListString .= $key . ' = ?,';
                $sqlParams[] = $value;
            }
            $setListString = substr($setListString, 0, -1);
            $whereListString = '';
            foreach ($whereAssocArray as $key => $value)
            {
                $whereListString .= $key . ' = ? AND ';
                $sqlParams[] = $value;
            }
            $whereListString = substr($whereListString, 0, -5);
            $sql = 'update ' . $table . ' set ' . $setListString . ' where ' . $whereListString;
            if ($returnValues)
            {
                $sql .= ' returning *';
                return self::gcQuery($caller, $sql, $sqlParams);
            }
            else
            {
               self::gcQuery($caller, $sql, $sqlParams);
               return true;
            }
        }
        catch (Exception $e)
        {
            global $CFG;
            $CFG->current_app->gcError("Error executing $sql on $table, {$e->getMessage()}", 'gcdatabaseerror');
        }
        return false;
    }
    public static function upsertIntoTable($caller, $tableName, $valueAssocArray, $whereAssocArray)
    {
        $records = self::updateTable($caller, $tableName, $valueAssocArray, $whereAssocArray, true);
        if (!isset($records))
        {
            $records = self::insertIntoTable($caller, $tableName, $valueAssocArray);
        }
        return $records;
    }
}