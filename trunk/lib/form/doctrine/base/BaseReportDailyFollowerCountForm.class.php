<?php

/**
 * ReportDailyFollowerCount form base class.
 *
 * @method ReportDailyFollowerCount getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReportDailyFollowerCountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'profile_name' => new sfWidgetFormInputText(),
      'profile_type' => new sfWidgetFormInputText(),
      'date'         => new sfWidgetFormDate(),
      'count'        => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'profile_name' => new sfValidatorInteger(array('required' => false)),
      'profile_type' => new sfValidatorString(array('max_length' => 255)),
      'date'         => new sfValidatorDate(array('required' => false)),
      'count'        => new sfValidatorInteger(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'ReportDailyFollowerCount', 'column' => array('profile_name', 'profile_type', 'date')))
    );

    $this->widgetSchema->setNameFormat('report_daily_follower_count[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReportDailyFollowerCount';
  }

}
