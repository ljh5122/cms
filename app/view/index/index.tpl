<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>首页模板</title>
</head>
<body>
	<h1>首页模板</h1>
	<?php
		foreach($admin as $key => $val){
			echo '<h2>'.$val['name'].'</h2>';
		}
	?>
</body>
</html>