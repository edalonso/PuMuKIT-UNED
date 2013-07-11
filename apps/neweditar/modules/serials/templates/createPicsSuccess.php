<?php
$ver_siguiente = (($page == 1)? array('style' => 'display : none'): 
		  array('class' => 'tv_admin_action_next', 'style' => 'color : blue; font-weight : normal ', 'title' => 'Nueva Imagen') );
$ver_anterior = (($page == $total)||($total == 0)? array('style' => 'display : none'): 
		 array('class' => 'tv_admin_action_previous', 'style' => ' ', 'title' => 'Nueva Imagen') );
?>



<div id="tv_admin_container" style="width:100%">
<form id="form_udpate_pic" method="post">
    <?php echo input_hidden_tag('type', 'url') ?>
    <?php echo input_hidden_tag($que, $object_id) ?>

    <fieldset>
      <div class="form-row">
        <?php echo label_for('url', 'Escribir la url:', '') ?>
        <div class="content">
          <?php echo input_tag('url', '' ,'size=65') ?>
          <span id="error_url" style="display:none" class="error">Formato URL no v&aacute;lido</span>
        </div>
      </div>
    </fieldset>

    <ul class="tv_admin_actions">
      <li>
        <!-- TODO validate -->
        <li><input type="submit" name="add" value="Guardar" class="tv_admin_action_save MB_focusable"></li>
      </li>
    </ul>

  </form>
</div>
<div style="clear:both"></div>




<div id="tv_admin_container" style="width:100%">

  <form method="post" enctype="multipart/form-data" target="iframeUpload" action="<?php echo url_for('serials/uploadPics')?>">

    <?php echo input_hidden_tag('type', 'url') ?>
    <?php echo input_hidden_tag($que, $object_id) ?>


    <fieldset>
      <div class="form-row">
        <?php echo label_for('file', 'A&ntilde;adir un archivo:', '') ?>
        <div class="content">
          <?php echo input_file_tag('file', 'size=66') ?>
        </div>
      </div>
      <iframe name="iframeUpload" style="display:none" src=""></iframe>
    </fieldset>


    <ul class="tv_admin_actions">
      <li>
        <input type="submit" name="add" value="A&ntilde;adir" class="tv_admin_action_filenew" onclick="if($('file').value=='') { alert('Seleciona un archivo primero, Gracias');return false; }">
      </li>
    </ul>

    </form>
  </div>

<div style="clear:both"></div>

<div id="tv_admin_container" style="width:100%">

    <fieldset>
      <div class="form-row">
        <?php echo label_for('other', 'Usar Imagen:', '') ?>
        <div class="content">
          <?php if (count($pics) == 0):?>
            No hay imagenes en la base de datos.
          <?php endif ?>
          <?php foreach($pics as $pic):?>

	    <div style="padding: 18px; float:left">
              <div class="wrap0">
               <div class="wrap1">
                <div class="wrap2">
                 <div class="wrap3">
                    <a id="update_pic-<?php echo $pic->getId()?>" href="#" class="MB_focusable"><img src="<?php echo $pic->getUrl()?>" height="82" width="100"></a>
                 </div>
                </div>
               </div>
              </div>
            </div>
            <script type="text/javascript">
               $( "#update_pic-<?php echo $pic->getId()?>" ).click(function(e) {
                  e.preventDefault();
                  $.ajax({
                     method: 'POST',
                     url: '<?php echo url_for('serials/updatePics')?>',
                     data:{ type: 'pic', id: '<?php echo $pic->getId()?>', <?php echo $que?>: '<?php echo $object_id?>' },
                     success: function(e) {
                        $('#pic_<?php echo $que?>s').html(e);
                        $('#create_pic_form-<?php echo $object_id?>').dialog('close');
                     }
                  });
               });
            </script>

          <?php endforeach;?>

        <div style="clear: left"></div>
        </div>
      </div>
    </fieldset>

<ul class="tv_admin_actions">
  <li>
    <a id="prev" style="<?php if (($page == $total)||($total == 0)) { echo 'display:none'; }else{ echo 'color : blue;'.'font-weight : normal';}?>" class="<?php if (($page != $total)&&($total != 0)) { echo 'tv_admin_action_previous';}?>">Anterior</a>
  </li>
  <li>
  <a id="next" style="<?php if ($page == 1) { echo 'display:none'; }else{ echo 'color : blue;'.'font-weight : normal';}?>" class="<?php if ($page != 1) { echo 'tv_admin_action_next';}?>">Siguiente</a>
  </li>
</ul>

</div>

<script type="text/javascript">
$('#form_udpate_pic').submit(function(e){
           e.preventDefault();
           $.ajax({
               type:"POST",
               url: '<?php echo url_for('serials/updatePics') ?>',
               data: $(this).serialize(),
               success: function(e) {
                  $('#pic_<?php echo $que?>s').html(e);
                  $('#create_pic_form-<?php echo $object_id?>').dialog('close');
               }
           });
});
$('#prev').click(function(e){
           e.preventDefault();
           $.ajax({
               type:"POST",
               url: '<?php echo url_for('serials/createPics') ?>',
               data: {<?php echo $que?> : '<?php echo $object_id?>', 'page' : '<?php echo $page+1?>'},
               success: function(e) {
                  $('#pic_<?php echo $que?>s').html(e);
                  $('#create_pic_form-<?php echo $object_id?>').dialog('close');
               }
           });
});
$('#next').click(function(e){
           e.preventDefault();
           $.ajax({
               type:"POST",
               url: '<?php echo url_for('serials/createPics') ?>',
               data: {<?php echo $que?> : '<?php echo $object_id?>', 'page' : '<?php echo $page-1?>'},
               success: function(e) {
                  $('#pic_<?php echo $que?>s').html(e);
                  $('#create_pic_form-<?php echo $object_id?>').dialog('close');
               }
           });
});
</script>
