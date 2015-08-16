<?php
// GcrTableRecord class.
// Ron Stewart
// Feb 13, 2011
//
// This class represents an object from a nested application table, and offers methods
// to manipulate this object, without the need to actaully be logged in to the associated application. This
// class should not be used, but rather, should be extended for each application table to add object specific
// methods for each table.

class GcrTableRecord
{
    protected $obj;
    protected $app;

    function __construct($obj, $app)
    {
        $this->app = $app;
        $this->obj = $obj;
    }
    public function getApp()
    {
        return $this->app;
    }
    public function getObject()
    {
        return $this->obj;
    }
    public function getInstitution()
    {
        return $this->app->getInstitution();
    }
    public function hasSameInstitution(GcrTableRecord $obj)
    {
        return ($obj->getInstitution()->getId() == $this->getInstitution()->getId());
    }
}