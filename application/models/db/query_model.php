<?php

class Query_model extends CI_Model {
	/*
	 * 用來處理更新和查詢時候用到的條件處理
	* $where 條件集合
	*/
	public function condition_where($where) {
		foreach($where as $item) {
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
	
	/*
	 * 用來處理更新和查詢時候用到的額外條件處理
	* $other 額外條件集合
	*/
	public function condition_other($other) {
		foreach($other as $item) {
			switch($item['command']) {
				case 'group_by':
					$this->db->group_by($item['field']);
					break;
				case 'distinct':
					$this->db->distinct();
					break;
				case 'having':
					$this->db->having($item['condition']);
					break;
				case 'or_having':
					$this->db->or_having($item['condition']);
					break;
				case 'order_by':
					$this->db->order_by($item['field'], $item['sort']);
					break;
				case 'limit':
					$this->db->limit($item['amount'], $item['start']);
					break;
			}
		}
	}
	
	/*
	 * 執行查詢函式
	* 因為CI有提供多種查詢函式
	* 所以可以用字串控制要執行哪些額外條件查詢
	* 基本上命名都跟函式名稱一樣
	* select	欲查詢的欄位及是否有特殊查詢
	* from		欲查詢的資料表
	* join		使用到其他的資料表及相連欄位,若是沒有則傳入''
	* where	查詢的條件,若是沒有則傳入''
	* other	是否有其他的特殊指令,ex : order by ,若是沒有則傳入''
	*/
	public function query($query) {
		switch($query['select']['condition']) {
			case 'function':
				$this->db->select($query['select']['fields'], FALSE);
				break;
			default:
				$this->db->select($query['select']['fields']);
				break;
		}
		
		$this->db->from($query['from']);

		if($query['join'] != '') {
			foreach($query['join'] as $item) {
				if($item['style'] == '') {
					$this->db->join($item['table'], $item['field']);
				} else {
					$this->db->join($item['table'], $item['field'], $item['style']);
				}
			}
		}
	
		if($query['where'] != '') $this->condition_where($query['where']);
		if($query['other'] != '') $this->condition_other($query['other']);
		$a = $this->db->get();
		echo $this->db->_error_message();
		return $a;
// 		return $this->db->get();
	}
	
	/*
	 * 新增/更新資料
	* 使用迴圈
	* 若其中有一個新增/更新出錯則整個回復並回傳FALSE
	* where 種類請看query_model的query方法
	* table		Sql::$table 存放的資料表名稱
	* select		Sql::$select存放的資料及欄位
	* where		Sql::$where	存放的欲限制資料條件
	* user_log		Sql::$log	存放的記錄資料
	* system_log	Sql::$error	存放的發生錯誤時寫入錯誤記錄資料
	* kind			Sql::$kind	存放的要新增或更新資料
	*/
	public function execute_sql($sql) {
		$count = count($sql['table']);
	
		$this->db->trans_begin();
	
		for($i = 0; $i < $count; $i++) {
			if($sql['where'][$i] != '') $this->condition_where($sql['where'][$i]);
	
			if($sql['kind'][$i] == 2) {
				$this->db->update($sql['table'][$i], $sql['select'][$i]);
			} else {
				$this->db->insert($sql['table'][$i], $sql['select'][$i]);
			}
	
	
			if($this->db->trans_status() === FALSE) {
				$sql['error'][$i]['db_message'] = $this->db->_error_message();
				$this->db->trans_rollback();
				$this->db->insert(Table_1::$system_log, $sql['error'][$i]);
				return FALSE;
			} else {
				$this->db->insert(Table_1::$user_log, $sql['log'][$i]);
			}
		}
	
		$this->db->trans_commit();
		return TRUE;
	}
}