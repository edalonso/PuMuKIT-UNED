<?php use_helper('Object') ?>

<div id="tv_admin_container">

<form id="form_crear_link" method="post">

<input type="hidden" name="id" id="id" value="<?php echo $link->getId()?>">
<input type="hidden" name="mm" id="mm" value="<?php echo $link->getMmId() ?>" />
<input type="hidden" name="preview" id="preview" value="true" />

<fieldset>

<div class="form-row">
  <?php echo label_for('name', 'Nombre:', 'class="required" ') ?>
  <div class="content">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $link->setCulture($lang);  echo $sep ?>  
  
        <?php $value = object_input_tag($link, 'getName', array ('size' => 80,  'control_name' => 'name_' . $lang,
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('url', 'Url:', 'class="required" ') ?>

  <div class="content">
    <?php $value = object_input_tag($link, 'getUrl', array (
      'size' => 66,
      'control_name' => 'url'
    )); echo $value ? $value : '&nbsp;' ?>
    <span id="error_url" style="display:none" class="error">Formato URL no v&aacute;lido. Ejemplo: http://pumukit.org</span>
  </div>
</div>



</fieldset>


<ul class="tv_admin_actions">
  <!-- TODO comprobar url del formulario -->
  <li><input type="submit" name="OK" value="OK" class="tv_admin_action_save MB_focusable"></li>
</ul>

</form>
</div>

<script type="text/javascript">
   $('#form_crear_link').submit(function(e){
           e.preventDefault();
           $.ajax({
               type:"POST",
               url: '<?php echo url_for('serials/updateLinks') ?>',
               data: $(this).serialize(),
               success: function(e) {
                  $('#create_link_form').dialog('close');
                  $('#edit_link_form-<?php echo $link->getId();?>').dialog('close');
                  $('#links_mms').html(e);
               }
           });
   });
</script>