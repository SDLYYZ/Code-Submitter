<?php 
	require("config.php");
	if (!array_key_exists("answer", $_FILES)) {
		Header("HTTP/2.0 400");
		echo '<h1>Bad Request</h1><br><a href="javascript:history.back();">Go Back</a>';
		exit;
	}
	if (array_key_exists("answer", $_FILES) && $_FILES["answer"]["error"] != 0) {
		Header("HTTP/2.0 400");
		echo '<h1>Bad Request</h1><br><a href="javascript:history.back();">Go Back</a>';
		exit;
	}
	$cookie = $_COOKIE["login"];
	$sloppy_guy = 0;
	str_replace('"', '\"', $cookie);
	$cookie = urlencode($cookie);
	if ($_FILES["answer"]["size"] / 1024 > 100) {
		Header("HTTP/2.0 413");
		echo '<h1>Request Entity Too Large</h1><br><h2>Restricted to 100KB.</h2><br><a href="javascript:history.back();">Go Back</a>';
		exit;
	}
	$fname = explode(".", $_FILES["answer"]["name"]);
	$lang = "none";
	switch($fname[1]) {
		case 'c':
			$lang = "c";
			break;
		case 'cpp' || 'cc':
			$lang = "cpp";
			break;
		case 'pas':
			$lang = "pascal";
			break;
	}
	if ($lang == "none") {
		Header("HTTP/2.0 400");
		echo '<h1>Unsupported language.</h1><br><a href="javascript:history.back();">Go Back</a>';
		exit;
	}
	$result = "^.^~233";
	if (array_key_exists($fname[0], $ids)) {
		$result = shell_exec('curl -F "answer=@'.$_FILES["answer"]["tmp_name"].'" -F "language='.$lang.'" "'.($OUTSIDE==1 ? $EXT_BASEURL : $INT_BASEURL)."/problem/".$ids[$fname[0]].'/submit?contest_id='.$contest.'" --cookie "login='.$cookie.'"');
	}
	else {
		$sloppy_guy = 1;
	}
	if ($result != "^.^~233") {
		if (explode(".", $result)[0] == "Found") {
			echo '<h1>Submission finished.</h1><br><a href="javascript:history.back();">Go Back</a>';
		}
		else {
			str_replace("\r\n", "\n", $result);
			$error_code = explode("\n", $result);
			$i = 0;
			foreach ($error_code as $x) {
				echo "\n\n";
				$i++;
				if (strpos($x, '<div class="header" style="margin-bottom: 10px; ">') != false) {
					//$i++;
					break;
				}
			}
			echo '<h1>Error Response from Online Judge: </h1><br>';
			echo '<pre style="font-size: 1.2em">'.$error_code[$i].'</pre>';
			echo '<br><a href="javascript:history.back();">Go Back</a>';
		}
	}
	else {
		if ($sloppy_guy)
			echo '<h1>Submission finished.</h1><br><a href="javascript:history.back();">Go Back</a>';
		else
			echo '<h1>Unknown error.</h1><br><a href="javascript:history.back();">Go Back</a>';
	}
?>