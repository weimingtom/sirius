<?php

/**
 * BaseThread
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $title
 * @property integer $owner_id
 * @property integer $tab_id
 * @property integer $profile_id
 * @property string $profile_name
 * @property string $profile_type
 * @property string $type
 * @property string $parameters
 * @property User $User
 * @property Tab $Tab
 * @property Profile $Profile
 * 
 * @method string  getTitle()        Returns the current record's "title" value
 * @method integer getOwnerId()      Returns the current record's "owner_id" value
 * @method integer getTabId()        Returns the current record's "tab_id" value
 * @method integer getProfileId()    Returns the current record's "profile_id" value
 * @method string  getProfileName()  Returns the current record's "profile_name" value
 * @method string  getProfileType()  Returns the current record's "profile_type" value
 * @method string  getType()         Returns the current record's "type" value
 * @method string  getParameters()   Returns the current record's "parameters" value
 * @method User    getUser()         Returns the current record's "User" value
 * @method Tab     getTab()          Returns the current record's "Tab" value
 * @method Profile getProfile()      Returns the current record's "Profile" value
 * @method Thread  setTitle()        Sets the current record's "title" value
 * @method Thread  setOwnerId()      Sets the current record's "owner_id" value
 * @method Thread  setTabId()        Sets the current record's "tab_id" value
 * @method Thread  setProfileId()    Sets the current record's "profile_id" value
 * @method Thread  setProfileName()  Sets the current record's "profile_name" value
 * @method Thread  setProfileType()  Sets the current record's "profile_type" value
 * @method Thread  setType()         Sets the current record's "type" value
 * @method Thread  setParameters()   Sets the current record's "parameters" value
 * @method Thread  setUser()         Sets the current record's "User" value
 * @method Thread  setTab()          Sets the current record's "Tab" value
 * @method Thread  setProfile()      Sets the current record's "Profile" value
 * 
 * @package    Sirius
 * @subpackage model
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseThread extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('thread');
        $this->hasColumn('title', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('owner_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('tab_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('profile_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('profile_name', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => '255',
             ));
        $this->hasColumn('profile_type', 'string', 255, array(
             'type' => 'string',
             'notnull' => false,
             'length' => '255',
             ));
        $this->hasColumn('type', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('parameters', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'default' => '',
             'length' => '255',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('User', array(
             'local' => 'owner_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Tab', array(
             'local' => 'tab_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $this->hasOne('Profile', array(
             'local' => 'profile_id',
             'foreign' => 'id',
             'onDelete' => 'CASCADE'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}