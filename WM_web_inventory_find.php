<DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="Content-Style-Type" content="text/css">
    <title>棚卸しのテスト</title>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
 <!-- <script src="js/PaginateMyTable.js"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
  <script src="js/WM_web_inventory_find.js?1038"></script>
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="css/WM_web_inventory_find.css">
  <link rel="stylesheet" href="css/tablesorter/theme.green.css">
  
  <!--<link rel="stylesheet" href="css/PaginateMyTable.css"> -->
  
 

  </head>
  <body>

  <?php
 //ini_set('display_errors',1);

session_start();
if(!$_SESSION['userid']){
  echo '<h1>ログインしてください</h1>';
  echo' <p><button type="button" id="close">閉じる</button></p>';
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

$find1w = $fm->newFindcommand('棚卸し編集');
$find1w->addFindCriterion('棚卸し日付', '>'.date('m/d/Y', strtotime('-1 week')));
$find1w->addSortRule('作成情報タイムスタンプ', 1, FILEMAKER_SORT_DESCEND);
$result = $find1w->execute();

if(FileMaker::isError($result)){
  echo '<h1>直近1週間の棚卸しはありません</h1>';
  echo' <p><button type="button" id="close">閉じる</button></p>';
}else{
  $record = $result->getFirstRecord();
  $date = $record->getField('棚卸し日付');

  $findCommand = array();
  $findCommand[0] = $fm->newFindRequest('棚卸し編集');
  $findCommand[0]->addFindCriterion('棚卸し日付', date('m/d/Y', strtotime('-1 week', strtotime($date))) .'...'.date('m/d/Y', strtotime($date)));

 $findCommand[1] = $fm->newFindRequest('棚卸し編集');
 $findCommand[1]->addFindCriterion('削除フラグ',1);
 $findCommand[1]->setOmit(true);

 $compoundFind = $fm->newCompoundFindCommand('棚卸し編集');
 $compoundFind->add(1, $findCommand[0]);
 $compoundFind->add(2, $findCommand[1]);

  $result2 = $compoundFind->execute();
  if(FileMaker::isError($result2)){
    echo '<h1>直近1週間の棚卸しはありません</h1>';
    echo' <p><button type="button" id="close">閉じる</button></p>';
  }else{
    $records = $result2->getRecords();



?>

<h1>棚卸しリスト</h1>
<p>直近一週間の棚卸しデータを表示しています</p>

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
<!--
<input type="text" id="tokui_find" placeholder="得意先で絞り込み">
<input type="text" id="user_find" placeholder="ユーザーで絞り込み">
<input type="text" id="title_find" placeholder="タイトルで絞り込み">
<button type="button" id="find_button">検索</button>
-->
</p>


<div id="inventory_table">

  <table border="1" id="mytable" class="tablesorter-green">
    <thead>
    <tr>
      <th>棚卸し日付</th>
      <th>整理番号</th>
      <th>受注番号</th>
      <th>得意先名</th>
      <th>ユーザー名</th>
      <th>タイトル名</th>
      <th>入数</th>
      <th>棚番</th>
      <th>ケース数</th>
      <th>差異</th>
      <th>削除</th>
     </tr>
     </thead>


    <tbody>
    <?php
      foreach($records as $record){
        echo '<tr data-href="WM_web_inventory_revision_form.php?id='.$record->getField('c_レコードID').'"><input type="hidden" name="recordid" class="recordid" value="'.$record->getField('c_レコードID').'">';
        echo '<td>'. date('Y/m/d', strtotime($record->getField('棚卸し日付'))). '</td>';
        echo '<td>'. $record->getField('整理番号'). '</td>';
        echo '<td>'. $record->getField('受注番号'). '</td>';
        echo '<td>'. $record->getField('棚卸し_dbo.findview::得意先名');
        echo '<td>'. $record->getField('棚卸し_dbo.findview::ユーザー名');
        echo '<td>'. $record->getField('棚卸し_dbo.findview::タイトル名');
        echo '<td>'. $record->getField('入数');
        echo '<td>'. $record->getField('棚番');
        echo '<td>'. $record->getField('ケース数');
        echo '<td>'. $record->getField('c_棚卸し在庫数比較');
        echo '<td class="button"><button type="button" class="delete_button">削除</button>';
        echo '</tr>';
          }
    ?>
    </tbody>
  </table>
 
</div>


<p>
    <button type="button" id="close">閉じる</button>
</p>
<?php
  }
}
}
?>

</body>
</html>