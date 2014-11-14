<?php

class User_model extends CI_Model {
	/*
	 * 新增使用者
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function insert($post, $user) {
		//確認名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email), ''),
																		  'from' => Table_1::$user_list,
																		  'join'=> '',
																		  'where' => $this->sql->where(array('where'), array(Field_1::$email), array($post['email']), array('')),
																		  'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;
	
		//查詢等級名稱最大代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		  'from' => Table_1::$user_list,
																		  'join'=> '',
																		  'where' => '',
																		  'other' => '')), 'row_array');
		$id = $this->create->id('US', $sql_result['max']);
	
		//新增等級名稱
		$this->sql->add_static(array('table'=> Table_1::$user_list,
									'select'=> $this->sql->field(array(Field_1::$code, Field_2::$kind, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['object'], $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									'where'=> '',
									'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$level_kind, '新增等級名稱_新增等級名稱', $this->sql->get_time(1))),
									'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$level_kind, '新增等級名稱_新增等級名稱', $this->sql->get_time(1), '')),
									'kind'=> 1));
		$this->sql->add_static(array('table'=> Table_1::$user_list,
				'select'=> $this->sql->field(array(Field_1::$code, Field_2::$kind, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['object'], $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
				'where'=> '',
				'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$level_kind, '新增等級名稱_新增等級名稱', $this->sql->get_time(1))),
				'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$level_kind, '新增等級名稱_新增等級名稱', $this->sql->get_time(1), '')),
				'kind'=> 1));
		
		
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
}