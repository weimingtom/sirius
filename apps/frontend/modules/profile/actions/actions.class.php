<?php

/**
 * profile actions.
 *
 * @package    Sirius
 * @subpackage profile
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profileActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request) {
    $this->forward('default', 'index');
  }
  
  /**
   * Display add profile page (iframe)
   */
  public function executeAdd(sfWebRequest $request) {
  	$support_list = array(
		"qq" => "腾讯微博",
		"sina" => "新浪微博",
	);
	
	$this->supportList = $support_list;
  }
  
  
  /**
   * Delete profile by profile id
   */
  public function executeDelete(sfWebRequest $request) {
  }
}
