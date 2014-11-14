<?php

class Normal_member_model extends CI_Model {
	/*
	 * 查詢一般會員資料
	 * $post 查詢資料
	 */
	public function search_data($post) {
		//查詢一般會員資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name', Field_1::$identify_data, Table_1::$normal_member . '.' . Field_1::$name . ' AS normal_name', Field_1::$data1, Field_1::$data2, Field_1::$data3, Field_1::$data4, Field_1::$data5, 'IF(' . Field_3::$action_member_identity . ' = "y", "是", "否") AS ' . Field_3::$action_member_identity), 'function'),
																		 'from' => Table_1::$normal_member,
																		'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$normal_member . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$normal_member . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		$normal_member = $sql_result;
		
		//查詢業者帳單資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$identify_data), ''),
																		 'from' => Table_1::$normal_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		$bill_data = $sql_result;
// echo $bill_data['identify_data'];exit();
		//查詢有訂閱的行動會員資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array('SUBSTRING(' . Field_3::$subscribe_code . ', 1, 6) =', 'SUBSTRING(' . Field_3::$subscribe_code . ', 7) ='), array($bill_data['trader_code'] . $bill_data['bill_kind_code'], $bill_data['identify_data']), array('', '')),
																		 'other' => '')), 'result_array');
		if(count($sql_result)) {
			$id = array();
			foreach($sql_result as $data) array_push($id, $data['id']);
			$normal_member['subscribe'] = $id;
		}
	
		return json_encode($normal_member, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢一般會員資料
	 * $post 查詢條件資料
	 */
	public function search_normal_member($post) {
		if(strlen($post['trader']) > 4) $post['trader'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';
		if(strlen($post['action_member_identity']) > 1) $post['action_member_identity'] = '';

		//查詢一般會員列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$normal_member,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_1::$identify_data, Field_3::$action_member_identity, Field_1::$trader_code, Field_1::$bill_kind_code), array($post['id'], $post['name'], $post['identify_data'], $post['action_member_identity'], $post['trader'], $post['bill_kind'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢一般會員列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name', Field_1::$identify_data, Table_1::$normal_member . '.' . Field_1::$name . ' AS normal_name', 'IF(' . Field_3::$action_member_identity . ' = "y", "是", "否")'), 'function'),
																		 'from' => Table_1::$normal_member,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$normal_member . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$normal_member . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		 'where' => $this->sql->where_search(array(Field_1::$id, Table_1::$normal_member . '.' .Field_1::$name, Field_1::$identify_data, Field_3::$action_member_identity, Field_1::$trader_code, Field_1::$bill_kind_code), array($post['id'], $post['name'], $post['identify_data'], $post['action_member_identity'], $post['trader'], $post['bill_kind'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		
		return $this->option->table($sql_result, array('會員編號', '業者', '帳單種類', '辨識資料', '會員名稱', '是否為行動會員'), base_url() . Param::$index_url . 'normal_member/update_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	