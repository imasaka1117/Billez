<?php

class Common extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('web/param');
		$this->load->model('db/query_model');
		$this->load->library('web/option');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/field_3');
		$this->load->library('db/field_4');
		$this->load->library('db/table_1');
		$this->load->library('sql');
	}
	
	/*
	 * 將等級選單初始化
	 */
	public function init_level() {
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