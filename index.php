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

  <body onload="init();">
    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="js/materialize.min.js"></script>
    <nav>
        <div class="nav-wrapper">
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li class="active"><a href="#">Train Data</a></li>
            <li><a href="testing.php">Testing</a></li>
          </ul>
        </div>
      </nav>
      
      <div class="container">
            <div class="card center">
 
                    <div class="card-content">
                            <div class="card-action">
                                <a class="btn-floating btn-large waves-effect waves-light red" onclick="startWebcam();"><i class="material-icons">camera</i></a>
                                <a class="btn-floating btn-large waves-effect waves-light red" onclick="snapshot();"><i class="material-icons">verified_user</i></a>
                            </div>
                            <div class="input-field col s6">
                                    <input placeholder="Enter Name" type="text" class="validate" id="ip">
                            </div>
                            <p>
                                <video onclick="snapshot(this);" width=300 height=300 id="video" controls autoplay></video>
                                <br>
                                <canvas  id="myCanvas" width="300" height="220"></canvas>
                            </p>
                    </div>

                  </div>
      </div>
  </body>
  <script type="text/javascript">
    //--------------------
    // GET USER MEDIA CODE
    //--------------------
        navigator.getUserMedia = ( navigator.getUserMedia ||
                           navigator.webkitGetUserMedia ||
                           navigator.mozGetUserMedia ||
                           navigator.msGetUserMedia);

    var video;
    var webcamStream;

    function startWebcam() {
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
    var canvas, ctx;

    function init() {
      // Get the canvas and obtain a context for
      // drawing in it
      canvas = document.getElementById("myCanvas");
      ctx = canvas.getContext('2d');
    }

    function snapshot() {
      
      ctx.drawImage(video, 0,0, canvas.width, canvas.height);
      var img1 = new Image();
      img1.src = canvas.toDataURL();
      var ip = document.getElementById('ip').value;
      datad = "{\r\n    \"image\":\"" + img1.src+ "\",\r\n    \"subject_id\":\"" + ip + "\",\r\n    \"gallery_name\":\"Arti\"\r\n}";
      var settings = {
          "async": true,
          "crossDomain": true,
          "url": "https://api.kairos.com/enroll",
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
        //
        if((response.images[0].transaction.status) == "success"){
            Materialize.toast("Image Trained succesfully by name " +response.images[0].transaction.subject_id+ " in gallery name " +response.images[0].transaction.gallery_name, 4000);
        }
        else{
            Materialize.toast("Unable to Train Image", 4000);
        }
      });
      //console.log(img1.src);
    }

</script>
</html>