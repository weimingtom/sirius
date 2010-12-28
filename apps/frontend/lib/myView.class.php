<?php

class myView extends sfPHPView {
	public function configure() {
		parent::configure();
		
		// Grab the theme from the user
		$theme = $this->context->getUser()->getTheme();
		
		// If there is a theme and if the theme feature is enabled
		if ($theme && sfConfig::get('app_theme')) {
			//Look for templates in a $theme/ subdirectory of the usual template location
			if (is_readable($this->getDirectory().'/'.$theme.'/'.$this->getTemplate())) {
				$this->setDirectory($this->getDirectory().'/'.$theme);
			}
			
			// Look for a layout in a $theme/ subdirectory of the usual layout location
			if (is_readable($this->getDecoratorDirectory().'/'.$theme.'/'.$this->getDecoratorTemplate())) {
				$this->setDecoratorDirectory($this->getDecoratorDirectory().'/'.$theme);
			}
		}
	}
}
