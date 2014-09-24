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
		$app = '7_1';
		
		//查詢上未讀取的帳單編號
		$sql_select = $this->sql->select(array('billez_code'), '');
		$sql_where = $this->sql->where(array('where', 'where'), array('id', 'read'), array($route_data['id'], 'n'), array(''));
		$sql_query = $this->query_model->query($sql_select, 'push_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$billez_code_list = $sql_result;
		$outer_array = array();
		
		foreach($billez_code_list as $data) {
			//查詢帳單資料
			$sql_select = $this->sql->select(array('bill.billez_code', 
												   'bill_owner',
												   'publish_time',
												   'due_time',
												   'trader_code.name AS trader_name',
												   'bill_kind_code.name AS bill_kind_name',
												   'pay_place.pay_place AS pay_place',
												   'pay_place.overdue_pay_place AS overdue_pay_place',
												   "IFNULL(amount, 'blank') AS amount", 
												   "IFNULL(lowest_pay_amount, 'blank') AS lowest_pay_amount",
												   "IFNULL(post_charge, 'blank') AS post_charge",
												   "IFNULL(bank_charge, 'blank') AS bank_charge",
												   "IFNULL(cvs_charge, 'blank') AS cvs_charge",
												   "IFNULL(cvs_barcode1, 'blank') AS cvs_barcode1",
												   "IFNULL(cvs_barcode2, 'blank') AS cvs_barcode2",
												   "IFNULL(cvs_barcode3, 'blank') AS cvs_barcode3", 
												   "IFNULL(post_barcode1, 'blank') AS post_barcode1", 
												   "IFNULL(post_barcode2, 'blank') AS post_barcode2", 
												   "IFNULL(post_barcode3, 'blank') AS post_barcode3", 
												   "IFNULL(bank_barcode1, 'blank') AS bank_barcode1",
												   "IFNULL(bank_barcode2, 'blank') AS bank_barcode2",
												   "IFNULL(bank_barcode3, 'blank') AS bank_barcode3", 
												   "IFNULL(trader_bill.bill_ad_url, 'blank') AS ad_id",
												   'CONCAT(bill.trader_code, bill.bill_kind_code, identify_data) AS subscribe_code',
												   "CONCAT(IFNULL(CONCAT(data1, ','), ''), IFNULL(CONCAT(data2, ','), ''), IFNULL(CONCAT(data3, ','), ''), IFNULL(CONCAT(data4, ','), ''), IFNULL(CONCAT(data5, ','), '')) AS remark"
													  ), 'function');
			$sql_join = $this->sql->join(array('trader_code', 'bill_kind_code', 'pay_place', 'trader_bill'), array('trader_code = trader_code.code', 'bill_kind_code = bill_kind_code.code', 'bill.billez_code = pay_place.billez_code', 'bill.trader_code = trader_bill.trader_code'), array('', '', '', ''));
			$sql_where = $this->sql->where(array('where'), array('bill.billez_code'), array($data['billez_code']), array(''));
			$sql_other = $this->sql->other(array('distinct'), array(''));
			$sql_query = $this->query_model->query($sql_select, 'bill', $sql_join, $sql_where, $sql_other);
			$sql_result = $this->sql->result($sql_query, 'row_array');
			
			//將帳單資料放入傳回的格式裡
			array_push($outer_array, $sql_result);
			
			//更新帳單已讀取狀態
			array_push(Sql::$table, 'push_state');
			array_push(Sql::$select, $this->sql->field(array('read', 'read_time', 'update_user', 'update_time'), array('y', $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'billez_code'), array($route_data['id'], $data['billez_code']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'push_state', '帳單請求更新帳單已讀取狀態', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'push_state', '帳單請求更新帳單已讀取狀態', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, $outer_array);
		} else {
			$json_data = $this->json->encode_json($app, '7_101');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
	
	/*
	 * 查詢已入帳的帳單編號
	 * 更新已繳費狀態
	 * $route_data  所需參數資料
	 */
	public function receive_bill($route_data) {
		$app = '7_2';
		
		//查詢上未讀取入帳帳單的帳單編號
		$sql_select = $this->sql->select(array('billez_code'), '');
		$sql_where = $this->sql->where(array('where', 'where'), array('id', 'receive_read'), array($route_data['id'], 'n'), array(''));
		$sql_query = $this->query_model->query($sql_select, 'push_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$billez_code_list = $sql_result;
		
		foreach($billez_code_list as $data) {
			//更新帳單已讀取狀態
			array_push(Sql::$table, 'push_state');
			array_push(Sql::$select, $this->sql->field(array('receive_read', 'read_time', 'update_user', 'update_time'), array('y', $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'billez_code'), array($route_data['id'], $data['billez_code']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'push_state', '帳單請求更新入帳帳單已讀取狀態', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'push_state', '帳單請求更新入帳帳單已讀取狀態', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, $billez_code_list);
		} else {
			$json_data = $this->json->encode_json($app, '7_201');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
	
	/*
	 * 查詢分享帳單資料
	 * 並不需要有訂閱才有的
	 * $route_data 所需參數資料
	 */
	public function share_bill($route_data) {
		$app = '7_3';
		
		//查詢會員手機
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$mobile_phone = $sql_result['mobile_phone'];
		
		//查詢被分享的帳單編號
		$sql_select = $this->sql->select(array('id', 'billez_code', "IF(message IS NULL OR message = '', 'blank', message) as message"), 'function');
		$sql_where = $this->sql->where(array('where', 'where'), array('mobile_phone', 'read'), array($mobile_phone, 'n'), array(''));
		$sql_query = $this->query_model->query($sql_select, 'bill_share_log', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$share_data = $sql_result;
		
		//符合json格式
		$outer_array = array();
		
		foreach($share_data as $data) {
			//查詢分享人資料
			$sql_select = $this->sql->select(array('email', 'last_name', 'first_name', 'mobile_phone'), '');
			$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$action_member_info = $sql_result;
			
			//查詢該筆帳單資料
			$sql_select = $this->sql->select(array('bill.billez_code',
													'bill_owner',
													'publish_time',
													'due_time',
													'trader_code.name AS trader_name',
													'bill_kind_code.name AS bill_kind_name',
													'pay_place.pay_place AS pay_place',
													'pay_place.overdue_pay_place AS overdue_pay_place',
													"IFNULL(amount, 'blank') AS amount",
													"IFNULL(lowest_pay_amount, 'blank') AS lowest_pay_amount",
													"IFNULL(post_charge, 'blank') AS post_charge",
													"IFNULL(bank_charge, 'blank') AS bank_charge",
													"IFNULL(cvs_charge, 'blank') AS cvs_charge",
													"IFNULL(cvs_barcode1, 'blank') AS cvs_barcode1",
													"IFNULL(cvs_barcode2, 'blank') AS cvs_barcode2",
													"IFNULL(cvs_barcode3, 'blank') AS cvs_barcode3",
													"IFNULL(post_barcode1, 'blank') AS post_barcode1",
													"IFNULL(post_barcode2, 'blank') AS post_barcode2",
													"IFNULL(post_barcode3, 'blank') AS post_barcode3",
													"IFNULL(bank_barcode1, 'blank') AS bank_barcode1",
													"IFNULL(bank_barcode2, 'blank') AS bank_barcode2",
													"IFNULL(bank_barcode3, 'blank') AS bank_barcode3",
													"IFNULL(trader_bill.bill_ad_url, 'blank') AS ad_id",
													'CONCAT(bill.trader_code, bill.bill_kind_code, identify_data) AS subscribe_code',
													"CONCAT(IFNULL(CONCAT(data1, ','), ''), IFNULL(CONCAT(data2, ','), ''), IFNULL(CONCAT(data3, ','), ''), IFNULL(CONCAT(data4, ','), ''), IFNULL(CONCAT(data5, ','), '')) AS remark"
													), 'function');
			$sql_join = $this->sql->join(array('trader_code', 'bill_kind_code', 'pay_place', 'trader_bill'), array('trader_code = trader_code.code', 'bill_kind_code = bill_kind_code.code', 'bill.billez_code = pay_place.billez_code', 'bill.trader_code = trader_bill.trader_code'), array('', '', '', ''));
			$sql_where = $this->sql->where(array('where'), array('bill.billez_code'), array($data['billez_code']), array(''));
			$sql_other = $this->sql->other(array('distinct'), array(''));
			$sql_query = $this->query_model->query($sql_select, 'bill', $sql_join, $sql_where, $sql_other);
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$sql_result['email'] = $action_member_info['email'];
			$sql_result['last_name'] = $action_member_info['last_name'];
			$sql_result['first_name'] = $action_member_info['first_name'];
			$sql_result['mobile_phone'] = $action_member_info['mobile_phone'];
			
			array_push($outer_array, $sql_result);
			
			//更新分享帳單讀取紀錄
			array_push(Sql::$table, 'bill_share_log');
			array_push(Sql::$select, $this->sql->field(array('read', 'update_user', 'update_time'), array('y', $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where', 'where'), array('id', 'billez_code', 'mobile_phone'), array($data['id'], $data['billez_code'], $mobile_phone), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'bill_share_log', '帳單請求更新分享帳單已讀取狀態', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'bill_share_log', '帳單請求更新分享帳單已讀取狀態', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, $outer_array);
		} else {
			$json_data = $this->json->encode_json($app, '7_301');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
	
	/*
	 * 查詢可能帳單資料
	 * 並傳回
	 * $route_data 所需參數資料
	 */
	public function possible_bill($route_data) {
		$app = '7_4';
		
		
		
		
	}
}//end