<?php

class Subscribe_model extends CI_Model {
	/*
	 * 帳單訂閱起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '5_1':
				return $this->search_trader($route_data);
				break;
			case '5_2':
				return $this->subscribe_bill($route_data);
				break;
			case '5_3':
				return $this->cancel_subscribe($route_data);
				break;
			case '5_4':
				return $this->share_bill($route_data);
				break;
			default:
				break;
		}
	}
	
	/*
	 * 查詢該訂閱資料的業者資料
	 * $route_data	所需參數資料
	 */
	public function search_trader($route_data) {
		//解析業者帳單編號
		$trader_code = substr($route_data["trader_data"], 0, 4);
		$bill_kind_code  = substr($route_data["trader_data"], 4, 2);

		//查詢該業者帳單資料依據
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$bill_basis . '.' . Field_1::$name . ' AS ' . Field_1::$name), ''),
																		 'from' => Table_1::$trader_bill,
																		 'join'=> $this->sql->join(array(Table_1::$bill_basis), array(Table_1::$trader_bill . '.' . Field_1::$bill_kind_code . '=' . Table_1::$bill_basis . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($trader_code, $bill_kind_code), array('')),
																		 'other' => '')), 'row_array');
		$bill_basis_name = $sql_result['name'];

		//查詢該業者帳單的寄送條件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$send_condition, Field_4::$send_condition_times), ''),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$trader_code), array($trader_code), array('')),
																		 'other' => '')), 'row_array');
		//將代碼轉換為說明
		$sql_result['send_condition'] = $this->transform->send_condition($sql_result['send_condition']);
		
		if($sql_result['send_condition_times'] == '') $sql_result['send_condition_times'] = 'blank';
		
		$json_array['send_condition'] 	   = $sql_result['send_condition'];
		$json_array['send_condition_times'] = $sql_result['send_condition_times'];
		$json_array['bill_basis_name'] 	   = $bill_basis_name;
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $json_array), $route_data['private_key'], ''));
	}
	
	/*
	 * 訂閱失敗處理
	 * $route_data 所需參數資料
	 */
	public function subscribe_fail($route_data) {
		//查詢失敗訂閱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$subscribe_fail), ''),
																		 'from' => Table_1::$action_member_alter_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
																		 'other' => '')), 'row_array');
		$subscribe_fail = $sql_result['$subscribe_fail'] . ',' . substr($route_data['subscribe_code'], 6);
		
		//更新訂閱失敗資料
		$this->sql->add_static(array('table'=> Table_1::$action_member_alter_log,
									 'select'=> $this->sql->field(array(Field_1::$subscribe_fail, Field_1::$update_user, Field_1::$update_time), array($subscribe_fail, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member_alter_log, '帳單訂閱_訂閱失敗新增失敗資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member_alter_log, '帳單訂閱_訂閱失敗新增失敗資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
			
		return $route_data['sub_param'] . '01';
	}
	
	/*
	 * 新增訂閱處理中
	 * $route_data 所需參數資料
	 */
	public function subscribe_handle($route_data) {
		//新增處理中訂閱狀態
		$this->sql->add_static(array('table'=> Table_1::$subscribe,
				'select'=> $this->sql->field(array(Field_1::$id, Field_3::$subscribe_code, Field_1::$state, Field_2::$time, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($route_data['id'], $route_data['subscribe_code'], 1, $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
				'where'=> '',
				'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$subscribe, '帳單訂閱_新增處理中狀態訂閱', $this->sql->get_time(1))),
				'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$subscribe, '帳單訂閱_新增處理中狀態訂閱', $this->sql->get_time(1), '')),
				'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $route_data['sub_param'] . '03';
		} else {
			return $route_data['sub_param'] . '04';
		}
	}
	
	/*
	 * 訂閱帳單
	 * $route_data 所需參數資料
	 * $trader_code	業者編號
	 * $bill_kind_code 帳單編號
	 */
	public function subscribe($route_data, $trader_code, $bill_kind_code) {
		//查詢該帳單寄送實體帳單次數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_4::$send_condition_times), ''),
																		 'from' =>Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$trader_code), array($trader_code), array('')),
																		 'other' => '')), 'row_array');
		$send_condition_times = $sql_result['send_condition_times'];
		
		//帳單辨識資料
		$identify_data = substr($route_data['subscribe_code'], 6);

		//查詢該資料的一般會員是否已有行動會員身分
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$action_member_identity), ''),
																		 'from' => Table_1::$normal_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$identify_data), array($trader_code, $bill_kind_code, $identify_data), array('')),
																		 'other' => '')), 'row_array');
		if($sql_result['action_member_identity'] == 'n') {
			//更新一般會員擁有行動會員
			$this->sql->add_static(array('table'=> Table_1::$normal_member, 
										 'select'=> $this->sql->field(array(Field_3::$action_member_identity, Field_1::$update_user, Field_1::$update_time), array('y', $route_data['id'], $this->sql->get_time(1))), 
										 'where'=> $this->sql->where(array('where', 'where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$identify_data), array($trader_code, $bill_kind_code, $identify_data), array('')), 
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$normal_member, '帳單訂閱_更新一般會員擁有行動會員身分', $this->sql->get_time(1))), 
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$normal_member, '帳單訂閱_更新一般會員擁有行動會員身分', $this->sql->get_time(1), '')), 
										 'kind'=> 2));
		}
		
		//查詢該業者帳單最大帳單批次碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$batch_code . ') AS max'), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('like'), array(Field_1::$billez_code), array($route_data['subscribe_code']), array('after')),
																		 'other' => '')), 'row_array');
		//查詢該最新的帳單編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), ''),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'like'), array(Field_1::$batch_code, Field_1::$billez_code), array($sql_result['max'], $route_data['subscribe_code']), array('', 'after')),
																		 'other' => '')), 'row_array');
		$billez_code = $sql_result['billez_code'];
		
		//新增推播狀態
		$this->sql->add_static(array('table'=> Table_1::$push_state,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$billez_code, Field_2::$time, Field_1::$read, Field_3::$receive_read, Field_1::$times, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($route_data['id'], $billez_code, $this->sql->get_time(1), 'n', 'n', 1, $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$push_state, '帳單訂閱_新增該帳單推播狀態', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$push_state, '帳單訂閱_新增該帳單推播狀態', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增訂閱中資料
		$this->sql->add_static(array('table'=> Table_1::$subscribe,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_3::$subscribe_code, Field_1::$state, Field_3::$send_condition, Field_2::$time, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($route_data['id'], $route_data['subscribe_code'], 2, $send_condition_times, $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$subscribe, '帳單訂閱_新增訂閱資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$subscribe, '帳單訂閱_新增訂閱資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return true;
		} else {
			return false;
		}
	}

	/*
	 * 推播最新的帳單
	 * $route_data	所需參數資料
	 */
	public function latest_bill($route_data) {
		//查詢該會員手機資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone, Field_1::$mobile_phone_id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$mobile_info = $sql_result;
	
		//將要推播的訊息丟進推播變數
		$this->push->add_static(array('id' => $route_data['id'],
									  'moblie_phone' => $mobile_info['mobile_phone'],
									  'moblie_phone_id' => $mobile_info['mobile_phone_id'],
									  'billez_code' => '',
									  'result' => '',
									  'message' => ''));
		
		return $this->push_model->bill_push($route_data, array('message' => 'gcm_2', 'event' => 2, 'record' => '帳單訂閱_最新帳單推播通知', 'code' => '05'));
	}
	
	/*
	 * 訂閱帳單資料相關處理
	 * 或者還沒有帳單先訂閱
	 * $route_data 所需參數資料
	 */
	public function subscribe_bill($route_data) {
		//解析業者帳單編號
		$trader_code = substr($route_data["subscribe_code"], 0, 4);
		$bill_kind_code = substr($route_data["subscribe_code"], 4, 2);

		//確認是否有該業者帳單存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code), ''),
																		 'from' => Table_1::$trader_bill,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($trader_code, $bill_kind_code), array('')),
																		 'other' => '')), 'num_rows');
		//若不存在則將失敗的訂閱紀錄
		if(!$sql_result) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->subscribe_fail($route_data)), $route_data['private_key'], ''));
		
		//檢查該會員是否重複訂閱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_3::$subscribe_code), array($route_data["id"], $route_data["subscribe_code"]), array('')),
																		 'other' => '')), 'num_rows');
		//若存在則回傳重複訂閱
		if($sql_result) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));  
		
		//檢查該訂閱帳單目前是否有帳單存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), ''),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('like'), array(Field_1::$billez_code), array($route_data["subscribe_code"]), array('after')),
																		 'other' => '')), 'num_rows');
		//若沒有則新增訂閱處理中狀態
		if(!$sql_result) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->subscribe_handle($route_data)), $route_data['private_key'], ''));
		
		if($this->subscribe($route_data, $trader_code, $bill_kind_code)) {
			//若新增訂閱完成,則推播一個最近的帳單
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->latest_bill($route_data)), $route_data['private_key'], ''));
		} else {
			//若新增失敗回傳錯誤碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '06'), $route_data['private_key'], ''));
		}
	}
	
	/*
	 * 取消訂閱
	 * $route_data 所需參數資料
	 */
	public function cancel_subscribe($route_data) {
		//取消訂閱
		$this->sql->add_static(array('table'=> Table_1::$subscribe,
									 'select'=> $this->sql->field(array(Field_1::$state, Field_1::$update_user, Field_1::$update_time), array(3, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_3::$subscribe_code), array($route_data['id'], $route_data['subscribe_code']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$subscribe, '帳單訂閱_取消該帳單訂閱', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$subscribe, '帳單訂閱_取消該帳單訂閱', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));	
		} else {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));
		}
	}
	
	/*
	 * 分享帳單
	 * $route_data 所需參數資料
	 */
	public function share_bill($route_data) {
		//查詢被分享會員資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$mobile_phone, Field_1::$mobile_phone_id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$mobile_phone), array($route_data['mobile_phone']), array('')),
																		 'other' => '')), 'result_array');
		$action_member_info = $sql_result;

		//查詢分享人是否重複分享
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), ''),
																		 'from' => Table_1::$bill_share_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where', 'where'), array(Field_1::$id, Field_1::$mobile_phone, Field_1::$billez_code), array($route_data['id'], $route_data['mobile_phone'], $route_data['billez_code']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) {
			//重複分享回傳錯誤碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
		
		//新增帳單分享記錄
		$this->sql->add_static(array('table'=> Table_1::$bill_share_log,
									 'select'=> $this->sql->field(array(Field_1::$billez_code, Field_1::$id, Field_1::$mobile_phone, Field_1::$read, Field_1::$message, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($route_data['billez_code'], $route_data['id'], $route_data['mobile_phone'], 'n', $route_data['message'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$bill_share_log, '帳單訂閱_新增分享帳單記錄', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$bill_share_log, '帳單訂閱_新增分享帳單記錄', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if(!$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '04'), $route_data['private_key'], ''));
				
		//若是沒有查詢到會員資料,代表還沒加入,所以用簡訊通知
		if(count($action_member_info) == 0) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->sms_model->send_sms($route_data, array('billez_code' => $route_data['billez_code'], 'message' => $route_data['message']), array('event' => 5, 'success' => '02', 'fail' => '03'))), $route_data['private_key'], ''));

		//檢查是否分享給自己
		$myself = FALSE;
		foreach($action_member_info as $action_member) {
			if($action_member['id'] == $route_data['id']) {
				$myself = TRUE;
				continue;
			}
			
			//將要推播的訊息丟進推播變數
			$this->push->add_static(array('id' => $action_member['id'],
										  'moblie_phone' => $route_data['mobile_phone'],
										  'moblie_phone_id' => $action_member['mobile_phone_id'],
										  'billez_code' => '',
										  'result' => '',
										  'message' => ''));
		}

		if($myself) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->push_model->bill_push($route_data, array('message' => 'gcm_1', 'event' => 1, 'record' => '帳單訂閱_分享帳單推播通知', 'code' => '05'))), $route_data['private_key'], ''));
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->push_model->bill_push($route_data, array('message' => 'gcm_1', 'event' => 1, 'record' => '帳單訂閱_分享帳單推播通知', 'code' => '06'))), $route_data['private_key'], ''));	
	}
}//end