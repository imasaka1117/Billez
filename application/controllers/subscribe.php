<?php

class Subscribe extends CI_Controller {
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
	 * 更改業者代收機構狀態
	 */
	public function update_trader_machinery() {
		session_start();
		$this->load->model('web/subscribe_model');
		$this->load->library('push');
		$this->load->model('send/push_model');
		$data['ajax'] = $this->subscribe_model->update_trader_machinery($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 改變業者代收機構狀態頁面
	 */
	public function trader_machinery_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'subscribe/subscribe_trader_machinery.js';
		$data['class_name'] = 'subscribe';
		$this->load->view('templates/header', $data);
		$this->load->view('web/subscribe/trader_machinery', $data);
	}
	
	/*
	 * 更改業者訂閱狀態
	 */
	public function update_state() {
		session_start();
		$this->load->model('web/subscribe_model');
		$this->load->library('push');
		$this->load->model('send/push_model');
		$data['ajax'] = $this->subscribe_model->update_state($this->input->post(), $_SESSION['user']);
		$this->load->view('web/ajax', $data);
	}
	
	/*
	 * 改變訂閱狀態頁面
	 */
	public function state_web() {
		$data = $this->param->resources(array('validate_js'=>Param::$validate_js, 'base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'subscribe/subscribe_state.js';
		$data['class_name'] = 'subscribe';
		$this->load->view('templates/header', $data);
		$this->load->view('web/subscribe/state', $data);
	}
	
	/*
	 * 查詢訂閱
	 */
	public function search() {
		$this->load->model('web/subscribe_model');
		$this->load->library('web/option');
		$data['ajax'] = $this->subscribe_model->search_subscribe($this->input->post());
		$this->load->view('web/ajax', $data);
	}

	/*
	 * 查詢訂閱頁面
	 */
	public function search_web() {
		$data = $this->param->resources(array('base_css'=>Param::$base_css, 'js_path'=>Param::$js_path, 'jquery_js'=>Param::$jquery_js, 'function_js'=>Param::$function_js, 'index_url'=>Param::$index_url));
		$data['now_use'] = 'subscribe/subscribe_search.js';
		$data['class_name'] = 'subscribe';
		$this->load->view('templates/header', $data);
		$this->load->view('web/subscribe/search', $data);
	}
}//end