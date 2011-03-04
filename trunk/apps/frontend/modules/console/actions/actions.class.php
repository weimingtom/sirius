<?php

/**
 * console actions.
 *
 * @package    Sirius
 * @subpackage console
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class consoleActions extends sfActions
{
	/**
	 * Executes index action
	 *
	 * @param sfRequest $request A request object
	 */
	public function executeIndex(sfWebRequest $request)
	{
		return sfView::SUCCESS;
	}
	
	public function executeConfig(sfWebRequest $request) {
		$provider = $request->getParameter("provider");
		$clazz = $this->getReflectionClassByProvider($provider);
		if ($clazz === NULL) {
			return $this->renderText(json_encode( array("error"=>"") ));
		}
		
		$methods = $clazz->getMethods(ReflectionMethod::IS_PUBLIC);
		
		$outputMethods = array();
		foreach ($methods as $method) {
			$methodName = $method->getName();
			if (strpos($methodName, "__", 0) === 0) {
				continue;
			}
			$outputMethods[$methodName] = array();
			$parameters = $method->getParameters();
			foreach ($parameters as $paremeter) {
				$outputMethods[$methodName][] = $paremeter->getName();
			}
		}
		return $this->renderText(
			json_encode( array(
				"formats" => array("json"=>"JSON"),
				"methods" => $outputMethods
			))
		);
	}
	
	public function executeCall(sfWebRequest $request) {
		$provider = $request->getParameter('provider');
		$method = $request->getParameter('method');
		$format = $request->getParameter("format", "json");
		if ($provider == null || $method == null) {
			$this->forward404("{error: ''}"); 
		}
		$clazz = $this->getReflectionClassByProvider($provider);
		if ($clazz === NULL) {
			return $this->renderText(json_encode( array("error"=>"Provider doesn't exists.") ));
		}
		$method = $clazz->getMethod($method);
		if ($clazz === NULL) {
			return $this->renderText(json_encode( array("error"=>"Method doesn't exists.") ));
		}
		$i = 0; $parametes = array();
		while ($request->hasParameter('val_' . $i)) {
			$parametes[] = $request->getParameter('val_' . $i);
		}
		$result = $method->invokeArgs($parametes);
		return $this->renderText($result);
	}
	
	protected function getReflectionClassByProvider($provider) {
		switch ($provider) {
			case 'sina':
				return new ReflectionClass("WeiboClient");
			case 'qq':
				return new ReflectionClass("QQClient");
			case 'sohu':
				return new ReflectionClass("SohuClient");
			case 'fanfou':
				return new ReflectionClass("fanfouClient");
			case 'douban':
				return new ReflectionClass("doubanClient");
			default:
				return NULL;
		}		
	}
}
