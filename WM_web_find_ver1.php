<!DOCTYPE html>
  <head>
  <meta charset="utf-8">
  <meta name="Content-Style-Type" content="text/css">
  <title>在庫検索のテスト</title>
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

 <!-- 在庫検索のjs -->
  <script src="js/WM_web_find_php.js?1547"></script>
   <!-- paginateのjs -->
  <script src="js/PaginateMyTable.js"></script>
   <!-- tablesorterのjs -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>
  
  <!-- tablesorterのcss -->
  <link rel="stylesheet" href="css/tablesorter/theme.green.css?1631">
   <!-- paginateのcss -->
  <link rel="stylesheet" href="css/PaginateMyTable.css">
   <!-- JQueryUIのcss -->
<link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!--  <link rel="stylesheet" href="css/WM_web_find_test_php.css"> -->
</head>


<body>
<?php

 //ini_set('display_errors',1);
session_start();
if(!$_SESSION['userid']){
  die('<h1>ログインしてください</h1>');
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

//フォームからもらった値をエスケープ
$find_word = htmlspecialchars($_POST['find_word'], ENT_QUOTES, 'UTF-8');
$zerocheck = htmlspecialchars($_POST['zerocheck'], ENT_QUOTES, 'UTF-8');
$unknown = htmlspecialchars($_POST['unknown'], ENT_QUOTES, 'UTF-8');
$title = htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8');


if(!empty($unknown)){
  $findCommand = $fm->newFindCOmmand('在庫検索_リスト');
  $findCommand->addFindCriterion('受注番号不明', $_POST['unknown']);
  if(empty($zerocheck)){
    $findCommand->addFindCriterion('現在ケース数', '>0');
  }//'現在ケース数'

  $findCommand->addSortRule('棚番', 1, FILEMAKER_SORT_ASCEND);
  $result = $findCommand->execute();

}else{



//値が数字か文字列かで場合分けして、データベース内を検索
if(is_numeric($find_word)){
  //入力が数字だった場合
  //タイトルは無視する

  $findCommandA = array();

  $findCommandA[0] = $fm->newfindRequest('在庫検索_リスト');
  $findCommandA[0]->addFindCriterion('整理番号', $find_word);
  if(empty($zerocheck)){
    $findCommandA[0]->addFindCriterion('現在ケース数', '>0');
  }//'現在ケース数'

  $findCommandA[1] = $fm->newfindRequest('在庫検索_リスト');
  $findCommandA[1]->addFindCriterion('oldフラグ', 1);
  $findCommandA[1]->setOmit(true);

  $compoundFindA = $fm->newCompoundFindCommand('在庫検索_リスト');
  $compoundFindA->add(1, $findCommandA[0]);
  $compoundFindA->add(2, $findCommandA[1]);
  
  
  $result = $compoundFindA->execute();

}else if(empty($find_word) & $title){
  //タイトルだけ入力された場合の処理

  $findrequest = array();
  $findrequest[0] = $fm->newFindRequest('在庫検索_リスト');
  $findrequest[0]->addFindCriterion('在庫検索画面dbo.findview::タイトル名', $title);
  if(empty($zerocheck)){
    $findrequest[0]->addFindCriterion('現在ケース数', '>0');
  };

  $findrequest[1] =  $fm->newFindRequest('在庫検索_リスト');
  $findrequest[1]->addFindCriterion('oldフラグ', 1);
  $findrequest[1]->setOmit(true);

  $compoundFind = $fm->newCompoundFindCommand('在庫検索_リスト');
  $compoundFind->add(1, $findrequest[0]);
  $compoundFind->add(2, $findrequest[1]);

  $result = $compoundFind->execute();

  

}else if(is_string($find_word) and empty($title)){
  //得意先名もしくはユーザー名、受注番号のみで検索する場合
  //CompoundFindは新規検索条件を追加しての検索
  $orderO = array("AO-", "BO-", "CO-", "DO-", "EO-", "FO-", "GO-", "HO-", "IO-", "JO-", "KO-", "LO-", "MO-", "NO-", "OO-", "PO-", "QO-", "RO-", "SO-", "TO-", "UO-", "VO-", "WO-", "XO-", "YO-", "ZO-");
  $order0 = array("A0-", "B0-", "C0-", "D0-", "E0-", "F0-", "G0-", "H0-", "I0-", "J0-", "K0-", "L0-", "M0-", "N0-", "O0-", "P0-", "Q0-", "R0-", "S0-", "T0-", "U0-", "V0-", "W0-", "X0-", "Y0-", "Z0-");

  $find_word = str_replace($orderO, $order0, $find_word);

  $findrequest = array();
  $findrequest[0] = $fm->newFindRequest('在庫検索_リスト');
  $findrequest[0]->addFindCriterion('在庫検索画面dbo.findview::得意先名', $find_word)
;
if(empty($zerocheck)){
  $findrequest[0]->addFindCriterion('現在ケース数', '>0');
}

  $findrequest[1] = $fm->newFindRequest('在庫検索_リスト');
  $findrequest[1]->addFindCriterion('在庫検索画面dbo.findview::ユーザー名', $find_word);
  if(empty($zerocheck)){
    $findrequest[1]->addFindCriterion('現在ケース数', '>0');
  }

  $findrequest[2] = $fm->newFindRequest('在庫検索_リスト');
  $findrequest[2]->addFindCriterion('受注番号', $find_word);
  if(empty($zerocheck)){
    $findrequest[2]->addFindCriterion('現在ケース数', '>0');
  }

  $findrequest[3] = $fm->newfindRequest('在庫検索_リスト');
  $findrequest[3]->addFindCriterion('oldフラグ', 1);
  $findrequest[3]->setOmit(true);
  

  $compoundFind = $fm->newCompoundFindCommand('在庫検索_リスト');
  $compoundFind->add(1, $findrequest[0]);
  $compoundFind->add(2, $findrequest[1]);
  $compoundFind->add(3, $findrequest[2]);
  $compoundFind->add(4, $findrequest[3]);

  $result = $compoundFind->execute();
}else if(is_string($find_word) and !empty($title)){
  //得意先名もしくはユーザー名とタイトルが入力された場合
  //addFindCriterionは同一検索条件内での検索項目追加

  $findrequest = array();
  $findrequest[0] = $fm->newFindRequest('在庫検索_リスト');
  $findrequest[0]->addFindCriterion('在庫検索画面dbo.findview::得意先名', $find_word);
  $findrequest[0]->addFindCriterion('在庫検索画面dbo.findview::タイトル名', $title);
  if(empty($zerocheck)){
    $findrequest[0]->addFindCriterion('現在ケース数', '>0');
  }//'在庫_HT情報_入庫::c_現在ケース数'

  $findrequest[1] = $fm->newFindRequest('在庫検索_リスト');
  $findrequest[1]->addFindCriterion('在庫検索画面dbo.findview::ユーザー名', $find_word);
  $findrequest[1]->addFindCriterion('在庫検索画面dbo.findview::タイトル名', $title);
  if(empty($zerocheck)){
    $findrequest[1]->addFindCriterion('現在ケース数', '>0');
  }//'在庫_HT情報_入庫::c_現在ケース数'

  //$findrequest[2] = $fm->newFindRequest('入力メニュー');
  //$findrequest[2]->addFindCriterion('ユーザー2', $find_word);
  //$findrequest[2]->addFindCriterion('タイトル名', $title);

  $findrequest[2] = $fm->newfindRequest('在庫検索_リスト');
  $findrequest[2]->addFindCriterion('oldフラグ', 1);
  $findrequest[2]->setOmit(true);


  $compoundFind = $fm->newCompoundFindCommand('在庫検索_リスト');
  $compoundFind->add(1, $findrequest[0]);
  $compoundFind->add(2, $findrequest[1]);
  $compoundFind->add(3, $findrequest[2]);

  //$compoundFind->addSortRule('最新加工年月日', 1, FILEMAKER_SORT_DESCEND);
  $result = $compoundFind->execute();

}

}
if(FileMaker::isError($result)){
  //エラー処理
  echo '<div class="error">FileMaker Error Code:'. $result->getCode();
  echo '<p>'. $result->getMessage(). '</p>';
  echo '<a href="WM_web_find_form_ver1.php">検索画面へ</a></div>';
}else{
  //正常処理
  $records = $result->getRecords();
  
  ?>
 

<h1>検索結果</h1>
  
  <table border="1" id="mytable" class="tablesorter-green">
    <thead>
    <tr>
    <th>ピッキング</th> 
    <th>整理番号</th>
    <th>受注番号</th>
    <th>得意先名</th>
    <th>ユーザー名</th>
    <th>タイトル名</th>
    <th>棚番</th>
    <th>ケース数</th>
    <th>入数</th>
    <th>オーダー未記入・誤記入</th>
    <th>備考</th>
    </tr>
    </thead>
    
  <tbody>

  <?php
  $list[$key] =array(); 
  $key = 0;
  //getFieldでフィールド内容を取得
    foreach($records as $record){
    $list[$key] = array(
      'seiri'=>$record->getField('整理番号'),
      'order'=>$record->getField('受注番号'),
      'tana'=>$record->getField('棚番')
    );
    $json[$key] = json_encode($list[$key], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo '<tr id="tr'.$key.'">';
    echo '<td><input type="number" class="case_amount" name="case_amount" size="4" maxlength="4" placeholder="出荷するケース数"><button class="ajax_button">送信</button>
    <input type ="hidden" class="seiri" name="seiri" value="'.$record->getField('整理番号').'">
    <input type ="hidden" class="order" name="order" value="'.$record->getField('受注番号').'">
    <input type ="hidden" class="tana" name="tana" value="'.$record->getField('棚番').'">
    </td>';
    echo '<td>'. $record->getField('整理番号'). '</td>';
    echo '<td>'. $record->getField('受注番号'). '</td>';
    echo '<td>'. $record->getField('在庫検索画面dbo.findview::得意先名'). '</td>';
    echo '<td>'. $record->getField('在庫検索画面dbo.findview::ユーザー名'). '</td>';
    echo '<td>'. $record->getField('在庫検索画面dbo.findview::タイトル名'). '</td>';
    echo '<td>'. $record->getField('棚番'). '</td>';
    echo '<td class="case_now">'. $record->getField('現在ケース数').'</td>';
    echo '<td class="amount">'. $record->getField('入数'). '</td>';
    echo '<td>'. $record->getField('在庫_棚卸しリスト::オーダー未記入'). '</td>';
    echo '<td>'. $record->getField('備考'). '</td>';
    
    /*
    $portals = $record->getRelatedSet('在庫検索画面_HT情報_入庫');//'在庫_HT情報_入庫'
    foreach($portals as $portal){
      if($portal->getField('在庫検索画面_HT情報_入庫::c_現在庫数') == 0 or $tana == $portal->getField('在庫検索画面_HT情報_入庫::棚番_入庫')){
        continue;
      }else{
        echo '<td>'. $portal->getField('在庫検索画面_HT情報_入庫::棚番_入庫'). '</td>';
        echo '<td>'. $portal->getField('在庫検索画面_HT情報_入庫::c_現在数量'). '</td>';
        echo '<td>'. $portal->getField('在庫検索画面_HT情報_入庫::c_現在庫数'). '</td>';
        $tana = $portal->getField('在庫検索画面_HT情報_入庫::棚番_入庫');
      }//'在庫_HT情報_入庫'
      
    }
    
   */
    
    //echo '<td>'. $record->getField('在庫検索画面_HT情報_入庫::棚番_入庫'). '</td>';
    //echo '<td>'. $record->getField('在庫検索画面_HT情報_入庫::数量_入庫'). '</td>';
    //echo '<td>'. $record->getField('在庫検索画面_HT情報_入庫::ケース数_入庫'). '</td>';
    //echo '<td>'. $list[$key]. '</td>';
    //echo '<td><a href="web_find_test2.php?list='. $list[$key]. '">加工履歴</a>';
    echo '</tr>';
    $key ++;
  }

  ?>
  </tbody>
  </table>
  <a href="WM_web_find_form_ver1.php">検索画面へ</a>
  <?php
}

}

?>
</body>
</html>