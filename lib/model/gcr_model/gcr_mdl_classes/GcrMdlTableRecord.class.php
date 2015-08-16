<?php
// MdlTableRecord class.
// Ron Stewart
// September 10, 2010
//
// This class represents an object from an mdl table of a given $this->eschool, and offers methods
// to manipulate this object, without the need to actaully be logged in to the associated eschool. This
// class should not be used, but rather, should be extended for each moodle table to add object specific
// methods for each table.

class GcrMdlTableRecord extends GcrTableRecord
{	
    function __construct($obj, $eschool)
    {
        global $CFG;
        if (!$eschool->isMoodle())
        {
            $CFG->current_app->gcError('Attempt to create GcrMdlTableRecord using a non-moodle schema: ' .
                    $eschool->getShortName(), 'gcdatabaseerror');
        }
        parent::__construct($obj, $eschool);
    }
}