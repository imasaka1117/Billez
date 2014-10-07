<?php

class Sms_model extends CI_Model {
	/*
	 * 更新資料及寄送認證碼簡訊
	 * $route_data	所需參數
	 * $authentication_code 要寄發的認證碼
	 * $data	此次寄發參數資料陣列  包含 event 事件, success 成功回傳代碼  fail 失敗回傳代碼 
	 */
	public function send_sms($route_data, $send_data, $data) {
		/*
		 * 這裡待加入簡訊內容規格
		 */
		$sms_form = array();
		$sms_result = $this->sms->send_sms(1, $route_data['mobile_phone'], $sms_form, $send_data);

		//判斷失敗或成功及記錄失敗原因
		if($sms_result == 1) {
			$result = 1;
			$sms_result = '';
			$code = $data['success'];
		} else {
			$result = 2;
			$code =  $data['fail'];
		}
	
		//清空之前更新存放資料
		$this->sql->clear_static();
	
		//新增簡訊紀錄
		$this->sql->add_static(array('table'=> Table_1::$sms_log,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_2::$result, Field_2::$error_message, Field_1::$create_time), array($route_data['id'], $route_data['mobile_phone'], $data['event'], $result, $sms_result, $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$sms_log, '新增發送簡訊紀錄', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$sms_log, '新增發送簡訊紀錄', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
			
		return $route_data['sub_param'] . $code;
	}
	
	/*
	 * 再次寄送簡訊認證碼
	 * 總共有三個認證碼都可通過
	 * $route_data 所需資料
	 * $data	此次寄發參數資料陣列  包含 event 事件, success 成功回傳代碼  fail 失敗回傳代碼 
 	 */
	public function send_again($route_data, $data) {
		//區別修改資料和申請會員手機
		if($data['event'] == 2) {
			//查詢會員手機
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																			 'other' => '')), 'row_array');
		} else {
			//查詢修改會員手機
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$action_member_alter_log,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$frequency), array($route_data['id'], 1), array('')),
																			 'other' => '')), 'row_array');
		}
			
		$route_data['mobile_phone'] = $sql_result['mobile_phone'];
	
		//查詢會員簡訊狀態
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$sms_frequency, Field_3::$authentication_code), ''),
																		 'from' => Table_1::$sms_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$sms_state_info = $sql_result;
	
		//查詢簡訊傳送次數上限
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$sms_times), ''),
																		 'from' => Table_1::$system_setting,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$using), array('y'), array('')),
																		 'other' => '')), 'row_array');
		$sms_times_limit = $sql_result['sms_times'];
	
		//產生認證碼
		$authentication_code = $this->create->authentication();
	
		//若已傳送次數等於系統設定就不再傳送
		if($sms_state_info['sms_frequency'] == $sms_times_limit) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
	
		//更新簡訊次數
		$this->sql->add_static(array('table'=> Table_1::$sms_state,
									 'select'=> $this->sql->field(array(Field_3::$sms_frequency, Field_1::$update_user, Field_1::$update_time), array($sms_state_info['sms_frequency'] + 1, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_累積寄送認證碼次數', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_累積寄送認證碼次數', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//查詢認證碼2是否產生
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$authentication_code2), ''),
																		 'from' => Table_1::$sms_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		//若沒有認證碼2則新增認證碼2
		//若有的話則新增認證碼3
		//目的是要讓所有認證碼都能夠通過
		//之後再傳送則只更新認證碼3
		if($sql_result['authentication_code2'] == '') {
			//更新認證碼2
			$this->sql->add_static(array('table'=> Table_1::$sms_state,
										 'select'=> $this->sql->field(array(Field_3::$authentication_code2, Field_1::$update_user, Field_1::$update_time), array($authentication_code, $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_更新認證碼2', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_更新認證碼2', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		} else {
			//更新認證碼3
			$this->sql->add_static(array('table'=> Table_1::$sms_state,
										 'select'=> $this->sql->field(array(Field_3::$authentication_code3, Field_1::$update_user, Field_1::$update_time), array($authentication_code, $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_更新認證碼3', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$sms_state, '加入會員_更新認證碼3', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
	
		//執行,寄發認證碼簡訊
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->send_sms($route_data, $authentication_code, $data)), $route_data['private_key'], ''));
		} else {
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '04'), $route_data['private_key'], ''));
		}
	}
}