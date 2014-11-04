<?php

class Bill_import_model extends CI_Model {
	/*
	 * 匯入繳費帳單
	 * $file	帳單檔案
	 * $get		web傳來的參數
	 * $user	使用者	return json_encode(array(''=>), JSON_UNESCAPED_UNICODE);
	 */
	public function import_pay($file, $get, $user) {
		$get['import_kind'] = 1;

		$start = $this->pre_import_check($file, $get, $user);

		if($start !== true) return $start;

		//開啟檔案
		$pay_bill = fopen(iconv("UTF-8", "BIG5", $file['full_path']), "r");

		//標題列
		$title = $this->search_pay_set($get, fgets($pay_bill));
		
		//指標指向檔案起點
		rewind($pay_bill);
		
		//標題欄位處理
		$form = $this->title_handle($pay_bill, $title);

		$file_data = fgetcsv($pay_bill);
		$year = $file_data[$form['bill'][Field_1::$year]];
		$month = $file_data[$form['bill'][Field_1::$month]];
		
		//指標指向檔案起點
		rewind($pay_bill);
		$file_data = fgetcsv($pay_bill);
		
		//開始新增帳單
		$result = $this->insert_bill($pay_bill, $form, $user, $get);

		if($result === false) {
			$this->import_error($file, $get, $user, '匯入出錯');
			return json_encode(array('b6'=>'匯入出錯,請至錯誤紀錄查詢'), JSON_UNESCAPED_UNICODE);
		}
		
		$this->sql->clear_static();
		
		//新增帳單匯入紀錄
		$this->sql->add_static(array('table'=> Table_1::$bill_import_log, 
									 'select'=> $this->sql->field(array(Field_1::$batch_code, Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$import_bill_kind, Field_1::$year, Field_1::$month, Field_1::$import_time, Field_2::$file_name, Field_1::$pushed, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($result['batch_code'], $get['trader'], $get['bill_kind'], $get['import_kind'], $year, $month, $this->sql->get_time(1), $file['raw_name'], 'n', $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))), 
									 'where'=> '', 
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$bill_import_log, '帳單匯入_新增帳單匯入紀錄', $this->sql->get_time(1))), 
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$bill_import_log, '帳單匯入_新增帳單匯入紀錄', $this->sql->get_time(1), '')), 
									 'kind'=> 1));
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
		return json_encode($result, JSON_UNESCAPED_UNICODE);
	}

	/*
	 * 組成新增帳單
	* $pay_bill	帳單檔案
	* $form		匯入格式
	* $user		使用者資料
	* $get			業者帳單種類
	*/
	private function insert_bill($pay_bill, $form, $user, $get) {
		//查詢最大批次碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$batch_code . ') AS max'), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//指定批次碼
		if(!isset($sql_result['max'])) {
			$insert_data['batch_code'] = 1;
		} else {
			$insert_data['batch_code'] = $sql_result['max'] + 1;
		}

		//查詢一般會員最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$normal_member,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//指定一般會員編號
		if(!isset($sql_result['max'])) {
			$insert_data['normal_member_id'] = $this->create->id('NO', '');
		} else {
			$insert_data['normal_member_id'] = $sql_result['max'];
		}

		//查詢該業者帳單最大billez_code編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(SUBSTRING(' . Field_1::$billez_code . ', 7, 4)) AS max'), 'function'),
																		 'from' => Table_1::$bill,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($get['trader'], $get['bill_kind']), array('', '')),
																		 'other' => '')), 'row_array');
		//指定帳單編號
		if(!isset($sql_result['max'])) {
			$new_part2_billez_code = '';
		} else {
			$new_part2_billez_code = $sql_result['max'];
		}

		$part3_billez_code = date("y") . date("m") . date("d");
		$part1_billez_code = $get['trader'] . $get['bill_kind'];
		$new_bill_count = 0;
		$new_normal_count = 0;
		
		while($file_data = fgetcsv($pay_bill)) {
			//查詢該辨識資料的part2_billez_code
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('SUBSTRING(' . Field_1::$billez_code . ', 7, 4) AS part2'), 'function'),
																			 'from' => Table_1::$bill,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_1::$identify_data), array($file_data[$form['bill'][Field_1::$identify_data]]), array('')),
																			 'other' => '')), 'row_array');
			//指定帳單編號2
			if(!isset($sql_result['part2'])) {
				$part2_billez_code = $this->create->code(4, $new_part2_billez_code);
				$new_part2_billez_code = $part2_billez_code;
			} else {
				$part2_billez_code = $sql_result['part2'];
			}

			//整理新增資料
			$insert_data['billez_code'] = $part1_billez_code . $part2_billez_code . $part3_billez_code;
			$insert_data['file_data'] = $file_data;

			//新增帳單資料
			$this->bill_sql($form['bill'], $insert_data, $user, $get);
			$new_bill_count++;
			
			//新增繳費地點資料
			$this->pay_place_sql($form['pay_place'], $insert_data, $user, $get);
			
			//檢查該筆資料是否有一般會員
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																			 'from' => Table_1::$normal_member,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where', 'where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_1::$identify_data), array($get['trader'], $get['bill_kind'], $file_data[$form['bill'][Field_1::$identify_data]]), array('', '', '')),
																			 'other' => '')), 'num_rows');
			if(!$sql_result) {
				$insert_data['normal_member_id'] = $this->create->id('NO', $insert_data['normal_member_id']);
				//新增一般會員資料
				$this->normal_member_sql($form['bill'], $insert_data, $user, $get);
				$new_normal_count++;
			}
			
			//更新訂閱處理中資料
			$this->subscribe_sql($form['bill'], $insert_data, $user, $get);
				
			//新增推播狀態資料
			$this->push_state_sql($form['bill'], $insert_data, $user, $get);	
		}

		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return array('bill'=>$new_bill_count, 'normal'=>$new_normal_count, 'batch_code' => $insert_data['batch_code']);
		} else {
			return false;
		}
	}

	/*
	 * 新增推播狀態sql指令
	 * $form	帳單格式
	 * $insert_data	新增所需資料
	 * $user	使用者
	 * $get		業者帳單
	 */
	private function push_state_sql($form, $insert_data, $user, $get) {
		//查詢有訂閱該帳單的行動會員ID
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$subscribe_code), array($get['trader'] . $get['bill_kind'] . $insert_data['file_data'][$form[Field_1::$identify_data]]), array('', '')),
																		 'other' => '')), 'result_array');
		foreach($sql_result as $action_id) {
			//新增推播狀態
			$this->sql->add_static(array('table'=> Table_1::$push_state,
										 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$billez_code, Field_1::$read, Field_3::$receive_read, Field_1::$times, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($action_id, $insert_data['billez_code'], 'n', 'n', 0, $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_3::$subscribe_code), array($action_id, $get['trader'] . $get['bill_kind'] . $insert_data['file_data'][$form[Field_1::$identify_data]]), array('', '')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$push_state, '帳單匯入_新增推播狀態' . $action_id, $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$push_state, '帳單匯入_新增推播狀態' . $action_id, $this->sql->get_time(1), '')),
										 'kind'=> 1));
		}
	}
	
	/*
	 * 更新訂閱狀態sql指令
	 * $form	帳單格式
	 * $insert_data	新增所需資料
	 * $user	使用者
	 * $get		業者帳單
	 */
	private function subscribe_sql($form, $insert_data, $user, $get) {
		//查詢訂閱狀態為處理中的行動會員編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$subscribe,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$state, Field_3::$subscribe_code), array(1, $get['trader'] . $get['bill_kind'] . $insert_data['file_data'][$form[Field_1::$identify_data]]), array('', '')),
																		 'other' => '')), 'result_array');
		foreach($sql_result as $action_id) {
			//更新訂閱狀態為訂閱中
			$this->sql->add_static(array('table'=> Table_1::$subscribe,
										 'select'=> $this->sql->field(array(Field_1::$state, Field_1::$update_user, Field_1::$update_time), array(2, $user['id'], $this->sql->get_time(1))),
										 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$id, Field_3::$subscribe_code), array($action_id, $get['trader'] . $get['bill_kind'] . $insert_data['file_data'][$form[Field_1::$identify_data]]), array('', '')),
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$subscribe, '帳單匯入_更新訂閱狀態' . $action_id, $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$subscribe, '帳單匯入_更新訂閱狀態' . $action_id, $this->sql->get_time(1), '')),
										 'kind'=> 2));
		}
	}
	
	/*
	 * 新增一般會員sql指令
	 * $form	帳單格式
	 * $insert_data	新增所需資料
	 * $user	使用者
	 * $get		業者帳單
	 */
	private function normal_member_sql($form, $insert_data, $user, $get) {
		$normal_field = array(Field_1::$id, Field_1::$trader_code, Field_1::$bill_kind_code, Field_3::$action_member_identity, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time);
		$normal_value = array($insert_data['normal_member_id'], $get['trader'], $get['bill_kind'], 'n', $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1));
		$count = count($form);
		
		//欄位資料對應
		foreach($form as $item => $value) {
			if($item == Field_1::$identify_data) {
				array_push($normal_field, $item);
				array_push($normal_value, $insert_data['file_data'][$value]);
			}
			
			if($item == Field_1::$bill_owner) {
				array_push($normal_field, Field_1::$name);
				array_push($normal_value, $insert_data['file_data'][$value]);
			}
			
			if($item == Field_1::$data1 || 
				$item == Field_1::$data2 ||
				$item == Field_1::$data3 ||
				$item == Field_1::$data4 ||
				$item == Field_1::$data5) {
				array_push($normal_field, $item);
				array_push($normal_value, $insert_data['file_data'][$value]);
			}
		}
		
		//新增帳單敘述
		$this->sql->add_static(array('table'=> Table_1::$normal_member,
									 'select'=> $this->sql->field($normal_field, $normal_value),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$normal_member, '帳單匯入_新增一般會員' . $insert_data['normal_member_id'], $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$normal_member, '帳單匯入_一般會員' . $insert_data['normal_member_id'], $this->sql->get_time(1), '')),
									 'kind'=> 1));
	}
	
	/*
	 * 新增繳費地點sql指令
	 * $bill_form	帳單格式
	 * $bill	新增所需資料
	 * $user	使用者
	 * $get		業者帳單
	 */
	private function pay_place_sql($form, $insert_data, $user, $get) {
		$pay_place_field = array(Field_1::$billez_code, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time);
		$pay_place_value = array($insert_data['billez_code'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1));
		$count = count($form);
		$pay_place = array(Field_3::$pay_place => '');
		$overdue_pay_place = array(Field_3::$overdue_pay_place => '');
		
		//資料對應
		foreach($form as $item => $value) {
			if(substr_count($item, Field_3::$overdue_pay_place) == 1) {
				
				$overdue_pay_place[Field_3::$overdue_pay_place] .= $insert_data['file_data'][$value] . ',';
			} else {
				$pay_place[Field_3::$pay_place] .= $insert_data['file_data'][$value] . ',';
			}			
		}

		foreach($pay_place as $item => $value) {
			array_push($pay_place_field, $item);
			array_push($pay_place_value, $value);
		}
		foreach($overdue_pay_place as $item => $value) {
			array_push($pay_place_field, $item);
			array_push($pay_place_value, $value);
		}

		//新增繳費地點敘述
		$this->sql->add_static(array('table'=> Table_1::$pay_place,
									 'select'=> $this->sql->field($pay_place_field, $pay_place_value),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$pay_place, '帳單匯入_新增繳費地點' . $insert_data['billez_code'], $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$pay_place, '帳單匯入_新增繳費地點' . $insert_data['billez_code'], $this->sql->get_time(1), '')),
									 'kind'=> 1));
	}
	
	/*
	 * 新增帳單sql指令
	 * $form	帳單格式
	 * $insert_data	新增所需資料
	 * $user	使用者
	 * $get		業者帳單
	 */
	private function bill_sql($form, $insert_data, $user, $get) {
		$bill_field = array(Field_1::$batch_code, Field_1::$billez_code, Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$pay_state, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time);
		$bill_value = array($insert_data['batch_code'], $insert_data['billez_code'], $get['trader'], $get['bill_kind'], 'n', $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1));
		$count = count($form);
		
		//欄位資料對應
		for($i = 0; $i < $count; $i++) {
			foreach($form as $item => $value) {
				if($i == $value) {
					array_push($bill_field, $item);
					array_push($bill_value, $insert_data['file_data'][$i]);
					break;
				}
			}
		}

		//新增帳單敘述
		$this->sql->add_static(array('table'=> Table_1::$bill,
									 'select'=> $this->sql->field($bill_field, $bill_value),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$bill, '帳單匯入_新增帳單' . $insert_data['billez_code'], $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$bill, '帳單匯入_新增帳單' . $insert_data['billez_code'], $this->sql->get_time(1), '')),
									 'kind'=> 1));
	}
	
	/*
	 * 處理標題對應
	 * $pay_bill	帳單檔案
	 * $title	標題
	 */
	private function title_handle($pay_bill, $title) {
		//用csv抓取標題
		$csv_title = fgetcsv($pay_bill);
		$csv_title_count = count($csv_title);

		//bill資料表對應
		$bill_match = array();

		//pay_place資料表對應
		$pay_place_match = array();

		foreach($title as $field_name => $field_value) {
			for($i = 0;$i < $csv_title_count;$i++) {
				if($csv_title[$i] == $field_value) {
					if($field_name == "pay_place1" ||
						$field_name == "pay_place2" ||
						$field_name == "pay_place3" ||
						$field_name == "pay_place4" ||
						$field_name == "pay_place5" ||
						$field_name == "overdue_pay_place1" ||
						$field_name == "overdue_pay_place2") {
							$pay_place_match[$field_name] = $i;
								break;
							}
							$bill_match[$field_name] = $i;
							break;
				}
			}
		}

		return array('bill'=>$bill_match, 'pay_place'=>$pay_place_match);
	}

	/*
	 * 開始匯入帳單前的檢查
	 * $file	帳單檔案
	 * $get		web傳來的參數
	 * $user	使用者
	 */
	private function pre_import_check($file, $get, $user) {
		if(!is_array($file)) {
			$get['error_kind'] = 1;
			$error['raw_name'] = 'no_file';
			$error['full_path'] = 'no_file';
			$this->import_error($error, $get, $user, strip_tags($file));
			return json_encode(array('b1'=>strip_tags($file)), JSON_UNESCAPED_UNICODE);
		}

		//開啟檔案
		$pay_bill = fopen(iconv("UTF-8", "BIG5", $file['full_path']), "r");

		//讀取標題列
		$file_title = fgets($pay_bill);

		//查詢格式設定
		if($get['import_kind'] == 1) {
			$title = $this->search_pay_set($get, $file_title);
		} else {
			$title = $this->search_receive_set($get, $file_title);
		}

		if(!is_array($title)) {
			$get['error_kind'] = 2;
			$this->import_error($file, $get, $user, $title);
			return json_encode(array('b2'=>$title), JSON_UNESCAPED_UNICODE);
		}

		//查詢標題
		$title_error = $this->check_title($file_title, $title);

		if($title_error != 1) {
			$get['error_kind'] = 5;
			$this->import_error($file, $get, $user, $title_error);
			return json_encode(array('b5'=>$title_error), JSON_UNESCAPED_UNICODE);
		}

		//檢查欄位
		$error_line = $this->check_data($title, $file_title, $pay_bill);

		if(is_array($error_line)) {
			$get['error_kind'] = 3;
			$this->import_error($file, $get, $user, $error_line);
			return json_encode(array('b3'=>$error_line), JSON_UNESCAPED_UNICODE);
		}

		//檔案指標移動到起點
		rewind($pay_bill);

		//檢查資料欄位是否空白
		$error_line = $this->check_blank($pay_bill);

		if(is_array($error_line)) {
			$get['error_kind'] = 4;
			$this->import_error($file, $get, $user, $error_line);
			return json_encode(array('b4'=>$error_line), JSON_UNESCAPED_UNICODE);
		}

		fclose($pay_bill);

		return true;
	}

	/*
	 * 檢查標題是否一致
	 * $title	繳費帳單設定格式標題
	 * $file_title	檔案標題
	 */
	private function check_title($file_title, $title) {
		//將檔案標題做分割
		$file_titles = explode(',', $file_title);
		$file_col_right_count = 1;

		//計算標題有幾個
		$file_title_count = count($file_titles);

		for($i = 0;$i < $file_title_count;$i++) {
			foreach($title as $value) {
				if($value == $file_titles[$i]) {
					$file_col_right_count++;
					break;
				}
			}
		}

		if($file_col_right_count == $file_title_count) return 1;

		return '檔案標題與繳費帳單格式資料不同';
	}

	/*
	 * 檢查資料是否有空白
	 * $pay_bill 檔案
	 */
	private function check_blank($pay_bill) {
		//存放錯誤行數
		$error_line = array();

		//目前檔案的行數位置
		$line_number = 1;

		// 檢查底下資料是否有欄位不一
		while($file_data = fgets($pay_bill)) {
			++$line_number;
				
			$file_datas = explode(',', $file_data);
				
			foreach($file_datas as $datas) {
				if($datas == '') {
					array_push($error_line, $line_number . ':' . $file_data . ';');
					break;
				}
			}
		}

		if(count($error_line) != 0) return $error_line;

		return 1;
	}

	/*
	 * 檢查標題及底下資料是否有錯誤
	 * $title 標題資料
	 * $file_title 檔案標題列
	 * $pay_bill 檔案
 	 */
	private function check_data($title, $file_title, $pay_bill) {
		//計算標題有幾個
		$file_title_count = count(explode(',', $file_title));

		//存放錯誤行數
		$error_line = array();

		//目前檔案的行數位置
		$line_number = 1;

		// 檢查底下資料是否有欄位不一
		while($file_data = fgets($pay_bill)) {
			++$line_number;

			if($file_title_count != count(explode(',', $file_data))) array_push($error_line, $line_number . ':' . $file_data . ';');
		}

		if(count($error_line) != 0) return $error_line;

		return 1;
	}

	/*
	 * 查詢繳費帳單格式設定
	 * $get	業者及帳單種類
	 * $file_title	檔案標題
	 */
	private function search_pay_set($get, $file_title) {
		//查詢該業者帳單是否有客製化
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code), ''),
																		 'from' => Table_1::$customer_publish_bill_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($get['trader'], $get['bill_kind']), array('', '')),
																		 'other' => '')), 'num_rows');
		if($sql_result) {
			//客製化內容
		}

		//查詢該業者帳單繳費設定
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => Table_1::$trader_publish_bill_form,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($get['trader'], $get['bill_kind']), array('', '')),
																		 'other' => '')), 'row_array');
		//若沒有則回傳尚未設定錯誤
		if(!isset($sql_result['trader_code'])) {
			return '尚未新增繳費帳單格式';
		}

		foreach($sql_result as $item => $value) {
			$byte_num = explode(',', $value);
				
			if(count($byte_num) == 1 || $byte_num[0] == '') continue;
				
			$titles[$item] 	= substr($file_title, $byte_num[0], $byte_num[1] - $byte_num[0] + 1 );
		}

		return $titles;
	}

	/*
	 * 新增匯入帳單錯誤資料
	 * $file	檔案資料
	 * $get		業者及帳單資料
	 * $user	使用者
	 * $data	錯誤資料
	 * $kind	錯誤種類
	 */
	private function import_error($file, $get, $user, $data) {
		//依照錯誤種類分
		switch ($get['error_kind']) {
			case 1:
				$error_data = $data;
				break;
			case 2:
				$error_data = '尚未新增繳費帳單格式設定';
				break;
			case 3:
			case 4:
				$error_data = '';
				foreach($data as $value) $error_data = $error_data . $value;
				break;
			case 5:
				$error_data = $data;
				break;
			case 6:
				$error_data = $data;
				break;
			case '':
				;
				break;
		}

		//新增匯入錯誤紀錄
		$this->sql->add_static(array('table'=> Table_1::$import_error_log,
				'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$file_name, Field_2::$file_path, Field_2::$kind, Field_2::$reason, Field_2::$data, Field_2::$user, Field_2::$time, Field_2::$result),
						array($get['trader'], $get['bill_kind'], $file['raw_name'], $file['full_path'], $get['import_kind'], $get['error_kind'], $error_data, $user['id'], $this->sql->get_time(1), 'n')),
				'where'=> '',
				'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$import_error_log, '繳費帳單匯入_新增匯入錯誤紀錄', $this->sql->get_time(1))),
				'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$import_error_log, '繳費帳單匯入_新增匯入錯誤紀錄', $this->sql->get_time(1), '')),
				'kind'=> 1));
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
	}

}//end