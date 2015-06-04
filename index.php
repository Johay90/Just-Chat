<?php
include "db.php";
$conn = dbh();
date_default_timezone_set("Europe/London");

if (isset($_POST['submit'])){
  $content = htmlspecialchars($_POST['content']);

  //update users online every time submit button is pressed
  $sth = $conn->query("DELETE FROM usersonline WHERE `timestamp` < (NOW() - INTERVAL 5 MINUTE)");

  if(isset($_COOKIE['chatname']) && $_COOKIE['chatname'] != "null" && !empty($_POST['content'])){
    $name = htmlspecialchars($_COOKIE['chatname']);
    $sth = $conn->prepare("INSERT INTO chat (content, name) VALUES (:content, :name)");
    $sth->bindParam(':content', $content);
    $sth->bindParam(':name', $name);
    $sth->execute();

    // check usersonline row
    $select = $conn->prepare("SELECT * FROM usersonline WHERE name = :name");
    $select->bindParam(":name", $name);
    $select->execute();
    $row = $select->fetch(PDO::FETCH_ASSOC);

    if ($row['name'] == $name){
      $sth = $conn->prepare("UPDATE usersonline SET timestamp=:timestamp WHERE name = :name");
      $sth->bindParam(":timestamp", date("Y-m-d H:i:s"));
      $sth->bindParam(":name", $name);
      $sth->execute();
    }
    else
    {
      // insert into insertusers
      $sth = $conn->prepare("INSERT INTO usersonline (name, timestamp) VALUES (:name, :timestamp)");
      $sth->bindParam(":name", $name);
      $sth->bindParam(":timestamp", date("Y-m-d H:i:s"));
      $sth->execute();
    }

    // del old records from chat table
    $del = $conn->prepare("DELETE FROM `chat`
                          WHERE id NOT IN (
                          SELECT id
                          FROM (
                          SELECT id
                          FROM `chat`
                          ORDER BY id DESC
                          LIMIT 35
                          ) chatalias
                          );");
    $del->execute();
  }
  elseif(!isset($_COOKIE['chatname']) || $_COOKIE['chatname'] == "null")
  {
    setcookie("chatname", "", time()-3600);
    die();
  }
}
?>


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
    <script>
    function reload_content() {
    $('#contentul').load('index.php #content-box li');
    }
    window.setInterval(reload_content, 1000);
    </script>
    <ul id="contentul">
      <?php //ASC
      $sth = $conn->prepare("SELECT * FROM chat ORDER BY id DESC");
      $sth->execute();
      $rows = $sth->fetchAll();

      foreach ($rows as $row) {
        echo   "<li>[" . $row['name'] . "]: " . $row['content'] . "</li>";
      }
      ?>
    </ul>


  </div>
  <div id="userlist">
    <script>
    function reload_content() {
    $('#ulonline').load('index.php #userlist li');
    }
    window.setInterval(reload_content, 1000);
    </script>
    <ul id="ulonline">
    <li id="users">Currently Online</li>

    <?php
    $sth = $conn->prepare("SELECT * FROM usersonline ORDER BY id");
    $sth->execute();
    $online = $sth->fetchAll();

    foreach ($online as $row) {
      echo   "<li>" . $row['name'] . "</li>";
    }?>
    </ul>


  </div>
  <div id="post-content">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
      <input type="text" name="content" autocomplete="off" autofocus> <input type="submit" name="submit" value="Post">
      <span id="terms">By clicking post you agree to the <a href="#">Terms and Conditions</a> of our website, and by not obeying them accept the consequences</span>
    </form>
  </div>

</main>

</body>

</html>
