<?php

class Insert_update_model extends CI_Model {
	/*
	 * 新增/更新資料
	 * 使用迴圈
	 * 若其中有一個新增/更新出錯則整個回復並回傳FALSE
	 * where 種類請看query_model的query方法
	 * $table		Sql::$table 存放的資料表名稱
	 * $select		Sql::$select存放的資料及欄位
	 * $where		Sql::$where	存放的欲限制資料條件
	 * $user_log	Sql::$log	存放的記錄資料
	 * $system_log	Sql::$error	存放的發生錯誤時寫入錯誤記錄資料
	 * $kind		Sql::$kind	存放的要新增或更新資料
	 */
	public function execute_sql($table, $select, $where, $user_log, $system_log, $kind) {
		$count = count($table);
	
		$this->db->trans_begin();
	
		for($i = 0; $i < $count; $i++) {
			if($where[$i] != '') {
				foreach($where[$i] as $item) {
					switch($item['command']) {
						case 'where':
							$this->db->where($item['field'], $item['value']);
							break;
						case 'or_where':
							$this->db->or_where($item['field'], $item['value']);
							break;
						case 'where_in':
							$this->db->where_in($item['field'], $item['value']);
							break;
						case 'or_where_in':
							$this->db->or_where_in($item['field'], $item['value']);
							break;
						case 'where_not_in':
							$this->db->where_not_in($item['field'], $item['value']);
							break;
						case 'or_where_not_in':
							$this->db->or_where_not_in($item['field'], $item['value']);
							break;
						case 'like':
							$this->db->like($item['field'], $item['value'], $item['control']);
							break;
						case 'or_like':
							$this->db->or_like($item['field'], $item['value'], $item['control']);
							break;
						case 'not_like':
							$this->db->not_like($item['field'], $item['value'], $item['control']);
							break;
						case 'or_not_like':
							$this->db->or_not_like($item['field'], $item['value'], $item['control']);
							break;
					}
				}
			}
	
			if($kind[$i] == 2) {
				$this->db->update($table[$i], $select[$i]);
			} else {
				$this->db->insert($table[$i], $select[$i]);
			}
			
				
			if($this->db->trans_status() === FALSE) {
				$system_log[$i]['db_message'] = $this->db->_error_message();
				$this->db->trans_rollback();
				$this->db->insert('system_log', $system_log[$i]);
				return FALSE;
			} else {
				$this->db->insert('user_log', $user_log[$i]);
			}
		}
		
		$this->db->trans_commit();
		return TRUE;
	}
	
	/*
	 *
	 */
	public function update_batch() {
	
	}
}