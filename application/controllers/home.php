<?php

class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$this->load->view('home/home.php');
	}
	
	public function aa() {
		echo 1;
// 		$data['title'] = 'Billez 管理系統';
// 		$data['dd'] = 'aaaa';

// 		$this->load->view('login/login.php', $data);
	}
}
