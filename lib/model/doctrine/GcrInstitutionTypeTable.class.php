<?php


class GcrInstitutionTypeTable extends Doctrine_Table
{
    
    public static function getInstance()
    {
        return Doctrine_Core::getTable('GcrInstitutionType');
    }
    // This function returns a list of institution type objects
    // If public_only is set to true, only types with is_public = true
    // will be returned.
    public static function getTypes($public_only = false)
    {
        $q = new Doctrine_Query();
        $q->select('i.*');
        $q->from('GcrInstitutionType i');

        if ($public_only)
        {
            $q->where('i.is_public = ?', 't');
        }

        return $q->execute();
    }
    // This function handles validating the institution type to make sure it is not hacked.
    public static function validateInstitutionType ($type)
    {
        global $CFG;
        if ($institution_type = Doctrine::getTable('GcrInstitutionType')->find($type))
        {
            if ($institution_type->getIsPublic() || $CFG->current_app->hasPrivilege('GCUser'))
            {
                return true;
            }
        }
        return false;
    }
}