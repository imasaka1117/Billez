<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Param {
	/*
	 * 關於網頁引用路徑
	 */
	//css
	static public $login_css = 'resources/css/login.css';
	static public $base_css = 'resources/css/base.css';
	
	//js
	static public $js_path = 'resources/js/';
	static public $jquery_js = 'resources/js/function/jQuery 1.11.0.min.js';
	static public $ajax_file_js = 'resources/js/function/ajaxfileupload.js';
	static public $validate_js = 'resources/js/function/validate.js';
	static public $address_js = 'resources/js/function/address.js';
	static public $date_js = 'resources/js/function/date.js';
	static public $function_js = 'resources/js/function/function.js';
	
	//網址路徑
	static public $index_url = 'index.php/';

	public function resources($paths) {
		$data = array();
		
		foreach($paths as $item => $value) {
			$data[$item] = base_url() . $value;
		}
		
		return $data;
	}
	
	
}//end