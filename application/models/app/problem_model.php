<?php

class Problem_model extends CI_Model {
	/*
	 * 加入會員起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '8_1':
				return $this->storage_problem($route_data);
				break;
		}
	}
	
	/*
	 * 將問題儲存
	 * 需要注意的是關於圖片的存放
	 * $route_data 所需參數資料
	 */
	public function storage_problem($route_data) {
		//查詢最大問題編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$problem_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生問題編號
		$id = $this->create->id('PR', $sql_result['max']);
		
		//新增問題
		$this->sql->add_static(array('table'=> Table_1::$problem_log,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_3::$problem, Field_3::$asker, Field_3::$scope, Field_1::$state, Field_3::$ask_time, Field_3::$star, Field_4::$page, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($id, $route_data['problem'], $route_data['id'], 1, 'n', $this->sql->get_time(1), $route_data['star'], $route_data['page'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$problem_log, '問題回報_新增問題記錄', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$problem_log, '問題回報_新增問題記錄', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//成功回傳狀態碼
			return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '01'), $route_data['private_key'], ''));		
		}
		
		return $this->json->encode_json('vale', $this->key->encode_app($this->json->encode_json($route_data['sub_param'], $route_data['sub_param'] . '02'), $route_data['private_key'], ''));
	}
}//end