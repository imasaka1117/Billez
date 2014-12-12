<?php

class Action_member extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('web/param');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/field_3');
		$this->load->library('db/field_4');
		$this->load->library('db/table_1');
		$this->load->library('sql');
	}
	
	/*
	 * 更新行動會員密碼
	 */
	public function update() {
		session_start();
		$this->load->model('web/action_member_model');
		$this->load->model('send/email_model');
		$this->load->library('create');
		$this->load->library('email');
		$data['ajax'] = $this->action_member_model->update_password($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢行動會員資料
	 */
	public function search_data() {
		$this->load->model('web/action_member_model');
		$data['ajax'] = $this->action_member_model->search_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 觀看行動會員資料頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'action_member/action_member_update.js';
		$data['class_name'] = 'action_member';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/action_member/update', $data);
	}
	
	/*
	 * 查詢行動會員
	 */
	public function search() {
		$this->load->model('web/action_member_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->action_member_model->search_action_member($this->input->post());
		$this->load->view('web/ajax', $data);
	}

	/*
	 * 查詢行動會員頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'action_member/action_member_search.js';
		$data['class_name'] = 'action_member';
		$this->load->view('templates/header', $data);
		$this->load->view('web/action_member/search', $data);
	}
	
	/*
	 * 匯出行動會員資料
	*/
	public function export() {
		$this->load->model('web/action_member_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->action_member_model->export($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 匯出行動會員資料
	 */
	public function export_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'action_member/action_member_export.js';
		$data['class_name'] = 'action_member';
		$this->load->view('templates/header', $data);
		$this->load->view('web/action_member/export', $data);
	}
}//end