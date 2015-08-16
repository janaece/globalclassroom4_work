<?php
// MhrTableRecord class.
// Ron Stewart
// Feb 13, 2011
//
// This class represents an object from an mhr table of a given institution (mahara), and offers methods
// to manipulate this object, without the need to actaully be logged in to the associated mahara. This
// class should not be used, but rather, should be extended for each moodle table to add object specific
// methods for each table.

class GcrMhrTableRecord extends GcrTableRecord
{	
	function __construct($obj, $institution)
	{
		global $CFG;
		if (!$institution->isMahara())
		{
			$CFG->current_app->gcError('Attempt to create GcrMhrTableRecord using a non-mahara schema: ' . 
				$institution->getShortName(), 'gcdatabaseerror');
		}
		parent::__construct($obj, $institution);
	}
}