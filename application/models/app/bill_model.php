<?php

class Bill_model extends CI_Model {
	/*
	 * 帳單請求員起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '7_1':
				return $this->normal_bill($route_data);
				break;
			case '7_2':
				return $this->check_account($route_data);
				break;
			case '7_3':
				return $this->send_password($route_data);
				break;
		}
	}
	
	/*
	 * 請求一般帳單
	 * $route_data 所需參數資料
	 */
	public function normal_bill($route_data) {
		$app = '7_1';
		
	}
	
}//end