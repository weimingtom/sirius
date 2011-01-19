<?php

class uploadAction extends sfActions {
	public function execute($request) {
		$files = $request->getFiles();
		if ($files === null || count($files) <= 0) {
			return sfView::NONE;
		}
		$file = $files["Filedata"];
		if ($file['error'] != 0) {
			return sfView::NONE;
		}
		$fileName = $this->generateFileName($file["name"]);
		move_uploaded_file($file["tmp_name"], sfConfig::get('sf_upload_dir') . "/" . $fileName);
		$returnData = array("imageUrl" => "/uploads/" . $fileName);
		return $this->renderText(json_encode($returnData));
	}
	
	protected function generateFileName($originName) {
		$extensionPos = strripos($originName, '.');
		if ($extensionPos !== false) {
			$ext = substr($originName, $extensionPos + 1);
		}
		
		do {
			$fileName = substr(md5(microtime()), 0, 10);
			if ($ext) {
				$fileName .= '.' . $ext;
			}
		} while($this->fileExist($fileName));
		
		return $fileName;
	}
	
	protected function fileExist($fileName) {
		return file_exists(sfConfig::get('sf_upload_dir').'/' .$fileName);
	}
}
