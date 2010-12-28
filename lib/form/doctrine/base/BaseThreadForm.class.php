<?php

/**
 * Thread form base class.
 *
 * @method Thread getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseThreadForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'title'        => new sfWidgetFormInputText(),
      'owner_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'tab_id'       => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Tab'), 'add_empty' => false)),
      'profile_id'   => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('Profile'), 'add_empty' => true)),
      'profile_name' => new sfWidgetFormInputText(),
      'profile_type' => new sfWidgetFormInputText(),
      'type'         => new sfWidgetFormInputText(),
      'parameters'   => new sfWidgetFormInputText(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'title'        => new sfValidatorString(array('max_length' => 255)),
      'owner_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'tab_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Tab'))),
      'profile_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('Profile'), 'required' => false)),
      'profile_name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'profile_type' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'type'         => new sfValidatorString(array('max_length' => 255)),
      'parameters'   => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('thread[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Thread';
  }

}
