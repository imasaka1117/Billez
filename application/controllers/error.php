<?php

class Error extends CI_Controller {
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
	 * 查詢系統錯誤資料
	 */
	public function search_system() {
		$this->load->model('web/error_model');
		$this->load->library('web/option');
		$this->load->library('transform');
		$data['ajax'] = $this->error_model->search_system($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢系統錯誤頁面
	 */
	public function search_system_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'error/error_system_search.js';
		$data['class_name'] = 'error';
		$this->load->view('templates/header', $data);
		$this->load->view('web/error/search_system', $data);
	}
	
	/*
	 * 查詢發簡訊失敗資料
	 */
	public function search_sms() {
		$this->load->model('web/error_model');
		$this->load->library('web/option');
		$this->load->library('transform');
		$data['ajax'] = $this->error_model->search_sms($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢寄發簡訊失敗頁面
	 */
	public function search_sms_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'error/error_sms_search.js';
		$data['class_name'] = 'error';
		$this->load->view('templates/header', $data);
		$this->load->view('web/error/search_sms', $data);
	}
	
	/*
	 * 查詢發電子郵件失敗資料
	 */
	public function search_email() {
		$this->load->model('web/error_model');
		$this->load->library('web/option');
		$this->load->library('transform');
		$data['ajax'] = $this->error_model->search_email($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢寄發電子郵件失敗頁面
	 */
	public function search_email_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'error/error_email_search.js';
		$data['class_name'] = 'error';
		$this->load->view('templates/header', $data);
		$this->load->view('web/error/search_email', $data);
	}
	
	/*
	 * 推播尚未讀取的會員
	 */
	public function push_not_read() {
		session_start();
		$this->load->model('web/error_model');
		$this->load->model('send/push_model');
		$this->load->library('push');
		$data['ajax'] = $this->error_model->push_not_read($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢推播失敗資料
	 */
	public function search_push() {
		$this->load->model('web/error_model');
		$this->load->library('web/option');
		$this->load->library('transform');
		$data['ajax'] = $this->error_model->search_push($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢推播錯誤頁面
	 */
	public function search_push_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'error/error_push_search.js';
		$data['class_name'] = 'error';
		$this->load->view('templates/header', $data);
		$this->load->view('web/error/search_push', $data);
	}
	
	/*
	 * 更新帳單匯入錯誤資料
	 */
	public function update_bill_import() {
		session_start();
		$this->load->model('web/error_model');
		$data['ajax'] = $this->error_model->update_bill_import($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢帳單匯入錯誤資料
	 */
	public function search_bill_import_data() {
		$this->load->model('web/error_model');
		$data['ajax'] = $this->error_model->search_bill_import_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 觀看帳單匯入錯誤資料頁面
	 */
	public function update_bill_import_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'error/error_bill_import_update.js';
		$data['class_name'] = 'error';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/error/update_bill_import', $data);
	}
	
	/*
	 * 查詢帳單匯入錯誤資料
	 */
	public function search_bill_import() {
		$this->load->model('web/error_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->error_model->search_bill_import($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢帳單匯入錯誤頁面
	 */
	public function search_bill_import_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'error/error_bill_import_search.js';
		$data['class_name'] = 'error';
		$this->load->view('templates/header', $data);
		$this->load->view('web/error/search_bill_import', $data);
	}
}//end