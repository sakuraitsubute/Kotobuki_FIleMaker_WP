<DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="Content-Style-Type" content="text/css">
    <title>DTP_検索</title>
    <link rel="stylesheet" href="css/web_find_test_html.css">
    <meta name="viewport" content="width=device-width">
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="js/DTP_web_find_form.js"></script>
  </head>
  <body>
<?php
session_start();
if(!$_SESSION['userid']){
  echo '<h1>ログインしてください</h1>';
  echo '<p><button type="button" class="close">閉じる</button></p>';
  die();
}else{
  ?>


    <img src="img_box/logo.png" alt="会社のロゴ">
    <p></p>
    <h1>DTPデータベース検索</h1>
    <hr>
    <form action="DTP_web_find_ver1.php" method="POST">
      <div align="center">
      <label for="find_word">整理番号/受注番号<br>得意先名/ユーザー名<br>のいずれかを入力</label>
     <p> <input type="text" name="find_word" class="text"></p>
     <label for="title">タイトル名を入力</label>
     <p><input type="text" name="title" class="text"></p>
     <p>
     最新加工年月日
     <input type="date" name="start_date" class="text">
     〜
     <input type="date" name="end_date" class="text">
     </p>
      <input type="submit" name="submit" value="検索"  class="submit_button">
    </div>
    
    <!---
    <div>
      <label for="member_name">名前</label>
      <input type="text" name="member_name">
      <input type="submit" value="送信">
    </div>
  --->
    </form>
    <p><button type="button" class="close">閉じる</button></p>
  </body>
  </html>
  <?php
}
?>