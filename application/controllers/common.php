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
	 * 將已推播帳單選單初始化
	 */
	public function init_pushed_bill() {
		//查詢已推播的帳單匯入資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code, Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$import_bill_kind, Field_2::$file_name, Field_1::$year, Field_1::$month), ''),
																		 'from' => Table_1::$bill_import_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where', 'where', 'where'), array(Field_1::$year, Field_1::$month, Field_1::$pushed, Field_1::$import_bill_kind), array(date("Y") - 1911, date("n"), 'y', $this->input->post('value')), array('', '', '', '')),
																		 'other' => '')), 'result_array');
		$push_data = array();
		if(count($sql_result)) {
			foreach($sql_result as $data) {
				//查詢業者帳單名稱
				$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name'), 'function'),
																				 'from' => Table_1::$trader_bill,
																				 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$trader_bill . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$trader_bill . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																				 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($data['trader_code'], $data['bill_kind_code']), array('', '')),
																				 'other' => '')), 'row_array');
				array_push($push_data, array('code'=>$data['trader_code'] . ',' . $data['bill_kind_code'] . ',' . $data['import_bill_kind'] . ',' . $data['batch_code'] . ',' . $data['year'] . ',' . $data['month'], 
										'name'=>$sql_result['trader_name'] . $sql_result['bill_kind_name'] . ' ' . $data['year'] . '年  ' . $data['month'] . '月份 ' . $data['file_name'] . ' 帳單'));
			}
		} else {
			array_push($push_data, array('code'=>'', 'name'=>'查無已推播的帳單資料'));
		}
		
		$data['ajax'] = $this->option->select($push_data, 1);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將尚未推播帳單選單初始化 
	 */
	public function init_not_push_bill() {
		//查詢未推播的帳單匯入資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$batch_code, Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$import_bill_kind, Field_2::$file_name, Field_1::$year, Field_1::$month), ''),
																		 'from' => Table_1::$bill_import_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where', 'where', 'where'), array(Field_1::$year, Field_1::$month, Field_1::$pushed, Field_1::$import_bill_kind), array(date("Y") - 1911, date("n"), 'n', $this->input->post('value')), array('', '', '', '')),
																		 'other' => '')), 'result_array');
		$push_data = array();
		if(count($sql_result)) {
			foreach($sql_result as $data) {
				//查詢業者帳單名稱
				$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name'), 'function'),
																				 'from' => Table_1::$trader_bill,
																				 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$trader_bill . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$trader_bill . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																				 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($data['trader_code'], $data['bill_kind_code']), array('', '')),
																				 'other' => '')), 'row_array');
				array_push($push_data, array('code'=>$data['trader_code'] . ',' . $data['bill_kind_code'] . ',' . $data['import_bill_kind'] . ',' . $data['batch_code'] . ',' . $data['year'] . ',' . $data['month'], 
										'name'=>$sql_result['trader_name'] . $sql_result['bill_kind_name'] . ' ' . $data['year'] . '年  ' . $data['month'] . '月份 ' . $data['file_name'] . ' 帳單'));
			}
		} else {
			array_push($push_data, array('code'=>'', 'name'=>'查無未推播的帳單資料'));
		}
		
		$data['ajax'] = $this->option->select($push_data, 1);
		$this->load->view('web/ajax', $data);
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
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
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
}//end