<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins(array(
    	'sfDoctrinePlugin',
    	//'sfThumbnailPlugin'
    ));
  }
  
  public function configureDoctrine(Doctrine_Manager $manager) {
  	$manager->setCollate('utf8_unicode_ci');
    $manager->setCharset('utf8');
  } 
}
