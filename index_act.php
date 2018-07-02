<?php

include("function.php");


//1. FILEデータ取得
if (isset($_FILES["upfile"] ) && $_FILES["upfile"]["error"] ==0 ) {
    $file_name= $_FILES["upfile"]["name"]; //bindさせる値
    $tmp_path= $_FILES["upfile"]["tmp_name"]; //サーバー上の一時保存場所の情報
    $file_dir_path = "video/".$file_name;
    if (is_uploaded_file($tmp_path)){
        if(move_uploaded_file($tmp_path,$file_dir_path)){
            chmod($file_dir_path,0644);;
        }
    // FileUpload [--Start--]
    $video='<img src="video/'.$file_name. '">';
  
    // FileUpload [--End--]
    }else{
        $video = "画像が送信されていません";
    }
  }

//2. DB接続します(エラー処理追加)
$pdo = db_con();

//３．データ登録SQL作成
$stmt = $pdo->prepare("INSERT INTO video_table(id, video_name)VALUES(NULL, :video_name)");
$stmt->bindValue(':video_name', $file_name, PDO::PARAM_STR); 
$status = $stmt->execute();

//４．データ登録処理後
if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    echo "false"; 
    exit("QueryError:".$error[2]);
  }else{
    echo "true";
    // setting.phpへリダイレクト
    header("Location: confirm.php"); 
    exit;
  }


?>