<?php
	require("config.php");
	function login($username, $password) {
		$ch = curl_init();
		$post="username=".$username."&password=".$password;
		curl_setopt($ch, CURLOPT_URL, ($GLOBALS['OUTSIDE'] == 1 ? $GLOBALS['EXT_BASEURL'] : $GLOBALS['INT_BASEURL'])."/api/login");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		list($header, $body) = explode("\r\n\r\n", $result);
		preg_match("/set\-cookie:([^\r\n]*)/i", $header, $matches); 
		$cookie = $matches[1];
		Header("set-cookie: $cookie");
		$json_result = json_decode($body);
		curl_close($ch);
		return $json_result->{"error_code"};
	}
	if (array_key_exists("action", $_GET) && $_GET["action"] == "check") {
		$cookie = $_COOKIE["login"];
		$account = explode('"', $cookie);
		$username = $account[1];
		$password = $account[3];
		$stat = login($username, $password);
		echo $stat == 1 ? "OK" : $stat;
		exit;
	}
	if (isset($_POST["username"]) && isset($_POST["password"])) {
		$stat = login($_POST["username"], md5($_POST["password"]."syzoj2_xxx"));
		if ($stat != 1) {
			Header("HTTP/2.0 401");
			echo '<h1>Unauthorized</h1><br><a href="javascript:history.back();">Go Back</a>';
			exit;
		}
		Header("HTTP/2.0 302");
		Header("Location: index.php");
		exit;
	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<style type="text/css"> body { font-family: serif; }</style>
</head>
<body>
<h1>Login to <?php echo $OJ_TITLE; ?></h1>
<h3>You're currently not logged in.</h3>
<form action="login.php" method="POST">
	<span>Username:</span><input type="text" name="username"></input>
	<br>
	<span>Password:</span><input type="password" name="password" id="password_text"></input>
	<br>
	<input type="submit" value="Login"></input>
</form>
</body>
</html>