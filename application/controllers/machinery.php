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
	
	/*
	 * 更新代收機構合約資料
	 */
	public function update_contract() {
		session_start();
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->check_update_machinery_contract($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 查詢代收機構合約資料
	 */
	public function search_contract_data() {
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->search_machinery_contract_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改代收機構合約頁面
	 */
	public function update_contract_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'machinery_contract_update.js';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/update_contract', $data);
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
	
	/*
	 * 查詢代收機構合約頁面
	 */
	public function search_contract_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'machinery_contract_search.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/search_contract', $data);
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
	
	/*
	 * 查詢代收機構資料
	 */
	public function search_data() {
		$this->load->model('web/machinery_model');
		$data['ajax'] = $this->machinery_model->search_machinery_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 更改代收機構頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_machinery_update.js';
		$data['id'] = $this->input->get('id');
		$data['class_path'] = 'trader_machinery_update.js';
		$data['error_word'] = '代收機構名稱已存在!!';
		$data['level_init'] = 'machinery/init_level';
		$data['level_value'] = 'b';
		$data['search_path'] = 'machinery/search_data';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/update', $data);
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
	
	/*
	 * 查詢代收機構頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'machinery_search.js';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/search', $data);
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
	
	/*
	 * 新增代收機構合約頁面
	 */
	public function insert_contract_web() {
		$data = $this->param->resources(array('date_js'=>Param::$date_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'machinery_contract_insert.js';
		$data['title'] = 'Billez 新增代收機構合約';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/insert_contract', $data);
	}
	
	/*
	 * 新增代收機構頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'machinery_insert.js';
		$data['class_path'] = 'trader_machinery_insert.js';
		$data['error_word'] = '代收機構名稱已存在!!';
		$data['level_init'] = 'machinery/init_level';
		$data['level_value'] = 'b';
		$this->load->view('templates/header', $data);
		$this->load->view('web/machinery/insert', $data);
	}
	
	/*
	 * 將等級選單初始化
	 */
	public function init_level() {
		$this->load->library('web/option');
		
		//查詢代收機構等級代碼及名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$kind), array($this->input->post('value')), array('')),
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將代收代收機構名稱初始化
	 */
	public function init_machinery() {
		$this->load->library('web/option');
		
		//查詢代收機構編號及代收機構名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$machinery_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將代收代收機構合約初始化
	 */
	public function init_machinery_contract() {
		$this->load->library('web/option');
		
		//查詢代收機構編號及代收機構名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id . ' AS code', Field_1::$name), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_2::$machinery_code), array($this->input->post('value')), array('')),
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result);
		$this->load->view('web/ajax', $data);
	}
}//end