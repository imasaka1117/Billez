<?php

class Query_model extends CI_Model {
	
	/*
	 * 執行查詢函式
	 * 因為CI有提供多種查詢函式
	 * 所以可以用字串控制要執行哪些額外條件查詢
	 * 基本上命名都跟函式名稱一樣
	 * 
	 * $select	欲查詢的欄位及是否有特殊查詢
	 * $from	欲查詢的資料表
	 * $join	使用到其他的資料表及相連欄位,若是沒有則傳入''
	 * $where	查詢的條件,若是沒有則傳入''
	 * $other	是否有其他的特殊指令,ex : order by ,若是沒有則傳入''
	 */
	public function query($select, $from, $join, $where, $other) {
		switch($select['condition']) {
			case 'max':
				$this->db->select_max($select['fields'], 'max');
				break;
			case 'min':
				$this->db->select_min($select['fields'], 'min');
				break;
			case 'avg':
				$this->db->select_avg($select['fields'], 'avg');
				break;
			case 'sum':
				$this->db->select_sum($select['fields'], 'sum');
				break;
			case 'function':
				$this->db->select($select['fields'], FALSE);
				break;
			default:
				$this->db->select($select['fields']);
				break;
		}
		
		$this->db->from($from);
		
		if($join != '') {
			foreach($join as $item) {
				if($item['style'] == '') {
					$this->db->join($item['table'], $item['field']);
				} else {
					$this->db->join($item['table'], $item['field'], $item['style']);
				}			
			}
		}
		
		if($where != '') {
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

		if($other != '') {
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

		return $this->db->get();
	}

}