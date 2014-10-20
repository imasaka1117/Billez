<?php

class Login_model extends CI_Model {
	/*
	 * 首次登入起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '4_2':
				return $this->check_account($route_data);
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
	 * 查詢行動會員資料
	 * $route_data 所需參數資料
	 */
	public function member_data($route_data) {
		//符合json格式的外層陣列
		$json_array = array();
			
		//查詢會員基本資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, 'IF (' . Field_1::$fb_id . " = '', 'blank'," .  Field_1::$fb_id . ') AS ' . Field_1::$fb_id, 'IF (' . Field_1::$google_id . " = '', 'blank'," .  Field_1::$google_id . ') AS ' . Field_1::$google_id), 'function'),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$action_member_info = $sql_result;

		//查詢公鑰
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$public_key), ''),
																		 'from' => Table_1::$key,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		//將必須資料放入會員基本資料陣列
		$action_member_info['public_key'] = $sql_result['public_key'];
		$action_member_info['password'] = $route_data['password'];
		$action_member_info['id'] = $route_data['id'];
		
		//查詢會員帳單備忘錄資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => Table_1::$action_member_data,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		$action_member_data = $sql_result;
		
		//將行動會員資料不用的資料取出
		for($i = 0; $i < 6; $i++) array_shift($action_member_data);
		
		if(count($action_member_data) != 0) {
			//將帳單備忘錄做處理
			foreach($action_member_data as $item => $value) {
				if($value != '') {
					$other_datas = split(',', $value);
					$action_member_info[$other_datas[0]] = $other_datas[1] . ',' . $other_datas[2];
				}
			}
		}
	
		array_push($json_array, $action_member_info);
		return $json_array;
	}
	
	/*
	 * 確認帳號密碼是否正確
	 * 若是正確則回傳該會員基本資料
	 * $route_data 所需參數資料
	 */
	public function check_account($route_data) {
		//使用電子郵件查詢正式加入的會員編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$email, Field_1::$state), array($route_data['email'], 2), array('')),
																		 'other' => '')), 'row_array');
		if(!isset($sql_result['id'])) {
			//若不存在則代表沒有該帳號
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
		
		$route_data['id'] = $sql_result['id'];

		//查詢該會員密碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$password), ''),
																		 'from' => Table_1::$password,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'row_array');
		if($sql_result['password'] == '') return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->member_data($route_data)), $route_data['private_key'], ''));
		
		//檢查密碼是否正確,若不正確回傳錯誤碼
		if($sql_result['password'] != md5($route_data['password'])) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));		
		
		//查詢會員資料
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $this->member_data($route_data)), $route_data['private_key'], ''));
	}
	
	/*
	 * 查詢該會員的訂閱紀錄並回傳
	 * $route_data	所需參數資料
	 */
	public function send_subscribe($route_data) {
		//查詢該會員的訂閱紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$subscribe_code), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'result_array');
		if(count($sql_result) == 0) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		
		$subscribe_list = array();
		
		//將訂閱碼放入陣列
		foreach($sql_result as $data) array_push($subscribe_list, $data);
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $subscribe_list), $route_data['private_key'], ''));
	}
	
	/*
	 * 查詢該會員有的帳單紀錄並回傳
	 * $route_data	所需參數資料
	 */
	public function send_bill($route_data) {
		//查詢該會員的訂閱紀錄
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$subscribe_code), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
																		 'other' => '')), 'result_array');
		$billez_code_list = array();
		
		//將訂閱碼丟到帳單編號集合
		foreach($sql_result as $data) {
			//查詢帳單編號
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$billez_code), ''),
																			 'from' => Table_1::$bill,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('like'), array(Field_1::$billez_code), array($data['subscribe_code']), array('after')),
																			 'other' => '')), 'result_array');
			//若是該訂閱沒有帳單則跳過一輪,有的話則把帳單編號丟入陣列
			if(count($sql_result) == 0) continue;
			foreach($sql_result as $sub_data) array_push($billez_code_list, $sub_data['billez_code']);
		}
		
		if(count($billez_code_list) == 0) return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
			
		$bill_info = array();

		//查詢各筆帳單資料
		foreach($billez_code_list as $billez_code) {
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select($this->format_model->normal_bill($this->format_model->subscribe_code(), $this->format_model->remark()), 'function'),
																			 'from' => Table_1::$bill,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code, Table_1::$pay_place, Table_1::$trader_bill), array(Field_1::$trader_code . '=' . Table_1::$trader_code . '.' . Field_1::$code, Field_1::$bill_kind_code . '=' . Table_1::$bill_kind_code . '.' . Field_1::$code, Table_1::$bill . '.' . Field_1::$billez_code . '=' . Table_1::$pay_place . '.' . Field_1::$billez_code, Table_1::$bill . '.' . Field_1::$trader_code . '=' . Table_1::$trader_bill . '.' . Field_1::$trader_code), array('', '', '', '')),
																			 'where' => $this->sql->where(array('where'), array(Table_1::$bill . '.' . Field_1::$billez_code), array($billez_code), array('')),
																			 'other' => '')), 'row_array');
			array_push($bill_info, $sql_result);
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $bill_info), $route_data['private_key'], ''));
	}
}//end