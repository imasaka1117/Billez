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
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_insert_update.js';
		$data['now_use2'] = 'trader_machinery/trader_machinery_contract_insert_update2.js';
		$data['id'] = $this->input->get('id');
		$data['class_name'] = 'trader';
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
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_search.js';
		$data['class_name'] = 'trader';
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
		$data['now_use'] = 'trader_machinery/trader_machinery_insert_update.js';
		$data['id'] = $this->input->get('id');
		$data['level_value'] = 'a';
		$data['class_name'] = 'trader';
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
		$data['now_use'] = 'trader_machinery/trader_machinery_search.js';
		$data['level_value'] = 'a';
		$data['class_name'] = 'trader';
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
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_insert_update.js';
		$data['now_use2'] = 'trader_machinery/trader_machinery_contract_insert_update2.js';
		$data['class_name'] = 'trader';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/insert_contract', $data);
	}
	
	/*
	 * 新增業者頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery/trader_machinery_insert_update.js';
		$data['class_name'] = 'trader';
		$data['level_value'] = 'a';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/insert', $data);
	}
}//end