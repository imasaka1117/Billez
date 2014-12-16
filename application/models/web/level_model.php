<?php

class Level_model extends CI_Model {
	/*
	 * 新增等級
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function insert($post, $user) {
		//確認名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code), ''),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;
	
		//查詢等級名稱最大代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		$code = $this->create->id('LV', $sql_result['max']);
	
		//新增等級
		$this->sql->add_static(array('table'=> Table_1::$level,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$level, '新增等級_新增等級', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$level, '新增等級_新增等級', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 更新等級
	* $post 查詢資料
	* $user 使用者
	*/
	public function update($post, $user) {
		//確認名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code), ''),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$name, Field_1::$code . ' !='), array($post['name'], $post['id']), array('', '')),
																		 'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;

		//更新等級名稱
		$this->sql->add_static(array('table'=> Table_1::$level,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_1::$update_user, Field_1::$update_time), array($post['name'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$code), array($post['id']), array('', '')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$level, '查詢等級_更新等級', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$level, '查詢等級_更新等級', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢等級資料
	 * $post 查詢資料
	 */
	public function search_data($post) {
		//查詢等級資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code . ' AS id', Field_1::$name), 'function'),
																		 'from' => Table_1::$level,
																		 'join'=>  '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$code), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢等級資料
	 * $post 查詢條件資料
	 */
	public function search($post) {
		//查詢等級列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$code), ''),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$code, Field_1::$name), array($post['code'], $post['name'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢等級列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$level . '.' . Field_1::$code . ' AS id', Table_1::$level . '.' .Field_1::$name . ' AS level_name'), 'function'),
																		 'from' => Table_1::$level,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$code, Field_1::$name), array($post['code'], $post['name'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		
		return $this->option->table($sql_result, array('等級編號', '等級名稱'), base_url() . Param::$index_url . 'level/update_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	