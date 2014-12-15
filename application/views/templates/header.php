<?php 
	session_start();
	if(!isset($_SESSION['user'])) header("Location: " . base_url() . 'index.php/login');
	
	$index_array = array('insert_trader'=>'trader/insert_web">新增業者',
						  'insert_trader_contract'=>'trader/insert_contract_web">新增業者合約',
						  'search_trader'=>'trader/search_web">查詢業者',
						  'search_trader_contract'=>'trader/search_contract_web">查詢業者合約',
						  'export_trader'=>'trader/export_web">匯出業者',
						  'export_trader_report'=>'trader/report_web">匯出報表',
						  'insert_machinery'=>'machinery/insert_web">新增代收機構',
						  'insert_machinery_contract'=>'machinery/insert_contract_web">新增代收機構合約',
						  'search_machinery'=>'machinery/search_web">查詢代收機構',
						  'search_machinery_contract'=>'machinery/search_contract_web">查詢代收機構合約',
						  'export_machinery'=>'machinery/export_web">匯出代收機構',
						  'export_machinery_report'=>'machinery/report_web">匯出報表',
						  'insert_bill_kind'=>'bill/insert_kind_web">新增帳單種類',
						  'insert_bill_basis'=>'bill/insert_basis_web">新增帳單依據',
						  'search_bill'=>'bill/search_web">查詢帳單',
						  'insert_pay_bill_set'=>'bill/insert_pay_set_web">新增繳費單格式',
						  'insert_receive_bill_set'=>'bill/insert_receive_set_web">新增入帳單格式',
						  'update_pay_bill_set'=>'bill/update_pay_set_web">修改繳費單格式',
						  'update_receive_bill_set'=>'bill/update_receive_set_web">修改入帳單格式',
						  'insert_customer_pay_bill_set'=>'bill/insert_customer_set_web">新增客製繳費格式',
						  'update_customer_pay_bill_set'=>'bill/update_customer_set_web">修改客製繳費格式',
						  'import_pay_bill'=>'bill/import_pay_web">匯入繳費帳單',
						  'import_receive_bill'=>'bill/import_receive_web">匯入入帳帳單',
						  'push_bill'=>'bill/push_bill_web">推播帳單',
						  'search_subscribe'=>'subscribe/search_web">查詢訂閱',
						  'update_trader_subscribe_state'=>'subscribe/state_web">業者訂閱狀態更改',
						  'update_trader_machinery'=>'subscribe/trader_machinery_web">業者代收機構更改',
						  'search_action_member'=>'action_member/search_web">查詢行動會員',
						  'export_action_member'=>'action_member/export_web">匯出行動會員',
						  'search_normal_member'=>'normal_member/search_web">查詢一般會員',
						  'insert_level_object'=>'level/insert_object_web">新增等級對象',
						  'insert_level_name'=>'level/insert_name_web">新增等級名稱',
					      'search_level'=>'level/search_web">查詢等級',
						  'insert_promotion'=>'promo/insert_web">新增促銷優惠',
						  'search_promotion'=>'promo/search_web">查詢促銷優惠',
						  'send_promotion_email'=>'promo/send_web">寄發電子報',
						  'insert_problem'=>'problem/insert_web">新增問題',
						  'search_problem'=>'problem/search_web">查詢問題',
						  'bill_import_error'=>'error/search_bill_import_web">帳單匯入錯誤',
						  'push_error'=>'error/search_push_web">推播錯誤',
						  'sms_error'=>'error/search_sms_web">簡訊錯誤',
						  'email_error'=>'error/search_email_web">電子郵件錯誤',
						  'system_error'=>'error/search_system_web">系統錯誤',
						  'search_operator'=>'operate/search_operate_web">查詢操作',
						  'insert_system_set'=>'operate/insert_system_set_web">新增系統設定',
						  'search_system_set'=>'operate/search_system_set_web">查詢系統設定',
						  'scheduling_set'=>'operate/scheduling_set_web">排程設定',
						  'insert_email_set'=>'operate/insert_email_set_web">新增電子郵件設定',
						  'search_email_set'=>'operate/search_email_set_web">查詢電子郵件設定',
						  'insert_sms_set'=>'operate/insert_sms_set_web">新增簡訊設定',
						  'search_sms_set'=>'operate/search_sms_set_web">查詢簡訊設定',
						  'search_user'=>'user/search_web">查詢使用者',
						  'insert_user'=>'user/insert_web">新增使用者',
						 );
	
	$trader_array=array();$machinery_array=array();$bill_array=array();
	$subscribe_array=array();$action_member_array=array();$normal_member_array=array();
	$level_array=array();$promo_array=array();$problem_array=array();
	$error_array=array();$operate_array=array();$user_array=array();
	
	foreach($_SESSION['user']['function_authority'] as $id=>$value) 
	{
		foreach($index_array as $id2=>$value2) 
		{
			if($id == $id2) 
			{
				if($value=='y')
				{
					if(preg_match("/^trader/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($trader_array, $a);
					}
				
					else if(preg_match("/^machinery/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($machinery_array, $a);
					}
				
					else if(preg_match("/^bill/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($bill_array, $a);
					}
				
					else if(preg_match("/^subscribe/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($subscribe_array, $a);
					}
				
					else if(preg_match("/^action_member/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($action_member_array, $a);
					}
				
					else if(preg_match("/^normal_member/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($normal_member_array, $a);
					}
				
					else if(preg_match("/^level/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($level_array, $a);
					}
				
					else if(preg_match("/^promo/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($promo_array, $a);
					}
				
					else if(preg_match("/^problem/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($problem_array, $a);
					}
				
					else if(preg_match("/^error/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($error_array, $a);
					}
				
					else if(preg_match("/^operate/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($operate_array, $a);
					}
				
					else if(preg_match("/^user/i",$value2)==1)
					{
						$a=$index_url.$value2;
						array_push($user_array, $a);
					}
				}
			}
		}
	}
?>

<div id="header_div">
	<div id="logo_div"><h1><a href="<?=$index_url ?>home/ma">Billez 管理系統</a></h1></div>
	<div id="user_div"><pre><span style="color: blue">	<?=$_SESSION['user']['name'] ?> </span></pre></div>	
	<div id="menu_div">
		<ul>
			<?php 
				if(count($trader_array)!=0)
				{
					echo '<li><a href="#">業者</a><ul>';
					
					foreach ($trader_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					};
					echo'</ul></li>';
				}
				
			?>
<!--  			
			<li><a href="#">業者</a>
				<ul>
					<li><a href="<?=$index_url ?>trader/insert_web">新增業者</a></li>
					<li><a href="<?=$index_url ?>trader/insert_contract_web">新增業者合約</a></li>
					<li><a href="<?=$index_url ?>trader/search_web">查詢業者</a></li>
					<li><a href="<?=$index_url ?>trader/search_contract_web">查詢業者合約</a></li>
					<li><a href="<?=$index_url ?>trader/export_web">匯出業者</a></li>
					<li><a href="<?=$index_url ?>trader/report_web">匯出報表</a></li>
				</ul>
			</li>
-->					
			<?php 
				if(count($machinery_array)!=0)
				{
					echo '<li><a href="#">代收機構</a><ul>';
					
					foreach ($machinery_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">代收機構</a>
				<ul>
					<li><a href="<?=$index_url ?>machinery/insert_web">新增代收機構</a></li>
					<li><a href="<?=$index_url ?>machinery/insert_contract_web">新增代收機構合約</a></li>
					<li><a href="<?=$index_url ?>machinery/search_web">查詢代收機構</a></li>
					<li><a href="<?=$index_url ?>machinery/search_contract_web">查詢代收機構合約</a></li>
					<li><a href="<?=$index_url ?>machinery/export_web">匯出代收機構</a></li>
					<li><a href="<?=$index_url ?>machinery/report_web">匯出報表</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($bill_array)!=0)
				{
					echo '<li><a href="#">帳單</a><ul>';
					
					foreach ($bill_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">帳單</a>
				<ul>
					<li><a href="<?=$index_url ?>bill/insert_kind_web">新增帳單種類</a></li>
					<li><a href="<?=$index_url ?>bill/insert_basis_web">新增帳單依據</a></li>
					<li><a href="<?=$index_url ?>bill/search_web">查詢帳單</a></li>
					<li><a href="<?=$index_url ?>bill/insert_pay_set_web">新增繳費單格式</a></li>
					<li><a href="<?=$index_url ?>bill/insert_receive_set_web">新增入帳單格式</a></li>
					<li><a href="<?=$index_url ?>bill/update_pay_set_web">修改繳費單格式</a></li>
					<li><a href="<?=$index_url ?>bill/update_receive_set_web">修改入帳單格式</a></li>
			  		<li><a href="<?=$index_url ?>bill/insert_customer_set_web">新增客製繳費格式</a></li>
					<li><a href="<?=$index_url ?>bill/update_customer_set_web">修改客製繳費格式</a></li>
					<li><a href="<?=$index_url ?>bill/import_pay_web">匯入繳費帳單</a></li>
					<li><a href="<?=$index_url ?>bill/import_receive_web">匯入入帳帳單</a></li>
					<li><a href="<?=$index_url ?>bill/push_bill_web">推播帳單</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($subscribe_array)!=0)
				{
					echo '<li><a href="#">訂閱</a><ul>';
					
					foreach ($subscribe_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">訂閱</a>
				<ul>
					<li><a href="<?=$index_url ?>subscribe/search_web">查詢訂閱</a></li>
					<li><a href="<?=$index_url ?>subscribe/state_web">業者訂閱狀態更改</a></li>
					<li><a href="<?=$index_url ?>subscribe/trader_machinery_web">業者代收機構更改</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($action_member_array)!=0)
				{
					echo '<li><a href="#">行動會員</a><ul>';
					
					foreach ($action_member_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">行動會員</a>
				<ul>
					<li><a href="<?=$index_url ?>action_member/search_web">查詢行動會員</a></li>
					<li><a href="<?=$index_url ?>action_member/export_web">匯出行動會員</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($normal_member_array)!=0)
				{
					echo '<li><a href="#">一般會員</a><ul>';
					
					foreach ($normal_member_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">一般會員</a>
				<ul>
					<li><a href="<?=$index_url ?>normal_member/search_web">查詢一般會員</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($level_array)!=0)
				{
					echo '<li><a href="#">等級</a><ul>';
					
					foreach ($level_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">等級</a>
				<ul>
					<li><a href="<?=$index_url ?>level/insert_object_web">新增等級對象</a></li>
					<li><a href="<?=$index_url ?>level/insert_name_web">新增等級名稱</a></li>
					<li><a href="<?=$index_url ?>level/search_web">查詢等級</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($promo_array)!=0)
				{
					echo '<li><a href="#">促銷優惠</a><ul>';
					
					foreach ($promo_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">促銷優惠</a>
				<ul>
					<li><a href="<?=$index_url ?>promo/insert_web">新增促銷優惠</a></li>
					<li><a href="<?=$index_url ?>promo/search_web">查詢促銷優惠</a></li>
					<li><a href="<?=$index_url ?>promo/send_web">寄發電子報</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($problem_array)!=0)
				{
					echo '<li><a href="#">問題記錄</a><ul>';
					
					foreach ($problem_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">問題記錄</a>
				<ul>
					<li><a href="<?=$index_url ?>problem/insert_web">新增問題</a></li>
					<li><a href="<?=$index_url ?>problem/search_web">查詢問題</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($error_array)!=0)
				{
					echo '<li><a href="#">錯誤記錄</a><ul>';
					
					foreach ($error_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">錯誤記錄</a>
				<ul>
					<li><a href="<?=$index_url ?>error/search_bill_import_web">帳單匯入錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_push_web">推播錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_email_web">電子郵件錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_sms_web">簡訊錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_system_web">系統錯誤</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($operate_array)!=0)
				{
					echo '<li><a href="#">操作設定</a><ul>';
					
					foreach ($operate_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">操作設定</a>
				<ul>
					<li><a href="<?=$index_url ?>operate/search_operate_web">查詢操作</a></li>
					<li><a href="<?=$index_url ?>operate/insert_email_set_web">新增電子郵件設定</a></li>
					<li><a href="<?=$index_url ?>operate/search_email_set_web">查詢電子郵件設定</a></li>
					<li><a href="<?=$index_url ?>operate/insert_sms_set_web">新增簡訊設定</a></li>
					<li><a href="<?=$index_url ?>operate/search_sms_set_web">查詢簡訊設定</a></li>
					<li><a href="<?=$index_url ?>operate/insert_system_set_web">新增系統設定</a></li>
					<li><a href="<?=$index_url ?>operate/search_system_set_web">查詢系統設定</a></li>
					<li><a href="<?=$index_url ?>operate/scheduling_set_web">排程設定</a></li>
				</ul>
			</li>
-->
			
			<?php 
				if(count($user_array)!=0)
				{
					echo '<li><a href="#">作業人員</a><ul>';
					
					foreach ($user_array as $id=>$value)
					{
						echo '<li><a href="'. $value .'</a></li>';
					}
					echo'</ul></li>';
				}
			?>
<!--  
			<li><a href="#">作業人員</a>
				<ul>
					<li><a href="<?=$index_url ?>user/insert_web">新增使用者</a></li>
					<li><a href="<?=$index_url ?>user/search_web">查詢使用者</a></li>
				</ul>
			</li>
-->
			<li><a href="<?=$index_url ?>" onclick="javascript : return confirm('確定要登出嗎?');">登出系統</a></li>
		</ul>
	</div>
</div>