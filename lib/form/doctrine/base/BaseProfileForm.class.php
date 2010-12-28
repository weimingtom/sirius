<?php

/**
 * Profile form base class.
 *
 * @method Profile getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseProfileForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'           => new sfWidgetFormInputHidden(),
      'owner_id'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'profile_name' => new sfWidgetFormInputText(),
      'screen_name'  => new sfWidgetFormInputText(),
      'type'         => new sfWidgetFormInputText(),
      'avatar_url'   => new sfWidgetFormTextarea(),
      'connect_data' => new sfWidgetFormTextarea(),
      'created_at'   => new sfWidgetFormDateTime(),
      'updated_at'   => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'           => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'owner_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'profile_name' => new sfValidatorString(array('max_length' => 255)),
      'screen_name'  => new sfValidatorString(array('max_length' => 255)),
      'type'         => new sfValidatorString(array('max_length' => 255)),
      'avatar_url'   => new sfValidatorString(array('max_length' => 1023, 'required' => false)),
      'connect_data' => new sfValidatorString(array('max_length' => 1023)),
      'created_at'   => new sfValidatorDateTime(),
      'updated_at'   => new sfValidatorDateTime(),
    ));

    $this->validatorSchema->setPostValidator(
      new sfValidatorDoctrineUnique(array('model' => 'Profile', 'column' => array('profile_name', 'screen_name')))
    );

    $this->widgetSchema->setNameFormat('profile[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Profile';
  }

}
