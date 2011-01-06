<?php

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $email
 * @property string $full_name
 * @property string $password
 * @property enum $status
 * @property Doctrine_Collection $Profiles
 * @property Doctrine_Collection $Tabs
 * @property Doctrine_Collection $Threads
 * 
 * @method string              getEmail()     Returns the current record's "email" value
 * @method string              getFullName()  Returns the current record's "full_name" value
 * @method string              getPassword()  Returns the current record's "password" value
 * @method enum                getStatus()    Returns the current record's "status" value
 * @method Doctrine_Collection getProfiles()  Returns the current record's "Profiles" collection
 * @method Doctrine_Collection getTabs()      Returns the current record's "Tabs" collection
 * @method Doctrine_Collection getThreads()   Returns the current record's "Threads" collection
 * @method User                setEmail()     Sets the current record's "email" value
 * @method User                setFullName()  Sets the current record's "full_name" value
 * @method User                setPassword()  Sets the current record's "password" value
 * @method User                setStatus()    Sets the current record's "status" value
 * @method User                setProfiles()  Sets the current record's "Profiles" collection
 * @method User                setTabs()      Sets the current record's "Tabs" collection
 * @method User                setThreads()   Sets the current record's "Threads" collection
 * 
 * @package    Sirius
 * @subpackage model
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('email', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'unique' => true,
             'length' => '255',
             ));
        $this->hasColumn('full_name', 'string', 255, array(
             'type' => 'string',
             'length' => '255',
             ));
        $this->hasColumn('password', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('status', 'enum', null, array(
             'type' => 'enum',
             'values' => 
             array(
              0 => 'unverify',
              1 => 'verified',
             ),
             'default' => 'verified',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Profile as Profiles', array(
             'local' => 'id',
             'foreign' => 'owner_id'));

        $this->hasMany('Tab as Tabs', array(
             'local' => 'id',
             'foreign' => 'owner_id'));

        $this->hasMany('Thread as Threads', array(
             'local' => 'id',
             'foreign' => 'owner_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}