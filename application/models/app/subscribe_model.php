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
		}
	}
	
	/*
	 * 查詢該訂閱資料的業者資料
	 * $route_data	所需參數資料
	 */
	public function search_trader($route_data) {
		$app = '5_1';
		
		//解析業者帳單編號
		$trader_code = substr($route_data["trader_data"], 0, 4);
		$bill_kind_code  = substr($datas["trader_data"], 4, 2);

		//查詢該業者帳單資料依據
		$sql_select = $this->sql->select(array('bill_basis.name'), '');
		$sql_where = $this->sql->where(array('where', 'where'), array('trader_code', 'bill_kind_code'), array($trader_code, $bill_kind_code), array(''));
		$sql_join = $this->sql->join(array('bill_basis'), array('trader_bill.bill_kind_code = bill_basis.code'));
		$sql_query = $this->query_model->query($sql_select, 'trader_bill', $sql_join, $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$bill_basis_name = $sql_result['name'];
		
		//查詢該業者帳單的寄送條件
		$sql_select = $this->sql->select(array('send_condition', 'send_condition_times'), '');
		$sql_where = $this->sql->where(array('where'), array('trader_code'), array($trader_code), array(''));
		$sql_query = $this->query_model->query($sql_select, 'trader_contract', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');

		$array = array();
		
		//將代碼轉換為說明
		switch ($sql_result['send_condition']) {
			case 1:
				$sql_result['send_condition'] = '無限制次數寄送實體帳單';
				break;
			case 2:
				$sql_result['send_condition'] = '有限制次數寄送實體帳單';
				break;
			case 3:
				$sql_result['send_condition'] = '只有寄送實體帳單';
				break;
		}
		
		if($sql_result['send_condition_times'] == '') {
			$sql_result['send_condition_times'] = 'blank';
		}
		
		$array['send_condition'] 	   = $sql_result['send_condition'];
		$array['send_condition_times'] = $sql_result['send_condition_times'];
		$array['bill_basis_name'] 	   = $bill_basis_name;
		
		$json_data = $this->json->encode_json($app, $array);
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 訂閱帳單資料相關處理
	 * 或者還沒有帳單先訂閱
	 * $route_data 所需參數資料
	 */
	public function subscribe_bill($route_data) {
		$app = '5_2';
		
		//解析業者帳單編號
		$trader_code = substr($route_data["subscribe_code"], 0, 4);
		$bill_kind_code = substr($datas["subscribe_code"], 4, 2);
		
		//確認是否有該業者帳單存在
		$sql_select = $this->sql->select(array('trader_code'), '');
		$sql_where = $this->sql->where(array('where', 'where'), array('trader_code', 'bill_kind_code'), array($trader_code, $bill_kind_code), array(''));
		$sql_query = $this->query_model->query($sql_select, 'trader_bill', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');
		
		if($sql_result) {
			//檢查該會員是否重複訂閱相同帳單
			$sql_select = $this->sql->select(array('id'), '');
			$sql_where = $this->sql->where(array('where', 'where'), array('id', 'subscribe_code'), array($route_data["id"], $route_data["subscribe_code"]), array(''));
			$sql_query = $this->query_model->query($sql_select, 'subscribe', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'num_rows');
			
			if($sql_result) {
				$json_data = $this->json->encode_json($app, '5_202');
			} else {
				//檢查該訂閱帳單是否目前有帳單存在
				$sql_select = $this->sql->select(array('billez_code'), '');
				$sql_where = $this->sql->where(array('like'), array('billez_code'), array($route_data["subscribe_code"]), array('after'));
				$sql_query = $this->query_model->query($sql_select, 'bill', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'num_rows');
				
				if($sql_result) {
					//查詢該業者帳單最大帳單批次碼
					$sql_select = $this->sql->select(array('batch_code'), 'max');
					$sql_where = $this->sql->where(array('like'), array('billez_code'), array($route_data['subscribe_code']), array('after'));
					$sql_query = $this->query_model->query($sql_select, 'bill', '', $sql_where, '');
					$sql_result = $this->sql->result($sql_query, 'row_array');
					$batch_code = $sql_result['max'];
					
					//查詢該業者帳單最近一期的帳單編號
					$sql_select = $this->sql->select(array('billez_code'), '');
					$sql_where = $this->sql->where(array('where', 'like'), array('batch_code', 'billez_code'), array($batch_code, $route_data['subscribe_code']), array('', 'after'));
					$sql_query = $this->query_model->query($sql_select, 'bill', '', $sql_where, '');
					$sql_result = $this->sql->result($sql_query, 'row_array');
					$billez_code = $sql_result['billez_code'];
					
					//查詢該業者帳單寄送條件
					$sql_select = $this->sql->select(array('send_condition', 'send_condition_times'), '');
					$sql_where = $this->sql->where(array('where'), array('trader_code'), array($trader_code), array(''));
					$sql_query = $this->query_model->query($sql_select, 'trader_contract', '', $sql_where, '');
					$sql_result = $this->sql->result($sql_query, 'row_array');
					$send_condition = $sql_result;
					
					if($send_condition['send_condition_times'] != '') {
						$send_condition['send_condition'] = $send_condition['send_condition_times'];
					}
					
					//帳單辨識資料
					$identify_data = substr($route_data['subscribe_code'], 6);
					
					//查詢該資料是否在一般會員資料中
					$sql_select = $this->sql->select(array('action_member_identity'), '');
					$sql_where = $this->sql->where(array('where', 'where', 'where'), array('trader_code', 'bill_kind_code', 'identify_data'), array($trader_code, $bill_kind_code, $identify_data), array(''));
					$sql_query = $this->query_model->query($sql_select, 'normal_member', '', $sql_where, '');
					$sql_result = $this->sql->result($sql_query, 'row_array');
					
					if($sql_result['action_member_identity'] == 'n') {
						//更新一般會員擁有行動會員資格資料
						array_push(Sql::$table, 'normal_member');
						array_push(Sql::$select, $this->sql->field(array('action_member_identity', 'update_user', 'update_time'), array('y', $route_data['id'], $this->sql->get_time(1))));
						array_push(Sql::$where, $this->sql->where(array('where', 'where', 'where'), array('trader_code', 'bill_kind_code', 'identify_data'), array($trader_code, $bill_kind_code, $identify_data), array('')));
						array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'normal_member', '帳單訂閱更新一般會員具行動會員資格', $this->sql->get_time(1))));
						array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'normal_member', '帳單訂閱更新一般會員具行動會員資格', $this->sql->get_time(1), '')));
						array_push(Sql::$kind, 2);
					} else {
						//新增訂閱中資料
						array_push(Sql::$table, 'subscribe');
						array_push(Sql::$select, $this->sql->field(array('id', 'subscribe_code', 'state', 'time', 'send_condition', 'remark', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['id'], $route_data['subscribe_code'], 2, $this->sql->get_time(1), $send_condition['send_condition'], '', $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
						array_push(Sql::$where, '');
						array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'subscribe', '帳單訂閱新增訂閱中訂閱', $this->sql->get_time(1))));
						array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'subscribe', '帳單訂閱新增訂閱中訂閱', $this->sql->get_time(1), '')));
						array_push(Sql::$kind, 1);
						
						//執行更新,成功的話推播一個最近的帳單給該會員
						if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
							//查詢該會員手機ID資料
							$sql_select = $this->sql->select(array('mobile_phone', 'mobile_phone_id'), '');
							$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
							$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
							$sql_result = $this->sql->result($sql_query, 'row_array');
							$action_member_info = $sql_result;
							
							//將要推播的訊息丟進推播變數
							array_push(Push::$id, $route_data['id']);
							array_push(Push::$moblie_phone, $action_member_info['mobile_phone']);
							array_push(Push::$moblie_phone_id, $action_member_info['mobile_phone_id']);
							array_push(Push::$billez_code, '');
							
							$this->sql->clear_static();
							//執行推播
							$this->push->send_push('gcm_2');
								
							//新增推播紀錄
							array_push(Sql::$table, 'push_log');
							array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'mobile_phone_id', 'event', 'time', 'result', 'gcm_message'), array(Push::$id[0], Push::$moblie_phone[0], Push::$moblie_phone_id[0], 2, $this->sql->get_time(1), Push::$result[0], Push::$gcm[0])));
							array_push(Sql::$where, '');
							array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'push_log', '帳單訂閱推播最近一張帳單', $this->sql->get_time(1))));
							array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'push_log', '帳單訂閱推播最近一張帳單', $this->sql->get_time(1), '')));
							array_push(Sql::$kind, 1);
								
							$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
							$json_data = $this->json->encode_json($app, '5_205');
						} else {
							$json_data = $this->json->encode_json($app, '5_206');
						}
					}
				} else {
					//新增處理中訂閱狀態
					array_push(Sql::$table, 'subscribe');
					array_push(Sql::$select, $this->sql->field(array('id', 'subscribe_code', 'state', 'time', 'send_condition', 'remark', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['id'], $route_data['subscribe_code'], 1, $this->sql->get_time(1), '', '', $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
					array_push(Sql::$where, '');
					array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'subscribe', '帳單訂閱新增處理中訂閱', $this->sql->get_time(1))));
					array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'subscribe', '帳單訂閱新增處理中訂閱', $this->sql->get_time(1), '')));
					array_push(Sql::$kind, 1);
					
					//執行更新
					if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
						$json_data = $this->json->encode_json($app, '5_203');
					} else {
						$json_data = $this->json->encode_json($app, '5_204');
					}
				}	
			}
		} else {
			$json_data = $this->json->encode_json($app, '5_201');

			//查詢修改記錄的訂閱失敗資料
			$sql_select = $this->sql->select(array('subscribe_fail'), '');
			$sql_where = $this->sql->where(array('where', 'where'), array('id', 'frequency'), array($route_data['id'], 1), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');
			$new_subscribe_fail = $sql_result['subscribe_fail'] . ',' . substr($route_data['subscribe_code'], 6);
			
			//將失敗的資料存放在會員修改記錄中
			array_push(Sql::$table, 'action_member_alter_log');
			array_push(Sql::$select, $this->sql->field(array('subscribe_fail', 'update_user', 'update_time'), array($new_subscribe_fail, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'frequency'), array($route_data['id'], 1), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_alter_log', '訂閱帳單訂閱失敗存放失敗資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_alter_log', '訂閱帳單訂閱失敗存放失敗資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//執行更新
			$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 取消訂閱
	 * $route_data 所需參數資料
	 */
	public function cancel_subscribe($route_data) {
		$app = '5_3';
		
		//取消訂閱
		array_push(Sql::$table, 'subscribe');
		array_push(Sql::$select, $this->sql->field(array('state', 'update_user', 'update_time'), array(3, $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id', 'subscribe_code'), array($route_data['id'], $route_data['subscribe_code']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'subscribe', '帳單訂閱取消訂閱', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'subscribe', '帳單訂閱取消訂閱', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, '5_301');
		} else {
			$json_data = $this->json->encode_json($app, '5_302');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 分享帳單
	 * $route_data 所需參數資料
	 */
	public function share_bill($route_data) {
		$app = '5_4';
		
		//查詢該會員編號
		$sql_select = $this->sql->select(array('id', 'mobile_phone', 'mobile_phone_id'), '');
		$sql_where = $this->sql->where(array('where'), array('mobile_phone'), array($route_data['mobile_phone']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$action_member_info = $sql_result;
		
		//查詢是否重複分享
		$sql_select = $this->sql->select(array('billez_code'), '');
		$sql_where = $this->sql->where(array('where', 'where', 'where'), array('id', 'mobile_phone', 'billez_code'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'bill_share_log', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		if($sql_result) {
			$json_data = $this->json->encode_json($app, '5_401');
		} else {
			if(count($action_member_info) == 0) {
				/*
				 * 待增加簡訊內容規格
				 */
				
				$sms_result = $this->sms->send_sms(2, $route_data['mobile_phone'], '', array('billez_code' => $route_data['billez_code'], 'message' => $route_data['message']));
				
				if($sms_result == 1) {
					$result = 1;
					$sms_result = '';
					$json_data = $this->json->encode_json($app, '5_402');
				} else {
					$result = 2;
					$json_data = $this->json->encode_json($app, '5_403');
				}

				//新增帳單分享記錄
				array_push(Sql::$table, 'bill_share_log');
				array_push(Sql::$select, $this->sql->field(array('billez_code', 'id', 'mobile_phone', 'read', 'message', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['billez_code'], $route_data['id'], $route_data['mobile_phone'], 'n', $route_data['message'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'bill_share_log', '帳單訂閱新增分享帳單紀錄', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'bill_share_log', '帳單訂閱新增分享帳單紀錄', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
				
				//新增簡訊記錄
				array_push(Sql::$table, 'sms_log');
				array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'event', 'result', 'error_message', 'create_time'), array($route_data['id'], $route_data['mobile_phone'], 5, $result, $sms_result, $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_log', '帳單訂閱分享帳單簡訊通知', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_log', '帳單訂閱分享帳單簡訊通知', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
				
				if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
					$json_data = $this->json->encode_json($app, '5_404');
				}
			} else {
				//檢查是否分享給自己
				foreach($action_member_info as $action_member) {
					if($action_member['id'] == $route_data['id']) {
						$myself = TRUE;
						continue;
					}
					
					//將要推播的訊息丟進推播變數
					array_push(Push::$id, $action_member['id']);
					array_push(Push::$moblie_phone, $route_data['mobile_phone']);
					array_push(Push::$moblie_phone_id, $action_member['mobile_phone_id']);
					array_push(Push::$billez_code, $route_data['billez_code']);	
				}

				//執行推播
				$this->push->send_push('gcm_1');
				
				//新增帳單分享記錄
				array_push(Sql::$table, 'bill_share_log');
				array_push(Sql::$select, $this->sql->field(array('billez_code', 'id', 'mobile_phone', 'read', 'message', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['billez_code'], $route_data['id'], $route_data['mobile_phone'], 'n', $route_data['message'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'bill_share_log', '帳單訂閱新增分享帳單紀錄', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'bill_share_log', '帳單訂閱新增分享帳單紀錄', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
				
				$count = count(Push::$id);
				
				for($i = 0; $i < $count; $i++) {
					//新增推播紀錄
					array_push(Sql::$table, 'push_log');
					array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'mobile_phone_id', 'event', 'time', 'result', 'gcm_message'), array(Push::$id[0], Push::$moblie_phone[0], Push::$moblie_phone_id[0], 1, $this->sql->get_time(1), Push::$result[0], Push::$gcm[0])));
					array_push(Sql::$where, '');
					array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'push_log', '帳單訂閱新增分享帳單推播紀錄', $this->sql->get_time(1))));
					array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'push_log', '帳單訂閱新增分享帳單推播紀錄', $this->sql->get_time(1), '')));
					array_push(Sql::$kind, 1);
				}
				
				//執行更新
				if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
					if($myself) {
						$json_data = $this->json->encode_json($app, '5_405');
					} else {
						$json_data = $this->json->encode_json($app, '5_406');
					}
				} else {
					$json_data = $this->json->encode_json($app, '5_407');
				}
			}
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
}//end