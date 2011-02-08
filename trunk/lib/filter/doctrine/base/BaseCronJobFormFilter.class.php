<?php

/**
 * CronJob filter form base class.
 *
 * @package    Sirius
 * @subpackage filter
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCronJobFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'minute'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'hour'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'day'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'month'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'job'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'priority'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'enabled'    => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'run_once'   => new sfWidgetFormChoice(array('choices' => array('' => 'yes or no', 1 => 'yes', 0 => 'no'))),
      'run_times'  => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'last_run'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'created_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'updated_at' => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
    ));

    $this->setValidators(array(
      'minute'     => new sfValidatorPass(array('required' => false)),
      'hour'       => new sfValidatorPass(array('required' => false)),
      'day'        => new sfValidatorPass(array('required' => false)),
      'month'      => new sfValidatorPass(array('required' => false)),
      'job'        => new sfValidatorPass(array('required' => false)),
      'priority'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'enabled'    => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'run_once'   => new sfValidatorChoice(array('required' => false, 'choices' => array('', 1, 0))),
      'run_times'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'last_run'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'created_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
      'updated_at' => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 00:00:00')), 'to_date' => new sfValidatorDateTime(array('required' => false, 'datetime_output' => 'Y-m-d 23:59:59')))),
    ));

    $this->widgetSchema->setNameFormat('cron_job_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CronJob';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'minute'     => 'Text',
      'hour'       => 'Text',
      'day'        => 'Text',
      'month'      => 'Text',
      'job'        => 'Text',
      'priority'   => 'Number',
      'enabled'    => 'Boolean',
      'run_once'   => 'Boolean',
      'run_times'  => 'Number',
      'last_run'   => 'Date',
      'created_at' => 'Date',
      'updated_at' => 'Date',
    );
  }
}
