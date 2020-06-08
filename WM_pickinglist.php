<!DOCTYPE html>
  <head>
  <meta charset="utf-8">
  <meta name="Content-Style-Type" content="text/css">
  <title>ピッキングリスト</title>
  <meta name="viewport" content="width=device-width">
  <!-- JQueryとJQueryUIのCDN -->
  <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>

  <!-- ピッキングリストのjs -->
  <script src="js/WM_pickinglist_php.js?1656"></script>
  <!-- paginateのjs 
  <script src="js/PaginateMyTable.js"></script>-->
   <!-- tablesorterのCDN -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>

  <meta name="viewport" content="width=device-width">
  <!-- tablesorterのcss -->
  <link rel="stylesheet" href="css/tablesorter/theme.green.css?1609">
   <!-- paginateのcss
  <link rel="stylesheet" href="css/PaginateMyTable.css"> -->
   <!-- JQueryUIのcss -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" href="css/WM_pickinglist_php.css">
</head>


<body>
<?php

//ini_set('display_errors',1);

session_start();
if(!$_SESSION['userid']){
  echo '<h1>ログインしてください</h1>';
  
}else{
//タイムゾーンを日本に設定
date_default_timezone_set('Asia/Tokyo');
//FileMakerのクラスを使えるようにする
require_once('FileMaker.php');


$userid = $_SESSION['userid'];
$password = $_SESSION['password'];

//データベース名・ホスト・アカウントを定義
$fm = new FileMaker();
$fm->setProperty('database', '倉庫管理テスト_0330');
$fm->setProperty('hostspec', 'http://192.168.0.73');
$fm->setProperty('username', $userid);
$fm->setProperty('password', $password);

$findCommand[0] = $fm->newFindRequest('出庫手入力');
$findCommand[0]->addFindCriterion('出庫フラグ','1');

$findCommand[1] = $fm->newFindRequest('出庫手入力');
$findCommand[1]->addFindCriterion('削除フラグ','1');


$CompoundFind = $fm->newCompoundFindCommand('出庫手入力');
$CompoundFind-> add(1, $findCommand[0]);
$CompoundFind-> add(2, $findCommand[1]);


$CompoundFind->addSortRule('受注番号', 1, FILEMAKER_SORT_ASCEND);
$CompoundFind->setPreSortScript('PHP_setOmit');
$result = $CompoundFind->execute();

if(FileMaker::isError($result)){
  //出庫リストに未出庫のレコードがない
  echo "<h1>ピッキングリストがありません</h1>";
  echo 'FileMaker Error Code:'. $result->getCode();
  echo '<p>'. $result->getMessage(). '</p>';
}else{
  //正常処理
  $records = $result->getRecords();
  ?>

<h1>ピッキングリスト</h1>

<p class="select">
<select name="order" id="order_select">
<option value="">営業コードで絞り込み</option>
<option value="A0">A0</option>
<option value="B0">B0</option>
<option value="D0">D0</option>
<option value="F0">F0</option>
<option value="G0">G0</option>
<option value="H0">H0</option>
<option value="I0">I0</option>
<option value="K0">K0</option>
<option value="N0">N0</option>
<option value="P0">P0</option>
<option value="Q0">Q0</option>
<option value="R0">R0</option>
<option value="T0">T0</option>
<option value="V0">V0</option>
<option value="W0">W0</option>
<option value="Z0">Z0</option>
</select>
</p>
<div>
<table border="1" id="mytable" class="tablesorter-green">
<thead>
<tr>
<th>QRコード読込</th>
<th>受注番号</th>
<th>整理番号</th>
<th>得意先名</th>
<th>ユーザー名</th>
<th>タイトル名</th>
<th>入数</th>
<th>ケース数</th>
<th>棚番</th>
<th class="qrcode">QRコード</th>
<th>削除</th>
</tr>
</thead>
<tbody>

<?php
$key = 0;
foreach($records as $record){
echo '<tr id="tr'.$key. '">';
echo '<td><input type="text" class="qr" placeholder="QRコード読み取り"><button type="button" class="ajax_button">出庫</button>';
echo '<td class="order">'.$record->getField('受注番号').'</td>';
echo '<td class="seiri">'.$record->getField('整理番号').'</td>';
echo '<td>'.$record->getField('手入力_dbo.findview::得意先名').'</td>';
echo '<td>'.$record->getField('手入力_dbo.findview::ユーザー名').'</td>';
echo '<td>'.$record->getField('手入力_dbo.findview::タイトル名').'</td>';
echo '<td class="amount">'.$record->getField('入数_出庫').'</td>';
echo '<td class="case_amount">'.$record->getField('ケース数_出庫').'</td>';
echo '<td class="tana">'.$record->getField('棚番_出庫').'</td>';
$qr_order = explode('-', $record->getField('受注番号'));
$qr_text = $record->getField('整理番号').' '.$record->getField('整理枝番').' '.$qr_order[0].' '.$qr_order[1].' '.$record->getField('入数_出庫');
$data = urlencode($qr_text);
echo '<td class="qrcode"><img src="qrcode_test.php?data='.$data.'" width="60" height="60"></td>';
echo '<td><input type="hidden" class="recordid" value="'.$record->getField('c_レコードID').'"><button type=button class="delete_button"> 削除</button></td>';
echo '</tr>';
$key ++;

}



?>
</tbody>

</table>
</div>



<?php
}

}
?>
 <p>
    <button type="button" id="close">閉じる</button>
  </p>
</body>
</html>