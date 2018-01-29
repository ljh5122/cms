<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>首页模板</title>
</head>
<body>
	<h1>首页模板</h1>
	<table>
		<tr><th>用户名</th><th>创建时间</th></tr>
		<?php
			foreach($admin as $key => $val){
				echo '<tr><td>'.$val['name'].'</td><td>'.date('Y-m-d H:i:s',$val['ctime']).'</td></tr>';
			}
		?>
	</table>
	
</body>
</html>