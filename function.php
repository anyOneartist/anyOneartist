<?php


// DB接続関数（PDO）
// サーバーアクセス
// function db_con(){
//   $dbname='gsacademy-yohei_gs_db';
//   try {
//     // mysql719.db.sakura.ne.jp
//     $pdo = new PDO('mysql:dbname='.$dbname.';charset=utf8;host=mysql719.db.sakura.ne.jp','gsacademy-yohei','42vv77hf9a');
//   } catch (PDOException $e) {
//     exit('DbConnectError:'.$e->getMessage());
//   }
//   return $pdo;
// }

// // ローカルアクセス
function db_con(){
  $dbname='gs_db_teamdevelopment';
  try {
    $pdo = new PDO('mysql:dbname='.$dbname.';charset=utf8;host=localhost','root','');
  } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
  }
  return $pdo;
}


function isAjax(){
  if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
     strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') //ajaxが送る文字
  {
       return true;
  }else{
       return false;
  }
}


//SQL処理エラー
function queryError($stmt){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}


function h($str){
  return htmlspecialchars($str, ENT_QUOTES, "UTF-8");
}

//SessionIDのチェック
function chkssid(){
  if(!isset($_SESSION["chk_ssid"]) || $_SESSION["chk_ssid"] != session_id()
  ) {
    exit("LOGIN ERROR");
  }
}


?>
