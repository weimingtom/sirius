<?php

/**
 * ReportDailyCount form base class.
 *
 * @method ReportDailyCount getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseReportDailyCountForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'              => new sfWidgetFormInputHidden(),
      'profile_name'    => new sfWidgetFormInputText(),
      'profile_type'    => new sfWidgetFormInputText(),
      'date'            => new sfWidgetFormDate(),
      'followers_count' => new sfWidgetFormInputText(),
      'friends_count'   => new sfWidgetFormInputText(),
      'statuses_count'  => new sfWidgetFormInputText(),
      'created_at'      => new sfWidgetFormDateTime(),
      'updated_at'      => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'              => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'profile_name'    => new sfValidatorString(array('max_length' => 255)),
      'profile_type'    => new sfValidatorString(array('max_length' => 255)),
      'date'            => new sfValidatorDate(array('required' => false)),
      'followers_count' => new sfValidatorInteger(array('required' => false)),
      'friends_count'   => new sfValidatorInteger(array('required' => false)),
      'statuses_count'  => new sfValidatorInteger(array('required' => false)),
      'created_at'      => new sfValidatorDateTime(),
      'updated_at'      => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'ReportDailyCount', 'column' => array('profile_name', 'profile_type', 'date')))
    );

    $this->widgetSchema->setNameFormat('report_daily_count[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ReportDailyCount';
  }

}
