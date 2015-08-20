<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('BaseGcrInstitutionProductOrders', 'doctrine');

abstract class BaseGcrInstitutionProductOrders extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gcr_institution_product_orders');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'gcr_institution_product_orders_id',
             'length' => 8,
             ));
        $this->hasColumn('user_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('institution_short_name', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));
        $this->hasColumn('product_short_name', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));			 
        $this->hasColumn('register_flag', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('paid_flag', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('paid_amt', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 8,
             ));			 
        $this->hasColumn('start_date', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('orig_start_date', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('expiry_date', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('deleted_flag', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 8,
             ));			 
        $this->hasColumn('renewal_attempts', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('created_on', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('updated_on', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}