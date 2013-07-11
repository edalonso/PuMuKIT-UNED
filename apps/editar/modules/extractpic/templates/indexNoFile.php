<div class="tv_admin_container extractpic">
    <fieldset>
      <div style="width: 525px; height: 390px; float: left;">
        <p class="SimPlayerText" style="color: #000; border-radius: 4px; margin-top: 150px; font-size: 20px;">
          El objeto seleccionado no posee ningún archivo multimedia de video asociado. Por tanto no puede usar el capturador automático de imágenes.
        </p>
      </div>
      <div style="width: 525px; height: 390px; float: right; margin-right: 10px;">
         <img class="image" style="margin-left: 1%; width: 520px; height: 380px;" id="img" src="/images/uned/defaultOnlySelect.png" />
      </div>
    </fieldset>
    <div style="clear:left"></div>

    <input type="file" id="file-upload-button" name="file" style="width: 370px; float: left; margin: 15px;" title="Upload a local file." accept="image/*" />
    <div class="buttons" style="margin: 15px 40px 10px 10px; float: right;">
      <ul class="tv_admin_actions" style="width: 100%">
         <li><input type="submit" name="OK" value="OK" onclick="return false;" class="tv_admin_action_save" id="submit-button" title="Submit."></li>
         <li><input class="tv_admin_action_delete MB_focusable" onclick="Modalbox.hide(); return false;" type="button" value="Cancel"></li>
      </ul>
    </div>
</div>

<script type="text/javascript">


(function() {

    var img = document.querySelector("#img");
    var upload_img = document.querySelector("#file-upload-button");

upload_img.addEventListener("change", function(e){
  console.log('entra');
  var file = e.target.files[0];
  var reader = new FileReader();
  reader.onload = function(e) {
          // Render thumbnail.
          img.src = e.target.result;
  };
  reader.readAsDataURL(file);
}, false);

})();

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
</script>