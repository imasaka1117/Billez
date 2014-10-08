<?php

class Bill_model extends CI_Model {
	/*
	 * 帳單請求員起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '7_1':
				return $this->normal_bill($route_data);
				break;
			case '7_2':
				return $this->receive_bill($route_data);
				break;
			case '7_3':
				return $this->share_bill($route_data);
				break;
			case '7_4':
				return $this->possible_bill($route_data);
				break;
		}
	}
	
	/*
	 * 請求一般帳單
	 * $route_data 所需參數資料
	 */
	public function normal_bill($route_data) {
		//查詢上未讀取的帳單編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), ''),
																		 'from' => Table_1::$push_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$read), array($route_data['id'], 'n'), array('')),
																		 'other' => '')), 'result_array');
		$billez_code_list = array();
		foreach($sql_result as $data) array_push($billez_code_list, $data['billez_code']);
		$json_array = array();
		
		foreach($billez_code_list as $billez_code) {
			//查詢帳單資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select($this->format_model->normal_bill(), 'function'),
																			 'from' => Table_1::$bill,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code, Table_1::$pay_place, Table_1::$trader_bill), array(Field_1::$trader_code . '=' . Table_1::$trader_code . '.' . Field_1::$code, Field_1::$bill_kind_code . '=' . Table_1::$bill_kind_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$billez_code . '=' . Table_1::$pay_place . '.' . Field_1::$billez_code, Table_1::$bill . '.' . Field_1::$trader_code . '=' . Table_1::$trader_bill . '.' . Field_1::$trader_code), array('', '', '', '')),
																			 'where' => $this->sql->where(array('where'), array(Table_1::$bill . '.' . Field_1::$billez_code), array($billez_code), array('')),
																			 'other' => '')), 'row_array');
			//將帳單資料放入傳回的格式裡
			array_push($json_array, $sql_result);
			
			//更新帳單已讀取狀態
			$this->sql->add_static(array('table'=> Table_1::$push_state,
										 'select'=> $this->sql->field(array(Field_1::$read, Field_3::$read_time, Field_1::$update_user, Field_1::$update_time), array('y', $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$billez_code), array($route_data['id'], $billez_code), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$push_state, '帳單請求_更新帳單已讀取', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$push_state, '帳單請求_更新帳單已讀取', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳帳單資料
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $json_array), $route_data['private_key'], ''));	
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
	}
	
	/*
	 * 查詢已入帳的帳單編號
	 * 更新已繳費狀態
	 * $route_data  所需參數資料
	 */
	public function receive_bill($route_data) {
		//查詢上未讀取入帳帳單的帳單編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), ''),
																		 'from' => Table_1::$push_state,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id, Field_3::$receive_read), array($route_data['id'], 'n'), array('')),
																		 'other' => '')), 'result_array');
		$billez_code_list = $sql_result;
		
		foreach($billez_code_list as $data) {
			//更新入帳帳單已讀取狀態
			$this->sql->add_static(array('table'=> Table_1::$push_state,
										 'select'=> $this->sql->field(array(Field_3::$receive_read, Field_3::$read_time, Field_1::$update_user, Field_1::$update_time), array('y', $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_1::$billez_code), array($route_data['id'], $data['billez_code']), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$push_state, '帳單請求_更新入帳帳單已讀取', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$push_state, '帳單請求_更新入帳帳單已讀取', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳有已更新的帳單編號
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $billez_code_list), $route_data['private_key'], ''));	
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
	}
	
	/*
	 * 查詢分享帳單資料
	 * 並不需要有訂閱才有的
	 * $route_data 所需參數資料
	 */
	public function share_bill($route_data) {
		//查詢會員手機
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$mobile_phone = $sql_result['mobile_phone'];

		//查詢被分享的資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$billez_code, 'IF(' . Field_1::$message . ' IS NULL OR ' . Field_1::$message . " = '', 'blank', " . Field_1::$message . ') AS ' . Field_1::$message), 'function'),
																		 'from' => Table_1::$bill_share_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$mobile_phone, Field_1::$read), array($mobile_phone, 'n'), array('')),
																		 'other' => '')), 'result_array');
		$json_array = array();
		
		foreach($sql_result as $data) {
			//查詢分享人資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone), ''),
																			 'from' => Table_1::$action_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($data['id']), array('')),
																			 'other' => '')), 'row_array');
			$action_member_info = $sql_result;
		
			//查詢帳單資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select($this->format_model->normal_bill(), 'function'),
																			 'from' => Table_1::$bill,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code, Table_1::$pay_place, Table_1::$trader_bill), array(Field_1::$trader_code . '=' . Table_1::$trader_code . '.' . Field_1::$code, Field_1::$bill_kind_code . '=' . Table_1::$bill_kind_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$billez_code . '=' . Table_1::$pay_place . '.' . Field_1::$billez_code, Table_1::$bill . '.' . Field_1::$trader_code . '=' . Table_1::$trader_bill . '.' . Field_1::$trader_code), array('', '', '', '')),
																			 'where' => $this->sql->where(array('where'), array(Table_1::$bill . '.' . Field_1::$billez_code), array($data['billez_code']), array('')),
																			 'other' => '')), 'row_array');
			$sql_result['email'] = $action_member_info['email'];
			$sql_result['last_name'] = $action_member_info['last_name'];
			$sql_result['first_name'] = $action_member_info['first_name'];
			$sql_result['mobile_phone'] = $action_member_info['mobile_phone'];
			
			array_push($json_array, $sql_result);
			
			//更新分享帳單讀取紀錄
			$this->sql->add_static(array('table'=> Table_1::$bill_share_log,
										 'select'=> $this->sql->field(array(Field_1::$read, Field_1::$update_user, Field_1::$update_time), array('y', $route_data['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where', 'where', 'where'), array(Field_1::$id, Field_1::$billez_code, Field_1::$mobile_phone), array($data['id'], $data['billez_code'], $mobile_phone), array('')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$bill_share_log, '帳單請求_更新已讀取分享帳單', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$bill_share_log, '帳單請求_更新已讀取分享帳單', $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳帳單資料
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $json_array), $route_data['private_key'], ''));
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
	}
	
	/*
	 * 查詢會員所有資料
	 * $route_data 所需參數資料
	 */
	public function search_data($route_data) {
		//存放各種需要比對的資料
		$possible_data = array();
		
		//查詢會員修改資料紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email, 'CONCAT(' . Field_1::$last_name . ',' . Field_1::$first_name . ') AS name', Field_1::$mobile_phone, Field_1::$bill_memo, Field_1::$subscribe_fail), 'function'),
																		 'from' => Table_1::$action_member_alter_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'result_array');
		//將有用的資料取出來
		foreach($sql_result as $result) {
			foreach($result as $item => $value) {
				if($item == 'bill_memo' || $item == 'subscribe_fail') {
					$datas = split(',', $value);
					
					foreach($datas as $data) {
						if($data != '') array_push($possible_data, $data);
					}
				} else {
					array_push($possible_data, $value);
				}
			}
		}
		
		//查詢會員備忘錄資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => Table_1::$action_member_data,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		//將有用的資料取出來
		for($i = 0; $i < 6; $i++) array_shift($sql_result);

		foreach($sql_result as $result) {
			if($result != '') {
				$datas = split(',', $result);
				array_push($possible_data,$datas[2]);
			}
		}
		
		//查詢會員手機資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$mobile_phone), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		array_push($possible_data,$sql_result['mobile_phone']);

		return $possible_data;
	}
	
	/*
	 * 查詢可能帳單資料
	 * 並傳回
	 * $route_data 所需參數資料
	 */
	public function possible_bill($route_data) {
		//存放各種需要比對的資料
		$possible_data = $this->search_data($route_data);
		
		//查詢該會員有訂閱帳單的訂閱碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$subscribe_code), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'result_array');
		$subscribe_code_list = array();
		foreach($sql_result as $result) array_push($subscribe_code_list, $result['subscribe_code']);	
		
		//查詢可能帳單
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select($this->format_model->possible_bill(), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code, Table_1::$trader_contract), array(Table_1::$bill . '.' . Field_1::$trader_code . '=' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$bill_kind_code . '=' . Table_1::$bill_kind_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$trader_code . '=' . Table_1::$trader_contract . '.' . Field_1::$trader_code . ' AND ' . Table_1::$bill . '.' . Field_1::$bill_kind_code . '=' . Table_1::$trader_contract . '.' . Field_1::$bill_kind_code), array('', '', '')),
																		 'where' => $this->sql->where(array('where_in', 'or_where_in', 'or_where_in', 'or_where_in', 'or_where_in', 'or_where_in', 'or_where_in', 'where', 'where', 'where_not_in'), array(Field_1::$identify_data, Field_1::$bill_owner, Field_1::$data1, Field_1::$data2, Field_1::$data3, Field_1::$data4, Field_1::$data5, 'YEAR(NOW()) - YEAR(' . Field_2::$publish_time . ') =', 'MONTH(NOW()) - MONTH(' . Field_2::$publish_time . ') =', 'CONCAT(' . Table_1::$bill . '.' . Field_1::$trader_code . ',' . Table_1::$bill . '.' . Field_1::$bill_kind_code . ',' . Field_1::$identify_data . ')'), array($possible_data, $possible_data, $possible_data, $possible_data, $possible_data, $possible_data, $possible_data, 0, 0, $subscribe_code_list), array('')),
																		 'other' => '')), 'result_array');
		$possible_bill_list = array();

		//比對必須要有兩筆以上相同才列為可能帳單
		foreach($sql_result as $result) {
			$i = 0;
			
			foreach($result as $item => $data) {
				switch ($item) {
					case 'subscribe_code':
					case 'trader_name':
					case 'bill_kind_name':
					case 'send_condition_times':
						continue;
						break;
					default:
						foreach ($possible_data as $possible) {
							if($data == $possible) {
								$i++;
								break;
							}
						}
						break;
				}
				if($i == 2) {
					for($j = 0; $j < 5; $j++) array_shift($result);
					array_push($possible_bill_list, $result);
					break;
				}
			}
		}
			
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $possible_bill_list), $route_data['private_key'], ''));
	}
}//end