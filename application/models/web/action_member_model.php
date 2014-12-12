<?php

class Action_member_model extends CI_Model {
	/*
	 * 匯出行動會員資料
	 * $post 網頁傳送資料
	 */
	public function export($post) {
		require 'resources/api/PHPExcel.php';
		
		//查詢業者及帳單種類名稱
		$trader = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$trader_code,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$code), array($post['trader']), array('')),
																		 'other' => '')), 'row_array');
		$bill_kind = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$bill_kind_code,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$code), array($post['bill_kind']), array('')),
																		 'other' => '')), 'row_array');
		$file_name = iconv('utf-8', 'big5', '訂閱' . $trader['name'] . $bill_kind['name'] . '行動會員資料');
		
		//查詢使用者資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('DISTINCT ' . Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$email), 'function'),
																		 'from' => Table_1::$subscribe,
																		 'join'=> $this->sql->join(array(Table_1::$action_member), array(Table_1::$subscribe . '.' . Field_1::$id . ' = ' . Table_1::$action_member . '.' . Field_1::$id), array('')),
																		 'where' => $this->sql->where(array('where'), array('SUBSTRING(' . Field_3::$subscribe_code . ', 1, 6) ='), array($post['trader'] . $post['bill_kind']), array('')),
																		 'other' => '')), 'result_array');
		$title = array("姓氏", "名字", "手機號碼", "電子郵件");
		$row = array("A", "B", "C", "D");
		$title_num = count($title);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=$file_name.xlsx");
		header('Cache-Control: max-age=0');
		
		$objPHPExcel = new PHPExcel();
		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle('行動會員資料');
		
		for($i = 0; $i < $title_num; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row[$i] . 1, $title[$i]);
			$objActSheet->getColumnDimension($row[$i])->setAutoSize(true);
		}
		
		if(count($sql_result) != 0) {
			//$i是 excel欄位名稱 ABCD	$k是excel列數 要從2開始  因為1是標題列
			$k = 2;
			
			foreach($sql_result as $data) {
				$i = 0;
					
				foreach($data as $item => $value) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row[$i] . $k, $value);
					$objActSheet->getColumnDimension($row[$i])->setAutoSize(true);
			
					$i++;
				}
					
				$k++;
			}
		} else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '查無訂閱該業者帳單行動會員');
			$objActSheet->getColumnDimension($row[$i])->setAutoSize(true);
		}

		$objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save( 'php://output');
		
		exit();
	}
	
	/*
	 * 更新行動會員密碼
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function update_password($post, $user) {
		$route_data['password'] = $this->create->authentication();
		$route_data['id'] = $user['id'];
		$route_data['sub_param'] = '0';
		
		//查詢該行動會員電子郵件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$email), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		$route_data['email'] = $sql_result['email'];
		
		//更新密碼
		$this->sql->add_static(array('table'=> Table_1::$password,
									 'select'=> $this->sql->field(array(Field_1::$password, Field_1::$update_user, Field_1::$update_time), array(md5($route_data['password']), $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$password, '查詢行動會員_更改密碼資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$password, '查詢行動會員_更改密碼資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			//查詢忘記密碼目前使用的電子郵件版本
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$server_name, Field_2::$server_port, Field_2::$account, Field_1::$password, Field_2::$send_email, Field_2::$send_name, Field_2::$subject, Field_2::$body), ''),
																			 'from' => Table_1::$email_form,
																			 'join'=> '',
																			 'where' => $this->sql->where(array('where'), array(Field_2::$form_kind, Field_1::$state), array(1, 'y'), array('')),
																			 'other' => '')), 'row_array');
			$this->email_model->send_email($route_data, $sql_result, array('record' => '查詢行動會員_新增寄發新密碼電子郵件紀錄', 'event' => 1, 'success' => '02', 'fail' => '03'));
			return 'reload';
		} else {
			return 1;
		}
	}
	
	/*
	 * 查詢行動業者資料
	 * $post 查詢資料
	 */
	public function search_data($post) {
		//查詢行動會員資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, 'IF(' . Field_1::$state . ' = 1, "未認證", IF(' . Field_1::$state . ' = 2, "完成註冊", IF(' . Field_1::$state . ' = 3, "刪除", "黑名單"))) AS ' . Field_1::$state, 'IF(SUBSTRING(' . Field_1::$mobile_phone_id . ', 1, 2) = "AP", "Android", "iOS") AS ' . Field_1::$mobile_phone_id), 'function'),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		$action_member = $sql_result;
		//查詢帳單備忘錄資料
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*'), ''),
																		 'from' => Table_1::$action_member_data,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		for($i = 0; $i < 6; $i++) array_shift($sql_result);
		
		if(count($sql_result)) {
			$bill_memo = array();
			foreach($sql_result as $data) array_push($bill_memo, $data);
			$action_member['bill_memo'] = $bill_memo;
		}
	
		return json_encode($action_member, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢行動會員資料
	 * $post 查詢條件資料
	 */
	public function search_action_member($post) {
		if(strlen($post['state']) > 1) $post['state'] = '';

		//查詢行動會員列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$state), array($post['id'], $post['email'], $post['last_name'], $post['first_name'], $post['mobile_phone'], $post['state'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢行動會員列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$email, Field_1::$mobile_phone, Field_1::$last_name, Field_1::$first_name, 'IF(' . Field_1::$state . ' = 1, "未認證", IF(' . Field_1::$state . ' = 2, "完成註冊", IF(' . Field_1::$state . ' = 3, "刪除", "黑名單")))', 'IF(SUBSTRING(' . Field_1::$mobile_phone_id . ', 1, 2) = "AP", "Android", "iOS") AS ' . Field_1::$mobile_phone_id), 'function'),
																		 'from' => Table_1::$action_member,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$email, Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$state), array($post['id'], $post['email'], $post['last_name'], $post['first_name'], $post['mobile_phone'], $post['state'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		
		return $this->option->table($sql_result, array('會員編號', '會員帳號', '手機號碼', '會員姓氏', '會員名稱', '帳號狀態', 'OS種類'), base_url() . Param::$index_url . 'action_member/update_web') . $this->option->page($page_count, $post['page']);
	}
}//end 	