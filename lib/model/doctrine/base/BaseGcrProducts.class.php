<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('GcrProducts', 'doctrine');

abstract class BaseGcrProducts extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gcr_products');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'gcr_products_id',
             'length' => 8,
             ));
        $this->hasColumn('product_type_id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
			 'notnull' => true,
			 'default' => '0',
             'primary' => false,
             'length' => 8,
             ));			 
        $this->hasColumn('short_name', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
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
        $this->hasColumn('catalog_short_name', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));			 
		$this->hasColumn('platform_short_name', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));			 
        $this->hasColumn('full_name', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));
        $this->hasColumn('description', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));
        $this->hasColumn('long_description', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));
        $this->hasColumn('icon', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));
        $this->hasColumn('pricing_html', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));			 
        $this->hasColumn('cost', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('status', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '1',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('expiry_no_of_days', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('network_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => false,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));			 
        $this->hasColumn('archive', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));			 
        $this->hasColumn('sponsor_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('created_date', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => 8,
             ));
        $this->hasColumn('last_updated_date', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => false,
             'unsigned' => false,
             'notnull' => true,
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