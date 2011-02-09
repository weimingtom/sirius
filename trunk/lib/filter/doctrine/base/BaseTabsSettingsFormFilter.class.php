<?php

/**
 * TabsSettings filter form base class.
 *
 * @package    Sirius
 * @subpackage filter
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseTabsSettingsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'owner_id'      => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('User'), 'add_empty' => true)),
      'tab_ids'       => new sfWidgetFormFilterInput(),
      'active_tab_id' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'owner_id'      => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('User'), 'column' => 'id')),
      'tab_ids'       => new sfValidatorPass(array('required' => false)),
      'active_tab_id' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('tabs_settings_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'TabsSettings';
  }

  public function getFields()
  {
    return array(
      'id'            => 'Number',
      'owner_id'      => 'ForeignKey',
      'tab_ids'       => 'Text',
      'active_tab_id' => 'Number',
    );
  }
}
