<?php

class GcrEschoolTypeTable extends Doctrine_Table
{
    // This function returns a list of eschool type objects
    // If public_only is set to true, only types with is_public = true
    // will be returned.
    public static function getEschoolTypes($public_only = false)
    {
        $q = new Doctrine_Query();
        $q->select('e.*');
        $q->from('GcrEschoolType e');

        if ($public_only)
        {
            $q->where('e.is_public = ?', 't');
        }

        return $q->execute();
    }
    // This function handles validating the eschool type to make sure it is not hacked.
    public static function validateEschoolType ($type)
    {
        global $CFG;
        if ($eschool_type = Doctrine::getTable('GcrEschoolType')->find($type))
        {
            if ($eschool_type->getIsPublic() || $CFG->current_app->hasPrivilege('GCUser'))
            {
                return true;
            }
        }
        return false;
    }
}
