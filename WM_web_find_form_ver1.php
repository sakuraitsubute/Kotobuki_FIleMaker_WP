<DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="Content-Style-Type" content="text/css">
  <title>在庫検索のテスト</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="css/WM_web_find_test_html.css">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
 <script src="js/WM_web_find_form.js"></script>
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
  <h1>在庫検索</h1>
  <hr>
  <form action="WM_web_find_ver1.php" method="POST">
    <div>
    <label for="find_word">整理ナンバー or 受注ナンバー or 得意先名 or エンドユーザー名を入力</label>
   <p> <input type="text" name="find_word" class="text"></p>
   <label for="title">タイトル名を入力</label>
   <p><input type="text" name="title" class="text"></p>
   <p><input type = "checkbox" name = "zerocheck" value = "1">在庫ゼロを表示する</p>
   
   <p>
     <input type="checkbox" name="unknown" id= "unknown" value="不明"><label for="unknown">不明商品</label>
  </p>

   <div class="div_submit">
    <input type="submit" name="submit" value="検索" class="submit_button"    >
   </div>
   
    
  </div>
  
  <!---
  <div>
    <label for="member_name">名前</label>
    <input type="text" name="member_name">
    <input type="submit" value="送信">
  </div>
--->
  </form>
  <p>
    <button type="button" class="close">閉じる</button>
  </p>
</body>
</html>
<?php
}
?>