<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Record_field {
	/*
	 * 整個資料庫的欄位名稱
	 * 加進新的之前先查詢是否已經有現存的
	 */
	static public $pay_day = 'pay_day';
	static public $pay_week = 'pay_week';
	static public $pay_month = 'pay_month';
	static public $pay_season_month = 'pay_season_month';
	static public $ad_url = 'ad_url';
	static public $remark = 'remark';
	static public $address = 'address';
	static public $telephone = 'telephone';
	static public $vat_number = 'vat_number';
	static public $main_contact_name = 'main_contact_name';
	static public $main_contact_phone = 'main_contact_phone';
	static public $main_contact_email = 'main_contact_email';
	static public $second_contact_name = 'second_contact_name';
	static public $second_contact_phone = 'second_contact_phone';
	static public $second_contact_email = 'second_contact_email';
	static public $level_code = 'level_code';
	static public $action_member_identity = 'action_member_identity';
	static public $pay_place = 'pay_place';
	static public $overdue_pay_place = 'overdue_pay_place';
	static public $problem = 'problem';
	static public $asker = 'asker';
	static public $scope = 'scope';
	static public $answer = 'answer';
	static public $response = 'response';
	static public $ask_time = 'ask_time';
	static public $reply_time = 'reply_time';
	static public $image = 'image';
	static public $star = 'star';
	static public $gcm_message = 'gcm_message';
	static public $receive_read = 'receive_read';
	static public $read_time = 'read_time';
	static public $recommender = 'recommender';
	static public $begin_time = 'begin_time';
	static public $end_time = 'end_time';
	static public $range = 'range';
	static public $way = 'way';
	static public $level = 'level';
	static public $person_list = 'person_list';
	static public $authentication_code = 'authentication_code';
	static public $sms_frequency = 'sms_frequency';
	static public $authentication_code2 = 'authentication_code2';
	static public $authentication_code3 = 'authentication_code3';
	static public $subscribe_code = 'subscribe_code';
	static public $send_condition = 'send_condition';
	static public $operate = 'operate';
	static public $table = 'table';
	static public $db_message = 'db_message';
	static public $using = 'using';
	static public $push_times = 'push_times';
	static public $sms_times = 'sms_times';
}