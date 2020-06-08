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
  <meta name="viewport" content="width=device-width">
  <link rel="stylesheet" href="css/WM_web_inventory_test_html.css">

  </head>
  <body>

  <?php

session_start();
if(!$_SESSION['userid']){
  echo '<h1>ログインしてください</h1>';
  echo '<p><button type="button" class="close">閉じる</button></p>';
}else{
?>
   
    <h1>棚卸し</h1>
    <form action="WM_web_inventory_test.php" method="POST" id="php" onsubmit="return false;">
    
     
      <label for="tana" class="label">棚番　</label>
      <input type="text" name="tana" id="tana" class="text" maxlength="7">
    
      
      <hr>
      <label for="qrcode_input">QRコード</label>
      <input type="text" id="qrcode_input" class="text">
      <button type="button" id="ajax_button" class="ajax_button">表示</button>
      
      <div>
        <label for="tokui">得意先名：</label>
        <span id="tokui"></span><br>
        <label for="user">ユーザー名：</label>
        <span id="user"></span><br>
        <label for="title">タイトル名：</label>
        <span id="title"></span>
       
      </div>
      <table>
          <tr>
            <th>受注番号</th>
            <td><input type="text" name="order" id="order" class="text"></td>
          </tr>
          <tr>
            <th>入数</th>
            <td><input type="tel" name="amount" id="amount" class="text"></td>
          </tr>
          <tr>
            <th>ケース数</th>
            <td><input type="tel" name="case" id="case" class="text"></td>
          </tr>
          <tr>
            <th>備考</th>
            <td><input type="text" name="extra" id="extra" class="text"><input type="hidden" id="seiri"><input type="hidden" id="eda"></td>
          </tr>
      </table>
      <button type="button" id="submit_button" class="submit_button">送信</button>
  

    </form>
    <p><button type="button" class="close">閉じる</button></p>
  
    
<?php
}
?>
  <script src="js/WM_web_inventory.js?1446"></script>
  </body>
</html>
