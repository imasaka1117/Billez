<?php

class Normal_member extends CI_Controller {
	/*
	 * 建構式,將會用到的函式先行載入
	 */
	public function __construct() {
		parent::__construct();
		$this->load->library('web/param');
		$this->load->model('db/query_model');
		$this->load->library('db/field_1');
		$this->load->library('db/field_2');
		$this->load->library('db/field_3');
		$this->load->library('db/field_4');
		$this->load->library('db/table_1');
		$this->load->library('sql');
	}
	
	/*
	 * 查詢一般會員資料
	 */
	public function search_data() {
		$this->load->model('web/normal_member_model');
		$data['ajax'] = $this->normal_member_model->search_data($this->input->post());
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 觀看一般會員資料頁面
	 */
	public function update_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'normal_member/normal_member_update.js';
		$data['class_name'] = 'normal_member';
		$data['id'] = $this->input->get('id');
		$this->load->view('templates/header', $data);
		$this->load->view('web/normal_member/update', $data);
	}
	
	/*
	 * 查詢一般會員
	 */
	public function search() {
		$this->load->model('web/normal_member_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->normal_member_model->search_normal_member($this->input->post());
		$this->load->view('web/ajax', $data);
	}

	/*
	 * 查詢一般會員頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'normal_member/normal_member_search.js';
		$data['class_name'] = 'normal_member';
		$this->load->view('templates/header', $data);
		$this->load->view('web/normal_member/search', $data);
	}
}//end