<?php
include("function.php");

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM video_table ORDER BY id DESC" );
$status = $stmt->execute();

//３．データ表示
$view="";
$item_number=0;
if($status==false) {
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
} else {
    //Selectデータの数だけ自動でループしてくれる
    while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
        $view .= '<tr align="center">';
        $view .= '<td>';
        $view .= '<video src="./video/'.$result["video_name"].'" class="video">';
        $view .= '</td>';
        $view .= '</tr>';

        if ($item_number < $result["id"]){
            $item_number *= 0;
            $item_number += $result["id"];
        }   
    }
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>anyOneartist</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>

    <?= file_get_contents("header.html"); ?>

    <div class="main">
        <table align="center">
            <tbody>
            <?= $view?>
            </tbody>

        </table>
    </div>
    <div class="footer-space"></div>

    <!-- １が表示されないための書き方 -->
    <?= file_get_contents("footer.php"); ?>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
        // ウィンドウ高さを計算して動画のサイズを指定
        $(function(){
            var movieWidth = $(window).width();
            var headerHeight = $('header').height();
            var footerHeight = $('footer').height();
            var screenHeight = $(window).height();
            var movieHeight = screenHeight - headerHeight - footerHeight;
            // console.log(headerHeight);
            // console.log(footerHeight);
            // console.log(screenHeight);
            // console.log(movieHeight);
            $('video').height(movieHeight).width(movieWidth).css('background-color','#000000');
        });
        


        // ビデオ再生ボタンの装飾
        for (i=0; i< <?= $item_number?>;i++){
            var video = $('.video').get(i);
            video.setAttribute("controls", "controls");
        }

        // アップロード動画の選択確認
        $('#upload').on('click',function(){
            if ($(".choice").val()==""){
                alert("ファイルを選択してください");
                return false;
            }
        });

        // スティッキーヘッダー１
        $('header').each(function(){
            var $window = $(window), //Windowオブジェクト＝ブラウザのウィンドウを表す
                $header = $(this), //$thisには$('header')が格納されている
                headerOffsetTop = $header.offset().top; //offset()メソッドには「jQuery要素のドキュメント上の位置を取得するメソッド」

                $window.on('scroll',function(){ //スクロールイベント
                    if($window.scrollTop()>headerOffsetTop){ //ウィンドウのスクロール量を調べて比較
                        $header.addClass('sticky');
                    }else {
                        $header.removeClass('sticky');
                    }
                });
                $window.trigger('scroll');
        });

        // スティッキーヘッダー２
        // $(function() {    
        //         var nav = $('header'),
        //         offset = nav.offset();
        
        //         $(window).scroll(function () {
        //             if($(window).scrollTop() > offset.top) {
        //               nav.addClass('sticky');
        //             } else {
        //               nav.removeClass('sticky');
        //             }
        //         });
        // });
        

        // 右上アップロードアイコンの変遷処理
        $('.glyphicon-plus').on('click',function(){
            $(this).removeClass("glyphicon glyphicon-plus");
            $('.hidden').removeClass("hidden").addClass('blinking');
        });


    </script>

</body>
</html>