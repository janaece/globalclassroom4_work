<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('GcrUserMonthlyData', 'doctrine');

/**
 * BaseGcrUserMonthlyData
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property integer $month_value
 * @property integer $year_value
 * @property integer $user_id
 * @property string $user_institution_id
 * @property decimal $user_balance
 * @property decimal $gross
 * @property decimal $gc_fee
 * @property decimal $owner_fee
 * 
 * @method integer            getId()                  Returns the current record's "id" value
 * @method integer            getMonthValue()          Returns the current record's "month_value" value
 * @method integer            getYearValue()           Returns the current record's "year_value" value
 * @method integer            getUserId()              Returns the current record's "user_id" value
 * @method string             getUserInstitutionId()   Returns the current record's "user_institution_id" value
 * @method decimal            getUserBalance()         Returns the current record's "user_balance" value
 * @method decimal            getGross()               Returns the current record's "gross" value
 * @method decimal            getGcFee()               Returns the current record's "gc_fee" value
 * @method decimal            getOwnerFee()            Returns the current record's "owner_fee" value
 * @method GcrUserMonthlyData setId()                  Sets the current record's "id" value
 * @method GcrUserMonthlyData setMonthValue()          Sets the current record's "month_value" value
 * @method GcrUserMonthlyData setYearValue()           Sets the current record's "year_value" value
 * @method GcrUserMonthlyData setUserId()              Sets the current record's "user_id" value
 * @method GcrUserMonthlyData setUserInstitutionId()   Sets the current record's "user_institution_id" value
 * @method GcrUserMonthlyData setUserBalance()         Sets the current record's "user_balance" value
 * @method GcrUserMonthlyData setGross()               Sets the current record's "gross" value
 * @method GcrUserMonthlyData setGcFee()               Sets the current record's "gc_fee" value
 * @method GcrUserMonthlyData setOwnerFee()            Sets the current record's "owner_fee" value
 * 
 * @package    globalclassroom
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseGcrUserMonthlyData extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('gcr_user_monthly_data');
        $this->hasColumn('id', 'integer', 8, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'sequence' => 'gcr_user_monthly_data_id',
             'length' => 8,
             ));
        $this->hasColumn('month_value', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('year_value', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('user_id', 'integer', 4, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 4,
             ));
        $this->hasColumn('user_institution_id', 'string', null, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '',
             'primary' => false,
             'length' => '',
             ));
        $this->hasColumn('user_balance', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 18,
             ));
        $this->hasColumn('gross', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 18,
             ));
        $this->hasColumn('gc_fee', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 18,
             ));
        $this->hasColumn('owner_fee', 'decimal', 18, array(
             'type' => 'decimal',
             'fixed' => 0,
             'unsigned' => false,
             'notnull' => true,
             'default' => '0',
             'primary' => false,
             'length' => 18,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}