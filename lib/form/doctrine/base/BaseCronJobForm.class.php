<?php

/**
 * CronJob form base class.
 *
 * @method CronJob getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseCronJobForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'minute'     => new sfWidgetFormInputText(),
      'hour'       => new sfWidgetFormInputText(),
      'day'        => new sfWidgetFormInputText(),
      'month'      => new sfWidgetFormInputText(),
      'job'        => new sfWidgetFormInputText(),
      'priority'   => new sfWidgetFormInputText(),
      'enabled'    => new sfWidgetFormInputCheckbox(),
      'run_once'   => new sfWidgetFormInputCheckbox(),
      'run_times'  => new sfWidgetFormInputText(),
      'last_run'   => new sfWidgetFormInputText(),
      'created_at' => new sfWidgetFormDateTime(),
      'updated_at' => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'minute'     => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'hour'       => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'day'        => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'month'      => new sfValidatorString(array('max_length' => 16, 'required' => false)),
      'job'        => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'priority'   => new sfValidatorInteger(array('required' => false)),
      'enabled'    => new sfValidatorBoolean(array('required' => false)),
      'run_once'   => new sfValidatorBoolean(array('required' => false)),
      'run_times'  => new sfValidatorInteger(array('required' => false)),
      'last_run'   => new sfValidatorPass(array('required' => false)),
      'created_at' => new sfValidatorDateTime(),
      'updated_at' => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('cron_job[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'CronJob';
  }

}
