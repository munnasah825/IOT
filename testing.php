<!DOCTYPE html>
<html>
  <head>
    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  </head>

  <body onload="myfunc();">
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <nav>
        <div class="nav-wrapper">
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a href="index.php">Train Data</a></li>
            <li class="active"><a href="#">Testing</a></li>
          </ul>
        </div>
      </nav>
      
      <div class="container">
            <div class="card center">
 
                    <div class="card-content">
                            <p>
                                <video onclick="snapshot(this);" width=400 height=400 id="video" controls autoplay></video>
                                <br>
                                <canvas  id="myCanvas" width="300" height="220"></canvas>
                            </p>
                    </div>
                    <audio id="myAudio">
                            <source src="success.wav" type="audio/wav">
                    </audio>

                  </div>
      </div>
  </body>
  <script type="text/javascript">
    //--------------------
    // GET USER MEDIA CODE
    //--------------------
        

    function startWebcam() {
      navigator.getUserMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);

    var video;
    var webcamStream;
      if (navigator.getUserMedia) {
         navigator.getUserMedia (

            // constraints
            {
               video: true,
               audio: false
            },

            // successCallback
            function(localMediaStream) {
                video = document.querySelector('video');
               video.src = window.URL.createObjectURL(localMediaStream);
               webcamStream = localMediaStream;
            },

            // errorCallback
            function(err) {
               console.log("The following error occured: " + err);
            }
         );
      } else {
         console.log("getUserMedia not supported");
      }  
    }


    //---------------------
    // TAKE A SNAPSHOT CODE
    //---------------------
    

    function init() {
      // Get the canvas and obtain a context for
      // drawing in it
      canvas = document.getElementById("myCanvas");
      ctx = canvas.getContext('2d');
    }

    function snapshot() {
      var canvas, ctx;
      canvas = document.getElementById("myCanvas");
      ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0,0, canvas.width, canvas.height);
        var img1 = new Image();
        img1.src = canvas.toDataURL();
        //var ip = document.getElementById('ip').value;
        datad = "{\r\n    \"image\":\"" + img1.src+ "\",\r\n    \"gallery_name\":\"Arti\"\r\n}"
        var settings = {
            "async": true,
            "crossDomain": true,
            "url": "https://api.kairos.com/recognize",
            "method": "POST",
            "headers": {
                "content-type": "application/json",
                "app_id": "91f4d958",
                "app_key": "168425636713957f3776c2a982dd9468",
                "cache-control": "no-cache"
            },
            "processData": false,
            "data": datad
        }
        $.ajax(settings).done(function (response) {
            var m = response;
            console.log(JSON.stringify(m).indexOf("success"));
            if(JSON.stringify(m).indexOf("success") > -1) {
                Materialize.toast('User Identfied. Name : ' +JSON.stringify(m.images[0].candidates[0].subject_id), 6000);
                console.log(m.images[0].candidates[0].subject_id);
                   abc();

            }
            else{
                abc1();
                }
        });
    }
     function abc(){
            $.post("https://api.thingspeak.com/update?api_key=LWPWGYKFMRD1U982&field2=1", function(result){});
          }
          function abc1(){
            $.post("https://api.thingspeak.com/update?api_key=LWPWGYKFMRD1U982&field2=0", function(result){});
          }
          function myfunc()
          {
            //init();
            startWebcam();
            window.setTimeout(snapshot,2000);
          }
</script>
</html>