<DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="Content-Style-Type" content="text/css">
    <title>DTP_検索結果</title>
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
    <!--<link rel="stylesheet" href="css/web_find_test_php.css">-->
    <meta name="viewport" content="width=device-width">
  </head>
  <body>

<?php

session_start();
header('Expires:-1');
header('Cache-Control:');
header('Pragma:');
if(!$_SESSION['userid']){
  die('<h1>ログインしてください</h1>');
}else{
//タイムゾーンを日本に設定
date_default_timezone_set('Asia/Tokyo');
//FileMakerのクラスを使えるようにする
require_once('FileMaker.php');




//データベース名・ホスト・アカウントを定義
$fm = new FileMaker();
$fm->setProperty('database', '190719_DTP_order_list');
$fm->setProperty('hostspec', 'http://192.168.0.73');
$fm->setProperty('username', 'web');
$fm->setProperty('password', 'tEL6728061');

//フォームからもらった値をエスケープ
$find_word = htmlspecialchars($_POST['find_word'], ENT_QUOTES, 'UTF-8');
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');
$start = htmlspecialchars($_POST['start_date'], ENT_QUOTES, 'UTF-8');
$end = htmlspecialchars($_POST['end_date'], ENT_QUOTES, 'UTF-8');

if($start and $end){
  $start_vali = date('m/d/Y', strtotime(str_replace('-','/', $start))) ;
  $end_vali = date('m/d/Y', strtotime(str_replace('-','/', $end))) ;
  $season = $start_vali.'...'.$end_vali;
}


//値が数字か文字列かで場合分けして、データベース内を検索
if(is_numeric($find_word)){
  $number = 1;
  //入力が数字だった場合
  //タイトルは無視する

  $findCommandA = $fm->newfindCommand('入力メニュー');
  $findCommandA->addFindCriterion('整理No.', $find_word);
  $findCommandA->addFindCriterion('最新加工年月日', $season);
  $findCommandA->addSortRule('最新加工年月日',1 , FILEMAKER_SORT_DESCEND);
  $result = $findCommandA->execute();
  

}else if(empty($find_word) and !empty($title)){
  $number = 2;
  //タイトルだけ入力された場合の処理

  $findCommandA = $fm->newfindCommand('入力メニュー');
  $findCommandA->addFindCriterion('タイトル名', $title);
  $findCommandA->addFindCriterion('最新加工年月日', $season);
  $findCommandA->addSortRule('最新加工年月日',1 , FILEMAKER_SORT_DESCEND);
  $result = $findCommandA->execute();
 


  //echo '<div class="empty">整理ナンバー・得意先名・エンドユーザー名のいずれかを入力してください。';
  //echo '<p><a href="DTP_web_find_form_kaihatsu.php">検索画面へ</a></p></div>';

}else if(is_string($find_word) and !$title){
  $number = 3;
  //得意先名もしくはユーザー名のみで検索する場合
  //CompoundFindは新規検索条件を追加しての検索

  $findrequest = array();
  $findrequest[0] = $fm->newFindRequest('入力メニュー');
  $findrequest[0]->addFindCriterion('得意先名', $find_word);
  $findrequest[0]->addFindCriterion('最新加工年月日', $season);

  $findrequest[1] = $fm->newFindRequest('入力メニュー');
  $findrequest[1]->addFindCriterion('ユーザー1', $find_word);
  $findrequest[1]->addFindCriterion('最新加工年月日', $season);

  $findrequest[2] = $fm->newFindRequest('入力メニュー');
  $findrequest[2]->addFindCriterion('ユーザー2', $find_word);
  $findrequest[2]->addFindCriterion('最新加工年月日', $season);

  $findrequest[3] = $fm->newFindRequest('入力メニュー');
  $findrequest[3]->addFindCriterion('order_list_B::最新受注No.', $find_word);
  $findrequest[3]->addFindCriterion('最新加工年月日', $season);

  $compoundFind = $fm->newCompoundFindCommand('入力メニュー');
  $compoundFind->add(1, $findrequest[0]);
  $compoundFind->add(2, $findrequest[1]);
  $compoundFind->add(3, $findrequest[2]);
  $compoundFind->add(4, $findrequest[3]);

  $compoundFind->addSortRule('最新加工年月日', 1, FILEMAKER_SORT_DESCEND);
  $result = $compoundFind->execute();

}else if(is_string($find_word) and $title){
  $number = 4;
  //得意先名もしくはユーザー名とタイトルが入力された場合
  //addFindCriterionは同一検索条件内での検索項目追加

  $findrequest = array();
  $findrequest[0] = $fm->newFindRequest('入力メニュー');
  $findrequest[0]->addFindCriterion('得意先名', $find_word);
  $findrequest[0]->addFindCriterion('タイトル名', $title);
  $findrequest[0]->addFindCriterion('最新加工年月日', $season);

  $findrequest[1] = $fm->newFindRequest('入力メニュー');
  $findrequest[1]->addFindCriterion('ユーザー1', $find_word);
  $findrequest[1]->addFindCriterion('タイトル名', $title);
  $findrequest[1]->addFindCriterion('最新加工年月日', $season);

  $findrequest[2] = $fm->newFindRequest('入力メニュー');
  $findrequest[2]->addFindCriterion('ユーザー2', $find_word);
  $findrequest[2]->addFindCriterion('タイトル名', $title);
  $findrequest[2]->addFindCriterion('最新加工年月日', $season);

  $findrequest[3] = $fm->newFindRequest('入力メニュー');
  $findrequest[3]->addFindCriterion('order_list_B::最新受注No.', $find_word);
  $findrequest[3]->addFindCriterion('タイトル名', $title);
  $findrequest[3]->addFindCriterion('最新加工年月日', $season);


  $compoundFind = $fm->newCompoundFindCommand('入力メニュー');
  $compoundFind->add(1, $findrequest[0]);
  $compoundFind->add(2, $findrequest[1]);
  $compoundFind->add(3, $findrequest[2]);
  $compoundFind->add(4, $findrequest[3]);

  $compoundFind->addSortRule('最新加工年月日', 1, FILEMAKER_SORT_DESCEND);
  $result = $compoundFind->execute();

}


if(FileMaker::isError($result)){
  //エラー処理
  echo '<div class="error">FileMaker Error Code:'. $result->getCode();
  echo '<p>'. $result->getMessage(). '</p>';
  //echo '<p>$number='.$number.'</p>';
  echo $season;
  echo '<a href="DTP_web_find_form_ver1.php">検索画面へ</a></div>';
}else{
  //正常処理
  $records = $result->getRecords();
  //echo '$number='.$number.'<br>';
  ?>
<h1>検索結果</h1>
  <table border="1" id="mytable" class="tablesorter-green">
  <thead>
    <tr>
    <th>最新加工日付</th>
    <th>整理No.</th>
    <th>タイトル</th>
    <th>得意先名</th>
    <th>ユーザー1</th>
    <th>ユーザー2</th>
    <th>リンク</th>
    </tr>
    </thead>
    <tbody>
  <?php
  $list[$key] =array(); 
  $key = 0;
  //getFieldでフィールド内容を取得
    foreach($records as $record){
    $list[$key] = $record->getField('リストNo.');
    echo '<tr>';
    echo '<td>'. date('Y/m/d', strtotime($record->getField('最新加工年月日'))). '</td>';
    echo '<td>'. $record->getField('整理No.'). '</td>';
    echo '<td>'. $record->getField('タイトル名'). '</td>';
    echo '<td>'. $record->getField('得意先名'). '</td>';
    echo '<td>'. $record->getField('ユーザー1'). '</td>';
    echo '<td>'. $record->getField('ユーザー2'). '</td>';
    //echo '<td>'. $list[$key]. '</td>';
    echo '<td><a href="DTP_web_find2_ver1.php?list='. $list[$key]. '">加工履歴</a>';
    echo '</tr>';
    $key ++;
  }

  ?>
  </tbody>
  </table>
 <a href="DTP_web_find_form_ver1.php">検索画面へ</a>
  <?php
}

}


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