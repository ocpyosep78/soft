<?php

class upload extends CI_Controller {
	function __construct() {
		parent::__construct();
	}
	
	function index() {
		$this->load->view( 'website/common/upload_single');
	}
	
	function checkUpdateDir($dateDir = array()) {
		$arrayReturn = array();
		if($dateDir!=null)
		{
			$targetDir = $newDir = "";
			foreach($dateDir as $value)
			{
				if($newDir == "")
				{
					$newDir = $value;
				}
				else
				{
					$newDir .= "/".$value;
				}
				
				$targetDir = $this->root_directory.$this->upload_directory.$newDir;
				$checkDir = is_dir($targetDir);
				if($checkDir == false)
				{
					mkdir($targetDir, 0777, true);
				}
			}

			$arrayReturn['newDir'] = $newDir;
			$arrayReturn['targetDir'] = $targetDir;
			$relativePath =  $this->upload_directory.$newDir;
			$arrayReturn['relativePath'] = str_replace('\/','/',$relativePath);
			return $arrayReturn;
		}else
		{
			return;
		}
	}
	
	function file($paramUpload = null) {
		$this->allowed_ext = $this->config->item('allowed_ext');
		$this->root_directory = $this->config->item('base_path')."/";
		$this->upload_directory = $this->config->item('upload_directory');
		
		// HTTP headers for no cache etc
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		
		// Settings
		$dateDir = array();
		$dateDir['Y']= date("Y");
		$dateDir['m']= date("m");
		$dateDir['d']= date("d");
		$newTargetDir = $this->checkUpdateDir($dateDir);

		if ($newTargetDir['targetDir'] != null or $newTargetDir['targetDir'] != '') {
			$targetDir      = $newTargetDir['targetDir'];
			$relativePath   = $newTargetDir['relativePath'];
		} else {
			$targetDir  = $this->root_directory.$this->upload_directory;
			$relativePath = $this->upload_directory;
		}
		
		$cleanupTargetDir = true; // Remove old files
		$maxFileAge     = 5 * 3600; // Temp file age in seconds
		
		// 5 minutes execution time
		@set_time_limit(5 * 60);
		
		// Uncomment this one to fake upload time
		// usleep(5000);
		
		// Get parameters
		$chunk      = isset($_REQUEST["chunk"]) ? intval($_REQUEST["chunk"]) : 0;
		$chunks     = isset($_REQUEST["chunks"]) ? intval($_REQUEST["chunks"]) : 0;
		$fileName   = isset($_REQUEST["name"]) ? $_REQUEST["name"] : '';

		$ext = GetExtention($fileName);
		if (!in_array($ext, $this->allowed_ext)) {
			die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Extention ini tidak bisa diupload.."}, "id" : "id"}');
		}

		// Clean the fileName for security reasons
		$fileName = preg_replace('/[^\w\._]+/', '_', $fileName);
		
		// Make sure the fileName is unique but only if chunking is disabled
		if ($chunks < 2 && file_exists($targetDir . "/" . $fileName)) {
			$ext = strrpos($fileName, '.');
			$fileName_a = substr($fileName, 0, $ext);
			$fileName_b = substr($fileName, $ext);
			
			$count = 1;
			while (file_exists($targetDir . "/" . $fileName_a . '_' . $count . $fileName_b))
			$count++;
			
			$fileName = $fileName_a . '_' . $count . $fileName_b;
		}
		
		$filePath = $targetDir . "/" . $fileName;
		
		// Create target dir
		if (!file_exists($targetDir))
		@mkdir($targetDir);
		
		// Remove old temp files	
		if ($cleanupTargetDir) {
			if (is_dir($targetDir) && ($dir = opendir($targetDir))) {
				while (($file = readdir($dir)) !== false) {
					$tmpfilePath = $targetDir . "/" . $file;
					
					// Remove temp file if it is older than the max age and is not the current file
					if (preg_match('/\.part$/', $file) && (filemtime($tmpfilePath) < time() - $maxFileAge) && ($tmpfilePath != "{$filePath}.part")) {
						@unlink($tmpfilePath);
					}
				}
				closedir($dir);
			} else {
				die('{"jsonrpc" : "2.0", "error" : {"code": 100, "message": "Failed to open temp directory."}, "id" : "id"}');
			}
		}	
		
		// Look for the content type header
		if (isset($_SERVER["HTTP_CONTENT_TYPE"]))
		$contentType = $_SERVER["HTTP_CONTENT_TYPE"];
		
		if (isset($_SERVER["CONTENT_TYPE"]))
		$contentType = $_SERVER["CONTENT_TYPE"];
		
		// Handle non multipart uploads older WebKit versions didn't support multipart in HTML5
		if (strpos($contentType, "multipart") !== false) {
			if (isset($_FILES['file']['tmp_name']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
				// Open temp file
				$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
				if ($out) {
					// Read binary input stream and append it to temp file
					$in = @fopen($_FILES['file']['tmp_name'], "rb");
					
					if ($in) {
						while ($buff = fread($in, 4096))
						fwrite($out, $buff);
					} else
					die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
					@fclose($in);
					@fclose($out);
					@unlink($_FILES['file']['tmp_name']);
				} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
			} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 103, "message": "Failed to move uploaded file."}, "id" : "id"}');
			} else {
			// Open temp file
			$out = @fopen("{$filePath}.part", $chunk == 0 ? "wb" : "ab");
			if ($out) {
				// Read binary input stream and append it to temp file
				$in = @fopen("php://input", "rb");
				
				if ($in) {
					while ($buff = fread($in, 4096))
					fwrite($out, $buff);
				} else
				die('{"jsonrpc" : "2.0", "error" : {"code": 101, "message": "Failed to open input stream."}, "id" : "id"}');
				
				@fclose($in);
				@fclose($out);
			} else
			die('{"jsonrpc" : "2.0", "error" : {"code": 102, "message": "Failed to open output stream."}, "id" : "id"}');
		}
		
		// Check if file has been uploaded
		if (!$chunks || $chunk == $chunks - 1) {
			// Strip the temp .part suffix off 
			rename("{$filePath}.part", $filePath);
		}

		/*
		// create thumbnail image // sementara off
		if($fileName_b == ".jpg" || $fileName_b == ".jpeg" || $fileName_b == ".png")
		{
			//$thumb = $this->thumbailImage(200,200,$fileName,$targetDir);
		}
		/*	*/
		
		$result['jsonrpc'] = '2.0';
		$result['filePath'] = $targetDir;
		$result['relativePath'] = $relativePath;
		$result['fileName'] = basename($filePath);
		$result['new_dir'] = $newTargetDir['newDir'];
		die(json_encode($result));
	}
}