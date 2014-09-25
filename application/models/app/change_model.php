<?php

class Change_model extends CI_Model {
	/*
	 * 更換起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '11_1':
				return $this->change_key($route_data);
				break;
		}
	}
	
	/*
	 * 更換金鑰組並回傳公鑰給APP
	 * $route_data 所需參數資料
	 */
	public function change_key($route_data) {
		$app = '11_1';
		
		//產生金鑰組
		$key = $this->key->create_key();
		$public_key['public_key'] = $key['public_key'];
		$outer_array = array();
		array_push($outer_array, $public_key);
		
		//更新金鑰組
		array_push(Sql::$table, 'key');
		array_push(Sql::$select, $this->sql->field(array('private_key', 'public_key', 'update_user', 'update_time'), array($key['private_key'], $key['public_key'], $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'key', '每次請求更換金鑰組', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'key', '每次請求更換金鑰組', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, $outer_array);
		} else {
			$json_data = $this->json->encode_json($app, '11_101');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
}//end