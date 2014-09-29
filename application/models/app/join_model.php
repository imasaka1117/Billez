<?php

class Join_model extends CI_Model {
	/*
	 * 加入會員起點函式
	 * $route_data從APP來的參數
	 */
	public function index($route_data) {
		switch($route_data['sub_param']) {
			case '1_2':
				return $this->check_account($route_data);
				break;
			case '1_3':
				return $this->send_password($route_data);
				break;
			case '1_4':
				return $this->send_authentication($route_data);
				break;
			case '1_5':
				return $this->send_again($route_data);
				break;
			case '1_6':
				return $this->check_authentication($route_data);
				break;
		}
	}
	
	/*
	 * 檢查帳號狀態及傳回公鑰
	 * 以及是否有選擇第三方登入
	 * 必須確認該帳號是否存在
	 * 若有則合併該帳號
	 * $route_data 所需參數
	 */
	public function check_account($route_data) {
		$app = '1_2';

		//符合回傳格式用
		$outer_array = array();	
		//查詢該電子郵件是否有會員狀態
		$sql_select = $this->sql->select(array('state'), '');
		$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		if(isset($sql_result['state'])) $state = $sql_result['state']; else $state = '';
		
		//產生新的金鑰組
		$key = $this->key->create_key();
		
// -------------------有第三方
		//檢查是否有第三方參數
		if(isset($route_data['google_id']) || isset($route_data['fb_id'])) {
			//查詢會員電子郵件
			$sql_select = $this->sql->select(array('email'), '');
			//google或fb
			if(isset($route_data['google_id'])) {
				$google_id = $route_data['google_id'];
				$fb_id = '';
				$sql_where = $this->sql->where(array('where', 'where'), array('email', 'google_id'), array($route_data['email'], $route_data['google_id']), array(''));
			} else {
				$fb_id = $route_data['fb_id'];
				$google_id = '';
				$sql_where = $this->sql->where(array('where', 'where'), array('email', 'fb_id'), array($route_data['email'], $route_data['fb_id']), array(''));
			}
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'num_rows');
				
			//判斷是否重複申請google或者fb
			if($sql_result) {
				if(isset($route_data['google_id'])) {
					$json_data = $this->json->encode_json($app, '1_201');
				} else {
					$json_data = $this->json->encode_json($app, '1_202');
				}
				//加密後回傳出去
				$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
				return $this->json->encode_json('vale', $encode_data);
			} else {
				//確認是否有該會員存在
				$sql_select = $this->sql->select(array('id'), '');
				$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
				$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'num_rows');

				if($sql_result) {
					//查詢會員編號
					$sql_select = $this->sql->select(array('id'), '');
					$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
					$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
					$sql_result = $this->sql->result($sql_query, 'row_array');

					//回傳給APP的資料
					$id_key['id'] = $sql_result['id'];
					
					//檢查該會員狀態 1為未認證狀態,3為刪除帳號狀態
					switch($state) {
						case 1:
						case 3:
							$id_key['public_key'] = $key['public_key'];
							//初始化會員資料,正常為六個一組
							array_push(Sql::$table, 'action_member');
							array_push(Sql::$select, $this->sql->field(array('last_name', 'first_name', 'mobile_phone', 'mobile_phone_id', 'state', 'update_user', 'update_time'), array('', '', '', $route_data['mobile_phone_id'], 1, $sql_result['id'], $this->sql->get_time(1))));
							array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'email'), array($sql_result['id'], $route_data['email']), array('')));
							array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將會員基本資料初始化', $this->sql->get_time(1))));
							array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將會員基本資料初始化', $this->sql->get_time(1), '')));
							array_push(Sql::$kind, 2);
							
							//初始化金鑰資料,正常為六個一組
							array_push(Sql::$table, 'key');
							array_push(Sql::$select, $this->sql->field(array('private_key', 'public_key', 'update_user', 'update_time'), array($key['private_key'], $key['public_key'], $sql_result['id'], $this->sql->get_time(1))));
							array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($sql_result['id']), array('')));
							array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將金鑰資料初始化', $this->sql->get_time(1))));
							array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將金鑰資料初始化', $this->sql->get_time(1), '')));
							array_push(Sql::$kind, 2);
							break;
						default:
							//更新會員資料,增加google或fb帳戶
							array_push(Sql::$table, 'action_member');
							if(isset($route_data['google_id'])) {
								array_push(Sql::$select, $this->sql->field(array('google_id', 'update_user', 'update_time'), array($route_data['google_id'], $sql_result['id'], $this->sql->get_time(1))));
								array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $sql_result['id'], 'action_member', '該會員已存在,更新google帳戶', $this->sql->get_time(1))));
								array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $sql_result['id'], 'action_member', '該會員已存在,更新google帳戶', $this->sql->get_time(1), '')));
								$id_key['1_203'] = '1_203';
							} else {
								array_push(Sql::$select, $this->sql->field(array('fb_id', 'update_user', 'update_time'), array($route_data['fb_id'], $sql_result['id'], $this->sql->get_time(1))));
								array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $sql_result['id'], 'action_member', '該會員已存在,更新fb帳戶', $this->sql->get_time(1))));
								array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $sql_result['id'], 'action_member', '該會員已存在,更新fb帳戶', $this->sql->get_time(1), '')));
								$id_key['1_204'] = '1_204';
							}		
							array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($sql_result['id']), array('')));
							array_push(Sql::$kind, 2);
							
							//查詢該會員公鑰
							$sql_select = $this->sql->select(array('public_key'), '');
							$sql_where = $this->sql->where(array('where'), array('id'), array($sql_result['id']), array(''));
							$sql_query = $this->query_model->query($sql_select, 'key', '', $sql_where, '');
							$sql_result = $this->sql->result($sql_query, 'row_array');
							
							$id_key['public_key'] = $sql_result['public_key'];
							break;
					}
					
					//丟到回傳格式
					array_push($outer_array, $id_key);		
// --------------用第三方申請會員		
				} else {
					//沒有則是用第三方新增一個會員
					//查詢最大編號
					$sql_select = $this->sql->select(array('id'), 'max');
					$sql_where = '';
					$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
					$sql_result = $this->sql->result($sql_query, 'row_array');
						
					//產生會員編號
					$id = $this->create->id('AC', $sql_result['max']);
					$id_key['id'] 			= $id;
					$id_key['public_key'] 	= $key['public_key'];
					//丟到回傳格式
					array_push($outer_array, $id_key);	

					//新增會員資料
					array_push(Sql::$table, 'action_member');
					array_push(Sql::$select, $this->sql->field(array('id', 'email', 'last_name', 'first_name', 'mobile_phone', 'mobile_phone_id', 'state', 'fb_id', 'google_id', 'create_user', 'create_time', 'update_user', 'update_time'), array($id, $route_data['email'], '', '', '', $route_data['mobile_phone_id'], 1, $fb_id, $google_id, $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))));
					array_push(Sql::$where, '');
					array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $id, 'action_member', '使用第三方新增會員資料', $this->sql->get_time(1))));
					array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $id, 'action_member', '使用第三方新增會員資料', $this->sql->get_time(1), '')));
					array_push(Sql::$kind, 1);
					//新增金鑰資料
					array_push(Sql::$table, 'key');
					array_push(Sql::$select, $this->sql->field(array('id', 'private_key', 'public_key', 'create_user', 'create_time', 'update_user', 'update_time'), array($id, $key['private_key'], $key['public_key'], $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))));
					array_push(Sql::$where, '');
					array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $id, 'key', '使用第三方新增金鑰資料', $this->sql->get_time(1))));
					array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $id, 'key', '使用第三方新增金鑰資料', $this->sql->get_time(1), '')));
					array_push(Sql::$kind, 1);
				}	
			}
// ---------------------------沒有第三方參數
		} else {
			//檢查一般帳號資料, 確認是否有該會員存在
			$sql_select = $this->sql->select(array('id'), '');
			$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'num_rows');
			
			if($sql_result) {
				//查詢會員編號及第三方
				$sql_select = $this->sql->select(array('id', 'google_id', 'fb_id'), '');
				$sql_where = $this->sql->where(array('where'), array('email'), array($route_data['email']), array(''));
				$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'row_array');
				
				//回傳給APP的資料
				$id_key['id'] = $sql_result['id'];
				
				switch($state) {
					case 1:
					case 3:
						$id_key['public_key'] = $key['public_key'];
						//初始化會員資料,正常為六個一組
						array_push(Sql::$table, 'action_member');
						array_push(Sql::$select, $this->sql->field(array('last_name', 'first_name', 'mobile_phone', 'mobile_phone_id', 'state', 'update_user', 'update_time'), array('', '', '', $route_data['mobile_phone_id'], 1, $sql_result['id'], $this->sql->get_time(1))));
						array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'email'), array($sql_result['id'], $route_data['email']), array('')));
						array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將會員基本資料初始化', $this->sql->get_time(1))));
						array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將會員基本資料初始化', $this->sql->get_time(1), '')));
						array_push(Sql::$kind, 2);
						
						//初始化金鑰資料,正常為六個一組
						array_push(Sql::$table, 'key');
						array_push(Sql::$select, $this->sql->field(array('private_key', 'public_key', 'update_user', 'update_time'), array($key['private_key'], $key['public_key'], $sql_result['id'], $this->sql->get_time(1))));
						array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($sql_result['id']), array('')));
						array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將金鑰資料初始化', $this->sql->get_time(1))));
						array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $sql_result['id'], 'action_member', '因會員重複申請帳號,所以將金鑰資料初始化', $this->sql->get_time(1), '')));
						array_push(Sql::$kind, 2);
						break;	
					default:
						if($sql_result['google_id'] == '' && $sql_result['fb_id'] == '') {
							//一般帳號重複
							$json_data = $this->json->encode_json($app, '1_205');

							//加密後回傳出去
							$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
							return $this->json->encode_json('vale', $encode_data);
						} else {
							$id_key['public_key'] = $key['public_key'];
						}
						break;
				}
				
				//丟到回傳格式
				array_push($outer_array, $id_key);
			} else {
// -----一般帳號新增
				//查詢最大編號
				$sql_select = $this->sql->select(array('id'), 'max');
				$sql_where = '';
				$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'row_array');

				//產生會員編號
				$id = $this->create->id('AC', $sql_result['max']);
				$id_key['id'] 			= $id;
				$id_key['public_key'] 	= $key['public_key'];
				//丟到回傳格式
				array_push($outer_array, $id_key);
				
				//新增會員資料
				array_push(Sql::$table, 'action_member');
				array_push(Sql::$select, $this->sql->field(array('id', 'email', 'last_name', 'first_name', 'mobile_phone', 'mobile_phone_id', 'state', 'create_user', 'create_time', 'update_user', 'update_time'), array($id, $route_data['email'], '', '', '', $route_data['mobile_phone_id'], 1, $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $id, 'action_member', '使用一般新增會員資料', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $id, 'action_member', '使用一般新增會員資料', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
				//新增金鑰資料
				array_push(Sql::$table, 'key');
				array_push(Sql::$select, $this->sql->field(array('id', 'private_key', 'public_key', 'create_user', 'create_time', 'update_user', 'update_time'), array($id, $key['private_key'], $key['public_key'], $id, $this->sql->get_time(1), $id, $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $id, 'key', '使用一般新增金鑰資料', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $id, 'key', '使用一般新增金鑰資料', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
			}
		}
		
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
			$json_data = $this->json->encode_json($app, '1_206');
		} else {
			$json_data = $this->json->encode_json($app, $outer_array);
		}

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 產生密碼資料
	 * 只限於一般帳號
	 * 第三方則跳過此步驟
	 * $route_data	所需參數
	 */
	public function send_password($route_data) {
		$app = '1_3';
		
		//查詢是否有google或fb的帳號
		$sql_select = $this->sql->select(array('google_id', 'fb_id'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		if($sql_result["google_id"] != "" || $sql_result["fb_id"] != "") {
				//新增密碼資料
				array_push(Sql::$table, 'password');
				array_push(Sql::$select, $this->sql->field(array('id', 'password', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['id'], $route_data['password'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'password', '有第三方的帳號新增密碼', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'password', '有第三方的帳號新增密碼', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
		} else {
			//查詢該會員有無密碼
			$sql_select = $this->sql->select(array('password'), '');
			$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
			$sql_query = $this->query_model->query($sql_select, 'password', '', $sql_where, '');
			$sql_result = $this->sql->result($sql_query, 'row_array');

			if(isset($sql_result['password'])) {
				//初始化密碼資料
				array_push(Sql::$table, 'password');
				array_push(Sql::$select, $this->sql->field(array('password', 'update_user', 'update_time'), array($route_data['password'], $route_data['id'], $this->sql->get_time(1))));
				array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'password', '因會員會完全註冊,所以將密碼資料初始化', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'password', '因會員會完全註冊,所以將密碼資料初始化', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 2);
			} else {
				//新增密碼資料
				array_push(Sql::$table, 'password');
				array_push(Sql::$select, $this->sql->field(array('id', 'password', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['id'], $route_data['password'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
				array_push(Sql::$where, '');
				array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'password', '有第三方的帳號新增密碼', $this->sql->get_time(1))));
				array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'password', '有第三方的帳號新增密碼', $this->sql->get_time(1), '')));
				array_push(Sql::$kind, 1);
			}
		}
		//執行更新
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
			$json_data = $this->json->encode_json($app, '1_301');
		} else {
			$json_data = $this->json->encode_json($app, '1_302');
		}

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 更新資料及寄送認證碼簡訊
	 * $route_data	所需參數
	 */
	public function send_authentication($route_data) {
		$app = '1_4';
		
		//查詢電子郵件
		$sql_select = $this->sql->select(array('email'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$email = $sql_result['email'];
		
		//查詢密碼
		$sql_select = $this->sql->select(array('password'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'password', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		if(isset($sql_result['password'])) $password = $sql_result['password']; else $password = '';
		
		//產生認證碼
		$authentication_code = $this->create->authentication();
		
		//更新第三方註冊資料google, fb 或者一般註冊資料
		array_push(Sql::$table, 'action_member');
		if(isset($route_data['google_id'])) {
			array_push(Sql::$select, $this->sql->field(array('email', 'last_name', 'first_name', 'mobile_phone', 'google_id', 'update_user', 'update_time'), array($email, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['google_id'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'google_id'), array($route_data['id'], $route_data['google_id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', 'google註冊,更新會員資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', 'google註冊,更新會員資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} elseif(isset($route_data['fb_id'])) {
			array_push(Sql::$select, $this->sql->field(array('email', 'last_name', 'first_name', 'mobile_phone', 'fb_id', 'update_user', 'update_time'), array($email, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['fb_id'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'fb_id'), array($route_data['id'], $route_data['fb_id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', 'fb註冊,更新會員資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', 'fb註冊,更新會員資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} else {
			array_push(Sql::$select, $this->sql->field(array('email', 'last_name', 'first_name', 'mobile_phone', 'update_user', 'update_time'), array($email, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', '一般註冊,更新會員資料', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', '一般註冊,更新會員資料', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//查詢會員修改資料紀錄有無紀錄存在,若有則要初始化
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member_alter_log', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');
		
		if($sql_result) {
			//初始化會員修改資料紀錄
			array_push(Sql::$table, 'action_member_alter_log');
			array_push(Sql::$select, $this->sql->field(array('email', 'last_name', 'first_name', 'mobile_phone', 'password', 'update_user', 'update_time'), array($email, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $password, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where', 'where'), array('id', 'frequency'), array($route_data['id'], 1), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_alter_log', '因註冊不完全,初始化會員修改資料紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_alter_log', '因註冊不完全,初始化會員修改資料紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//初始化簡訊紀錄
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('authentication_code', 'sms_frequency', 'update_user', 'update_time'), array($authentication_code, 0, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '因註冊不完全,初始化簡訊紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '因註冊不完全,初始化簡訊紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//初始化帳單備忘錄資料
			array_push(Sql::$table, 'action_member_data');
			array_push(Sql::$select, $this->sql->field(array('data_number', 'update_user', 'update_time'), array(0, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member_data', '因註冊不完全,初始化帳單備忘錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member_data', '因註冊不完全,初始化帳單備忘錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} else {
			//新增會員修改資料紀錄
			array_push(Sql::$table, 'action_member_alter_log');
			array_push(Sql::$select, $this->sql->field(array('frequency', 'id', 'email', 'password', 'last_name', 'first_name', 'mobile_phone', 'create_user', 'create_time', 'update_user', 'update_time'), array(1, $route_data['id'], $email, $password, $route_data['last_name'], $route_data['first_name'], $route_data['mobile_phone'], $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'action_member_alter_log', '加入會員新增會員修改資料紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'action_member_alter_log', '加入會員新增會員修改資料紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
				
			//新增簡訊狀態
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('id', 'authentication_code', 'sms_frequency', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['id'], $authentication_code, 0, $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_state', '加入會員新增簡訊狀態', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_state', '加入會員新增簡訊狀態', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
				
			//新增帳單備忘錄資料
			array_push(Sql::$table, 'action_member_data');
			array_push(Sql::$select, $this->sql->field(array('id', 'data_number', 'create_user', 'create_time', 'update_user', 'update_time'), array($route_data['id'], 0, $route_data['id'], $this->sql->get_time(1), $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'action_member_data', '加入會員新增帳單備忘錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'action_member_data', '加入會員新增帳單備忘錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
		}
		
		//執行更新,寄發認證碼簡訊
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind) === FALSE) {
			$json_data = $this->json->encode_json($app, '1_401');	
		} else {
			/*
			 * 這裡待加入簡訊內容規格
			 */
					
			$sms_result = $this->sms->send_sms(1, $route_data['mobile_phone'], '', $authentication_code);
			
			if($sms_result == 1) {
				$result = 1;
				$sms_result = '';
				$json_data = $this->json->encode_json($app, '1_402');
			} else {
				$result = 2;
				$json_data = $this->json->encode_json($app, '1_403');
			}
			
			$this->sql->clear_static();
			//新增簡訊記錄
			array_push(Sql::$table, 'sms_log');
			array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'event', 'result', 'error_message', 'create_time'), array($route_data['id'], $route_data['mobile_phone'], 1, $result, $sms_result, $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_log', '加入會員簡訊認證碼紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_log', '加入會員簡訊認證碼紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
			
			$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
		}

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 再次寄送簡訊認證碼
	 * 總共有三個認證碼都可通過
	 * $route_data 所需資料
	 */
	public function send_again($route_data) {
		$app = '1_5';
		
		//查詢會員手機
		$sql_select = $this->sql->select(array('mobile_phone'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$mobile_phone = $sql_result['mobile_phone'];
		
		//查詢會員簡訊狀態
		$sql_select = $this->sql->select(array('sms_frequency', 'authentication_code'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'sms_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$sms_state_info = $sql_result;
		
		//查詢簡訊傳送次數上限
		$sql_select = $this->sql->select(array('sms_times'), '');
		$sql_where = $this->sql->where(array('where'), array('using'), array('y'), array(''));
		$sql_query = $this->query_model->query($sql_select, 'system_setting', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		$sms_times_limit = $sql_result['sms_times'];
		
		//產生認證碼
		$authentication_code = $this->create->authentication();
		
		//若已傳送次數等於系統設定就不再傳送
		if($sms_state_info['sms_frequency'] == $sms_times_limit) {				
			$json_data = $this->json->encode_json($app, '1_501');
			
			$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
			return $this->json->encode_json('vale', $encode_data);
		}
		
		//更新簡訊次數
		array_push(Sql::$table, 'sms_state');
		array_push(Sql::$select, $this->sql->field(array('sms_frequency', 'update_user', 'update_time'), array(++$sms_state_info['sms_frequency'], $route_data['id'], $this->sql->get_time(1))));
		array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
		array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '加入會員增加傳送簡訊次數', $this->sql->get_time(1))));
		array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '加入會員增加傳送簡訊次數', $this->sql->get_time(1), '')));
		array_push(Sql::$kind, 2);
		
		//查詢認證碼2是否產生
		$sql_select = $this->sql->select(array('authentication_code2'), '');
		$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'sms_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'row_array');
		
		if($sql_result['authentication_code2'] == '') {
			//更新認證碼2
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('authentication_code2', 'update_user', 'update_time'), array($authentication_code, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '加入會員增加認證碼2', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '加入會員增加認證碼2', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		} else {
			//更新認證碼3
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('authentication_code3', 'update_user', 'update_time'), array($authentication_code, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '加入會員增加認證碼3', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '加入會員增加認證碼3', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
		}
		
		//執行更新,寄發認證碼簡訊
		if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
			/*
			 * 這裡待加入簡訊內容規格
			*/
					
			$sms_result = $this->sms->send_sms(1, $mobile_phone, '', $authentication_code);
				
			if($sms_result == 1) {
				$result = 1;
				$sms_result = '';
				$json_data = $this->json->encode_json($app, '1_502');
			} else {
				$result = 2;
				$json_data = $this->json->encode_json($app, '1_503');
			}
			
			$this->sql->clear_static();
			//新增簡訊記錄
			array_push(Sql::$table, 'sms_log');
			array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'event', 'result', 'error_message', 'create_time'), array($route_data['id'], $mobile_phone, 2, $result, $sms_result, $this->sql->get_time(1))));
			array_push(Sql::$where, '');
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'sms_log', '加入會員再次寄發簡訊認證碼紀錄', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'sms_log', '加入會員再次寄發簡訊認證碼紀錄', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 1);
				
			$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
		} else {
			$json_data = $this->json->encode_json($app, '1_504');
		}

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
	
	/*
	 * 確認認證碼是否正確
	 * 以及正式加入會員
	 * $route_data 所需參數
	 */
	public function check_authentication($route_data) {
		$app = '1_6';
		
		//查詢認證碼是否正確
		$sql_select = $this->sql->select(array('id'), '');
		$sql_where = $this->sql->where(array('where', 'or_where', 'or_where', 'where'), array('authentication_code', 'authentication_code2', 'authentication_code3', 'id'), array($route_data['authentication_code'], $route_data['authentication_code'], $route_data['authentication_code'], $route_data['id']), array(''));
		$sql_query = $this->query_model->query($sql_select, 'sms_state', '', $sql_where, '');
		$sql_result = $this->sql->result($sql_query, 'num_rows');
		
		if($sql_result) {
			//更新會員狀態
			array_push(Sql::$table, 'action_member');
			array_push(Sql::$select, $this->sql->field(array('state', 'update_user', 'update_time'), array(2, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'action_member', '加入會員完成註冊', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'action_member', '加入會員完成註冊', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//清空簡訊認證碼及次數
			array_push(Sql::$table, 'sms_state');
			array_push(Sql::$select, $this->sql->field(array('authentication_code', 'authentication_code2', 'authentication_code3', 'sms_frequency', 'update_user', 'update_time'), array('', '', '', 0, $route_data['id'], $this->sql->get_time(1))));
			array_push(Sql::$where, $this->sql->where(array('where'), array('id'), array($route_data['id']), array('')));
			array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(2, $route_data['id'], 'sms_state', '加入會員清空所有簡訊認證碼和次數', $this->sql->get_time(1))));
			array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(2, $route_data['id'], 'sms_state', '加入會員清空所有簡訊認證碼和次數', $this->sql->get_time(1), '')));
			array_push(Sql::$kind, 2);
			
			//執行更新
			if($this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind)) {
				//查詢手機ID和手機號碼
				$sql_select = $this->sql->select(array('mobile_phone', 'mobile_phone_id'), '');
				$sql_where = $this->sql->where(array('where'), array('id'), array($route_data['id']), array(''));
				$sql_query = $this->query_model->query($sql_select, 'action_member', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'row_array');
				$mobile_phone = $sql_result['mobile_phone'];
				$mobile_phone_id = $sql_result['mobile_phone_id'];
				
				//檢查是否有分享帳單
				$sql_select = $this->sql->select(array('billez_code'), '');
				$sql_where = $this->sql->where(array('where', 'where'), array('mobile_phone', 'read'), array($mobile_phone, 'n'), array(''));
				$sql_query = $this->query_model->query($sql_select, 'bill_share_log', '', $sql_where, '');
				$sql_result = $this->sql->result($sql_query, 'num_rows');
				
				if($sql_result) {
					//將要推播的訊息丟進推播變數
					array_push(Push::$id, $route_data['id']);
					array_push(Push::$moblie_phone, $mobile_phone);
					array_push(Push::$moblie_phone_id, $mobile_phone_id);
					array_push(Push::$billez_code, '');

					$this->sql->clear_static();
					//執行推播
					$this->push->send_push('gcm_1');
					
					//新增推播紀錄
					array_push(Sql::$table, 'push_log');
					array_push(Sql::$select, $this->sql->field(array('id', 'mobile_phone', 'mobile_phone_id', 'event', 'time', 'result', 'gcm_message'), array(Push::$id[0], Push::$moblie_phone[0], Push::$moblie_phone_id[0], 1, $this->sql->get_time(1), Push::$result[0], Push::$gcm[0])));
					array_push(Sql::$where, '');
					array_push(Sql::$log, $this->sql->field(Sql::$user_log, array(1, $route_data['id'], 'push_log', '加入會員分享帳單推播紀錄', $this->sql->get_time(1))));
					array_push(Sql::$error, $this->sql->field(Sql::$system_log, array(1, $route_data['id'], 'push_log', '加入會員分享帳單推播紀錄', $this->sql->get_time(1), '')));
					array_push(Sql::$kind, 1);
					
					$this->insert_update_model->execute_sql(Sql::$table, Sql::$select, Sql::$where, Sql::$log, Sql::$error, Sql::$kind);
				}
				
				$json_data = $this->json->encode_json($app, '1_601');
			} else {
				$json_data = $this->json->encode_json($app, '1_602');
			}			
		} else {
			$json_data = $this->json->encode_json($app, '1_603');
		}

		$encode_data = $this->key->encode_app($json_data, $route_data['private_key']);
		return $this->json->encode_json('vale', $encode_data);
	}
}