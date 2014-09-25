<?php

class Login_model extends CI_Model {
	/*
	 * 首次登入起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '4_1':
				return $this->create_key($route_data);
				break;
			case '4_2':
				return $this->send_member_data($route_data);
				break;
			case '4_3':
				return $this->send_subscribe($route_data);
				break;
			case '4_4':
				return $this->send_bill($route_data);
				break;
		}
	}
	
	/*
	 * 產生金鑰組並回傳加密公鑰
	 * 因為怕被駭客攔截
	 * 所以使用APP產生的公鑰去加密要給APP的公鑰
	 * $route_data 所需參數
	 */
	public function create_key($route_data) {
		$app = '4_1';
		$key = $this->key->create_key();
	
		//用意是要符合json格式
		$public_key['public_key'] = $key['public_key'];
		$outer_array = array();
		array_push($outer_array, $public_key);
	
		//查詢手機ID是否存在,若存在則更新金鑰組,不存在則新增一個手機ID及金鑰組
		$sql_select = $this->sql->select(array('mobile_phone_id'), '');
		$sql_where = $this->sql->where(array('where'), array('mobile_phone_id'), array($route_data['mobile_phone_id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'moblie_phone_id_and_key', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');
	
		if($sql_result) {
			//更新金鑰組
			array_push(Sql::$table, 'moblie_phone_id_and_key');
			array_push(Sql::$select, $this->sql->field(array('private_key', 'public_key', 'update_time'), array($key['private_key'], $key['public_key'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('mobile_phone_id'), array($route_data['mobile_phone_id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, 1, 'moblie_phone_id_and_key', '該手機ID存在,更新金鑰組', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, 1, 'moblie_phone_id_and_key', '該手機ID存在,更新金鑰組', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} else {
			//新增一個手機ID及金鑰組
			array_push(Sql::$table, 'moblie_phone_id_and_key');
			array_push(Sql::$select, $this->sql->field(array('mobile_phone_id', 'private_key', 'public_key', 'create_time', 'update_time'), array($route_data['mobile_phone_id'], $key['private_key'], $key['public_key'], $this->sql->get_time(1), $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, 1, 'moblie_phone_id_and_key', '該手機ID不存在,新增該手機ID及金鑰組', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, 1, 'moblie_phone_id_and_key', '該手機ID不存在,新增該手機ID及金鑰組', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
		}
	
		//執行新增/更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
			$json_data = $this->json->encode_json($app, '4_101');
		} else {
			$json_data = $this->json->encode_json($app, $outer_array);
		}
	
		$encode_data = $this->key->encode_app_public($json_data, $route_data['public_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 確認帳號密碼是否正確
	 * 若是正確則回傳該會員基本資料
	 * $route_data 所需參數資料
	 */
	public function send_member_data($route_data) {
		$app = '4_2';

		//判斷是用第三方首次登入或是一般首次登入
		if(isset($route_data['google_id']) || isset($route_data['fb_id'])) {
			if(isset($route_data['google_id'])) $third_code = $route_data['google_id'];
			if(isset($route_data['fb_id'])) $third_code = $route_data['fb_id'];
			
			//用第三方碼查出該會員編號
			$sql_select = $this->sql->select(array('id'), '');
			$sql_where = $this->sql->where(array('where', 'or_where'), array('fb_id', 'google_id'), array($third_code, $third_code), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			
			if($sql_result['id'] = '') {
				$json_data = $this->json->encode_json($app, '4_201');
			} else {
				$id = $sql_result['id'];
			}	
		} else {
			//查詢行動會員編號
			$sql_select = $this->sql->select(array('id'), '');
			$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			
			if($sql_result['id'] == '') {
				$json_data = $this->json->encode_json($app, '4_201');
			} else {
				//查詢會員狀態
				$sql_select = $this->sql->select(array('state'), '');
				$sql_where = $this->sql->where(array('where'), array('id'), array($sql_result['id']), array(''));
				$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'row_array');
				$state = $sql_result['state'];
				
				//未正式加入會員
				if($sql_result['state'] == 1) $json_data = $this->json->encode_json($app, '4_202');
				
				//查詢密碼是否正確
				$sql_select = $this->sql->select(array('id'), '');
				$sql_where = $this->sql->where(array('where', 'where'), array('id', 'password'), array($sql_result['id'], $route_data['password']), array(''));
				$sql_query = $this->query_model->query($sql_select, 'password', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'num_rows');
				
				//密碼錯誤
				if($sql_result) {
					$id = $sql_result['id'];
				} else {
					$json_data = $this->json->encode_json($app, '4_203');
				}
			}
		}
		
		if(isset($id)) {
			//符合json格式的外層陣列
			$outer_array = array();
			
			//查詢會員基本資料
			$sql_select = $this->sql->select(array('email', 'last_name', 'first_name', 'mobile_phone', "IF (fb_id = '', 'blank', fb_id) AS fb_id", "IF (google_id = '', 'blank', google_id) AS google_id"), 'function');
			$sql_where = $this->sql->where(array('where'), array('id'), array($id), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$action_member_info = $sql_result;
			
			//查詢公鑰
			$sql_select = $this->sql->select(array('public_key'), '');
			$sql_where = $this->sql->where(array('where'), array('id'), array($id), array(''));
			$sql_query = $this->query_model->query($sql_select, 'key', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			
			$action_member_info['public_key'] = $sql_result['public_key'];
			$action_member_info['password'] = $route_data['password'];
			$action_member_info['id'] = $id;
			
			//查詢會員帳單備忘錄資料
			$sql_select = $this->sql->select(array('*'), '');
			$sql_where = $this->sql->where(array('where'), array('id'), array($id), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member_data', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$action_member_data = $sql_result;
			
			//將不用的資料取出
			for($i = 0;$i < 5;$i++) {											
				array_shift($action_member_data);
			}
			
			//將帳單備忘錄做處理
			foreach($action_member_data as $item => $value) {	
				if($value != '') {
					$other_datas = split(',', $value);
					$action_member_info[$other_datas[0]] = $other_datas[1];
				}
			}
			
			array_push($outer_array, $action_member_info);
			$json_data = $this->json->encode_json($app, $outer_array);
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 查詢該會員的訂閱紀錄並回傳
	 * $route_data	所需參數資料
	 */
	public function send_subscribe($route_data) {
		$app = '4_3';
		
		//查詢該會員的訂閱紀錄
		$sql_select = $this->sql->select(array('subscribe_code'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'subscribe', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$subscribe_list = array();
		foreach($sql_result as $data) array_push($subscribe_list, $data['subscribe_code']);
		if(count($subscribe_list) == 0) {
			$json_data = $this->json->encode_json($app, '4_301');
		} else {
			$json_data = $this->json->encode_json($app, $subscribe_list);
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 查詢該會員有的帳單紀錄並回傳
	 * $route_data	所需參數資料
	 */
	public function send_bill($route_data) {
		$app = '4_4';
		
		//查詢該會員的訂閱紀錄
		$sql_select = $this->sql->select(array('subscribe_code'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'subscribe', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$billez_code_list = array();
		
		//將訂閱碼丟到帳單編號集合
		foreach($sql_result as $data) {
			//查詢帳單編號
			$sql_select = $this->sql->select(array('billez_code'), '');
			$sql_where = $this->sql->where(array('like'), array('billez_code'), array($data['subscribe_code']), array('after'));
			$sql_query = $this->query_model->query($sql_select, 'bill', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'result_array');
			
			foreach($sql_result as $sub_data) array_push($billez_code_list, $sub_data['billez_code']);
		}
		if(count($billez_code_list) == 0) {
			$json_data = $this->json->encode_json($app, '4_401');
		} else {
			$bill_info = array();
			//查詢各筆帳單資料
			foreach($billez_code_list as $billez_code) {
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
				$sql_where = $this->sql->where(array('where'), array('bill.billez_code'), array($billez_code), array(''));
				$sql_other = $this->sql->other(array('distinct'), array(''));
				$sql_query = $this->query_model->query($sql_select, 'bill', $sql_join, $sql_where, $sql_other);
				$sql_result = $this->sql->result($sql_query, 'row_array');
				array_push($bill_info, $sql_result);
			}
			$json_data = $this->json->encode_json($app, $bill_info);
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
}//end