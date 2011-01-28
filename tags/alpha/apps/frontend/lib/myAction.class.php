<?php

abstract class myAction extends sfAction {
	protected function formatTime($timestamp) {
		$timestr = "";
		
		if (!is_integer($timestamp)) {
			$timestamp = strtotime($timestamp);
		}
		
		$timenow = strtotime('now');
		
		if (strftime("%m/%d/%Y", $timestamp) !=  strftime("%m/%d/%Y", $timenow)) {
			if (strftime("%Y", $timestamp) !=  strftime("%Y", $timenow)) {
				$timestr = strftime("%Y-%m-%d %H:%M", $timestamp);
			} else {
				$month = intval(strftime('%m', $timestamp));
				$day = intval(strftime('%d', $timestamp));
				$timestr = $month . '月' . $day . '日 ' . strftime("%H:%M", $timestamp);
			}
		} else {
			$timestr = "今天 " . strftime("%H:%M", $timestamp);
		}
		
		return $timestr;
	}
}
