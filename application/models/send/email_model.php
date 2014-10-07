<?php

class Email_model extends CI_Model {
	/*
	 * 寄送電子郵件
	 * $route_data	所需參數資料
	 * $email_form	設定的寄發格式
	 * $data		要傳送的格式內容
	 * record		記錄
	 * event		事件
	 * success		成功代碼
	 * fail			失敗代碼
	 * data 		種類資料
	 */
	public function send_email($route_data, $email_form, $data) {
		$config['smtp_host'] = $email_form['server_name'];
		$config['smtp_port'] = $email_form['server_port'];
		$config['smtp_user'] = $email_form['account'];
		$config['smtp_pass'] = $email_form['password'];
		$config['protocol'] = 'smtp';
		$config['mailtype'] = 'html';

		switch($data['event']) {
			case '':
				;
				break;
					
			default:
				;
				break;
		}
		
		$this->email->initialize($config);
		$this->email->from($email_form['send_email'], $email_form['send_name']);
		$this->email->to($route_data['email']);
		$this->email->subject($email_form['subject']);
		$this->email->message(str_replace('$password', $route_data['password'], $email_form['body']));
		$result = $this->email->send();

		if($result == 1) {
			$code = $data['success'];
		} else {
			$code = $data['fail'];
			$result == 2;
		}
		
		//清空靜態變數
		$this->sql->clear_static();
		
		//新增電子郵件紀錄
		$this->sql->add_static(array('table'=> Table_1::$email_log,
									 'select'=> $this->sql->field(array(Field_1::$id, Field_1::$email, Field_2::$event, Field_2::$result, Field_1::$create_time), array($route_data['id'], $route_data['email'], $data['event'], $result, $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_4::$purpose, Field_1::$create_time), array(1, $route_data['id'], Table_1::$email_log, $data['record'], $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, Field_3::$table, Field_1::$message, Field_1::$create_time, Field_3::$db_message), array(1, $route_data['id'], Table_1::$email_log, $data['record'], $this->sql->get_time(1), '')),
									 'kind'=> 1));
		//執行
		$this->query_model->execute_sql(array('table' => Sql::$table, 'select' => Sql::$select, 'where' => Sql::$where, 'log' => Sql::$log, 'error' => Sql::$error, 'kind' => Sql::$kind));
		
		return $route_data['sub_param'] . $code;
	}
}