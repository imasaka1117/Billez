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
		$app = '6_1';
		
		//修改該會員狀態為刪除狀態
		array_push(Sql::$table, 'action_member');
		array_push(Sql::$select, $this->sql->field(array('state', 'update_user', 'update_time'), array(3, $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', '刪除帳號修改狀態為刪除狀態', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', '刪除帳號修改狀態為刪除狀態', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, '6_101');
		} else {
			$json_data = $this->json->encode_json($app, '6_102');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
}//end