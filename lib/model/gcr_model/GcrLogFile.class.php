<?php
class GcrLogFile
{
    protected $log_files;
    protected $log_file_location;
    protected $log_file_data;
    protected $num_lines;

    public function __construct($log_file, $num_lines)
    {
        $this->log_files = self::getLogFilesList();
        $this->log_file_location = $this->log_files[$log_file];
        $this->num_lines = $num_lines;
        $this->log_file_data = $this->getLastLines($num_lines);
    }
    public function getLogFileLocation()
    {
        return $this->log_file_location;
    }
    public function getLogFile()
    {
        return $this->log_file_data;
    }
    public function getNumLines()
    {
        return $this->num_lines;
    }
    public static function getLogFilesList()
    {
        return array('gcErrorLog' => '/var/www/globalclassroom4/debug/error.log',
                    'gcPaypalLog' => '/var/www/globalclassroom4/debug/paypal.log',
                    'gcSqlLog' => '/var/www/globalclassroom4/debug/sql.log',
                    'gcSymfonyLog' => '/var/www/globalclassroom4/log/frontend_dev.log');
    }
    protected function getLastLines($lines)
    {
        $filename = $this->log_file_location;
        $handle = @fopen($filename, "r");
        if (!$handle)
        {
           global $CFG;
           $CFG->current_app->gcError("Error: can't find or open $filename", 'gcdatabaseerror');
        }
        $linecounter = $lines;
        $pos = -1;
        $beginning = false;
        $text = array();

        /*read until more lines are needed*/
        while ($linecounter > 0)
        {
            $t = " ";
            /*read until you have found a newline character*/
            while ($t != "\n")
            {
                /* if fseek() returns -1 we need to break the cycle*/
                if (fseek($handle, $pos, SEEK_END) == -1)
                {
                    $beginning = true;
                    break;
                }
                $t = fgetc($handle);
                $pos --;
            }
            /*decrement the number of lines we still have to read*/
            $linecounter --;
            if($beginning)
            {
                rewind($handle);
            }

            /*read an entire line from the current position*/
            $text[$lines - $linecounter - 1] = fgets($handle);

            if($beginning)
            {
                break;
            }
        }
        fclose ($handle);
        return $text;
    }
}
?>