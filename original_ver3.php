<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>顔認識</title>
<link rel="stylesheet" href="css/style.css">
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<style>
  /* video 要素の上に canvas 要素をオーバーレイするための CSS */
  #container {              /* コンテナ用の div について */
    position: relative;     /* 座標指定を相対値指定にする */
  }
  #video {                  /* カメラ映像を流す video について */
    transform: scaleX(-1);  /* 左右反転させる */
  }
  #canvas {                 /* 描画用の canvas について */
    transform: scaleX(-1);  /* 左右反転させる */
    position: absolute;     /* 座標指定を絶対値指定にして */
    left: 0;                /* X座標を0に */
    top: 0;                 /* Y座標を0に */
  }
</style>
</head>
 
<body>
<?= file_get_contents("header.html"); ?>
  <div id="container">  <!-- video の上に canvas をオーバーレイするための div 要素 -->
    <video id="video" width="400" height="300" class="camera" autoplay="true", playsinline></video>  <!-- カメラ映像を流す video -->
    <canvas id="canvas" width="400" height="300" calss="camera"></canvas>        <!-- 重ねて描画する canvas -->
  </div>
  <div id="dat"></div>  <!-- データ表示用 div 要素 -->
  
 <form name="form1" action="">眉上げる
    <select id="Select1">
      <option>---</option>
      <option value="tan1.mp3">タンバリン1</option>
      <option value="tan2.mp3">タンバリン2</option>
      <option value="tan3_ver2.mov">タンバリン3</option>
      <option value="dolamu.mp3">ドラム</option>
      <option value="dolamu_hat.mp3">ドラム・ハット</option>
      <option value="dolamu_sunea.mp3">ドラム・スネア</option>
      <option value="wahaha.mp3">わっはっは</option>
      <option value="kya.mp3">キャー</option>

    </select>
  </form>
  <form name="form3" action="">目閉じる
    <select id="Select3">
      <option>---</option>
      <option value="tan1.mp3">タンバリン1</option>
      <option value="tan2.mp3">タンバリン2</option>
      <option value="tan3_ver2.mov">タンバリン3</option>
      <option value="dolamu.mp3">ドラム</option>
      <option value="dolamu_hat.mp3">ドラム・ハット</option>
      <option value="dolamu_sunea.mp3">ドラム・スネア</option>
      <option value="wahaha.mp3">わっはっは</option>
      <option value="kya.mp3">キャー</option>
    </select>
</form>
  <form name="form2" action="">口開ける
        <select id="Select2">
          <option>---</option>
          <option value="tan1.mp3">タンバリン1</option>
          <option value="tan2.mp3">タンバリン2</option>
          <option value="tan3_ver2.mov">タンバリン3</option>
          <option value="dolamu.mp3">ドラム</option>
          <option value="dolamu_hat.mp3">ドラム・ハット</option>
          <option value="dolamu_sunea.mp3">ドラム・スネア</option>
          <option value="wahaha.mp3">わっはっは</option>
          <option value="kya.mp3">キャー</option>
        </select>
  </form>
  眉テンポ<input id="slider2" type="range" min="1" max="800" value="0" oninput="document.getElementById('slider').value=this.value">
  <output id="slider">1</output>
  目テンポ<input id="slider6" type="range" min="1" max="800" value="0" oninput="document.getElementById('slider5').value=this.value">
  <output id="slider5">1</output>
  口テンポ<input id="slider4" type="range" min="1" max="800" value="0" oninput="document.getElementById('slider3').value=this.value">
  <output id="slider3">1</output>
  <div><p>口を閉じ目をはっきりと開けた状態で、顔のスタンプが出ない位置を基準に演奏します</p></div>
                                                              <!-- <button id='btn'>Play</button> -->
  <?= file_get_contents("footer.php"); ?>

    <!-- clmtrackr 関連ファイルの読み込み -->
    <script src="clmtrackr.js"></script>          <!-- clmtrackr のメインライブラリの読み込み -->
  <script src="model_pca_20_svm_emotion.js"></script>   <!-- 顔モデル（※1）の読み込み -->
  <script src="emotionClassifier.js"></script>  <!-- 感情を分類する外部関数の読み込み -->
  <script src="emotionModel.js"></script>       <!-- 感情モデル（※2）の読み込み -->
  <script src="//code.jquery.com/jquery-1.12.1.min.js"></script>
   
  <script>
    // もろもろの準備
    var video = document.getElementById("video");           // video 要素を取得
  var canvas = document.getElementById("canvas");         // canvas 要素の取得
  var context = canvas.getContext("2d");                  // canvas の context の取得
  var stampNose = new Image();                            // 鼻のスタンプ画像を入れる Image オブジェクト
  var stampEars = new Image();                            // 耳のスタンプ画像を入れる Image オブジェクト
  var stampTear = new Image();                            // ★涙のスタンプ画像を入れる Image オブジェクト
  var stampSurp = new Image();                            // ★驚きのスタンプ画像を入れる Image オブジェクト
  var stampEyes = new Image();                            // ★ハートのスタンプ画像を入れる Image オブジェクト
  var stampBears = new Image();
  stampNose.src = "nose.png";                             // 鼻のスタンプ画像のファイル名
  stampEars.src = "ears.png";                             // 耳のスタンプ画像のファイル名
  stampTear.src = "tear.png";                             // ★涙のスタンプ画像のファイル名
  stampSurp.src = "surp.png";                             // ★驚きのスタンプ画像のファイル名
  stampEyes.src = "eyes.png";                             // ★ハートのスタンプ画像のファイル名
  stampBears.src = "beard.png";  
  
console.log($("#Select1").val())
// slider = 0
$("#Select1").on('change',function(){
    select1 = $(this).val()
})
$("#Select2").on('change',function(){
    select2 = $(this).val()
})
$("#Select3").on('change',function(){
    select3 = $(this).val()
})
$("#slider2").on('change',function(){
      slider = $("#slider2").val()
//       console.log(slider)
});
$("#slider4").on('change',function(){
      slider2 = $("#slider3").val()
//       console.log(slider)
});
$("#slider6").on('change',function(){
      slider3 = $("#slider5").val()
//       console.log(slider)
});
    
  function playsound(select){
    audio = new Audio();
    audio.src = ""+select+"";
    audio.play();
  }
  // getUserMedia によるカメラ映像の取得
  var media = navigator.mediaDevices.getUserMedia({       // メディアデバイスを取得
    video: {facingMode: "user"},                          // カメラの映像を使う（スマホならインカメラ）
    audio: false                                          // マイクの音声は使わない
  });
  media.then((stream) => {                                // メディアデバイスが取得できたら
    video.srcObject = stream;       // video 要素にストリームを渡す
  });
   
  // clmtrackr の開始
  var tracker = new clm.tracker();  // tracker オブジェクトを作成
  tracker.init(pModel);             // tracker を所定のフェイスモデル（※1）で初期化
  tracker.start(video);             // video 要素内でフェイストラッキング開始
   
  // 感情分類の開始
  var classifier = new emotionClassifier();               // emotionClassifier オブジェクトを作成
  classifier.init(emotionModel);                          // classifier を所定の感情モデル（※2）で初期化
   
  // 描画ループ
  flag_dolamu = 0
  flag_dolamu_hat = 0
  flag_dolamu_sunea = 0
  flag_piano_shi = 0
  flag_piano_fa = 0
  flag_piano_mi = 0
  flag_piano_do = 0
  flag_piano_re = 0
  flag_piano_so = 0
  flag_piano_ra = 0
  flag_piano_do2 = 0
  function drawLoop() {
    requestAnimationFrame(drawLoop);                      // drawLoop 関数を繰り返し実行
    var positions = tracker.getCurrentPosition();         // 顔部品の現在位置の取得
    // console.log(positions)
    var parameters = tracker.getCurrentParameters();      // 現在の顔のパラメータを取得
    var emotion = classifier.meanPredict(parameters);     // そのパラメータから感情を推定して emotion に結果を入れる
    context.clearRect(0, 0, canvas.width, canvas.height); // canvas をクリア
    //tracker.draw(canvas);                                 // canvas にトラッキング結果を描画
  
    height_base = positions[62][1] - positions[33][1];
    width_base = positions[32][0] - positions[27][0];
    ago_height = positions[7][1] - positions[53][1];
    // console.log(positions[26][1] - positions[24][1])
    // console.log(height_base)
    // console.log(width_base)
    if((positions[34][1] - positions[22][1]) > ago_height*1.4 && (positions[57][1] - positions[60][1]) < ago_height*0.1){
      drawStamp(positions, stampSurp, 14, 1.0, 0.7, 0.0);
      if(flag_piano_do == 0){
        playsound(select1);
        flag_piano_do = 1
        flag0 = function(){
          flag_piano_do = 0
        }
        setTimeout(flag0,slider)
      }
    }
    
    if((positions[57][1] - positions[60][1]) > ago_height*0.30){
      drawStamp(positions, stampBears, 7, 3.0, 0.0, 0.0);
      if(flag_piano_mi == 0){
        
        playsound(select2);
        
        flag_piano_mi = 1
        flag0 = function(){
          flag_piano_mi = 0
        }
        setTimeout(flag0,slider2)
      }
    }
    if((positions[36][1] - positions[24][1]) < ago_height*1.35){
      drawStamp(positions, stampEyes, 27, 0.6, 0.0, 0.0); // ★ハートのスタンプを描画（右目）
      drawStamp(positions, stampEyes, 32, 0.6, 0.0, 0.0); // ★ハートのスタンプを描画（左目）
      if(flag_piano_fa == 0){
        
        playsound(select3);
        
        flag_piano_fa = 1
        flag0 = function(){
          flag_piano_fa = 0
        }
        setTimeout(flag0,slider3)
      }
    }
  }
  setTimeout("drawLoop()", 2000);
   
  // スタンプを描く drawStamp 関数
  // (顔部品の位置データ, 画像, 基準位置, 大きさ, 横シフト, 縦シフト)
  function drawStamp(pos, img, bNo, scale, hShift, vShift) {
    var eyes = pos[32][0] - pos[27][0];                   // 幅の基準として両眼の間隔を求める
    var nose = pos[62][1] - pos[33][1];                   // 高さの基準として眉間と鼻先の間隔を求める
    var wScale = eyes / img.width;                        // 両眼の間隔をもとに画像のスケールを決める
    var imgW = img.width * scale * wScale;                // 画像の幅をスケーリング
    var imgH = img.height * scale * wScale;               // 画像の高さをスケーリング
    var imgL = pos[bNo][0] - imgW / 2 + eyes * hShift;    // 画像のLeftを決める
    var imgT = pos[bNo][1] - imgH / 2 + nose * vShift;    // 画像のTopを決める
    context.drawImage(img, imgL, imgT, imgW, imgH);       // 画像を描く
  }

  //準備中表示
  $(".sec3Box, .glyphicon-th-list,.glyphicon-cog").on("click",function(){
            alert("coming soon");
            return false;
   }) ;


  //カメラ撮影画面の調整
  // $(function(){
  //         var cameraWidth = $(window).width();
  //         $('.camera').width(cameraWidth);
  // });

  // アンダーバーの移動
  $("ul>span").removeClass("underBar");
  $("ul>span:nth-child(3)").addClass("underBar");





  </script>
</body>
</html>
