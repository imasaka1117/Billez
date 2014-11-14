<?php

class Operate extends CI_Controller {
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
		$this->load->library('db/field_5');
		$this->load->library('db/table_1');
		$this->load->library('sql');
	}

	/*
	 * 查詢操作紀錄資料
	 */
	public function search_operate() {
		$this->load->model('web/operate_model');
		$this->load->library('web/option');
		$this->load->library('transform');
		$data['ajax'] = $this->operate_model->search_operate($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢操作紀錄頁面
	 */
	public function search_operate_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_operate_search.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/search_operate', $data);
	}
	
	/*
	 * 新增電子郵件設定頁面
	 */
	public function insert_email_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_email_set_insert.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/insert_email_set', $data);
	}
	
	/*
	 * 新增電子郵件設定
	 */
	public function insert_email_set() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->insert_email_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢電子郵件設定頁面
	 */
	public function search_email_set_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_email_set_search.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/search_email_set', $data);
	}
	
	/*
	 * 更新電子郵件設定頁面
	 */
	public function update_email_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_email_set_update.js';
		$data['class_name'] = 'operate';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/update_email_set', $data);
	}
	
	/*
	 * 查詢電子郵件設定資料
	 */
	public function search_email_set_data() {
		$this->load->model('web/operate_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->operate_model->search_email_set_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更新電子郵件設定
	 */
	public function update_email_set() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->update_email_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增系統設定頁面
	 */
	public function insert_system_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_system_set_insert.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/insert_system_set', $data);
	}
	
	/*
	 * 新增系統設定
	 */
	public function insert_system_set() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->insert_system_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢系統設定頁面
	 */
	public function search_system_set_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_system_set_search.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/search_system_set', $data);
	}
	
	/*
	 * 更新系統設定頁面
	 */
	public function update_system_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_system_set_update.js';
		$data['class_name'] = 'operate';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/update_system_set', $data);
	}
	
	/*
	 * 查詢系統設定資料
	 */
	public function search_system_set_data() {
		$this->load->model('web/operate_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->operate_model->search_system_set_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更新系統設定
	 */
	public function update_system_set() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->update_system_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增簡訊設定頁面
	 */
	public function insert_sms_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_sms_set_insert.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/insert_sms_set', $data);
	}
	
	/*
	 * 新增簡訊設定
	 */
	public function insert_sms_set() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->insert_sms_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢簡訊設定頁面
	 */
	public function search_sms_set_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_sms_set_search.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/search_sms_set', $data);
	}
	
	/*
	 * 更新簡訊設定頁面
	 */
	public function update_sms_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_sms_set_update.js';
		$data['class_name'] = 'operate';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/update_sms_set', $data);
	}
	
	/*
	 * 查詢簡訊設定資料
	 */
	public function search_sms_set_data() {
		$this->load->model('web/operate_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->operate_model->search_sms_set_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更新簡訊設定
	 */
	public function update_sms_set() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->update_sms_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 排程設定頁面
	 */
	public function scheduling_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'operate/operate_scheduling_set.js';
		$data['class_name'] = 'operate';
		$this->load->view('templates/header', $data);
		$this->load->view('web/operate/scheduling_set', $data);
	}
	
	/*
	 * 更改排程 啟動或停止
	 */
	public function change_scheduling() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->change_scheduling($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 暫停天數
	 */
	public function stop_day() {
		session_start();
		$this->load->model('web/operate_model');
		$data['ajax'] = $this->operate_model->stop_day($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 繳費帳單排程
	 */
	public function pay() {
		$this->load->model('web/operate_model');
		$this->load->model('web/bill_import_model');
		$this->load->library('create');
		$this->load->library('ftp');
		$this->operate_model->pay(array('id' => '2'));
	}
	
	/*
	 * 入帳帳單排程
	 */
	public function receive() {
		$this->load->model('web/operate_model');
		$this->load->model('web/bill_import_model');
		$this->load->library('ftp');
		$this->operate_model->receive(array('id' => '2'));
	}
	
	/*
	 * 推播未讀取帳單排程
	 */
	public function push() {
		$this->load->model('web/operate_model');
		$this->load->model('send/push_model');
		$this->load->library('push');
		$this->operate_model->push(array('id' => '2'));
	}
	
	/*
	 * 可能帳單配對排程
	 */
	public function possible() {
		$this->load->model('web/operate_model');
		$this->load->model('app/bill_model');
		$this->load->model('other/format_model');
		$this->load->model('send/push_model');
		$this->load->library('push');
		$this->operate_model->possible(array('id' => '2'));
	}
}//end