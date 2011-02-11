<?php

/**
 * DashboardSettings form base class.
 *
 * @method DashboardSettings getObject() Returns the current form's model object
 *
 * @package    Sirius
 * @subpackage form
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseDashboardSettingsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'                => new sfWidgetFormInputHidden(),
      'owner_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => false)),
      'tab_ids'           => new sfWidgetFormInputText(),
      'active_tab_id'     => new sfWidgetFormInputText(),
      'thread_width'      => new sfWidgetFormInputText(),
      'refresh_frequency' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'                => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'owner_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('User'))),
      'tab_ids'           => new sfValidatorPass(array('required' => false)),
      'active_tab_id'     => new sfValidatorInteger(array('required' => false)),
      'thread_width'      => new sfValidatorInteger(array('required' => false)),
      'refresh_frequency' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('dashboard_settings[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'DashboardSettings';
  }

}
