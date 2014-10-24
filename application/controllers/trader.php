<?php

class Trader extends CI_Controller {
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
	 * 更新業者合約資料
	 */
	public function update_contract() {
		session_start();
		$this->load->model('web/trader_model');
		$data['ajax'] = $this->trader_model->check_update_trader_contract($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢業者合約資料
	 */
	public function search_contract_data() {
		$this->load->model('web/trader_model');
		$data['ajax'] = $this->trader_model->search_trader_contract_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改業者合約頁面
	 */
	public function update_contract_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_contract_update.js';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/update_contract', $data);
	}
	
	/*
	 * 查詢業者合約列表
  	 */
	public function search_contract() {
		$this->load->model('web/trader_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->trader_model->search_trader_contract($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢業者合約頁面
	 */
	public function search_contract_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_contract_search.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/search_contract', $data);
	}
	
	/*
	 * 更新業者資料
	 */
	public function update() {
		session_start();
		$this->load->model('web/trader_model');
		$data['ajax'] = $this->trader_model->check_update_trader($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢業者資料
	 */
	public function search_data() {
		$this->load->model('web/trader_model');
		$data['ajax'] = $this->trader_model->search_trader_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改業者頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery_update.js';
		$data['id'] = $this->input->get('id');
		$data['class_path'] = 'trader_machinery_update.js';
		$data['error_word'] = '業者名稱已存在!!';
		$data['level_init'] = 'trader/init_level';
		$data['level_value'] = 'a';
		$data['search_path'] = 'trader/search_data';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/update', $data);
	}
	
	/*
	 * 查詢業者列表
	 */
	public function search() {
		$this->load->model('web/trader_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->trader_model->search_trader($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢業者頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_search.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/search', $data);
	}
	
	/*
	 * 新增業者處理
	 */
	public function insert() {
		session_start();
		$this->load->model('web/trader_model');
		$this->load->library('create');
		$data['ajax'] = $this->trader_model->check_trader($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增業者合約處理
	 */
	public function insert_contract() {
		session_start();
		$this->load->model('web/trader_model');
		$this->load->library('create');
		$data['ajax'] = $this->trader_model->check_trader_contract($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增業者合約頁面
	 */
	public function insert_contract_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_contract_insert.js';
		$data['title'] = 'Billez 新增業者合約';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/insert_contract', $data);
	}
	
	/*
	 * 新增業者頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_insert.js';
		$data['title'] = 'Billez 新增業者';
		$data['class_path'] = 'trader_machinery_insert.js';
		$data['error_word'] = '業者名稱已存在!!';
		$data['level_init'] = 'trader/init_level';
		$data['level_value'] = 'a';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/insert', $data);
	}
	
	/*
	 * 將等級選單初始化
	 */
	public function init_level() {
		$this->load->library('web/option');
		
		//查詢業者等級代碼及名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$kind), array($this->input->post('value')), array('')),
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
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
	 * 將代收業者名稱初始化
	 */
	public function init_machinery() {
		$this->load->library('web/option');
		
		//查詢業者編號及業者名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$machinery_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將代收業者合約初始化
	 */
	public function init_machinery_contract() {
		$this->load->library('web/option');
		
		//查詢業者編號及業者名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id . ' AS code', Field_1::$name), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$machinery_code), array($this->input->post('value')), array('')),
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將帳單種類初始化
	 */
	public function init_bill_kind() {
		$this->load->library('web/option');
	
		//查詢帳單種類名稱和代號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$bill_kind_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將帳單依據初始化
	 */
	public function init_bill_basis() {
		$this->load->library('web/option');
	
		//查詢帳單依據名稱和代號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$bill_basis,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
}//end