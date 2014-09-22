<?php

class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$data['title'] = 'Billez 管理系統';
		$data['tip'] = '管理者帳號 : root 密碼 : root';
		
		$this->load->view('login/login.php', $data);
	}
	
	public function aa() {
		echo 1;
// 		$data['title'] = 'Billez 管理系統';
// 		$data['dd'] = 'aaaa';

// 		$this->load->view('login/login.php', $data);
	}
}
