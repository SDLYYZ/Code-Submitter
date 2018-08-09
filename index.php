<?php 
  require("config.php");
  if (!isset($_COOKIE["login"])) {
    Header("HTTP/2.0 302");
    Header("Location: ./login.php");
  }
?>
<!DOCTYPE html>
<html>
<head>
  <title>Submit your code</title>
  <style type="text/css"> body { font-family: serif; }</style>
</head>
<body>
  <h1>Code Submitter</h1>
  <h3 for="answer">Submit your code here, please make sure that your filename is correct.</h3>
  <h4>Currently supported *.cpp for C++, *.c for C and *.pas for Pascal.</h4>
  <form action="./upload.php" enctype="multipart/form-data" method="POST">
    <div style="margin: 10px 10px 10px 10px; border: 1px solid #acacac; width: 300px">
      <input type="file" id="answer" name="answer">
    </div>
    <br>
    <input type="submit" value="Submit">
  </form>
  <p>Currently logged in as <b><script type="text/javascript">document.write(document.cookie.split("%22")[1]);</script></b>.</p>
  <script type="text/javascript">
    function sleep(d){
      for (var t = Date.now();Date.now() - t <= d;);
    }
    function check_login() {
      var xhr;
      if (window.XMLHttpRequest) {
        xhr = new XMLHttpRequest();
      } else {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
      xhr.open("GET", "login.php?action=check", false);
      xhr.send();
      sleep(3000);
      if (xhr.responseText != "OK") {
        alert("Cookie expired, please relogin.");
        window.location.href = "login.php";
      }
    }
    window.setInterval('check_login()', 10000); 
  </script>
  <div style="position: absolute; bottom: 0;">
    <b>Online Judge by <a href="https://men.ci/">Menci</a> & Code Submitter by <a href="https://imvictor.tech/">Victor_Huang</a>, <a href="https://github.com/SDLYYZ/Code-Submitter">GitHub Repository</a>.</b>
  </div>
</body>
</html>
