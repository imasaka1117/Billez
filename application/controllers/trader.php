<?php

class Trader extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('web/param');
	}
	
	/*
	 * 新增業者處理
	 */
	public function insert() {
		session_start();
		$this->load->model('web/trader_model');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/field_3');
		$this->load->library('db/field_4');
		$this->load->library('db/table_1');
		$this->load->library('create.php');
		$this->load->library('sql');
		$data['ajax'] = $this->trader_model->check_trader($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 新增業者頁面
	 */
	public function insert_web() {
		$data = $this->param->resources(array('address_js'=>Param::$address_js, 'validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'trader_insert.js';
		$data['title'] = 'Billez 新增業者';
		$this->load->view('templates/header', $data);
		$this->load->view('web/trader/insert', $data);
	}
	
	/*
	 * 將等級選單初始化
	 */
	public function init_level() {
		$this->load->library('sql');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/table_1');
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
	
	
	
}//end