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
		$data['now_use'] = 'bill_set_insert.js';
		$data['class_path'] = 'bill/insert_receive_set';
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
		$data['now_use'] = 'bill_set_insert.js';
		$data['class_path'] = 'bill/insert_pay_set';
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
		$data['now_use'] = 'bill_update.js';
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
		$data['now_use'] = 'bill_search.js';
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
		$data['now_use'] = 'bill_basis_insert.js';
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
		$data['now_use'] = 'bill_kind_insert.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/bill/insert_kind', $data);
	}
	
	/*
	 * 將業者名稱初始化
	 */
	public function init_trader() {
		$this->load->library('web/option');
	
		//查詢業者編號及業者名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$trader_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將年度初始化
	 */
	public function init_year() {
		$this->load->library('web/option');
	
		//查詢帳單年度
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$year . ' AS code', Field_1::$year . ' AS name'), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將業者帳單種類初始化
	 */
	public function init_bill_kind() {
		$this->load->library('web/option');
	
		//查詢帳單種類名稱和代號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$trader_bill,
																		 'join'=> $this->sql->join(array(Table_1::$bill_kind_code), array(Table_1::$trader_bill . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where(array('where'), array(Field_1::$trader_code), array($this->input->post('value')), array('')),
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將該年月份初始化
	 */
	public function init_month() {
		$this->load->library('web/option');
	
		//查詢帳單種類名稱和代號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$month . ' AS code', Field_1::$month . ' AS name'), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$year), array($this->input->post('value')), array('')),
																		 'other' => $this->sql->other(array('distinct'), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
}//end