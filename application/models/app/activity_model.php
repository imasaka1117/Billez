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
		$app = '10_1';
		$post = array('special' => 1, 'mobile_phone_list' => $datas['mobile_phone_list'], 'id' => $datas['id']);
		
		$ch = curl_init();
			
		$options = array(
				CURLOPT_URL => base_url() . 'app',
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
		
		$json_data = $this->json->encode_json($app, '10_01');

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
	
	/*
	 * 將要推薦的名單寄發簡訊
	 * 先將重複的剃除
	 * 然後檢查手機格式轉成統一的
	 * 再來寄發
	 * $route_data 所需參數資料
	 */
	public function send_sms($route_data) {
		$mobile_phone_list 	   = array_unique(split(",", $route_data['mobile_phone_list']));
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
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where_in'), array('mobile_phone'), array($mobile_phone_list_1st), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$mobile_phone_list_2nd = array();
		
		foreach($sql_result as $data) {
			array_push($mobile_phone_list_2nd, $data['mobile_phone']);
		}
		
		//剔除已經推薦過的手機號碼
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where_in'), array('mobile_phone'), array($mobile_phone_list_2nd), array(''));
		$sql_query = $this->query_model->query($sql_select, 'recommend_list', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'result_array');
		$mobile_phone_list_3rd = array();
		
		foreach($sql_result as $data) {
			array_push($mobile_phone_list_3rd, $data['mobile_phone']);
		}
		
		//查詢推薦人名稱
		$sql_select = $this->sql->select(array('last_name', 'first_name'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$name = $sql_result['last_name'] . $sql_result['first_name'];
		
		//寄發推薦簡訊
		foreach($mobile_phone_list_3rd as $mobile_phone) {
			/*
			 * 這裡待加入簡訊內容規格
			 */
				
			$sms_result = $this->sms->send_sms(3, $mobile_phone, '', $name);
				
			if($sms_result == 1) {
				$result = 1;
				$sms_result = '';
			} else {
				$result = 2;
			}
				
			$this->sql->clear_static();
			//新增簡訊記錄
			array_push(Sql::$table, 'sms_log');
			array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'event', 'result', 'error_message', 'create_time'), array($route_data['id'], $route_data['mobile_phone'], 1, $result, $sms_result, $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_log', '會員活動新增推薦名單簡訊記錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_log', '會員活動新增推薦名單簡訊記錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
				
			//新增推薦名單
			array_push(Sql::$table, 'recommend_list');
			array_push(Sql::$select, $this->sql->field(array('mobile_phone', 'recommender', 'create_user', 'create_time', 'update_user', 'update_time'), array($mobile_phone, $route_data['id'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'recommend_list', '會員活動新增推薦名單', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'recommend_list', '會員活動新增推薦名單', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
		}
		
		$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
	}
}//end