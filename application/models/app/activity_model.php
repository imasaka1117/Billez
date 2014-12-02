<?php

class Activity_model extends CI_Model {
	/*
	 * 會員活動起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '10_1':
				return $this->recommend_friend($route_data);
				break;
			case '10_2':
				return $this->send_sms($route_data);
				break;
		}
	}
	
	/*
	 * 推薦朋友名單
	 * 在此是將資料再轉移到另一頁面
	 * 因為推薦不讓使用者等待太久
	 * 怕推薦名單太多
	 * $route_data 所需參數資料
	 */
	public function recommend_friend($route_data) {
		$post = array('special' => 1, 'mobile_phone_list' => $route_data['mobile_phone_list'], 'id' => $route_data['id']);
		
		$ch = curl_init();
			
		$options = array(
				CURLOPT_URL => base_url() . 'index.php/app',
				CURLOPT_HEADER => false,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_TIMEOUT=>1,
				CURLOPT_USERAGENT => 'Google Bot',
				CURLOPT_POST=>true,
				CURLOPT_POSTFIELDS=>http_build_query($post),
				CURLOPT_FOLLOWLOCATION => true
		);
			
		curl_setopt_array($ch, $options);
		curl_exec($ch);
		curl_close($ch);
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
	}
	
	/*
	 * 將要推薦的名單寄發簡訊
	 * 先將重複的剃除
	 * 然後檢查手機格式轉成統一的
	 * 再來寄發
	 * $route_data 所需參數資料
	 */
	public function send_sms($route_data) {
		$mobile_phone_list = array_unique(split(',', $route_data['mobile_phone_list']));
		//符合規格的
		$mobile_phone_list_1st = array();
		//非行動會員的
		$mobile_phone_list_2nd = array();
		//沒有推薦過的
		$mobile_phone_list_3rd = array();
		
		foreach ($mobile_phone_list as $mobile_phone) {
			$temp_str = str_replace('+886', '0', $mobile_phone);
		
			if(strlen($temp_str) == 10) {
				if(strpos($temp_str, '0') == 0) {
					if(strpos($temp_str, '9') == 1) {
						array_push($mobile_phone_list_1st, $temp_str);
					}
				}
			}
		}
		
		//剔除已經是行動會員的手機號碼
		foreach($mobile_phone_list_1st as $mobile_phone_1) {
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$mobile_phone), array($mobile_phone_1), array('')),
																			 'other' => '')), 'num_rows');
			if(!$sql_result) array_push($mobile_phone_list_2nd, $mobile_phone_1);
		}

		//剔除已經推薦過的手機號碼
		foreach($mobile_phone_list_2nd as $mobile_phone_2) {
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$recommend_list,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$mobile_phone), array($mobile_phone_2), array('')),
																			 'other' => '')), 'num_rows');
			if(!$sql_result) array_push($mobile_phone_list_3rd, $mobile_phone_2);
		}

		//查詢推薦人名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$last_name, Field_1::$first_name), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$name = $sql_result['last_name'] . $sql_result['first_name'];
		
		//查詢目前使用的寄發認證碼簡訊規格
		$sms_form = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$body), ''),
																	   'from' => Table_1::$sms_form,
																	   'join'=> '',
																	   'where' => $this->sql->where(array('where', 'where'), array(Field_1::$state, Field_2::$form_kind), array('y', 6), array('', '')),
																	   'other' => '')), 'row_array');
		//寄發推薦簡訊
		foreach($mobile_phone_list_3rd as $mobile_phone) {
			$sms_result = $this->sms_model->sms(6, $mobile_phone, $sms_form, $name);
				
			if($sms_result == 1) {
				$result = 1;
				$sms_result = '';
			} else {
				$result = 2;
			}
				
			//新增簡訊記錄
			$this->sql->add_static(array('table'=> Table_1::$sms_log,
										 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$mobile_phone, Field_2::$event, Field_2::$result, Field_2::$error_message, Field_1::$create_time), array($route_data['id'], $mobile_phone, 6, $result, $sms_result, $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$sms_log, '會員活動_新增發送推薦好友簡訊記錄', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$sms_log, '會員活動_新增發送推薦好友簡訊記錄', $this->sql->get_time(1), '')),
										 'kind'=> 1));
			//新增推薦名單
			$this->sql->add_static(array('table'=> Table_1::$recommend_list,
										 'select'=> $this->sql->field(array(Field_1::$mobile_phone, Field_3::$recommender, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($mobile_phone, $route_data['id'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$recommend_list, '會員活動_新增會員推薦記錄', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$recommend_list, '會員活動_新增會員推薦記錄', $this->sql->get_time(1), '')),
										 'kind'=> 1));
		}
		
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
	}
}//end