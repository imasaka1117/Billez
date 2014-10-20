<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Option {
	/*
	 * 產生選單字串
	 */
	public function select($result) {
		$option = '<option value="">請選擇</option>';
		foreach($result as $data) $option = $option . '<option value="' . $data['code'] . '">' . $data['name'] . '</option>';
		
		return $option;
	}
}