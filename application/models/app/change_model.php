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
		//產生金鑰組
		$key = $this->key->create_key();
		$public_key['public_key'] = $key['public_key'];
		$json_array = array();
		array_push($json_array, $public_key);
		
		//更新金鑰組
		$this->sql->add_static(array('table'=> Table_1::$key,
									 'select'=> $this->sql->field(array(Field_2::$private_key, Field_2::$public_key, Field_1::$update_user, Field_1::$update_time), array($key['private_key'], $key['public_key'], $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(), array(), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$key, '金鑰_每次請求更新金鑰組', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$key, '金鑰_每次請求更新金鑰組', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳金鑰組
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $json_array), $route_data['private_key'], ''));
		}
	
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
	}
}//end