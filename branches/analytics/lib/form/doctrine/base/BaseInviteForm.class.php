<?php

/**
 * Invite form base class.
 *
 * @method Invite getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseInviteForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'code'         => new sfWidgetFormInputText(),
      'generater_id' => new sfWidgetFormInputText(),
      'purpose'      => new sfWidgetFormInputText(),
      'is_used'      => new sfWidgetFormInputCheckbox(),
      'used_by'      => new sfWidgetFormInputText(),
      'expire_date'  => new sfWidgetFormDate(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'code'         => new sfValidatorString(array('max_length' => 100)),
      'generater_id' => new sfValidatorInteger(array('required' => false)),
      'purpose'      => new sfValidatorString(array('max_length' => 100, 'required' => false)),
      'is_used'      => new sfValidatorBoolean(array('required' => false)),
      'used_by'      => new sfValidatorInteger(array('required' => false)),
      'expire_date'  => new sfValidatorDate(array('required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('invite[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Invite';
  }

}
