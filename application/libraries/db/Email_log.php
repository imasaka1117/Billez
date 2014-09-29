<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Email_log {
	var $table_name = 'email_log';
	var $id = 'id';
	var $email = 'email';
	var $event = 'event';
	var $result = 'result';
	var $file_name = 'file_name';
	var $error_message = 'error_message';
	var $create_time = 'create_time';
}