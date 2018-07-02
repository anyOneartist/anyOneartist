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
        $view .= '<li>';
        $view .= '<video src="./video/'.$result["video_name"].'" class="video">';
        $view .= '</li>';

        if ($item_number < $result["id"]){
            $item_number *= 0;
            $item_number += $result["id"];
        }   
    }
  }
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/account.css">
    <title>Anyone Artists</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>

<body>
    <div  class="outer-wrapper">
        <div class="uper">
            <div class="left">
                <div class="leftContents">
                    <div class="icon">
                        <img src="image/chinen.png" class="icon">
                    </div>
                    <div class="userName">
                        知念 サーフィン 舞
                    </div>
                    <div class="userID">
                        ID: 9858972
                    </div>
                </div>
            </div>
            <div class="right">
                <ul>
                    <li class="findFriends" class="glyphicon glyphicon-info-sign">
                    </li>
                    <li class="detail" class="glyphicon glyphicon-option-horizontal">
                    </li>
                </ul>
            </div>
        </div>
        <div class="middleWrapper">
            <div class="middle">
                <div class="status">
                    ステータスはまだ記入されていません
                </div>
                <div class="infoWrapper">
                    <ul class="info">
                        <li class="follow">0フォロー中</li>
                        <li class="fan">0ファン</li>
                        <li class="good">0ハート</li>
                    </ul>
                </div>
            </div>

            <div class="options">
                <div class="sec1">
                    <div class="sec1Box selected">
                        動画 0
                    </div>
                </div>
                <div class="sec2">
                    |
                </div>
                <div class="sec3">
                    <div class="sec3Box">
                        <a href="account-good.html">
                            いいね 0
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="downer">
        <!-- <div class="movies">
            ここに「自分で撮った動画」が横3分割で入ります
        </div> -->
        <ul class="frames">
            <?= $view?>
        </ul>
    </div>
    <!-- １が表示されないための書き方 -->
    <div class="footer-space"></div>
    <?= file_get_contents("footer.php"); ?>

    <script src="https:/ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
         $(function(){
            var movieWidth = $(window).width()/3;
            var footerHeight = $('footer').height();
            var screenHeight = ($(window).height()- $(".outer-wrapper").height()-footerHeight)/3;
            $('video').width(movieWidth).css('background-color','#000000').height(screenHeight);
        });

         // ビデオ再生ボタンの装飾
         for (i=0; i< <?= $item_number?>;i++){
            var video = $('.video').get(i);
            video.setAttribute("controls", "controls");
        }

        // スティッキーヘッダー１
        $('.options').each(function(){
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

         // アンダーバーの移動
        $("ul>span").removeClass("underBar");
        $("ul>span:nth-child(5)").addClass("underBar");


        //準備中表示
        $(".sec3Box, .glyphicon-th-list,.glyphicon-cog").on("click",function(){
            alert("coming soon");
            return false;
        }) ;

    
    </script>
</body>
</html>