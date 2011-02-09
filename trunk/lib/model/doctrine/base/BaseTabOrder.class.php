<?php

/**
 * BaseTabOrder
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $owner_id
 * @property array $tabs
 * @property User $User
 * 
 * @method integer  getOwnerId()  Returns the current record's "owner_id" value
 * @method array    getTabs()     Returns the current record's "tabs" value
 * @method User     getUser()     Returns the current record's "User" value
 * @method TabOrder setOwnerId()  Sets the current record's "owner_id" value
 * @method TabOrder setTabs()     Sets the current record's "tabs" value
 * @method TabOrder setUser()     Sets the current record's "User" value
 * 
 * @package    Sirius
 * @subpackage model
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseTabOrder extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('tab_order');
        $this->hasColumn('owner_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('tabs', 'array', 100, array(
             'type' => 'array',
             'length' => '100',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'owner_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));
    }
}