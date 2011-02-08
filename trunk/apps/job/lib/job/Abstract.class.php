<?php
abstract class MixMes_Job_Abstract {
	abstract public function execute($time, $params = array());
}