<?php

class Format_model extends CI_Model {
	/*
	 * 訂閱碼回傳
	 */
	public function subscribe_code() {
		return 'CONCAT(' . Table_1::$bill . '.' . Field_1::$trader_code . ',' . Table_1::$bill . '.' . Field_1::$bill_kind_code . ',' . Field_1::$identify_data . ') AS ' . Field_3::$subscribe_code;
	}
	
	/*
	 * 一般帳單格式
	 */
	public function normal_bill($subscribe_code) {
		return array(Table_1::$bill . '.' . Field_1::$billez_code,
					 Field_1::$bill_owner,
					 Field_2::$publish_time,
					 Field_2::$due_time,
					 Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name',
					 Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name',
					 Table_1::$pay_place . '.' . Field_3::$pay_place . ' AS ' . Field_3::$pay_place,
					 Table_1::$pay_place . '.' . Field_3::$overdue_pay_place . ' AS ' . Field_3::$overdue_pay_place,
					 'IFNULL(' . Field_2::$amount . ", 'blank') AS " . Field_2::$amount,
					 'IFNULL(' . Field_2::$lowest_pay_amount . ", 'blank') AS " . Field_2::$lowest_pay_amount,
					 'IFNULL(' . Field_2::$post_charge . ", 'blank') AS " . Field_2::$post_charge,
					 'IFNULL(' . Field_2::$bank_charge . ", 'blank') AS " . Field_2::$bank_charge,
					 'IFNULL(' . Field_2::$cvs_charge . ", 'blank') AS " . Field_2::$cvs_charge,
					 'IFNULL(' . Field_2::$cvs_barcode1 . ", 'blank') AS " . Field_2::$cvs_barcode1,
					 'IFNULL(' . Field_2::$cvs_barcode2 . ", 'blank') AS " . Field_2::$cvs_barcode2,
					 'IFNULL(' . Field_2::$cvs_barcode3 . ", 'blank') AS " . Field_2::$cvs_barcode3,
					 'IFNULL(' . Field_2::$post_barcode1 . ", 'blank') AS " . Field_2::$post_barcode1,
					 'IFNULL(' . Field_2::$post_barcode2 . ", 'blank') AS " . Field_2::$post_barcode2,
					 'IFNULL(' . Field_2::$post_barcode3 . ", 'blank') AS " . Field_2::$post_barcode3,
					 'IFNULL(' . Field_2::$bank_barcode1 . ", 'blank') AS " . Field_2::$bank_barcode1,
					 'IFNULL(' . Field_2::$bank_barcode2 . ", 'blank') AS " . Field_2::$bank_barcode2,
					 'IFNULL(' . Field_2::$bank_barcode3 . ", 'blank') AS " . Field_2::$bank_barcode3,
					 'IFNULL(' . Table_1::$trader_bill . '.' . Field_4::$bill_ad_url . ", 'blank') AS ad_id",
					 $subscribe_code,
					 'IFNULL(CONCAT(IFNULL(CONCAT(' . Field_1::$data1 . ", ','), NULL), IFNULL(CONCAT(" . Field_1::$data2 . ", ','), NULL), IFNULL(CONCAT(" . Field_1::$data3 . ", ','), NULL), IFNULL(CONCAT(" . Field_1::$data4 . ", ','), NULL), IFNULL(CONCAT(" . Field_1::$data5 . ", ','), NULL)), 'blank') AS " . Field_3::$remark);
	}
	
	/*
	 * 可能帳單格式
	 */
	public function possible_bill($subscribe_code) {
		return array('DISTINCT ' . Field_1::$data1,
				     Field_1::$data2,
				     Field_1::$data3,
				     Field_1::$data4,
				     Field_1::$data5,
				     Field_1::$bill_owner,
				     Field_1::$identify_data,
				     Table_1::$trader_code . '.' . Field_1::$name . ' AS trader_name',
				     Table_1::$bill_kind_code . '.' . Field_1::$name . ' AS bill_kind_name',
				     $subscribe_code,
				     'IFNULL(' . Table_1::$trader_contract . '.' . Field_4::$send_condition_times . ", 'blank') AS " . Field_4::$send_condition_times);
	}
}//class end