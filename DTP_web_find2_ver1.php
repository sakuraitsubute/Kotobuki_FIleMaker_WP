<DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="Content-Style-Type" content="text/css">
    <title>加工履歴</title>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  <script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
  crossorigin="anonymous"></script>
  <script src="js/PaginateMyTable.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
  <link rel="stylesheet" href="css/tablesorter/theme.green.css?1609">
  <link rel="stylesheet" href="css/PaginateMyTable.css">
   <!-- <link rel="stylesheet" href="css/web_find_test2_php.css"> -->
    <meta name="viewport" content="width=device-width">
  </head>
  <body>


<?php
session_start();
if(!$_SESSION['userid']){
  die('<h1>ログインしてください</h1>');
}else{

require_once('FileMaker.php');


$fm = new FileMaker();
$fm->setProperty('database', '190719_DTP_order_list');
$fm->setProperty('hostspec', 'http://192.168.0.73');
$fm->setProperty('username', 'Web');
$fm->setProperty('password', 'tEL6728061');


//加工履歴レイアウトで検索しますよ、という宣言
$findCommand = $fm->newFindCommand('加工履歴');

//web_find_test.phpからリストNo.を取得したものをURLデコード
$list = urldecode($_GET['list']);

//リストNo.で検索、加工年月日の降順でソート
$findCommand->addFindCriterion('リストNo.', $list);
$findCommand->addSortRule('加工年月日', 1, FILEMAKER_SORT_DESCEND);
$findCommand->addSortRule('入力時刻', 2, FILEMAKER_SORT_DESCEND);
$result= $findCommand->execute();

if(FileMaker::isError($result)){
  echo 'FileMaker Error Code:'. $result->getCode();
  echo '<p>'. $result->getMessage(). '</p>';
}else{
  $records = $result->getRecords();
  ?>
  <h1>加工履歴</h1>
  <table border="1" id="mytable" class="tablesorter-green">
  <thead>
  <tr>
  <th>加工年月日</th>
  <th>営業</th>
  <th>受注No.</th>
  <th>加工区分</th>
  <th>作業区分</th>
  <th>外注手配先</th>
  <th>申し送り事項</th>
  <th>最終処理担当</th>
  <th>最終処理</th>
  </tr>
  </thead>
  <tbody>
<?php
foreach($records as $record){
  echo '<tr>';
  echo '<td>'. date('Y/m/d', strtotime($record->getField('加工年月日'))). '</td>';
  echo '<td>'. $record->getField('営業担当').'</td>';
  echo '<td>'. $record->getField('受注No.').'</td>';
  echo '<td>'. $record->getField('加工区分').'</td>';
  echo '<td>'. $record->getField('作業区分').'</td>';
  echo '<td>'. $record->getField('外注手配先').'</td>';
  echo '<td>'. $record->getField('申し送り事項').'</td>';
  echo '<td>'. $record->getField('最終処理担当').'</td>';
  echo '<td>'. $record->getField('最終処理').'</td>';
  echo '</tr>';
}
  ?>  
  </tbody>
  </table>
  <a href="javascript:history.back()">前に戻る</a>
 
<?php
echo '<p><a href="DTP_web_find_form_ver1.php">検索画面へ</a></p>';
}
}//session
?>
<script>
$(document).ready(function(){
  $('#mytable').tablesorter();
});
$(document).ready(function(){
  $('#mytable').paginate();
})
</script>
</body>
</html>