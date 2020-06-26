/*
2020/06/26 入庫と出庫の位置を入れ替え、ラベルの値を「移動元」「移動先」に変更
*/

<DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta name="Content-Style-Type" content="text/css">
    <title>移動情報入力</title>
    <script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>
  
  <meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="css/WM_web_input_submit_move.css">
<script src="js/WM_web_input_submit_move.js?1313"></script>
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
    
    <p class="pc">テスト用に入力するときは、</p>
    <p class="pc"> 「整理番号 整理枝番 受注番号のハイフンを半角スペースに置き換え 入数」</p>
    <p class="pc">としてください（それぞれの要素を半角スペース区切り）（整理枝番は基本的にゼロです）</p>
    <p class="pc">例：1040975 0 H0 17859 1000</p>
    <p class="pc">営業コードはO（オー）ではなく0（ゼロ）なので注意！</p>

    <hr>

    <h1>移動情報</h1>

  <form  action="WM_web_input_receive_test.php" method="POST" id="php" onsubmit="return false;">
  <div>
      <span class="cp_ipradio">
        <input type="radio" name="inout" id="inout1" class="inout2" value="出庫" >
        <label for="inout1">移動元</label>
        <input type="radio" name="inout" id="inout2" class="inout1" value="入庫" >
        <label for="inout2">移動先</label>
        
       
      </span>
      
     
     
     
     <p>
     <!-- <label for="tant" class="text" >担当者</label>
      <input type="text" name="tant" id="tant" class="text" size="4" maxlength="4"> -->
      <label for="tana" class="text">棚番</label>
      <input type="text" name="tana" id="tana" class="text" size="8" maxlength="5"></p>
    
   <label for="qr01" class="qrcode">QRコード</label>
   <p><input type="text" name="qr01" id="id_qr01" class="text" size="30"></p>
   <div id="result"></div>
  <label for="case01" class="case_amount">ケース数</label>
  <input type="tel" name="case01" id="case01" class="text" size="5">
  <input type="hidden" name="move" id="move" value="移動">
        
        
        <button type="button" id="ajax_button" class="ajax_button">表示</button>
     
        
      
      <!--
      <tr>
        <td><input type="text" name="qr02" id="id_qr02" style="width:200px;"></td>
        <td><input type="text" name="case02"></td>
      </tr>
      <tr>
        <td><input type="text" name="qr03" id="id_qr03" style="width:200px;"></td>
        <td><input type="text" name="case03"></td>
      </tr>
      <tr>
        <td><input type="text" name="qr04" id="id_qr04" style="width:200px;"></td>
        <td><input type="text" name="case04"></td>
      </tr>
      <tr>
        <td><input type="text" name="qr05" id="id_qr05" style="width:200px;"></td>
        <td><input type="text" name="case05"></td>
      </tr>
      -->
     

      


      </table>
   
   

    
    <p><button type="button" id="submit_button" class="submit_button" >送信</button>
  </p> 
  
  </form>
  </div>
  <p>
    <button type="button" class="close">閉じる</button>
  </p>




<?php
}
?>

 </body>
</html>
