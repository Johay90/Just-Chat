<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Live Chat Platform</title>
  <link rel="Stylesheet" href="style.css">
  <link href='http://fonts.googleapis.com/css?family=Ubuntu:300' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Luckiest+Guy' rel='stylesheet' type='text/css'>
  <link href='http://fonts.googleapis.com/css?family=Open+Sans:300' rel='stylesheet' type='text/css'>
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>

<script>
$(document).ready(function(){
  if (document.cookie.indexOf("chatname") < 0)
    {
          var chatname = prompt("Please enter your chat name", "");
          var now = new Date();
          var time = now.getTime();
          time += 3600 * 1000;
          now.setTime(time);
          document.cookie = "chatname =" + chatname + "; expires=" + now.toUTCString();
    }
  });
</script>
<body>

  <main>

  <div id="content-box">
    <ul>
    <li>[johay]: writing a line here etc writing a line here etc writing a line here etc writing a line here etc writing a line here etc writing a line here etc writing a line here etc writing a line here etc </li>
    </ul>


  </div>
  <div id="userlist">
    <ul>
    <li id="users">CURRENTLY ONLINE</li>
    <li>USER2</li>
    </ul>


  </div>
  <div id="post-content">
    <input type="text" name="content"> <input type="submit" value="Post">
    <span id="terms">By clicking post you agree to the <a href="#">Terms and Conditions</a> of our website, and by not obeying them accept the consequences</span>
  </div>

</main>

</body>

</html>
