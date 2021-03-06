<?php

class Trader_model extends CI_Model {
	/*
	 * 匯出代收機構報表
	* $post 參數資料
	*/
	public function report($post) {
		require "resources/api/tcpdf/tcpdf.php";

		//查詢該業者合約價格
		$price_data = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_4::$bill_price_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price), ''),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['trader_contract']), array('')),
																		 'other' => '')), 'row_array');
		//查詢業者及合約名稱
		$trader = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader_code . '.' . Field_1::$name . ' AS trader', Table_1::$trader_contract . '.' . Field_1::$name . ' AS trader_contract', Field_1::$bill_kind_code, Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name'), 'function'),
																		'from' => Table_1::$trader_contract,
																		'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$trader_contract . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$trader_contract . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																		'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['trader_contract']), array('')),
																		'other' => '')), 'row_array');
		$begin_date = $post['begin_year'] . '/' . $post['begin_month'] . '/' . $post['begin_day'];
		$end_date = $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'];
	
		//查詢實體帳單紀錄
		$entity_log_list = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$send_time, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind', Field_2::$file_name, Field_2::$bill_count, Field_2::$print_trader_email), 'function'),
																			  'from' => Table_1::$entity_bill_log,
																			  'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$entity_bill_log . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$entity_bill_log . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																			  'where' => $this->sql->where(array('where', 'where', 'where', 'where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$send_result, 'DATE(' . Field_2::$send_time . ') >=', 'DATE(' . Field_2::$send_time . ') <='), array($post['trader'], $trader['bill_kind_code'], 1, $begin_date, $end_date), array('', '', '', '', '')),
																			  'other' => '')), 'result_array');

		//查詢行動帳單紀錄
		$push_log_list = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$billez_code, Field_2::$time), ''),
																			'from' => Table_1::$push_state,
																			'join'=> $this->sql->join(array(Table_1::$action_member), array(Table_1::$push_state . '.' . Field_1::$id . ' = ' . Table_1::$action_member . '.' . Field_1::$id), array('')),
																			'where' => $this->sql->where(array('like', 'where', 'where', 'where'), array(Field_1::$billez_code, Field_1::$read, 'DATE(' . Field_2::$time . ') >=', 'DATE(' . Field_2::$time . ') <='), array($post['trader'] . $trader['bill_kind_code'], 'y', $begin_date, $end_date), array('after', '', '', '')),
																			'other' => '')), 'result_array');

		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
		$pdf->SetFont('msungstdlight','',10);
		$pdf->AddPage();
	
		$html1 = '<p><span style="color:red">' . $trader['trader'] . ' ' . $trader['trader_contract'] . '</span> 費用報表</p>';
		$html1 = $html1 . '<p>查詢範圍 : <span style="color:red">' . $begin_date . '</span> ~ <span style="color:red">' . $end_date . '</span></p>';
	
		if($price_data["bill_price_kind"] == "1") {
			$year  = $post["end_year"] - $post["begin_year"];
			$month = $post["end_month"] - $post["begin_month"];
			$day = $post["end_day"] - $post["begin_day"];
	
			if($year <= 0) {
				$pay = $month * $price_data["month_rent_price"];
			} else {
				$pay = ($year * 12 + $month) * $price_data["month_rent_price"];
			}
	
			$html1 = $html1 . '<p>付費種類 : <span style="color:red">月租費</span></p>';
			$html1 = $html1 . '<p>付費費用 : <span style="color:red">' . $price_data["month_rent_price"] . '</span></p>';
			$html1 = $html1 . '<p>需付費用 : <span style="color:red">' . $pay . ' NT</span></p>';
		} else {
			$html1 = $html1 . '<p>付費種類 : <span style="color:red">以件計費</span></p>';
			$html1 = $html1 . '<p>付費費用 : 實體帳單 : <span style="color:red">' . $price_data["entity_price"] . '</span> 行動帳單 : <span style="color:red">' . $price_data["action_price"] . '</p>';
			$html1 = $html1 . '<p>需付帳單數量 : </p>';
	
			if(count($push_log_list)) {
				$billez_code_list = array();
					
				foreach ($push_log_list as $push_log) {
					array_push($billez_code_list, $push_log["billez_code"]);
				}
					
				$action_bill_count = count(array_unique($billez_code_list));
			} else {
				$action_bill_count = 0;
			}

			if(count($entity_log_list)) {
				$entity_bill_count = '';
					
				foreach ($entity_log_list as $entity_log) {
					$entity_bill_count += $entity_log["bill_count"];
				}

			} else {
				$entity_bill_count = 0;
			}
				
			$html1 = $html1 . '<p> ' . $trader["trader"] . ' ' . $trader["bill_kind_name"];
			$html1 = $html1 . ' 實體帳單數量為 : ' . $entity_bill_count . ' 行動帳單數量為 : ' . $action_bill_count . '</p>';
			$pay = $entity_bill_count * $price_data["entity_price"] + $action_bill_count * $price_data["action_price"];
			$html1 = $html1 . '<p>總計費用 : <span style="color:red">' . $pay . '</span></p>';
		}
	
		$html1 = $html1 . '<p>寄送記錄 : </p>';
		$html1 = $html1 . '<p>(一)實體帳單 : </p>';
	
		if(count($entity_log_list) == 0) {
			$html1 = $html1 . '<p>無記錄!!</p>';
		} else {
			$html1 = $html1 . '<table border="1" cellpadding="2">
									<tr>
										<td>寄出時間</td>
										<td>檔案名稱</td>
										<td>帳單筆數</td>
										<td>印刷業者Email</td>
									</tr>';
	
			foreach ($entity_log_list as $entity_logs) {
				$html1 = $html1 . '<tr>
									<td>' . $entity_logs["send_time"] . '</td>
									<td>' . $entity_logs["file_name"] . '</td>
									<td>' . $entity_logs["bill_count"] . '</td>
									<td>' . $entity_logs["print_trader_email"] . '</td>
								</tr>';
			}
			$html1 = $html1 . '</table>';
		}
	
		$html1 = $html1 . '<p>(二)行動帳單 : </p>';
	
		if(count($push_log_list) == 0) {
			$html1 = $html1 . '<p>無記錄!!</p>';
		} else {
			$html1 = $html1 . '<table border="1" cellpadding="2">
									<tr>
										<td>寄出時間</td>
										<td>姓氏</td>
										<td>名字</td>
										<td>手機號碼</td>
										<td>帳單編號</td>
									</tr>';
	
			foreach ($push_log_list as $push_logs) {
				$html1 = $html1 . '<tr>
									<td>' . $push_logs["time"] . '</td>
									<td>' . $push_logs["last_name"] . '</td>
									<td>' . $push_logs["first_name"] . '</td>
									<td>' . $push_logs["mobile_phone"] . '</td>
									<td>' . $push_logs["billez_code"] . '</td>
								</tr>';
			}
			$html1 = $html1 . '</table>';
		}
	
		$pdf->writeHTML($html1, true, false, false, false, '');
	
		$pdf->Output('trader_report.pdf', 'I');
	}
	
	/*
	 * 匯出業者資料
	* $post 網頁傳送資料
	*/
	public function export($post) {
		require 'resources/api/PHPExcel.php';
	
		$data = explode(',', $post['export_list']);
		$trader_data = array();
	
		if($post['kind'] == 'data') {
			//查詢業者資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader . '.' . Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS ' . Field_3::$address, Field_3::$telephone, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email), 'function'),
																			 'from' => Table_1::$trader,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code), array(Table_1::$trader . '.' . Field_1::$name . ' = ' . Table_1::$trader_code . '.' . Field_1::$name), array('')),
																			 'where' => $this->sql->where(array('where_in'), array(Field_1::$code), array($data), array('')),
																			 'other' => '')), 'result_array');
			$title = array("名稱", "地址", "電話", "主要聯絡人名稱", "主要聯絡人電話", "主要聯絡人電子郵件", "次要聯絡人名稱", "次要聯絡人電話", "次要聯絡人電子郵件");
			$row = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
			$file_name = iconv('utf-8', 'big5', '業者資料');
			$title_name = '業者';
			$trader_data = $sql_result;
		} else {
			//查詢業者合約資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader_contract . '.' . Field_1::$name, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind', Field_2::$age, Field_2::$begin, Field_2::$end, Field_4::$bill_price_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_4::$publish, Field_4::$publish_week, Field_4::$publish_day, Field_4::$publish_month, Field_4::$enter, Field_4::$enter_week, Field_4::$enter_day, Field_4::$enter_month, Field_4::$collection, Field_4::$collection_week, Field_4::$collection_day, Field_4::$collection_month, Field_3::$send_condition, Field_4::$send_condition_times, Field_4::$email_send, Field_4::$email_publish, Field_4::$email_publish_week, Field_4::$email_publish_day, Field_4::$email_publish_month, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_path, Field_4::$ftp_receive_path), 'function'),
																			 'from' => Table_1::$trader_contract,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$trader_contract . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$trader_contract . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																			 'where' => $this->sql->where(array('where_in'), array(Field_1::$id), array($data), array('')),
																			 'other' => '')), 'result_array');
			foreach($sql_result as $data) {
				$data['publish'] = $this->transform->time_kind($data['publish']);
				$data['enter'] = $this->transform->time_kind($data['enter']);
				$data['collection'] = $this->transform->time_kind($data['collection']);
				$data['email_publish'] = $this->transform->time_kind($data['email_publish']);
				$data['send_condition'] = $this->transform->send_condition($data['send_condition']);
				$data['bill_price_kind'] = $this->transform->bill_cost_kind($data['bill_price_kind']);
	
				array_push($trader_data, $data);
			}
	
			$title = array('合約名稱', '業者', '帳單種類', '合約年限', '合約開始日', '合約結束日', '收費種類', '月租費用', '實體帳單費用', '行動帳單費用', '帳單發行時間種類', '每週', '日期', '月份', '帳單入帳時間種類', '每週', '日期', '月份', '收款時間種類', '每週', '日期', '月份', '寄送帳單條件', '寄送實體帳單期數', '寄送電子帳單', '寄送電子帳單時間種類', '每週', '日期', '月份', 'FTP網址', 'FTP帳號', 'FTP密碼', 'FTP繳費帳單檔案路徑', 'FTP入帳帳單檔案路徑');
			$row = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH');
			$file_name = iconv('utf-8', 'big5', '業者合約資料');
			$title_name = '業者合約';
		}
	
		$title_num = count($title);

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header("Content-Disposition: attachment;filename=$file_name.xlsx");
		header('Cache-Control: max-age=0');
	
		$objPHPExcel = new PHPExcel();
		$objActSheet = $objPHPExcel->getActiveSheet();
		$objActSheet->setTitle($title_name);
	
		for($i = 0; $i < $title_num; $i++) {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row[$i] . 1, $title[$i]);
			$objActSheet->getColumnDimension($row[$i])->setAutoSize(true);
		}
	
		if(count($trader_data) != 0) {
			//$i是 excel欄位名稱 ABCD	$k是excel列數 要從2開始  因為1是標題列
			$k = 2;
				
			foreach($trader_data as $data) {
				$i = 0;
					
				foreach($data as $item => $value) {
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($row[$i] . $k, $value);
					$objActSheet->getColumnDimension($row[$i])->setAutoSize(true);
						
					$i++;
				}
					
				$k++;
			}
		} else {
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A2', '查無' . $title_name . '資料');
			$objActSheet->getColumnDimension($row[$i])->setAutoSize(true);
		}
	
		$objWriter = PHPExcel_IOFactory:: createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save( 'php://output');
	
		exit();
	}
	
	/*
	 * 確認更新業者合約資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_update_trader_contract($post, $user) {
		//查詢要更換的業者合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['contract_name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		return $this->update_trader_contract($post, $user);
	}
	
	/*
	 * 更新業者合約資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	private function update_trader_contract($post, $user) {
		//查詢業者代碼和帳單種類代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code, Field_1::$bill_kind_code), ''),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		$code = $sql_result;
		
		//更新業者代收機構
		$this->sql->add_static(array('table'=> Table_1::$trader_machinery, 
									 'select'=> $this->sql->field(array(Field_2::$machinery_code, Field_4::$machinery_contract, Field_1::$update_user, Field_1::$update_time), array($post['machinery'], $post['machinery_contract'], $user['id'], $this->sql->get_time(1))), 
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($code['trader_code'], $code['bill_kind_code']), array('')), 
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_machinery, '更新業者合約_更新業者代收機構', $this->sql->get_time(1))), 
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_machinery, '更新業者合約_更新業者代收機構', $this->sql->get_time(1), '')), 
									 'kind'=> 2));
		//更新業者帳單廣告
		$this->sql->add_static(array('table'=> Table_1::$trader_bill,
									 'select'=> $this->sql->field(array(Field_4::$bill_ad_url, Field_1::$update_user, Field_1::$update_time), array($post['ad_url'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code), array($code['trader_code'], $code['bill_kind_code']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_bill, '更新業者合約_更新業者帳單廣告', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_bill, '更新業者合約_更新業者帳單廣告', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新業者合約資料
		$this->sql->add_static(array('table'=> Table_1::$trader_contract,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_2::$age, Field_2::$begin, Field_2::$end, Field_4::$publish, Field_4::$publish_week, Field_4::$publish_day, Field_4::$publish_month, Field_4::$enter, Field_4::$enter_week, Field_4::$enter_day, Field_4::$enter_month, Field_4::$collection, Field_4::$collection_week, Field_4::$collection_day, Field_4::$collection_month, Field_4::$bill_price_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_3::$send_condition, Field_4::$send_condition_times, Field_4::$contract_remark, Field_4::$email_send, Field_4::$email_publish, Field_4::$email_publish_week, Field_4::$email_publish_day, Field_4::$email_publish_month, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_path, Field_4::$ftp_receive_path, Field_1::$update_user, Field_1::$update_time), 
																 array($post['contract_name'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['publish'], $post['publish_week'], $post['publish_day'], $post['publish_month'], $post['enter'], $post['enter_week'], $post['enter_day'], $post['enter_month'], $post['collection'], $post['collection_week'], $post['collection_day'], $post['collection_month'], $post['bill_price'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['send_condition'], $post['send_condition_times'], $post['contract_remark'], $post['send_email'], $post['email_publish'], $post['email_publish_week'], $post['email_publish_day'], $post['email_publish_month'], $post['ftp_ip'], $post['ftp_account'], $post['ftp_password'], $post['ftp_path'], $post['ftp_receive_path'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_contract, '更新業者合約_更新業者合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_contract, '更新業者合約_更新業者合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/search_contract_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢業者合約資料
	 * $post	web傳來的參數
	 */		
	public function search_trader_contract_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$trader_code . '.' . Field_1::$name . ' AS trader', Table_1::$trader_contract . '.' . Field_1::$name . ' AS contract_name', Field_4::$bill_ad_url . ' AS ad_url', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind', Table_1::$bill_basis . '.' . Field_1::$name . ' AS bill_basis', Field_2::$age . ' AS contract_age', 'YEAR(' . Field_2::$begin . ') AS begin_year', 'MONTH(' . Field_2::$begin . ') AS begin_month', 'DAY(' . Field_2::$begin . ') AS begin_day', 'YEAR(' . Field_2::$end . ') AS end_year', 'MONTH(' . Field_2::$end . ') AS end_month', 'DAY(' . Field_2::$end . ') AS end_day', Field_4::$publish, Field_4::$publish_week, Field_4::$publish_month, Field_4::$publish_day, Field_4::$enter, Field_4::$enter_week, Field_4::$enter_month, Field_4::$enter_day, Field_4::$bill_price_kind . ' AS bill_price', Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_4::$collection, Field_4::$collection_week, Field_4::$collection_month, Field_4::$collection_day, Field_3::$send_condition, Field_4::$send_condition_times, Field_4::$email_send . ' AS send_email', Field_4::$email_publish, Field_4::$email_publish_week, Field_4::$email_publish_month, Field_4::$email_publish_day, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_path, Field_4::$ftp_receive_path, Field_4::$contract_remark), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join' => $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code, Table_1::$trader_bill, Table_1::$bill_basis), array(Table_1::$trader_contract . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$trader_contract . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code, Table_1::$trader_contract . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_bill . '.' . Field_1::$trader_code . ' AND ' . Table_1::$trader_contract . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$trader_bill . '.' . Field_1::$bill_kind_code, Table_1::$trader_bill . '.' . Field_4::$bill_basis_code . ' = ' . Table_1::$bill_basis . '.' . Field_1::$code), array('', '', '', '')),
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		$machinery = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$machinery_code, Field_4::$machinery_contract), ''),
																		'from' => Table_1::$trader_contract,
																		'join'=> $this->sql->join(array(Table_1::$trader_machinery), array(Table_1::$trader_contract . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_machinery . '.' . Field_1::$trader_code . ' AND ' . Table_1::$trader_contract . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$trader_machinery . '.' . Field_1::$bill_kind_code), array('')),
																		'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		'other' => '')), 'row_array');
		if(count($machinery) != 0) {
			$sql_result['machinery'] = $machinery['machinery_code'];
			$sql_result['machinery_contract'] = $machinery['machinery_contract'];
		} 
		
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}	
	
	/*
	 * 查詢業者合約列表
	 * $post	web傳來的參數
	 */
	public function search_trader_contract($post) {
		if(strlen($post['trader_code']) > 4) $post['trader_code'] = '';
		if(strlen($post['bill_kind']) > 2) $post['bill_kind'] = '';														
		
		//查詢業者合約列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_1::$trader_code, Field_1::$bill_kind_code), array($post['id'], $post['name'], $post['trader_code'], $post['bill_kind'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		
		//查詢業者合約列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Table_1::$trader_contract . '.' .Field_1::$name . ' AS ' . Field_1::$name, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name', Field_2::$age, Field_2::$begin, Field_2::$end), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> $this->sql->join(array(Table_1::$trader_code), array(Table_1::$trader_contract . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where_search(array(Field_1::$id, Table_1::$trader_contract . '.' .Field_1::$name, Field_1::$trader_code, Field_1::$bill_kind_code), array($post['id'], $post['name'], $post['trader_code'], $post['bill_kind'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('合約編號', '合約名稱', '業者名稱', '合約年限', '合約開始日', '合約終止日'), base_url() . Param::$index_url . 'trader/update_contract_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 確認更新業者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_update_trader($post, $user) {
		//查詢要更換的業者名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		//查詢要更換的統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_3::$vat_number), array($post['id'], $post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
		
		return $this->update_trader($post, $user);
	}
	
	/*
	 * 更新業者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	private function update_trader($post, $user) {
		//查詢業者名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		//更新業者資料
		$this->sql->add_static(array('table'=> Table_1::$trader,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark, Field_1::$update_user, Field_1::$update_time), 
																 array($post['name'], $post['telephone'], $post['level_code'], $post['vat_number'], $post['city'], $post['district'], $post['address'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['remark'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader, '更新業者_更新業者資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader, '更新業者_更新業者資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新業者名稱
		$this->sql->add_static(array('table'=> Table_1::$trader_code,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_1::$update_user, Field_1::$update_time), array($post['name'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($sql_result['name']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader_code, '更新業者_更新業者名稱', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader_code, '更新業者_更新業者名稱', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/search_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 查詢業者資料
	 * $post	web傳來的參數
	 */
	public function search_trader_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢業者列表
	 * $post	web傳來的參數
	 */
	public function search_trader($post) {
		if(strlen($post['level_code']) > 1) $post['level_code'] = '';
		
		//查詢業者列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), 'function'),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);

		//查詢業者列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS address ', Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email), 'function'),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('編號', '名稱', '地址', '電話', '統一編號', '主要聯絡人名稱', '主要聯絡人電話', '主要聯絡人電子郵件'), base_url() . Param::$index_url . 'trader/update_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 檢查是否已有相同的業者或統一編號
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_trader($post, $user) {
		//查詢業者名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		//查詢統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$vat_number), array($post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
		
		return $this->insert_trader($post, $user);
	}
	
	/*
	 * 新增業者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	private function insert_trader($post, $user) {
		//查詢業者最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$trader,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生業者編號
		$id = $this->create->id('TR', $sql_result['max']);

		//查詢最大業者代碼編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$trader_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生業者代馬
		$code = $this->create->code(4, $sql_result['max']);
		
		//新增業者資料
		$this->sql->add_static(array('table'=> Table_1::$trader,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$level_code, Field_3::$remark, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
											 					 array($id, $post['name'], $post['city'], $post['district'], $post['address'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['level_code'], $post['remark'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader, '新增業者_創建業者資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader, '新增業者_創建業者資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增業者代碼
		$this->sql->add_static(array('table'=> Table_1::$trader_code,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_code, '新增業者_新增業者代碼', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_code, '新增業者_新增業者代碼', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/insert_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 檢查業者合約名稱
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_trader_contract($post, $user) {
		//查詢業者合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where', 'where'), array(Field_1::$name, Field_1::$trader_code, Field_1::$bill_kind_code), array($post['contract_name'], $post['trader'], $post['bill_kind']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;

		return $this->insert_trader_contract($post, $user);
	}
	
	/*
	 * 新增業者合約
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	private function insert_trader_contract($post, $user) {
		if(strlen($post['email_publish']) > 1) $post['email_publish'] = '';
		
		if(strlen($post['machinery']) == 2) {
			//新增業者代收機構
			$this->sql->add_static(array('table'=> Table_1::$trader_machinery,
										 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$machinery_code, Field_4::$machinery_contract, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['trader'], $post['bill_kind'], $post['machinery'], $post['machinery_contract'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_machinery, '新增業者合約_新增業者代收機構', $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_machinery, '新增業者合約_新增業者代收機構', $this->sql->get_time(1), '')),
										 'kind'=> 1));	
		}
		
		//查詢業者合約最搭編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$trader_contract,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生業者合約編號
		$id = $this->create->id('TC', $sql_result['max']);
		
		//新增業者合約
		$this->sql->add_static(array('table'=> Table_1::$trader_contract,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$age, Field_2::$begin, Field_2::$end, Field_4::$publish, Field_4::$publish_week, Field_4::$publish_day, Field_4::$publish_month, Field_4::$enter, Field_4::$enter_week, Field_4::$enter_day, Field_4::$enter_month, Field_4::$collection, Field_4::$collection_week, Field_4::$collection_day, Field_4::$collection_month, Field_4::$bill_price_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_3::$send_condition, Field_4::$send_condition_times, Field_4::$contract_remark, Field_4::$email_send, Field_4::$email_publish, Field_4::$email_publish_week, Field_4::$email_publish_day, Field_4::$email_publish_month, Field_4::$ftp_ip, Field_4::$ftp_account, Field_4::$ftp_password, Field_4::$ftp_path, Field_4::$ftp_receive_path, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), 
																  array($id, $post['contract_name'], $post['trader'], $post['bill_kind'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['publish'], $post['publish_week'], $post['publish_day'], $post['publish_month'], $post['enter'], $post['enter_week'], $post['enter_day'], $post['enter_month'], $post['collection'], $post['collection_week'], $post['collection_day'], $post['collection_month'], $post['bill_price'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['send_condition'], $post['send_condition_times'], $post['contract_remark'], $post['send_email'], $post['email_publish'], $post['email_publish_week'], $post['email_publish_day'], $post['email_publish_month'], $post['ftp_ip'], $post['ftp_account'], $post['ftp_password'], $post['ftp_path'], $post['ftp_receive_path'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_contract, '新增業者合約_新增業者合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_contract, '新增業者合約_新增業者合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//查詢帳單依據碼數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$digit), ''),
																		 'from' => Table_1::$bill_basis,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$code), array($post['bill_basis']), array('')),
																		 'other' => '')), 'row_array');
		$bill_digit_number = $sql_result['digit'];
		
		//新增業者帳單
		$this->sql->add_static(array('table'=> Table_1::$trader_bill,
									 'select'=> $this->sql->field(array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_4::$bill_basis_code, Field_4::$bill_digit_number, Field_4::$bill_ad_url, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($post['trader'], $post['bill_kind'], $post['bill_basis'], $bill_digit_number, $post['ad_url'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$trader_bill, '新增業者合約_新增業者帳單', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$trader_bill, '新增業者合約_新增業者帳單', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'trader/insert_contract_web';
		} else {
			return 2;
		}
	}
}//end