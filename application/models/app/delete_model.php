<?php

class Delete_model extends CI_Model {
	/*
	 * 刪除帳號起點函式
 	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '6_1':
				return $this->delete_member($route_data);
				break;
		}
	}
	
	/*
	 * 刪除帳號
	 * 但是只是把狀態標註而已
	 * 等到該會員想要復原
	 * 再把狀態改變
	 * $route_data	所需參數資料
	 */
	public function delete_member($route_data) {
		//修改該會員狀態為刪除狀態
		$this->sql->add_static(array('table'=> Table_1::$action_member,
									 'select'=> $this->sql->field(array(Field_1::$state, Field_1::$update_user, Field_1::$update_time), array(3, $route_data['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($route_data['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $route_data['id'], Table_1::$action_member, '刪除帳號_將帳號狀態更改為刪除狀態', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $route_data['id'], Table_1::$action_member, '刪除帳號_將帳號狀態更改為刪除狀態', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳狀態碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));
	}
}//end