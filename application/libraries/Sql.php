<?php if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Sql {
	/*
	 * 為了預防資料庫更新不完全
	 * 所以先將要新增或更新的資料丟進靜態變數
	 * 再一次新增或更新
	 * 若其中有錯則全部回復
	 * 以防止不一致情形發生
	 * $user_log和$system_log則是該資料表欄位名稱
	 * $kind 值為1代表新增,值為2代表更新
	 */
	static public $table = array();
	static public $select = array();
	static public $where = array();
	static public $log = array();
	static public $error = array();
	static public $kind = array();
	static public $user_log = array('operate', 'user', 'table', 'purpose', 'create_time');
	static public $system_log = array('operate', 'user', 'table', 'message', 'create_time', 'db_message');

	/*
	 * 取得當前時間,用來作為新增更新時間
	 * $kind 取得時間種類 
	 * 值為1時代表全部時間
	 * 值為2時代表年月日
	 * 				
	 */
	public function get_time($kind) {
		switch($kind) {
			case 1:
				$date = date('Y-m-d H:i:s');
				break;
			case 2:
				$date = date('Y-m-d');
				break;
		}
		
		return $date;
	}
	
	/*
	 * 將靜態屬性清空
	 * 目的是為了讓有需要進行二次新增或更新時
	 * 不重複新增或更新導致出錯
	 */
	public function clear_static() {
		Sql::$table = array();
		Sql::$select = array();
		Sql::$log = array();
		Sql::$error = array();
		Sql::$where = array();
		Sql::$kind = array();
	}
	
	/*
	 * 新增或更新時欄位處理 ex: array('id' => 'aaa') 
	 * $fields	欄位名稱
	 * $datas	欄位資料
	 */
	public function field($fields, $datas) {
		$count = count($fields);
		$array = array();
		
		for($i = 0; $i < $count; $i++) {
			$array[$fields[$i]] = $datas[$i];
		}
		
		return $array;
	}	
	
	/* 
	 * 產生查詢所需的欄位資料 
	 * 詳細請看query_model的query方法
	 * $fields 		欲查詢的欄位名稱 ex: array('private_key', 'private_key', 'public_key')
	 * $condition	其他可能的查詢條件,若沒有則傳入空白'', ex : 'sum', 'avg', ''
	 */
	public function select($fields, $condition) {
		switch($condition) {
			case '':
			case 'function':
				$string = '';
				foreach($fields as $field) $string = $string . $field . ', ';
				break;
			default:
				$string = $fields[0];
				break;
		}
		
		return array('fields' => $string, 'condition' => $condition);
	}
	
	/*
	 * 產生查詢所需的合併資料表資料
	 * $table	要合併的資料表
	 * $field	要連結的欄位名稱
	 * $style	要合併的方法,若沒有則傳入空白'' ex: left、right、outer、inner、left outer 以及 right outer
	 */
	public function join($table, $field, $style) {
		$count = count($table);
		$array = array();
		
		for($i = 0; $i < $count; $i++) {
			$temp_array = array('table' => $table[$i], 'field' => $field[$i], 'style' => $style[$i]);
			array_push($array, $temp_array);
		}
		return $array;
	}
	
	/*
     * 產生查詢所需的條件資料
     * 若有多條件則寫在後面
     * 詳細請看query_model的query方法
     * $command		條件語法 ex: array('where') 多條件: array('where', 'or_where')
	 * $fields 		欲使用條件的欄位名稱 ex: array('mobile_phone_id') 多條件: array('mobile_phone_id', 'id')
	 * $value		比對條件的值,若有in指令則要傳送陣列,若沒有in則傳送字串 ex: array($mobile_phone_id) 多條件: array($mobile_phone_id, array($mobile_phone_id, $id))
	 * $control		用於like指令,若沒有則傳入空白'' ex: array('before', 'after', 'both', '')
	 */
	public function where($command, $field, $value, $control) {
		$count = count($command);
		$array = array();
		
		for($i = 0; $i < $count; $i++) {
			$temp_array = array('command' => $command[$i], 'field' => $field[$i], 'value' => $value[$i]);
			
			switch($command[$i]) {
				case 'like':
				case 'or_like':
				case 'not_like':
				case 'or_not_like':
					$temp_array['control'] = $control[$i];
					break;
			}

			array_push($array, $temp_array);
		}
		
		return $array;
	}
	
	/*
	 * 產生查詢所需的額外條件資料
	 * 詳細請看query_model的query方法
	 * $command		條件語法 ex: array('distinct', 'having')
	 * $parameter	各語法所需的參數 , order_by 和 limit 傳入陣列且必須要有兩個參數, ex: array('', '', array('', ''), '')
	 */
	public function other($command, $parameter) {
		$count = count($command);
		$array = array();
		
		for($i = 0; $i < $count; $i++) {
			$temp_array = array('command' => $command[$i]);
			
			switch($command[$i]) {
				case 'group_by':
					$temp_array['field'] = $parameter[$i];
					break;
				case 'having':
				case 'or_having':
					$temp_array['condition'] = $parameter[$i];
					break;
				case 'order_by':
					$temp_array['field'] = $parameter[$i][0];
					$temp_array['sort'] = $parameter[$i][1];
					break;
				case 'limit':
					$temp_array['amount'] = $parameter[$i][0];
					$temp_array['start'] = $parameter[$i][1];
					break;
			}
	
			array_push($array, $temp_array);
		}
		
		return $array;
	}
	
	/*
	 * 將查詢結果做處理
	 * 處理完成並釋放查詢記憶體
	 * $query		$this->db->get()查詢的結果
	 * $condition	選擇處理的方式,ex : result_array, row_array, num_rows, num_fields
	 */
	public function result($query, $condition) {
		switch($condition) {
			case 'row_array':
				$data = $query->row_array();
				break;
			case 'result_array':
				$data = $query->result_array();
				break;
			case 'num_rows':
				$data = $query->num_rows(); 
				break;
			case 'num_fields':
				$data = $query->num_fields();
				break;
		}

		$query->free_result();
		return $data;
	}
	
}