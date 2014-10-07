<?php

class Push_model extends CI_Model {
	/*
	 * 推播帳單通知並記錄推播
	 * $route_data	所需參數資料
	 * $data		相關資料 
	 * 要推播訊息 :message
	 * 推播事件 : event
	 * 紀錄原因 : record
	 * 回傳狀態碼 : code
	 */
	public function bill_push($route_data, $data) {
		//清除之前加入的SQL靜態變數
		$this->sql->clear_static();
		
		//執行推播
		$this->push->setting_data($data['message']);

		//新增推播紀錄
		$count = count(Push::$id);
		
		for($i = 0; $i < $count; $i++) {
			$this->sql->add_static(array('table'=> Table_1::$push_log,
										 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$mobile_phone, Field_1::$mobile_phone_id, Field_2::$event, Field_2::$time, Field_2::$result, Field_1::$message), array(Push::$id[$i], Push::$moblie_phone[$i], Push::$moblie_phone_id[$i], $data['event'], $this->sql->get_time(1), Push::$result[$i], Push::$message[$i])),
										 'where'=> '',
										 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$push_log, $data['record'], $this->sql->get_time(1))),
										 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$push_log, $data['record'], $this->sql->get_time(1), '')),
										 'kind'=> 1));
		}
		
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
		
		return $route_data['sub_param'] . $data['code'];
	}
}