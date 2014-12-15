<?php

class Machinery_model extends CI_Model {
	/*
	 * 匯出代收機構報表
	 * $post 參數資料
	 */
	public function report($post) {
		require "resources/api/tcpdf/tcpdf.php";
		
		//查詢該代收機構合約下的業者帳單
		$trader_bill_list = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$trader_code, Field_1::$bill_kind_code), ''),
																			   'from' => Table_1::$trader_machinery,
																			   'join'=> '',
																			   'where' => $this->sql->where(array('where', 'where'), array(Field_2::$machinery_code, Field_4::$machinery_contract), array($post['machinery'], $post['machinery_contract']), array('', '')),
																			   'other' => '')), 'result_array');
		//查詢該代收機構合約價格
		$price_data = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$bill_cost_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price), ''),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['machinery_contract']), array('')),
																		 'other' => '')), 'row_array');
		//查詢代收機構及合約名稱
		$machinery = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery', Table_1::$machinery_contract . '.' . Field_1::$name . ' AS machinery_contract'), 'function'), 
																	    'from' => Table_1::$machinery_contract, 
																	    'join'=> $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery_contract . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code), array('')), 
																	    'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['machinery_contract']), array('')), 
																	    'other' => '')), 'row_array');
		$begin_date = $post['begin_year'] . '/' . $post['begin_month'] . '/' . $post['begin_day'];
		$end_date = $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'];
		
		$entity_log_list = array();
		$push_log_list = array();

		//查詢實體帳單及推播行動帳單紀錄
		foreach($trader_bill_list as $trader_bill) {
			//查詢實體帳單紀錄
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_2::$send_time, Table_1::$trader_code . '.' . Field_1::$name . ' AS trader', Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind', Field_2::$file_name, Field_2::$bill_count, Field_2::$print_trader_email), 'function'),
																			 'from' => Table_1::$entity_bill_log,
																			 'join'=> $this->sql->join(array(Table_1::$trader_code, Table_1::$bill_kind_code), array(Table_1::$entity_bill_log . '.' . Field_1::$trader_code . ' = ' . Table_1::$trader_code . '.' . Field_1::$code, Table_1::$entity_bill_log . '.' . Field_1::$bill_kind_code . ' = ' . Table_1::$bill_kind_code . '.' . Field_1::$code), array('', '')),
																			 'where' => $this->sql->where(array('where', 'where', 'where', 'where', 'where'), array(Field_1::$trader_code, Field_1::$bill_kind_code, Field_2::$send_result, 'DATE(' . Field_2::$send_time . ') >=', 'DATE(' . Field_2::$send_time . ') <='), array($trader_bill['trader_code'], $trader_bill['bill_kind_code'], 1, $begin_date, $end_date), array('', '', '', '', '')),
																			 'other' => '')), 'result_array');
			array_push($entity_log_list, $sql_result);
	
			//查詢行動帳單紀錄
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$last_name, Field_1::$first_name, Field_1::$mobile_phone, Field_1::$billez_code, Field_2::$time), ''),
																			 'from' => Table_1::$push_state,
																			 'join'=> $this->sql->join(array(Table_1::$action_member), array(Table_1::$push_state . '.' . Field_1::$id . ' = ' . Table_1::$action_member . '.' . Field_1::$id), array('')),
																			 'where' => $this->sql->where(array('like', 'where', 'where', 'where'), array(Field_1::$billez_code, Field_1::$read, 'DATE(' . Field_2::$time . ') >=', 'DATE(' . Field_2::$time . ') <='), array($trader_bill['trader_code'] . $trader_bill['bill_kind_code'], 'y', $begin_date, $end_date), array('after', '', '', '')),
																			 'other' => '')), 'result_array');
			array_push($push_log_list, $sql_result);
		}
		
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		$pdf->SetFont('msungstdlight','',10);
		$pdf->AddPage();
		
		$html1 = '<p><span style="color:red">' . $machinery['machinery'] . ' ' . $machinery['machinery_contract'] . '</span> 費用報表</p>';
		$html1 = $html1 . '<p>查詢範圍 : <span style="color:red">' . $begin_date . '</span> ~ <span style="color:red">' . $end_date . '</span></p>';
		
		if($price_data["bill_cost_kind"] == "1") {
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
				
			$entity_bill_num = 0;
			$action_bill_num = 0;
			$billez_code_list = array();
			$action_bill_count = array();
			$i = 0;
			$isfitst = true;
		
			foreach ($push_log_list as $push_logs) {
				$billez_code_list = array();
					
				foreach ($push_logs as $push_log) {
					array_push($billez_code_list, $push_log["billez_code"]);
				}
					
				array_push($action_bill_count, count(array_unique($billez_code_list)));
			}

			foreach ($entity_log_list as $entity_logs) {
				
				foreach ($entity_logs as $entity_log) {
					if($isfitst) {
						$html1 = $html1 . '<p> ' . $entity_log["trader"] . ' ' . $entity_log["bill_kind"]; 
						$isfitst = false;
					}
		
					$entity_bill_num = $entity_bill_num + $entity_log["bill_count"];
								
					
				}
				
				$action_bill_num = $action_bill_num + $action_bill_count[$i];
				$i++;
				$html1 = $html1 . ' 實體帳單數量為 : ' . $entity_bill_num . ' 行動帳單數量為 : ' . $action_bill_num . '</p>';
			}
				
			$pay = $entity_bill_num * $price_data["entity_price"] + $action_bill_num * $price_data["action_price"];
				
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
				foreach ($entity_logs as $entity_log) {
					$html1 = $html1 . '<tr>
										<td>' . $entity_log["send_time"] . '</td>
										<td>' . $entity_log["file_name"] . '</td>
										<td>' . $entity_log["bill_count"] . '</td>
										<td>' . $entity_log["print_trader_email"] . '</td>
									</tr>';
				}
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
				foreach ($push_logs as $push_log) {
					$html1 = $html1 . '<tr>
										<td>' . $push_log["time"] . '</td>
										<td>' . $push_log["last_name"] . '</td>
										<td>' . $push_log["first_name"] . '</td>
										<td>' . $push_log["mobile_phone"] . '</td>
										<td>' . $push_log["billez_code"] . '</td>
									</tr>';
				}
			}
			$html1 = $html1 . '</table>';
		}
		
		$pdf->writeHTML($html1, true, false, false, false, '');
		
		$pdf->Output('example_001.pdf', 'I');
	}
	
	/*
	 * 匯出代收機構資料
	* $post 網頁傳送資料
	*/
	public function export($post) {
		require 'resources/api/PHPExcel.php';
		
		$data = explode(',', $post['export_list']);
		$machinery_data = array();
		
		if($post['kind'] == 'data') {
			//查詢代收機構資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$machinery . '.' . Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS ' . Field_3::$address, Field_3::$telephone, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email), 'function'),
																			 'from' => Table_1::$machinery,
																			 'join'=> $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery . '.' . Field_1::$name . ' = ' . Table_1::$machinery_code . '.' . Field_1::$name), array('')),
																			 'where' => $this->sql->where(array('where_in'), array(Field_1::$code), array($data), array('')),
																			 'other' => '')), 'result_array');
			$title = array("名稱", "地址", "電話", "主要聯絡人名稱", "主要聯絡人電話", "主要聯絡人電子郵件", "次要聯絡人名稱", "次要聯絡人電話", "次要聯絡人電子郵件");
			$row = array("A", "B", "C", "D", "E", "F", "G", "H", "I");
			$file_name = iconv('utf-8', 'big5', '代收機構資料');
			$title_name = '代收機構';
			$machinery_data = $sql_result;
		} else {
			//查詢代收機構合約資料
			$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$machinery_contract . '.' . Field_1::$name, Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery', Field_2::$age, Field_2::$begin, Field_2::$end, Field_2::$bill_cost_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_day, Field_3::$pay_month, Field_3::$ad_url), 'function'),
																			 'from' => Table_1::$machinery_contract,
																			 'join'=> $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery_contract . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code), array('')),
																			 'where' => $this->sql->where(array('where_in'), array(Field_1::$id), array($data), array('')),
																			 'other' => '')), 'result_array');
			foreach($sql_result as $data) {
				$data['bill_cost_kind'] = $this->transform->bill_cost_kind($data['bill_cost_kind']);
				$data['pay'] = $this->transform->time_kind($data['pay']);

				array_push($machinery_data, $data);
			}

			$title = array('合約名稱', '代收機構', '合約年限', '合約開始日', '合約結束日', '付費種類', '月租費用', '實體帳單費用', '行動帳單費用', '付款時間種類', '每週', '日期', '月份', '廣告位置');
			$row = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N");
			$file_name = iconv('utf-8', 'big5', '代收機構合約資料');
			$title_name = '代收機構合約';
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
	
		if(count($machinery_data) != 0) {
			//$i是 excel欄位名稱 ABCD	$k是excel列數 要從2開始  因為1是標題列
			$k = 2;
			
			foreach($machinery_data as $data) {
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
	 * 確認更新代收機構合約資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_update_machinery_contract($post, $user) {
		//查詢要更換的代收機構合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['contract_name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		return $this->update_machinery_contract($post, $user);
	}
	
	/*
	 * 更新代收機構合約資料
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	private function update_machinery_contract($post, $user) {
		//更新代收機構合約資料
		$this->sql->add_static(array('table'=> Table_1::$machinery_contract,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_2::$age, Field_2::$begin, Field_2::$end, Field_2::$bill_cost_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_day, Field_3::$pay_month, Field_3::$remark, Field_1::$update_user, Field_1::$update_time),
																 array($post['contract_name'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['bill_cost'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['pay'], $post['pay_week'], $post['pay_day'], $post['pay_month'], $post['contract_remark'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$machinery_contract, '更新代收機構合約_更新代收機構合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$machinery_contract, '更新代收機構合約_更新代收機構合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/search_contract_web';
		} else {
			return 2;
		}
	}
	
	/*
	 * 查詢代收機構合約資料
	* $post	web傳來的參數
	*/
	public function search_machinery_contract_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery', Table_1::$machinery_contract . '.' . Field_1::$name . ' AS contract_name', Field_3::$ad_url, Field_2::$age . ' AS contract_age', 'YEAR(' . Field_2::$begin . ') AS begin_year', 'MONTH(' . Field_2::$begin . ') AS begin_month', 'DAY(' . Field_2::$begin . ') AS begin_day', 'YEAR(' . Field_2::$end . ') AS end_year', 'MONTH(' . Field_2::$end . ') AS end_month', 'DAY(' . Field_2::$end . ') AS end_day', Field_2::$bill_cost_kind . ' AS bill_cost', Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_month, Field_3::$pay_day, Field_3::$remark . ' AS contract_remark'), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join' => $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery_contract . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
	
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢代收機構合約列表
	* $post	web傳來的參數
	*/
	public function search_machinery_contract($post) {
		//暫存
		$machinery = '';
		$machinery_contract = '';
		
		if(strlen($post['machinery_code']) > 2) $post['machinery_code'] = '';
	
		//查詢代收機構合約列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_2::$machinery_code), array($post['id'], $post['name'], $post['machinery_code'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢代收機構合約列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Table_1::$machinery_contract . '.' .Field_1::$name . ' AS ' . Field_1::$name, Table_1::$machinery_code . '.' . Field_1::$name . ' AS machinery_name', Field_2::$age, Field_2::$begin, Field_2::$end), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> $this->sql->join(array(Table_1::$machinery_code), array(Table_1::$machinery_contract . '.' . Field_2::$machinery_code . ' = ' . Table_1::$machinery_code . '.' . Field_1::$code), array('')),
																		 'where' => $this->sql->where_search(array(Field_1::$id, Table_1::$machinery_contract . '.' .Field_1::$name, Field_2::$machinery_code), array($post['id'], $post['name'], $post['machinery_code'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('合約編號', '合約名稱', '業者名稱', '合約年限', '合約開始日', '合約終止日'), base_url() . Param::$index_url . 'machinery/update_contract_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 確認更新代收機構資料
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	public function check_update_machinery($post, $user) {
		//查詢要更換的代收機構名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_1::$name), array($post['id'], $post['name']), array('')),
																	 	 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		//查詢要更換的統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$id . ' !=', Field_3::$vat_number), array($post['id'], $post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
	
		return $this->update_machinery($post, $user);
	}
	
	/*
	 * 更新代收機構資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	private function update_machinery($post, $user) {
		//查詢代收機構名稱
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		//更新代收機構資料
		$this->sql->add_static(array('table'=> Table_1::$machinery,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark, Field_1::$update_user, Field_1::$update_time),
											array($post['name'], $post['telephone'], $post['level_code'], $post['vat_number'], $post['city'], $post['district'], $post['address'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['remark'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$trader, '更新代收機構_更新代收機構資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$trader, '更新代收機構_更新代收機構資料', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//更新代收機構名稱
		$this->sql->add_static(array('table'=> Table_1::$machinery_code,
									 'select'=> $this->sql->field(array(Field_1::$name, Field_1::$update_user, Field_1::$update_time), array($post['name'], $user['id'], $this->sql->get_time(1))),
									 'where'=> $this->sql->where(array('where'), array(Field_1::$name), array($sql_result['name']), array('')),
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(2, $user['id'], Table_1::$machinery_code, '更新代收機構_更新代收機構名稱', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(2, $user['id'], Table_1::$machinery_code, '更新代收機構_更新代收機構名稱', $this->sql->get_time(1), '')),
									 'kind'=> 2));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/search_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 查詢業者資料
	* $post	web傳來的參數
	*/
	public function search_machinery_data($post) {
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$name, Field_3::$telephone, Field_3::$level_code, Field_3::$vat_number, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$remark), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$id), array($post['id']), array('')),
																		 'other' => '')), 'row_array');
		return json_encode($sql_result, JSON_UNESCAPED_UNICODE);
	}
	
	/*
	 * 查詢代收機構列表
	 * $post	web傳來的參數
	 */
	public function search_machinery($post) {
		//暫存
		$machinery = '';
		
		if(strlen($post['level_code']) > 1) $post['level_code'] = '';
	
		//查詢代收機構列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), 'function'),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
		//查詢代收機構列表
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id, Field_1::$name, 'CONCAT(' . Field_1::$city . ',' . Field_1::$district . ',' . Field_3::$address . ') AS address ', Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email), 'function'),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$level_code), array($post['id'], $post['name'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['level_code'])),
																		 'other' => $this->sql->other(array('limit'), array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
		return $this->option->table($sql_result, array('編號', '名稱', '地址', '電話', '統一編號', '主要聯絡人名稱', '主要聯絡人電話', '主要聯絡人電子郵件'), base_url() . Param::$index_url . 'machinery/update_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 檢查新增代收機構名稱
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function check_machinery($post, $user) {
		//查詢代收機構名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_1::$name), array($post['name']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
		
		//查詢統一編號是否重複
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_3::$vat_number), ''),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where'), array(Field_3::$vat_number), array($post['vat_number']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 2;
		
		return $this->insert_machinery($post, $user);
	}
	
	/*
	 * 新增代收機構資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	private function insert_machinery($post, $user) {
		//暫存
		$machinery = '';
		
		//查詢代收機構最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$machinery,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生代收機構編號
		$id = $this->create->id('MA', $sql_result['max']);
	
		//查詢最大代收機構代碼編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$code . ') AS max'), 'function'),
																		 'from' => Table_1::$machinery_code,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生代收機構代馬
		$code = $this->create->code(2, $sql_result['max']);
	
		//新增代收機構資料
		$this->sql->add_static(array('table'=> Table_1::$machinery,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_1::$city, Field_1::$district, Field_3::$address, Field_3::$telephone, Field_3::$vat_number, Field_3::$main_contact_name, Field_3::$main_contact_phone, Field_3::$main_contact_email, Field_3::$second_contact_name, Field_3::$second_contact_phone, Field_3::$second_contact_email, Field_3::$level_code, Field_3::$remark, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
											array($id, $post['name'], $post['city'], $post['district'], $post['address'], $post['telephone'], $post['vat_number'], $post['main_contact_name'], $post['main_contact_phone'], $post['main_contact_email'], $post['second_contact_name'], $post['second_contact_phone'], $post['second_contact_email'], $post['level_code'], $post['remark'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$machinery, '新增代收機構_創建代收機構資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$machinery, '新增代收機構_創建代收機構資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//新增代收機構代碼
		$this->sql->add_static(array('table'=> Table_1::$machinery_code,
									 'select'=> $this->sql->field(array(Field_1::$code, Field_1::$name, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time), array($code, $post['name'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$machinery_code, '新增代收機構_新增代收機構代碼', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$machinery_code, '新增代收機構_新增代收機構代碼', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/insert_web';
		} else {
			return 3;
		}
	}
	
	/*
	 * 檢查代收機構合約名稱
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	public function check_machinery_contract($post, $user) {
		//查詢代收機構合約名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where', 'where'), array(Field_1::$name, Field_1::$id . ' !='), array($post['contract_name'], $post['machinery']), array('')),
																		 'other' => '')), 'num_rows');
		if($sql_result) return 1;
	
		return $this->insert_machinery_contract($post, $user);
	}
	
	/*
	 * 新增代收機構合約
	* $post	web傳來的參數
	* $user	當前使用該系統者
	*/
	private function insert_machinery_contract($post, $user) {
		//查詢代收機構合約最大編號
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		 'from' => Table_1::$machinery_contract,
																		 'join'=> '',
																		 'where' => '',
																		 'other' => '')), 'row_array');
		//產生代收機構合約編號
		$id = $this->create->id('MC', $sql_result['max']);
	
		//新增代收機構合約
		$this->sql->add_static(array('table'=> Table_1::$machinery_contract,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$name, Field_2::$machinery_code, Field_2::$age, Field_2::$begin, Field_2::$end, Field_2::$bill_cost_kind, Field_4::$month_rent_price, Field_2::$entity_price, Field_2::$action_price, Field_2::$pay, Field_3::$pay_week, Field_3::$pay_month, Field_3::$pay_day, Field_3::$ad_url, Field_3::$remark, Field_1::$create_user, Field_1::$create_time, Field_1::$update_user, Field_1::$update_time),
																 array($id, $post['contract_name'], $post['machinery'], $post['contract_age'], $post['begin_year'] . '/' .  $post['begin_month'] . '/' . $post['begin_day'], $post['end_year'] . '/' . $post['end_month'] . '/' . $post['end_day'], $post['bill_cost'], $post['month_rent_price'], $post['entity_price'], $post['action_price'], $post['pay'], $post['pay_week'], $post['pay_month'], $post['pay_day'], $post['ad_url'], $post['contract_remark'], $user['id'], $this->sql->get_time(1), $user['id'], $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $user['id'], Table_1::$machinery_contract, '新增代收機構合約_新增代收機構合約資料', $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $user['id'], Table_1::$machinery_contract, '新增代收機構合約_新增代收機構合約資料', $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind))) {
			return 'machinery/insert_contract_web';
		} else {
			return 2;
		}
	}
}