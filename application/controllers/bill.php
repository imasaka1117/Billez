<?php

class Bill extends CI_Controller {
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
	 * 匯入入帳帳單
	*/
	public function import_receive() {
		if(!file_exists('./resources/upload/receive_bill/' . $this->input->get('trader') . $this->input->get('bill_kind')))
			mkdir('./resources/upload/receive_bill/' . $this->input->get('trader') . $this->input->get('bill_kind'));
	
		$config['upload_path'] = './resources/upload/receive_bill/' . $this->input->get('trader') . $this->input->get('bill_kind');
		$config['allowed_types'] = 'csv';
	
		$this->load->library('upload', $config);
	
		if($this->upload->do_upload('bill_file') === false) {
			$result = $this->upload->display_errors();
		} else {
			$result = $this->upload->data();
		}
	
		session_start();
		$this->load->model('web/bill_import_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_import_model->import_receive($result, $this->input->get(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 匯入入帳帳單頁面
	 */
	public function import_receive_web() {
		$data = $this->param->resources(array('ajax_file_js'=>Param::$ajax_file_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['class_name'] = 'bill';
		$data['now_use'] = 'bill/bill_import.js';
		$data['function_name'] = 'import_receive';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/import_pay', $data);
	}
	
	/*
	 * 推播帳單
	 */
	public function push_bill() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->model('send/push_model');
		$this->load->library('push');
		
		$data['ajax'] = $this->bill_model->push_bill($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 推播帳單頁面
	 */
	public function push_bill_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['class_name'] = 'bill';
		$data['now_use'] = 'bill/bill_push.js';
		$data['function_name'] = 'push_bill';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/push_bill', $data);
	}
	
	/*
	 * 匯入繳費帳單
	 */
	public function import_pay() {
		if(!file_exists('./resources/upload/pay_bill/' . $this->input->get('trader') . $this->input->get('bill_kind')))
			mkdir('./resources/upload/pay_bill/' . $this->input->get('trader') . $this->input->get('bill_kind'));
		
		$config['upload_path'] = './resources/upload/pay_bill/' . $this->input->get('trader') . $this->input->get('bill_kind');
		$config['allowed_types'] = 'csv';
		
		$this->load->library('upload', $config);
		
		if($this->upload->do_upload('bill_file') === false) {
			$result = $this->upload->display_errors();
		} else {
			$result = $this->upload->data();
		}

		session_start();
		$this->load->model('web/bill_import_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_import_model->import_pay($result, $this->input->get(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢入帳帳單匯入紀錄列表
	 */
	public function search_import_receive_log() {
		$this->load->model('web/bill_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->bill_model->search_import_receive_log($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢繳費帳單匯入紀錄列表
	 */
	public function search_import_pay_log() {
		$this->load->model('web/bill_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->bill_model-> search_import_pay_log($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 匯入繳費帳單頁面
	 */
	public function import_pay_web() {
		$data = $this->param->resources(array('ajax_file_js'=>Param::$ajax_file_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['class_name'] = 'bill';
		$data['now_use'] = 'bill/bill_import.js';
		$data['function_name'] = 'import_pay';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/import_pay', $data);
	}
	
	/*
	 * 新增客製化繳費帳單設定頁面
	 */
// 	public function insert_customer_set_web() {
// 		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
// 		$data['class_name'] = 'bill';
// 		$data['now_use'] = 'bill/bill_normal_set_insert_update.js';
// 		$data['function_name'] = 'insert_receive_set';
// 		$this->load->view('templates/header', $data);
// 		$this->load->view('web/bill/insert_receive_set', $data);
// 	}

	/*
	 * 更新繳費帳單設定
	 */
	public function update_receive_set() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_model->update_receive_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更新繳費帳單設定
	 */
	public function update_pay_set() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_model->update_pay_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢繳費帳單設定資料
	 */
	public function search_normal_set_data() {
		$this->load->model('web/bill_model');
		$data['ajax'] = $this->bill_model->search_normal_set_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改入帳帳單設定頁面
	 */
	public function update_receive_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['class_name'] = 'bill';
		$data['now_use'] = 'bill/bill_normal_set_insert_update.js';
		$data['function_name'] = 'update_receive_set';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/update_receive_set', $data);
	}
	
	/*
	 * 更改繳費帳單設定頁面
	 */
	public function update_pay_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['class_name'] = 'bill';
		$data['now_use'] = 'bill/bill_normal_set_insert_update.js';
		$data['function_name'] = 'update_pay_set';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/update_pay_set', $data);
	}
	
	/*
	 * 新增入帳帳單設定
	 */
	public function insert_receive_set() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_model->check_receive_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增入帳帳單設定頁面
	 */
	public function insert_receive_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['class_name'] = 'bill';
		$data['now_use'] = 'bill/bill_normal_set_insert_update.js';
		$data['function_name'] = 'insert_receive_set';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/insert_receive_set', $data);
	}
	
	/*
	 * 新增繳費帳單設定
	 */
	public function insert_pay_set() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_model->check_pay_set($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增繳費帳單設定頁面
	 */
	public function insert_pay_set_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'bill/bill_normal_set_insert_update.js';
		$data['class_name'] = 'bill';
		$data['function_name'] = 'insert_pay_set';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/insert_pay_set', $data);
	}
	
	/*
	 * 查詢帳單資料
	 */
	public function search_data() {
		$this->load->model('web/bill_model');
		$data['ajax'] = $this->bill_model->search_bill_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改帳單頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'bill/bill_update.js';
		$data['class_name'] = 'bill';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/update', $data);
	}
	
	/*
	 * 查詢帳單列表
	 */
	public function search() {
		$this->load->model('web/bill_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->bill_model->search_bill($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢帳單頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'bill/bill_search.js';
		$data['class_name'] = 'bill';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/search', $data);
	}
	
	/*
	 * 新增帳單依據
	 */
	public function insert_basis() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_model->check_bill_basis($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增帳單依據頁面
	 */
	public function insert_basis_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'bill/bill_basis_insert.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/insert_basis', $data);
	}
	
	/*
	 * 新增帳單種類
	 */
	public function insert_kind() {
		session_start();
		$this->load->model('web/bill_model');
		$this->load->library('create');
		$data['ajax'] = $this->bill_model->check_bill_kind($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增帳單種類頁面
	 */
	public function insert_kind_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'bill/bill_kind_insert.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/insert_kind', $data);
	}
}//end