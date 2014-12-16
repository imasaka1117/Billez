<?php

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library('web/param');
	}

	/*
	 * login畫面起點
	 */
	public function index() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'login_css'=>Param::$login_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'login/login_login.js';
		$this->load->view('web/login/login', $data);
	}

	/*
	 * login驗證登入資料
	 */
	public function check_login() {
		$this->load->model('web/login_model');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/table_1');
		$this->load->library('sql');
		$this->load->library('transform');
		$data['ajax'] = $this->login_model->login($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 登出清除session資料
	*/
	public function logout() {
		$this->load->model('web/login_model');
		$this->login_model->logout();
	}
}//end