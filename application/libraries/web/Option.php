<?php	if ( ! defined('BASEPATH')) exit('No dirct script access allowed');

class Option {
	/*
	 * 產生選單字串
	 * $result 查詢結果
	 * $kind 選擇是否需要請選則
	 */
	public function select($result, $kind) {
		if($kind == 1) {
			$option = '';
		} else {
			$option = '<option value="">請選擇</option>';
		}
		
		foreach($result as $data) $option = $option . '<option value="' . $data['code'] . '">' . $data['name'] . '</option>';
		
		return $option;
	}
	
	/*
	 * 產生查詢列表表格
	 * $result 查詢結果
	 * $title  標題列
	 * $url    修改資料網址
	 */
	public function table($result, $title, $url) {
		$html_string = "<table class='search' cellpadding='5'><tr>";
		
		foreach($title as $row) $html_string = $html_string . '<th>' . $row . '</th>';

		$html_string = $html_string . '</tr>';
		
		if(count($result)) {
			foreach ($result as $data) {
				$html_string = $html_string . "<tr>";
			
				foreach ($data as $item => $value) {
					switch ($item) {
						case 'id':
							$html_string = $html_string . "<td><a href='" . $url . "?id=" . $value . "'>$value</a></td>";
							break;
						default:
							$html_string = $html_string . "<td>$value</td>";
							break;
					}
				}
			
				$html_string = $html_string . "</tr>";
			}
			
			return $html_string . "</table>";
		} else {
			return $html_string . "</table><h3>查無資料<h3>";
		}
	}
	
	/*
	 * 產生分頁字串
	 * $page_count	總頁數
	 * $page		要查詢頁數
	 * $js_function	函式名稱
	 */
	public function page($page_count, $page) {
		$html_string		= "";
		$post_page_count 	= ceil($page / 10);
		$show_page_up 		= $post_page_count * 10;
		$show_page_down 	= ($post_page_count - 1) * 10 + 1;
		$next_page			= $show_page_up + 1;
		$next_flag			= true;
		$pre_page			= $show_page_down - 1;
		$pre_flag			= true;
	
		if($page_count < $show_page_up) {
			$show_page_up = ($show_page_down - 1) + ($page_count % 10);
			$next_flag = false;
		}
	
		if($show_page_down == 1) $pre_flag = false;
	
		if ($page_count == 0) {
			$html_string = $html_string . "<p>查無相關記錄!!</p>";
		} else {
			$html_string= $html_string . "<a href='javascript: void(0)' onclick='search_page_num(1)'>第一頁</a>&nbsp;&nbsp; ";
	
			if($pre_flag) $html_string= $html_string . "<a href='javascript: void(0)' onclick='search_page_num($pre_page)'>上十頁</a>&nbsp;&nbsp; ";
	
			for ($i = $show_page_down; $i <= $show_page_up; $i++) $html_string= $html_string . "<a href='javascript: void(0)' onclick='search_page_num($i)'>$i</a>&nbsp;&nbsp; ";
	
			if($next_flag) $html_string= $html_string . "<a href='javascript: void(0)' onclick='search_page_num($next_page)'>下十頁</a>&nbsp;&nbsp; ";
	
			$html_string= $html_string . "<a href='javascript: void(0)' onclick='search_page_num($page_count)'>最後頁</a>&nbsp;&nbsp; ";
		}
	
		return $html_string;
	}
}