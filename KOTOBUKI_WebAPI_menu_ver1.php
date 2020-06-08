<?php
/*ここたぶん要らんと思う
date_default_timezone_set('Asia/Tokyo');
//FileMakerのクラスを使えるようにする
require_once('FileMaker.php');


$userid = htmlspecialchars($_POST['userid']);
$password = htmlspecialchars($_POST['password']);

//$password_hash = password_hash($password, PASSWORD_DEFAULT);
//echo $password_hash;

$hash = '$2y$10$5HWSSzo8q2lKgTbPWIxUWe8ZieI5BAgghVVLQ8Edsr/aIAUPQW3r.';

if(!$userid or !$password){
  session_unset();
  die('<h1>ユーザーIDとパスワードを入力してください<h1>');
}else if(!password_verify($password, $hash)){
  session_unset();
  die('<h1>パスワードが違います<h1>');
}else{

  $fm = new FileMaker();
$fm->setProperty('database', '190719_DTP_order_list');
$fm->setProperty('hostspec', 'http://192.168.0.73');
$fm->setProperty('username', $userid);
$fm->setProperty('password', $password);




//echo $_SESSION['userid'];
//echo $_SESSION['password'];



session_start();
session_regenerate_id(true);
$_SESSION['userid'] = $userid;
if($userid){
  echo 'ユーザー：'.$userid;
}
ここまで*/

?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="Content-Style-Type" content="text/css">
  <title>KOTOBUKI_MENU</title>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="css/KOTOBUKI_WebAPI_menu_copy.css?0853">
  <link href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" rel="stylesheet">
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script src="js/KOTOBUKI_WebAPI_menu.js?0858"></script>
 
</head>

<body>
<?php
session_start();
if($_SESSION['userid']){
  echo '<div id="username">ログイン：'.$_SESSION['userid'].'</div>';
}

?>
  <img src="img_box/logo.png" alt="会社のロゴ">

  <h1>メニュー</h1>
<hr>

<p></p>
<div class="login">

 <a href="KOTOBUKI_WebAPI_login_ver1.html" class="btn-circle-flat-login">    ログイン</a>
</div>
 <div class="dtp_WMfind">
   <h3>検索</h3>
  <a href="DTP_web_find_form_ver1.php" target="_blank" class="btn-circle-flat"><i class="fas fa-search"></i>    DTP検索</a>

  <a href="WM_web_find_form_ver1.php" target="_blank" class="btn-circle-flat"><i class="fas fa-search-location"></i>    在庫検索</a>


</div>
<div class="inout_picking">
  <h3>入出庫</h3>
  <a href="WM_web_input_submit.php" target="blank" class="btn-circle-flat"><i class="fas fa-pallet"></i>    入出庫</a>
  <a href="WM_web_input_submit_move.php" target="blank" class="btn-circle-flat"><i class="fas fa-dolly-flatbed"></i>    移動</a>
  <a href="WM_pickinglist.php" target="blank" class="btn-circle-flat"><i class="fas fa-list"></i>    ピッキングリスト</a>
  
</div>
<h3>棚卸し</h3>
<div class="inventory">
<a href="WM_web_inventory_form.php" target="blank" class="btn-circle-flat"><i class="fas fa-warehouse"></i>    棚卸し</a>
<a href="WM_web_inventory_find.php" target="blank" class="btn-circle-flat"><i class="fas fa-list-alt"></i>    棚卸しリスト</a>

</div>
<p class="logout_close">
  <button type="button" class="logout">ログアウト</button>
</p>
  
</body>
</html>
