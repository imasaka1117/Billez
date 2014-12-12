<?php 
	session_start();
	if(!isset($_SESSION['user'])) header("Location: " . base_url() . 'index.php/login');
?>
<div id="header_div">
	<div id="logo_div"><h1><a href="<?=$index_url ?>home/ma">Billez 管理系統</a></h1></div>
	<div id="user_div"><pre><span style="color: blue">	<?=$_SESSION['user']['name'] ?> </span></pre></div>	
	<div id="menu_div">
		<ul>
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
			<li><a href="#">帳單</a>
				<ul>
					<li><a href="<?=$index_url ?>bill/insert_kind_web">新增帳單種類</a></li>
					<li><a href="<?=$index_url ?>bill/insert_basis_web">新增帳單依據</a></li>
					<li><a href="<?=$index_url ?>bill/search_web">查詢帳單</a></li>
					<li><a href="<?=$index_url ?>bill/insert_pay_set_web">新增繳費單格式</a></li>
					<li><a href="<?=$index_url ?>bill/insert_receive_set_web">新增入帳單格式</a></li>
					<li><a href="<?=$index_url ?>bill/update_pay_set_web">修改繳費單格式</a></li>
					<li><a href="<?=$index_url ?>bill/update_receive_set_web">修改入帳單格式</a></li>
			  	<!--<li><a href="<?=$index_url ?>bill/insert_customer_set_web">新增客製繳費格式</a></li>-->
				<!--<li><a href="<?=$index_url ?>bill/update_customer_set_web">修改客製繳費格式</a></li>-->
					<li><a href="<?=$index_url ?>bill/import_pay_web">匯入繳費帳單</a></li>
					<li><a href="<?=$index_url ?>bill/import_receive_web">匯入入帳帳單</a></li>
					<li><a href="<?=$index_url ?>bill/push_bill_web">推播帳單</a></li>
				</ul>
			</li>
			<li><a href="#">訂閱</a>
				<ul>
					<li><a href="<?=$index_url ?>subscribe/search_web">查詢訂閱</a></li>
					<li><a href="<?=$index_url ?>subscribe/state_web">業者訂閱狀態更改</a></li>
					<li><a href="<?=$index_url ?>subscribe/trader_machinery_web">業者代收機構更改</a></li>
				</ul>
			</li>
			<li><a href="#">行動會員</a>
				<ul>
					<li><a href="<?=$index_url ?>action_member/search_web">查詢行動會員</a></li>
					<li><a href="<?=$index_url ?>action_member/export_web">匯出行動會員</a></li>
				</ul>
			</li>
			<li><a href="#">一般會員</a>
				<ul>
					<li><a href="<?=$index_url ?>normal_member/search_web">查詢一般會員</a></li>
				</ul>
			</li>
			<li><a href="#">等級</a>
				<ul>
					<li><a href="<?=$index_url ?>level/insert_object_web">新增等級對象</a></li>
					<li><a href="<?=$index_url ?>level/insert_name_web">新增等級名稱</a></li>
					<li><a href="<?=$index_url ?>level/search_web">查詢等級</a></li>
				</ul>
			</li>
			<li><a href="#">促銷優惠</a>
				<ul>
					<li><a href="<?=$index_url ?>promo/insert_web">新增促銷優惠</a></li>
					<li><a href="<?=$index_url ?>promo/search_web">查詢促銷優惠</a></li>
					<li><a href="<?=$index_url ?>promo/send_web">寄發電子報</a></li>
				</ul>
			</li>
			<li><a href="#">問題記錄</a>
				<ul>
					<li><a href="<?=$index_url ?>problem/insert_web">新增問題</a></li>
					<li><a href="<?=$index_url ?>problem/search_web">查詢問題</a></li>
				</ul>
			</li>
			<li><a href="#">錯誤記錄</a>
				<ul>
					<li><a href="<?=$index_url ?>error/search_bill_import_web">帳單匯入錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_push_web">推播錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_email_web">電子郵件錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_sms_web">簡訊錯誤</a></li>
					<li><a href="<?=$index_url ?>error/search_system_web">系統錯誤</a></li>
				</ul>
			</li>
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
			<li><a href="#">作業人員</a>
				<ul>
					<li><a href="<?=$index_url ?>user/insert_web">新增使用者</a></li>
					<li><a href="<?=$index_url ?>user/search_web">查詢使用者</a></li>
				</ul>
			</li>
			<li><a href="<?=$index_url ?>" onclick="javascript : return confirm('確定要登出嗎?');">登出系統</a></li>
		</ul>
	</div>
</div>