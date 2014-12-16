<?php

class User_model extends CI_Model {
	/*
	 * 新增使用者
	 * $post 查詢資料
	 * $user 使用者
	 */
	public function insert($post, $user) {
		//確認使用者名稱是否存在
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		  'from' => Table_1::$user_list,
																		  'join'=> '',
																		  'where' => $this->sql->where(
																		  							   array('where'), 
																		  							   array(Field_1::$email), 
																		  							   array($post['email']),
																		  							   array('')
																		  							  ),
																		  'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;

		//查詢使用者名稱最大代碼
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('MAX(' . Field_1::$id . ') AS max'), 'function'),
																		  'from' => Table_1::$user_list,
																		  'join'=> '',
																		  'where' => '',
																		  'other' => '')), 'row_array');
		$id = $this->create->id('US', $sql_result['max']);

		//新增使用者
		$this->sql->add_static(array('table'=> Table_1::$user_list,
									  'select'=> $this->sql->field(array(Field_1::$id,
									 									 Field_1::$email,
									 									 Field_1::$password,
									 									 Field_1::$name, 
									 									 Field_2::$kind, 
									 									 Field_1::$create_user, 
									 									 Field_1::$create_time, 
									 									 Field_1::$update_user, 
									 									 Field_1::$update_time),
											 					  array($id, 
											 					  		 $post['email'],
											 					  		 md5($post['password']), 
											 					  		 $post['name'], 
											 					  		 $post['kind'], 
											 					  		 $user['id'], 
											 					  		 $this->sql->get_time(1),
											 					  		 $user['id'], 
											 					  		 $this->sql->get_time(1))),
									 'where'=> '',
									 'log'=> $this->sql->field(array(Field_3::$operate, 
									 								  Field_2::$user, 
									 								  Field_3::$table, 
									 								  Field_4::$purpose, 
									 								  Field_1::$create_time),
										  					   array(1, 
										  					   		  $user['id'], 
										  					   		  Table_1::$user_list, 
										  					   		  '新增使用者_新增使用者', 
										  					   		  $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, 
									 									Field_2::$user, 
									 		                            Field_3::$table, 
									 									Field_1::$message, 
									 		                            Field_1::$create_time, 
									 		                            Field_3::$db_message),
																 array(1, 
																 		$user['id'], 
																 		Table_1::$user_list, 
																 		'新增使用者_新增使用者', 
																 		$this->sql->get_time(1), 
																 		'')),
									 'kind'=> 1));
		
		//新增使用者權限
		$key_array=array('id');
		$value_array=array($id);
		foreach ($post as $id => $value)
		{
			if($value=="y")
			{
				array_push($key_array, $id);
				
				array_push($value_array,$value);
			}
		}
		
		array_push($key_array, Field_1::$create_user);
		array_push($key_array, Field_1::$create_time);
		array_push($key_array, Field_1::$update_user);
		array_push($key_array, Field_1::$update_time);
		array_push($value_array, $user['id']);
		array_push($value_array, $this->sql->get_time(1));
		array_push($value_array, $user['id']);
		array_push($value_array, $this->sql->get_time(1));

		$this->sql->add_static(array('table'=> Table_1::$function_authority,
									  'select'=> $this->sql->field($key_array,$value_array),
									  'where'=> '',
									  'log'=> $this->sql->field(array(Field_3::$operate, 
																	   Field_2::$user, 
																	   Field_3::$table, 
																	   Field_4::$purpose, 
																	   Field_1::$create_time),
															   array(1,
																  	  $user['id'], 
																  	  Table_1::$function_authority, 
																  	  '新增使用者_新增使用者權限', 
																  	  $this->sql->get_time(1))),
									 'error'=> $this->sql->field(array(Field_3::$operate, 
											  						    Field_2::$user, 
												                        Field_3::$table, 
												                        Field_1::$message, 
												                        Field_1::$create_time, 
												                        Field_3::$db_message),
																array(1, 
																	   $user['id'], 
																	   Table_1::$function_authority, 
																	   '新增使用者_新增使用者權限', 
																	   $this->sql->get_time(1),
															     	   '')),
									'kind'=> 1));				
		
		//執行
		if($this->query_model->execute_sql(array('table' => Sql::$table, 
												  'select' => Sql::$select, 
												  'where' => Sql::$where,
												  'log' => Sql::$log, 
												  'error' => Sql::$error, 
												  'kind' => Sql::$kind))) 
		{	return 'reload';	} 
		else {	return 2;	}
	}
	
	/*
	 * 查詢使用者資料
	 * $post 查詢條件資料
	 */

	public function search($post) {
		if(strlen($post['kind']) > 1) $post['kind'] = '';
//查詢bug好用的指令 :	echo print_r($post);
		//查詢使用者列表筆數
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		  'from' => Table_1::$user_list,
																		  'join'=> '',
																		  'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name,Field_1::$email,Field_2::$kind), 
																		  									  array($post['id'], $post['name'],$post['email'],$post['kind'])),
																		  'other' => '')), 'num_rows');
		$page_count = ceil($sql_result / 10);
	
		//查詢使用者列表 
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id , Field_1::$name , 'IF(' . Field_2::$kind . ' = 1, "OP", 
																																			    IF(' . Field_2::$kind . ' = 2, "客服", 
																																			    IF(' . Field_2::$kind . ' = 3, "管理者",
																																			    IF(' . Field_2::$kind . ' = 4, "業者", 
																																			     "代收機構"))))', Field_1::$email ) , 'function'),
																		  'from' => Table_1::$user_list,
																		  'join'=> '',
																		  'where' => $this->sql->where_search(array(Field_1::$id, Field_1::$name, Field_2::$kind , Field_1::$email), 
																		  									  array($post['id'], $post['name'], $post['kind'],$post['email'])),
																		  'other' => $this->sql->other(array('limit'), 
																		  							   array(array(10, ($post['page'] * 10) - 10))))), 'result_array');
															
		return $this->option->table($sql_result, array('帳號','姓名', '種類', '信箱'), base_url() . Param::$index_url . 'user/update_web') . $this->option->page($page_count, $post['page']);
	}
	
	/*
	 * 更新使用者資料
	 * $post	web傳來的參數
	 * $user	當前使用該系統者
	 */
	public function update($post, $user) {

		//查詢使用者電子郵件
		$sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id), ''),
																		 'from' => Table_1::$user_list,
																		 'join'=> '',
																		 'where' => $this->sql->where(array('where','where'), 
																		 							  array(Field_1::$id . ' !=', Field_1::$email), 
																		 							  array($post['id'], $post['email']),
																		 							  array('')),
																		 'other' => '')), 'num_rows');
		//若存在回傳代碼
		if($sql_result) return 1;
		//更新使用者資料
					 $this->sql->add_static(array('table'=> Table_1::$user_list,
												   'select'=> $this->sql->field(array(Field_1::$name, Field_1::$email, Field_2::$kind, 
																					   Field_1::$update_user, Field_1::$update_time),
																				 array($post['name'], $post['email'], $post['kind'],
																				 		$user['id'], $this->sql->get_time(1))),
													'where'=> $this->sql->where(array('where'), 
																				array(Field_1::$id), 
																				array($post['id']), 
																				array('')),
													'log'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, 
																					 Field_3::$table, Field_4::$purpose, 
																					 Field_1::$create_time), 
																			  array(2, $user['id'], 
																			  		 Table_1::$user_list, '更新使用者_更新使用者資料', 
																			  		 $this->sql->get_time(1))),
													'error'=> $this->sql->field(array(Field_3::$operate, Field_2::$user, 
																					   Field_3::$table, Field_1::$message, 
																					   Field_1::$create_time, Field_3::$db_message), 
																				array(2, $user['id'], 
																					   Table_1::$user_list, '更新使用者_更新使用者資料', 
																					   $this->sql->get_time(1), '')),
													'kind'=> 2));
					
		//更新使用者權限
					 $sql_result = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array("*"), ''),
					 		'from' => Table_1::$function_authority,
					 		'join'=> '',
					 		'where' => $this->sql->where(array('where'),
					 				array(Field_1::$id ),
					 				array($post['id']),
					 				array('')),
					 		'other' => '')), 'row_array');
					 
 		//重置權限
		$null_key_array=array();
		$null_value_array=array();

		foreach($sql_result as $id=>$value)
		{
			switch ($id)
			{
				case 'id':
					break;
				case 'name':
					break;
				case 'email':
					break;
				case 'kind':
					break;
				case 'create_user':
					break;
				case 'create_time':
					break;
				case 'update_user':
					break;
				case 'update_time':
					break;
				default:
							$value=null;
							array_push($null_key_array, $id);
							array_push($null_value_array,$value);
					
			}
		}
					array_push($null_key_array, Field_1::$update_user);
					array_push($null_key_array, Field_1::$update_time);
					array_push($null_value_array, $user['id']);
					array_push($null_value_array, $this->sql->get_time(1));	

 		$this->sql->add_static(array('table'=> Table_1::$function_authority,
 									  'select'=> $this->sql->field($null_key_array,$null_value_array),
 									  'where'=> $this->sql->where(array('where'), 
																				array(Field_1::$id), 
																				array($post['id']), 
																				array('')),
 									  'log'=> $this->sql->field(array(Field_3::$operate, 
 																	   Field_2::$user, 
 																	   Field_3::$table, 
 																	   Field_4::$purpose, 
 																	   Field_1::$create_time),
 															    array(2,
 															  		   $user['id'], 
 															  		   Table_1::$function_authority, 
 															  		   '重置使用者_重置使用者權限', 
 															  		   $this->sql->get_time(1))),
 									  'error'=> $this->sql->field(array(Field_3::$operate, 
 																	     Field_2::$user, 
 											                             Field_3::$table, 
 											                             Field_1::$message, 
 											                             Field_1::$create_time, 
 											                             Field_3::$db_message),
 																  array(2, 
 																	     $user['id'], 
 																	     Table_1::$function_authority, 
 																	     '重置使用者_重置使用者權限', 
 																	     $this->sql->get_time(1),
 																 		 '')),
 							'kind'=> 2));
		
		
		
		//執行重置 		
 		if($this->query_model->execute_sql(array('table' => Sql::$table,
 				'select' => Sql::$select,
 				'where' => Sql::$where,
 				'log' => Sql::$log,
 				'error' => Sql::$error,
 				'kind' => Sql::$kind)))
 		{
 			//釋放暫存
 			$this->sql->clear_static();
 				
 			//重新新增權限
 			$key_array=array();
 			$value_array=array();
 			foreach ($post as $id => $value)
 			{
 				if($value=="y")
 				{
 					array_push($key_array, $id);
 					array_push($value_array,$value);
 				}
 			}
 			
 			array_push($key_array, Field_1::$update_user);
 			array_push($key_array, Field_1::$update_time);
 			
 			array_push($value_array, $user['id']);
 			array_push($value_array, $this->sql->get_time(1));

 			$this->sql->add_static(array('table'=> Table_1::$function_authority,
 					'select'=> $this->sql->field($key_array,$value_array),
 					'where'=> $this->sql->where(array('where'),
 							array(Field_1::$id),
 							array($post['id']),
 							array('')),
 					'log'=> $this->sql->field(array(Field_3::$operate,
 							Field_2::$user,
 							Field_3::$table,
 							Field_4::$purpose,
 							Field_1::$create_time),
 							array(2,
 									$user['id'],
 									Table_1::$function_authority,
 									'更新使用者_更新使用者權限',
 									$this->sql->get_time(1))),
 					'error'=> $this->sql->field(array(Field_3::$operate, 
 													   Field_2::$user, 
 									                   Field_3::$table, 
 				  		                               Field_1::$message, 
 											           Field_1::$create_time, 
 										               Field_3::$db_message),
 								  			    array(2, 
 													   $user['id'], 
 													   Table_1::$function_authority, 
 												       '更新使用者_更新使用者權限', 
 												       $this->sql->get_time(1),
 											   		   '')),
 					'kind'=> 2));
 			if($this->query_model->execute_sql(array('table' => Sql::$table,
 					'select' => Sql::$select,
 					'where' => Sql::$where,
 					'log' => Sql::$log,
 					'error' => Sql::$error,
 					'kind' => Sql::$kind)))
 			{
 				return 'user/search_web';
 			}
 			else
 			{
 				return 3;
 			}
 		}
 		else
 		{
 			return 3;
 		}
	}
	
	/*
	 * 查詢使用者資料
	 * $post	web傳來的參數
	 */
	
	public function search_data($post) {
		$sql_result1 = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array(Field_1::$id,
																											   Field_1::$name,
																											   Field_1::$email,
																											   Field_2::$kind) , 
																											   ''),
																	     'from' => Table_1::$user_list,
																	     'join'=> '',
																	     'where' => $this->sql->where(array('where'), 
																	    							  array(Field_1::$id), 
																	    							  array($post['id'] ), 
																	    							  array('')),
																	     'other' => '')), 'row_array');
					
		
		
		$sql_result2 = $this->sql->result($this->query_model->query(array('select' => $this->sql->select(array('*') , ''),
																		 'from' => Table_1::$function_authority,
																		 'join'=>'',
																		 'where' => $this->sql->where(array('where'),
																									  array(Field_1::$id),
																								      array($post['id'] ),
																								      array('')),
																		 'other' => '')), 'row_array');
		
		$sql_result_sum=array_merge($sql_result1,$sql_result2);
		
		return json_encode($sql_result_sum, JSON_UNESCAPED_UNICODE);
		
	}
}
