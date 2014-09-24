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
		$app = '8_1';
		
		//先將該暫存圖片打開,然後讀取該圖片
		if(isset($_FILES['image']['error'])) {
			if($_FILES['image']['error'] == 0){
				$instr 	= fopen($_FILES['image']['tmp_name'], 'rb');
				$image 	= addslashes(fread($instr, filesize($_FILES['image']['tmp_name'])));
			}
		}
		
		//查詢最大問題編號
		$sql_select = $this->sql->select(array('id'), 'max');
		$sql_where = '';
		$sql_query = $this->query_model->query($sql_select, 'problem_log', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		//產生問題編號
		$id = $this->create->id('PR', $sql_result['max']);
		
		//新增問題
		array_push(Sql::$table, 'problem_log');
		array_push(Sql::$select, $this->sql->field(array('id', 'problem', 'asker', 'scope', 'state', 'answer', 'response', 'ask_time', 'image', 'star', 'create_user', 'create_time', 'update_user', 'update_time'), array($id, $route_data['problem'], $route_data['id'], 1, 'n', '', '', $this->sql->get_time(1), $image, $route_data['star'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, '');
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'problem_log', '問題記錄新增行動會員問題', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'problem_log', '問題記錄新增行動會員問題', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 1);
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			$json_data = $this->json->encode_json($app, '8_101');
		} else {
			$json_data = $this->json->encode_json($app, '8_102');
		}
		
		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json(1, $encode_data);
	}
}//end