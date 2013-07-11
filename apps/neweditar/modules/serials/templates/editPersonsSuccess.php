<?php use_helper('Object') ?>

<div id="tv_admin_container">

<form id="form_crear_person" method="post">

<input type="hidden" name="id" id="id" value="<?php echo $person->getId()?>">
<fieldset>

<div class="form-row">
  <?php echo label_for('honorific', 'Honores:', 'class="required" ') ?>
  <div class="content">
    <?php $sep=''; foreach ($langs as $lang): ?>
      <?php $person->setCulture($lang);  echo $sep ?>  
      <?php $value = object_input_tag($person, 'getHonorific', array ('size' => 15,  'control_name' => 'honorific_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>

<div class="form-row">
  <?php echo label_for('name', 'Nombre:', 'class="required" ') ?>
  <div class="content">
  <?php $value = object_input_tag($person, 'getName', array ('size' => 80,  'control_name' => 'name',
)); echo $value ? $value : '&nbsp;' ?>
  </div>
</div>

<div class="form-row">
  <?php echo label_for('post', 'Puesto:', 'class="required" ') ?>
  <div class="content">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $person->setCulture($lang);  echo $sep ?>  
  
      <?php $value = object_input_tag($person, 'getPost', array ('size' => 80,  'control_name' => 'post_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('firm', 'Empresa:', 'class="required" ') ?>
  <div class="content">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $person->setCulture($lang);  echo $sep ?>  
 
      <?php $value = object_input_tag($person, 'getFirm', array ('size' => 80,  'control_name' => 'firm_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('bio_' . $lang, 'Bio.:', 'class="required" ') ?>
  <div class="content">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $person->setCulture($lang);  echo $sep ?>  
  
      <?php $value = object_input_tag($person, 'getBio', array ('size' => 80,  'control_name' => 'bio_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('email', 'Email:', 'class="required" ') ?>
  <div class="content">
  <?php $value = object_input_tag($person, 'getEmail', array (
  'size' => 30,
  'control_name' => 'email',
)); echo $value ? $value : '&nbsp;' ?>
    <span id="error_email" style="display:none" class="error">Formato email no v&aacute;lido</span>
    </div>
</div>

<div class="form-row">
  <?php echo label_for('web', 'Web:', 'class="required" ') ?>
  <div class="content">
    <?php $value = object_input_tag($person, 'getWeb', array (
      'size' => 50,
      'control_name' => 'web',
      )); echo $value ? $value : '&nbsp;' ?>
    <span id="error_url" style="display:none" class="error">Formato URL no v&aacute;lido</span>  
    </div>
</div>

<div class="form-row">
  <?php echo label_for('phone', 'Tel&eacute;fono:', 'class="required" ') ?>
  <div class="content">

  <?php $value = object_input_tag($person, 'getPhone', array (
    'size' => 30,
    'control_name' => 'phone',
  )); echo $value ? $value : '&nbsp;' ?>
  </div>
</div>

</fieldset>


<ul class="tv_admin_actions">
   <!-- TODO validate -->
  <li><input type="submit" name="OK" value="OK" class="tv_admin_action_save MB_focusable"></li>
</ul>

</form>
</div>


<script type="text/javascript">
   $('#form_crear_person').submit(function(e){
           e.preventDefault();
           $.ajax({
               type:"POST",
               url: '<?php echo url_for($sf_data->getRaw('url')) ?>',
               data: $(this).serialize(),
               success: function(e) {
                  $('#dialog-modal-new-inside-<?php echo $role_id?>').dialog('close');
                  $('#dialog-modal-new-<?php echo $role_id?>').dialog('close');
                  $('#dialog-modal-form-edit-<?php echo $role_id?>-<?php echo $person->getId()?>').dialog('close');
                  $('#<?php echo $update?>').html(e);
               }
           });
   });
</script>