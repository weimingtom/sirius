<?php

/**
 * BaseReportDailyCount
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $profile_name
 * @property string $profile_type
 * @property date $date
 * @property integer $followers_count
 * @property integer $friends_count
 * @property integer $statuses_count
 * 
 * @method string           getProfileName()     Returns the current record's "profile_name" value
 * @method string           getProfileType()     Returns the current record's "profile_type" value
 * @method date             getDate()            Returns the current record's "date" value
 * @method integer          getFollowersCount()  Returns the current record's "followers_count" value
 * @method integer          getFriendsCount()    Returns the current record's "friends_count" value
 * @method integer          getStatusesCount()   Returns the current record's "statuses_count" value
 * @method ReportDailyCount setProfileName()     Sets the current record's "profile_name" value
 * @method ReportDailyCount setProfileType()     Sets the current record's "profile_type" value
 * @method ReportDailyCount setDate()            Sets the current record's "date" value
 * @method ReportDailyCount setFollowersCount()  Sets the current record's "followers_count" value
 * @method ReportDailyCount setFriendsCount()    Sets the current record's "friends_count" value
 * @method ReportDailyCount setStatusesCount()   Sets the current record's "statuses_count" value
 * 
 * @package    Sirius
 * @subpackage model
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseReportDailyCount extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('report_daily_count');
        $this->hasColumn('profile_name', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('profile_type', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('date', 'date', null, array(
             'type' => 'date',
             'notnull' => false,
             ));
        $this->hasColumn('followers_count', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('friends_count', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));
        $this->hasColumn('statuses_count', 'integer', null, array(
             'type' => 'integer',
             'notnull' => false,
             ));


        $this->index('profile_index', array(
             'fields' => 
             array(
              0 => 'profile_name',
              1 => 'profile_type',
             ),
             ));
        $this->index('profile_date_index', array(
             'fields' => 
             array(
              0 => 'profile_name',
              1 => 'profile_type',
              2 => 'date',
             ),
             'type' => 'unique',
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}