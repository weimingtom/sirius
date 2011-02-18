<?php

/**
 * ReportDailyCount filter form base class.
 *
 * @package    Sirius
 * @subpackage filter
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReportDailyCountFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'profile_name'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'profile_type'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'date'            => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'followers_count' => new sfWidgetFormFilterInput(),
      'friends_count'   => new sfWidgetFormFilterInput(),
      'statuses_count'  => new sfWidgetFormFilterInput(),
      'created_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at'      => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'profile_name'    => new sfValidatorPass(array('required' => false)),
      'profile_type'    => new sfValidatorPass(array('required' => false)),
      'date'            => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'followers_count' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'friends_count'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'statuses_count'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'created_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at'      => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('report_daily_count_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReportDailyCount';
  }

  public function getFields()
  {
    return array(
      'id'              => 'Number',
      'profile_name'    => 'Text',
      'profile_type'    => 'Text',
      'date'            => 'Date',
      'followers_count' => 'Number',
      'friends_count'   => 'Number',
      'statuses_count'  => 'Number',
      'created_at'      => 'Date',
      'updated_at'      => 'Date',
    );
  }
}
