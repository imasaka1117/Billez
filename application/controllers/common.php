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
		$this->load->library('db/field_5');
		$this->load->library('db/table_1');
		$this->load->library('sql');
		$this->load->library('transform');
	}
	
	/*
	 * 將排程啟用狀態初始化
	 */
	public function init_scheduling() {
		//查詢排程啟用狀態
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$state, Field_5::$day), ''),
																		 'from' => Table_1::$scheduling_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($this->input->post('value')), array('')),
																		 'other' => '')), 'row_array');
		if(isset($sql_result['day'])) {
			$sql_result['state'] = 'OFF 剩餘 : ' . $sql_result['day'] . '天';
		} else if($sql_result['state'] == 'n') {
			$sql_result['state'] = 'OFF';
		} else {
			$sql_result['state'] = 'ON';
		}

		$data['ajax'] = $sql_result['state'];
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將系統設定名稱初始化
	 */
	public function init_system_name() {
		//查詢操作者
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name . ' AS code',Field_1::$name . ' AS name'), 'function'),
																		 'from' => Table_1::$system_set,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將目前系統設定名稱初始化
	 */
	public function init_system_set() {
		//查詢已啟用的電子郵件設定
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$system_set,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$using), array('y'), array('')),
																		 'other' => '')), 'row_array');
		if(isset($sql_result['name'])) {
			$data['ajax'] = $sql_result['name'];
			$this->load->view('web/ajax', $data);
		}
	}
	
	/*
	 * 將電子郵件設定名稱初始化
	 */
	public function init_form_name() {
		//查詢設定名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$form_name . ' AS code',Field_2::$form_name . ' AS name'), 'function'),
																		 'from' => Table_1::$email_form,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將電子郵件設定初始化
	 */
	public function init_email_set() {
		//查詢已啟用的電子郵件設定
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$form_name), ''),
																		 'from' => Table_1::$email_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$state, Field_2::$form_kind), array('y', $this->input->post('value')), array('', '')),
																		 'other' => '')), 'row_array');
		if(isset($sql_result['form_name'])) {
			$data['ajax'] = $sql_result['form_name'];
			$this->load->view('web/ajax', $data);
		}
	}
	
	/*
	 * 將操作者初始化
	 */
	public function init_user() {
		//查詢操作者
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$user . ' AS code',Field_2::$user . ' AS name'), 'function'),
																		 'from' => Table_1::$user_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將操作資料表初始化
	 */
	public function init_table() {
		//查詢資料表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('`' . Field_3::$table . '` AS code','`' . Field_3::$table . '` AS name'), 'function'),
																		 'from' => Table_1::$user_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將操作種類選單初始化
	 */
	public function init_operate_kind() {
		//查詢操作種類
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$operate), ''),
																		 'from' => Table_1::$user_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$operate_data = array();
	
		foreach($sql_result as $data) array_push($operate_data, array('code'=>$data['operate'], 'name'=>$this->transform->system_operate($data['operate'])));
	
		$data['ajax'] = $this->option->select($operate_data, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將操作時間初始化
	 */
	public function init_operate_time() {
		//查詢發生時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_1::$create_time . ') AS code', 'DATE(' . Field_1::$create_time . ') AS name'), 'function'),
																		 'from' => Table_1::$user_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將系統錯誤時間選單初始化
	 */
	public function init_system_time() {
		//查詢發生時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_1::$create_time . ') AS code', 'DATE(' . Field_1::$create_time . ') AS name'), 'function'),
																		 'from' => Table_1::$system_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將寄發簡訊時間選單初始化
	 */
	public function init_sms_time() {
		//查詢寄發時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_1::$create_time . ') AS code', 'DATE(' . Field_1::$create_time . ') AS name'), 'function'),
																		 'from' => Table_1::$sms_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將寄發簡訊事件選單初始化
	 */
	public function init_sms_event() {
		//查詢寄發簡訊事件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$event), ''),
																		 'from' => Table_1::$sms_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$sms_data = array();
	
		foreach($sql_result as $data) array_push($sms_data, array('code'=>$data['event'], 'name'=>$this->transform->sms_event($data['event'])));
	
		$data['ajax'] = $this->option->select($sms_data, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將寄發電子郵件時間選單初始化
	 */
	public function init_email_time() {
		//查詢寄發時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_1::$create_time . ') AS code', 'DATE(' . Field_1::$create_time . ') AS name'), 'function'),
																		 'from' => Table_1::$email_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將寄發電子郵件事件選單初始化
	 */
	public function init_email_event() {
		//查詢寄發電子郵件事件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$event), ''),
																		 'from' => Table_1::$email_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$email_data = array();
	
		foreach($sql_result as $data) array_push($email_data, array('code'=>$data['event'], 'name'=>$this->transform->email_event($data['event'])));
	
		$data['ajax'] = $this->option->select($email_data, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將推播事件選單初始化
	 */
	public function init_event() {
		//查詢推播事件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$event), ''),
																		 'from' => Table_1::$push_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$push_data = array();
		
		foreach($sql_result as $data) array_push($push_data, array('code'=>$data['event'], 'name'=>$this->transform->push_event($data['event'])));

		$data['ajax'] = $this->option->select($push_data, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將推播時間選單初始化
	 */
	public function init_push_time() {
		//查詢推播時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_2::$time . ') AS code', 'DATE(' . Field_2::$time . ') AS name'), 'function'),
																		 'from' => Table_1::$push_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將匯入時間選單初始化
	 */
	public function init_time() {
		//查詢匯入時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_2::$time . ') AS code', 'DATE(' . Field_2::$time . ') AS name'), 'function'),
																		 'from' => Table_1::$import_error_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將處理情況選單初始化
	 */
	public function init_state() {
		//查詢提問時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$result), ''),
																		 'from' => Table_1::$import_error_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$push_data = array();
		
		foreach($sql_result as $data) array_push($push_data, array('code'=>$data['result'], 'name'=>$this->transform->import_error_result($data['result'])));

		$data['ajax'] = $this->option->select($push_data, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將匯入種類選單初始化
	 */
	public function init_import_kind() {
		//查詢提問時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$kind), ''),
																		 'from' => Table_1::$import_error_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$push_data = array();
		
		foreach($sql_result as $data) array_push($push_data, array('code'=>$data['kind'], 'name'=>$this->transform->import_bill_kind($data['kind'])));

		$data['ajax'] = $this->option->select($push_data, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將提問時間選單初始化
	 */
	public function init_ask_time() {
		//查詢提問時間
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DATE(' . Field_3::$ask_time . ') AS code', 'DATE(' . Field_3::$ask_time . ') AS name'), 'function'),
																		 'from' => Table_1::$problem_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => $this->sql->other(array('distinct'), array(''), array('')))), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將等級對象選單初始化
	 */
	public function init_object() {
		//查詢業者等級代碼及名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code, Field_1::$name), ''),
																		 'from' => Table_1::$level_kind,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->select($sql_result, '');
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 將目前該業者帳單的代收機構顯示
	 * 	
	 */
	public function now_machinery() {
		$data = explode(',', $this->input->post('value'));
		$trader = $data[0];
		$bill_kind = $data[1];
		
		//查詢該業者帳單的代收機構及合約
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery_name', Table_1::$machinery_contract . '.' . Field_1::$name . ' AS machinery_contract_name'), 'function'),
																		 'from' => Table_1::$trader_machinery,
																		 'join'=> $this->sql->join(array(Table_1::$machinery_code, Table_1::$machinery_contract), array(Table_1::$trader_machinery . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code, Table_1::$trader_machinery . '.' . Field_4::$machinery_contract . ' = ' . Table_1::$machinery_contract . '.' . Field_1::$id), array('', '')),
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($trader, $bill_kind), array('', '')),
																		 'other' => '')), 'result_array');
		$data['ajax'] = $this->option->table($sql_result, array('代收機構', '代收機構合約'), '');
		$this->load->view('web/ajax', $data);
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