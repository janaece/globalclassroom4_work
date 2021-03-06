<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('GcrTrial', 'doctrine');

/**
 * BaseGcrTrial
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $organization_id
 * @property integer $start_date
 * @property integer $end_date
 * 
 * @method integer  getId()              Returns the current record's "id" value
 * @method integer  getOrganizationId()  Returns the current record's "organization_id" value
 * @method integer  getStartDate()       Returns the current record's "start_date" value
 * @method integer  getEndDate()         Returns the current record's "end_date" value
 * @method GcrTrial setId()              Sets the current record's "id" value
 * @method GcrTrial setOrganizationId()  Sets the current record's "organization_id" value
 * @method GcrTrial setStartDate()       Sets the current record's "start_date" value
 * @method GcrTrial setEndDate()         Sets the current record's "end_date" value
 * 
 * @package    globalclassroom
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGcrTrial extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gcr_trial');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'gcr_trial_id',
             'length' => 8,
             ));
        $this->hasColumn('organization_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('start_date', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('end_date', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}