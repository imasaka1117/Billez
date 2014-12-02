<?php

class Promo_model extends CI_Model {
	/*
	 * 寄發促銷優惠活動
	* $post 查詢資料
	*/
	public function send($post) {
		switch($post['range']) {
			case 1:
				$file_name = '行動會員Email';
				$table = Table_1::$action_member;
				$field = Field_1::$email;
				break;
			case 2:
				$file_name = '業者主聯絡人Email';
				$table = Table_1::$trader;
				$field = Field_3::$main_contact_email;
				break;
			case 3:
				$file_name = '代收機構主聯絡人Email';
				$table = Table_1::$machinery;
				$field = Field_3::$main_contact_email;
				break;
		}
		
		header("Content-type: text/x-csv");
		header("Content-Disposition:filename=" . iconv('utf-8', 'big5', $file_name) . ".csv");
		
		//查詢範圍內的所有電子郵件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array($field), ''),
																		 'from' => $table,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'result_array');
		$text = '';
		
		if(count($sql_result)) {
			foreach($sql_result as $data) {
				$text .= $data[$field] . "\n";
			}
		} else {
			$text = '查無任何電子郵件';
		}
		
		echo iconv("UTF-8", "big5", $text);
	}
	
	/*
	 * 新增促銷優惠活動
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function insert($post, $user) {
		//確認名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$sales_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;
	
		//查詢促銷活動最大代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$sales_log,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		$id = $this->create->id('SA', $sql_result['max']);
	
		//新增促銷活動
		$this->sql->add_static(array('table'=> Table_1::$sales_log,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_3::$begin_time, Field_3::$end_time, Field_3::$range, Field_3::$way, Field_3::$level, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($id, $post['name'], $post['begin_year'] . '/' . $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['range'], $post['way'], $post['level'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$sales_log, '新增促銷活動_新增促銷活動', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$sales_log, '新增促銷活動_新增促銷活動', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 更新促銷活動
	* $post 查詢資料
	* $user 使用者
	*/
	public function update($post, $user) {
		//確認名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$sales_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$name, Field_1::$id . ' !='), array($post['name'], $post['id']), array('', '')),
																		 'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;

		//更新促銷活動
		$this->sql->add_static(array('table'=> Table_1::$sales_log,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_3::$begin_time, Field_3::$end_time, Field_3::$range, Field_3::$way, Field_3::$level, Field_1::$update_user, Field_1::$update_time), array($post['name'], $post['begin_year'] . '/' . $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['range'], $post['way'], $post['level'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$sales_log, '查詢促銷活動_更新促銷活動', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$sales_log, '查詢促銷活動_更新促銷活動', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'reload';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢促銷活動資料
	 * $post 查詢資料
	 */
	public function search_data($post) {
		//查詢促銷活動資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, '`' . Field_3::$range . '`', 'YEAR(' . Field_3::$begin_time . ') AS begin_year', 'MONTH(' . Field_3::$begin_time . ') AS begin_month', 'DAY(' . Field_3::$begin_time . ') AS begin_day', 'YEAR(' . Field_3::$end_time . ') AS end_year', 'MONTH(' . Field_3::$end_time . ') AS end_month', 'DAY(' . Field_3::$end_time . ') AS end_day', Field_3::$way, Field_3::$level), 'function'),
																		 'from' => Table_1::$sales_log,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢促銷活動資料
	 * $post 查詢條件資料
	 */
	public function search($post) {
		if(strlen($post['range']) > 1) $post['range'] = '';
		if(strlen($post['level']) > 1) $post['level'] = '';
		
		//查詢促銷活動列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$sales_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$range, Field_3::$level), array($post['id'], $post['name'], $post['range'], $post['level'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢促銷活動列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, Field_3::$begin_time, Field_3::$end_time, Field_3::$range, Field_3::$level, Field_3::$way), ''),
																		 'from' => Table_1::$sales_log,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$range, Field_3::$level), array($post['id'], $post['name'], $post['range'], $post['level'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		$use_result = array();
		
		foreach($sql_result as $data) {
			$data['range'] = $this->transform->promo_range($data['range']);
			$data['way'] = $this->transform->promo_way($data['way']);
			array_push($use_result, $data);
		}
		
		return $this->option->table($use_result, array('活動編號', '活動名稱', '開始日期', '結束日期', '優惠範圍', '活動等級', '優惠方式'), base_url() . Param::$index_url . 'promo/update_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	