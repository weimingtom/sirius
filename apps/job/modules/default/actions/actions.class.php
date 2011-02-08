<?php

/**
 * default actions.
 *
 * @package    Sirius
 * @subpackage default
 * @author     Cary Yang <getcary@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class defaultActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
	public function executeIndex(sfWebRequest $request)
	{
		$this->forward('default', 'list');
	}
	
	public function executeList(sfWebRequest $request) {
		$time = $request->getParameter('time', time());
		$this->jobs = $this->getJobListByTime($time);
		return sfView::SUCCESS;
	}
	
	public function executeRun(sfWebRequest $request) {
		$time = $request->getParameter('time', time());
		$jobs = $this->getJobListByTime($time);
		foreach ($jobs as $job) {
			$jobString = explode('|', $job['job'], 2);
			$jobName = $jobString[0];
			$params = json_decode($jobString[1], true);
			
			$clazz = new ReflectionClass('MixMes_Job_' . $jobName);
			$executor = $clazz->newInstance();
			$executor->execute($time, $params);
			
			// post execute
			$jobObj = Doctrine_Core::getTable("CronJob")->find($job['id']);
			$jobObj->setLastRun(time());
			if ($jobObj->getRunOnce()) {
				$jobObj->setEnabled(false);
			}
			$jobObj->save();
		}
	}
	
	protected function getJobListByTime($time) {
		$minute = 15; 
		$hour = 12;
		$day = 23;
		$month = 2;
		
		$minuteCondition = array_merge(array($minute, '*'), $this->getSubTime($minute));
		$hourCondition = array_merge(array($hour, '*'), $this->getSubTime($hour));
		$dayCondition = array_merge(array($day, '*'), $this->getSubTime($day));
		$monthCondition = array_merge(array($month, '*'), $this->getSubTime($month));
		
		$jobsQuery = Doctrine::getTable('CronJob')
			->createQuery('')
			->where('enabled = ?', array(true))
			->andWhereIn('minute', $minuteCondition)
			->andWhereIn('hour', $hourCondition)
			->andWhereIn('day', $dayCondition)
			->andWhereIn('month', $monthCondition)
			->orderBy('priority DESC');
						
		$jobs = $jobsQuery->fetchArray();
		return $jobs;
	}
	
	private function getSubTime($number) {
		$resArray = array();
		for ($i = $number; $i > 0; $i--) {
			if ($number % $i == 0) {
				$resArray[] = '*/' . $i;
			}
		}
		return $resArray;
	}
}
