<?php

class Machinery extends CI_Controller {
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
	
	//暫存
	private function data1() {
		
	}
	
	/*
	 * 更新代收機構合約資料
	 */
	public function update_contract() {
		session_start();
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->check_update_machinery_contract($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data2() {
	
	}
	
	/*
	 * 查詢代收機構合約資料
	 */
	public function search_contract_data() {
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->search_machinery_contract_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data3() {
	
	}
	
	/*
	 * 更改代收機構合約頁面
	 */
	public function update_contract_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['id'] = $this->input->get('id');
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_insert_update.js';
		$data['class_name'] = 'machinery';
		$data['now_use2'] = 'trader_machinery/trader_machinery_contract_insert_update2.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/update_contract', $data);
	}
	
	//暫存
	private function data4() {
	
	}
	
	/*
	 * 查詢代收機構合約列表
  	 */
	public function search_contract() {
		$this->load->model('web/machinery_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->machinery_model->search_machinery_contract($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data5() {
	
	}
	
	/*
	 * 查詢代收機構合約頁面
	 */
	public function search_contract_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_search.js';
		$data['class_name'] = 'machinery';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/search_contract', $data);
	}
	
	//暫存
	private function data6() {
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_search.js';
		$data['class_name'] = 'machinery';
	}
	
	/*
	 * 更新代收機構資料
	 */
	public function update() {
		session_start();
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->check_update_machinery($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data7() {
	
	}
	
	/*
	 * 查詢代收機構資料
	 */
	public function search_data() {
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->search_machinery_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data8() {
	
	}
	
	/*
	 * 更改代收機構頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['level_value'] = 'b';
		$data['id'] = $this->input->get('id');
		$data['now_use'] = 'trader_machinery/trader_machinery_insert_update.js';
		$data['class_name'] = 'machinery';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/update', $data);
	}
	
	//暫存
	private function data9() {
	
	}
	
	/*
	 * 查詢代收機構列表
	 */
	public function search() {
		$this->load->model('web/machinery_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->machinery_model->search_machinery($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data10() {
	
	}
	
	/*
	 * 查詢代收機構頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery/trader_machinery_search.js';
		$data['class_name'] = 'machinery';
		$data['level_value'] = 'b';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/search', $data);
	}
	
	//暫存
	private function data11() {
	
	}
	
	/*
	 * 新增代收機構處理
	 */
	public function insert() {
		session_start();
		$this->load->model('web/machinery_model');
		$this->load->library('create');
		$data['ajax'] = $this->machinery_model->check_machinery($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data12() {
	
	}
	
	/*
	 * 新增代收機構合約處理
	 */
	public function insert_contract() {
		session_start();
		$this->load->model('web/machinery_model');
		$this->load->library('create');
		$data['ajax'] = $this->machinery_model->check_machinery_contract($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	//暫存
	private function data13() {
	
	}
	
	/*
	 * 新增代收機構合約頁面
	 */
	public function insert_contract_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery/trader_machinery_contract_insert_update.js';
		$data['now_use2'] = 'trader_machinery/trader_machinery_contract_insert_update2.js';
		$data['class_name'] = 'machinery';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/insert_contract', $data);
	}
	
	//暫存
	private function data14() {
	
	}
	
	/*
	 * 新增代收機構頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery/trader_machinery_insert_update.js';
		$data['class_name'] = 'machinery';
		$data['level_value'] = 'b';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/insert', $data);
	}
	
	//暫存
	private function data15() {
	
	}
}//end