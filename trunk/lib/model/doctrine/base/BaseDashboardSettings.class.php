<?php

/**
 * BaseDashboardSettings
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $owner_id
 * @property array $tab_ids
 * @property integer $active_tab_id
 * @property integer $thread_width
 * @property integer $refresh_frequency
 * @property User $User
 * 
 * @method integer           getOwnerId()           Returns the current record's "owner_id" value
 * @method array             getTabIds()            Returns the current record's "tab_ids" value
 * @method integer           getActiveTabId()       Returns the current record's "active_tab_id" value
 * @method integer           getThreadWidth()       Returns the current record's "thread_width" value
 * @method integer           getRefreshFrequency()  Returns the current record's "refresh_frequency" value
 * @method User              getUser()              Returns the current record's "User" value
 * @method DashboardSettings setOwnerId()           Sets the current record's "owner_id" value
 * @method DashboardSettings setTabIds()            Sets the current record's "tab_ids" value
 * @method DashboardSettings setActiveTabId()       Sets the current record's "active_tab_id" value
 * @method DashboardSettings setThreadWidth()       Sets the current record's "thread_width" value
 * @method DashboardSettings setRefreshFrequency()  Sets the current record's "refresh_frequency" value
 * @method DashboardSettings setUser()              Sets the current record's "User" value
 * 
 * @package    Sirius
 * @subpackage model
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: Builder.php 7691 2011-02-04 15:43:29Z jwage $
 */
abstract class BaseDashboardSettings extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('dashboard_settings');
        $this->hasColumn('owner_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('tab_ids', 'array', 100, array(
             'type' => 'array',
             'length' => '100',
             ));
        $this->hasColumn('active_tab_id', 'integer', null, array(
             'type' => 'integer',
             ));
        $this->hasColumn('thread_width', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 400,
             ));
        $this->hasColumn('refresh_frequency', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'default' => 20,
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