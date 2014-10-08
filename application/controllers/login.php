<?php

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	/*
	 * login畫面起點
	 */
	public function index() {
		$this->load->library('web/param');
		
		$data['title'] = 'Billez 管理系統';
		$data['css_path'] = Param::$css_path;
		$data['login_css'] = Param::$login_css;
		$data['js_path'] = Param::$js_path;
		$data['jquery_js'] = Param::$jquery_js;
		$data['function_js'] = Param::$function_js;
		$data['login_js'] = Param::$login_js;
		$data['index_url'] = Param::$index_url;
		$this->load->view('web/login/login.php', $data);
	}

	/*
	 * login驗證登入資料
	 */
	public function check_login() {
		
	}
	
	
}//end
