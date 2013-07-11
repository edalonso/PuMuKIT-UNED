<div class="tv_admin_container extractpic">
    <fieldset>
     <div id="CorrectBrowser">
      <div style="width: 525px; height: 390px; float: left; background-color: #000;">
        <video style="width:100%; height: 100%;" src="<?php echo $file->getUrl()?>" id="video" controls crossorigin="anonymous" />
      </div>
      <div class="buttons" class="tv_admin_actions" style="float: left; width: 90px; ">
         <div style="text-align: center">
           <button style="display:none;" id="prev-frame-button" title="Prev Frame">&laquo</button>
           <button style="display:none;" id="next-frame-button" title="Next Frame">&raquo</button>
         </div>

         <button id="take-img-button" title="Take image." style="display:none; margin-top: 35px;"><span class="">Tomar muestra</span><div class="pic_arrow">&nbsp</div></button>
         <div id="take-burst-div" style="margin-top: 200%; display:none" VALIGN="MIDDLE" ALIGN="CENTER">
            <select id="pic_on_burst" title="Número total de fotos para burst">
               <option>10</option>
               <option>30</option>
               <option>50</option>
            </select>
            <button id="take-burst-button" title="Take Burst.">
               <span class="">Tomar colección</span><div class="burst_arrow">&nbsp</div>
            </button>
         </div>
      </div>
     <div style="width: 525px; height: 390px; float: left; position:relative; background-color: #000; ">
        <img class="image" style="position: absolute; bottom:0px; top:0px; left:0px; right:0px; margin: auto; width: 100%; max-height:100%" id="img" src="/images/uned/default.png" /> 
     </div>
     </div>
     <div id="ObsoleteBrowser" style="width: 1160px; height: 390px; display: none">
        <div class="SimPlayerText" style="width: 450px; float:left; color: #000; border-radius: 4px; padding: 10px 10px 0px 10px; margin-top: 150px; font-size: 20px;">
           El archivo no dispone de la funcionalidad capturador automático de imágenes debido a que
           <div class="SimPlayerText" style="color: #000; border-radius: 0px 0px 4px 4px; padding-bottom: 10px; font-size: 20px;" id="initializing"></div>
        </div>
        <div style="width: 525px; height: 390px; margin-left: 50%">
           <img class="image" style="margin-left: 1%; width: 520px; height: 380px;" id="img" src="/images/def_only_pic.gif" />
        </div>
     </div>
    </fieldset>
    <div style="clear:left"></div>
    <canvas id="canvas-draw-frames" style="display:none;"></canvas>
    <fieldset id="fieldsetCorrectBrowser"><div id="frames" style="overflow-y: scroll; height: 210px; margin-top: 15px;"></div></fieldset>

    <input type="file" id="file-upload-button" name="file" style="width: 370px; float: left; margin: 15px;" title="Upload a local file." accept="image/*" />
    <div class="buttons" style="margin: 15px 40px 10px 10px; float: right;">
      <!-- <button id="crop-img-button" title="Crop a image"><span class="">Crop selected image</span></button> -->
      <ul class="tv_admin_actions" style="width: 100%">
         <li><input type="submit" name="OK" value="OK" onclick="return false;" class="tv_admin_action_save" id="submit-button" title="Submit."></li>
         <li><input class="tv_admin_action_delete MB_focusable" id="cancel_canvas" type="button" value="Cancel"></li>
      </ul>
    </div>
</div>


<script type="text/javascript">

function supports_video() {
  return !!document.createElement('video').canPlayType;
}
function supports_h264_baseline_video() {
  var v = document.querySelector("#video");
  return v.canPlayType('video/mp4; codecs="avc1.42E01E, mp4a.40.2"');
}

(function() {
    var video = document.querySelector("#video");
    var img = document.querySelector("#img");
    var canvas_draw = document.querySelector("#canvas-draw-frames");
    var frames = document.querySelector("#frames");
    var take_img = document.querySelector("#take-img-button");
    var take_burst = document.querySelector("#take-burst-button");
    var upload_img = document.querySelector("#file-upload-button");
    var prev_frame = document.querySelector("#prev-frame-button");
    var next_frame = document.querySelector("#next-frame-button");
    var ctx_draw = canvas_draw.getContext("2d");
    var draw_interval = null;
    //var crop_img = document.querySelector("#crop-img-button");


if ( !supports_video() ) {
  $('CorrectBrowser').hide();
  $('fieldsetCorrectBrowser').hide();
  $('MB_window').style.height = '500px';
  $('ObsoleteBrowser').show();
  $('initializing').innerHTML='el explorador no es compatible con video HTML5';

} else if ( !supports_h264_baseline_video() ) {
  $('CorrectBrowser').hide();
  $('fieldsetCorrectBrowser').hide();
  $('MB_window').style.height = '500px';
  $('ObsoleteBrowser').show();
  $('initializing').innerHTML='el explorador no es compatible con el codec de video H.264';
}


if(video.readyState == 4){
  console.log("readyState is ", video.readyState);
  init_extractpic();
}else{
  console.log("video.addEventListener loadeddata");
  video.addEventListener("loadeddata", init_extractpic, false);
}


function init_extractpic() {
  $('take-img-button').show();
  $('next-frame-button').show();
  $('prev-frame-button').show();
  $('take-burst-div').show();
  video.width = canvas_draw.width = video.videoWidth;
  video.height = canvas_draw.height = video.videoHeight;
}

function take_pic() {
  ctx_draw.drawImage(video, 0, 0, video.width, video.height);
  var new_img = new Image();
  new_img.classList.add("new_img");
  new_img.src = canvas_draw.toDataURL("image/png");
  new_img.width = 120;
  
  new_img.addEventListener("click", function(){
    img.src = this.src;
  })

  frames.appendChild(new_img);
  return new_img;
}

prev_frame.addEventListener("click", function(){
  video.currentTime -= 0.25;
}, false);

next_frame.addEventListener("click", function(){
  video.currentTime += 0.25;
}, false);

take_img.addEventListener("click", function(){
  console.log('Take a picture');
  var new_img = take_pic();
  img.src = new_img.src;
}, false);
 
take_burst.addEventListener("click", function(){
  console.log('Take a burst');

  var TotalTime = Math.ceil(parseInt(video.duration));
  var interval = TotalTime/$('pic_on_burst').value;

  console.log('Intevalo entre cada foto: ' + interval);

  if ($$('.new_img')){
      $$('.new_img').invoke('remove');
      ctx_draw.clearRect(0, 0, canvas_draw.width, canvas_draw.height);
  }

  video.currentTime = 0;
  take_pic();

  video.addEventListener("seeked", burst = function(e){
    if (!video.seeking) {
      take_pic();
      video.currentTime = (video.currentTime + interval);
      if (video.currentTime >= video.duration - interval) video.removeEventListener("seeked", burst);
    }
  });
  video.currentTime = (video.currentTime + interval);
}, false);

/*crop_img.addEventListener("click", function(){
  $(img).imageCrop({
    displayPreview : true,
    displaySize : true,
    overlayOpacity : 0.40,
    minSelect: [100, 100],
    aspectRatio: video.width / video.height,
  });
}, false);*/


upload_img.addEventListener("change", function(e){
  var file = e.target.files[0];
  var reader = new FileReader();
  reader.onload = function(e) {
          // Render thumbnail.
          img.src = e.target.result;
	  var new_img = new Image();
	  new_img.classList.add("new_img");
	  new_img.src = canvas_draw.toDataURL("image/png");
	  new_img.width = 120;
	  new_img.height = 90;
	  new_img.src = img.src;
	  new_img.addEventListener("click", function(){
	    img.src = this.src;
	  });

	  frames.appendChild(new_img);
  };
  reader.readAsDataURL(file);
}, false);

$('cancel_canvas').observe('click', function(e){
   video.src = "";
   ctx_draw.clearRect(0, 0, canvas_draw.width, canvas_draw.height);
   Modalbox.hide();
});

$(Modalbox.MBclose).observe('click', function(e){
   video.src = "";
   ctx_draw.clearRect(0, 0, canvas_draw.width, canvas_draw.height);
});

$('submit-button').observe('click', respondToClick);

function respondToClick(event) {
  var data = img.src;

  new Ajax.Request('<?php echo url_for('extractpic/upload')?>', {
    method: 'post',
    parameters: {img:data, mm:<?php echo $mm->getId()?>},
    asynchronous: true, 
    evalScripts: true,
    onSuccess: function(response) {
              new Ajax.Updater('edit_mms', '<?php echo url_for($module . "/edit")?>', {
                        asynchronous: true, 
                        evalScripts: true,
                        parameters: {id: <?php echo $mm->getId()?>}
                  });
       Modalbox.hide();
    }
  });
};

})();




</script>