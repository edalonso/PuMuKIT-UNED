<?php use_helper('Object', 'JSRegExp') ?>

<div id="tv_admin_container">

<?php echo form_remote_tag(array( 
  'update' => 'list_events', 
  'url' => 'events/update'. $div,
  'script' => 'true',  
)) ?>

<?php echo object_input_hidden_tag($event, 'getId') ?>


<fieldset>

<div class="form-row">
  <?php echo label_for('title', 'Título:', 'class="required" ') ?>
  <div class="content">
      <?php echo object_input_tag($event, 'getTitle', array (
        'size' => '80',
        'control_name' => 'title',
      )); ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('description', 'Descripción:', 'class="required" ') ?>
  <div class="content">
      <?php echo object_textarea_tag($event, 'getDescription', array (
        'size' => '68x2',
        'control_name' => 'description',
      )); ?>
  </div>
</div>



<div class="form-row">
   <?php echo label_for('author', 'Autor:', 'class="required" ') ?>
  <div class="content">
   <?php echo object_input_tag($event, 'getAuthor', array (
        'size' => '80',
        'control_name' => 'author',
	)); ?>
  </div>
</div>


<div class="form-row">
   <?php echo label_for('producer', 'Realizador:', 'class="required" ') ?>
  <div class="content">
   <?php echo object_input_tag($event, 'getProducer', array (
        'size' => '80',
        'control_name' => 'producer',
	)); ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('date', 'Fecha y hora de inicio: ', 'class="required" ') ?>
  <div class="content">
    <?php echo object_input_date_tag($event, 'getDateIni', array (
      'rich' => true,
      'withtime' => true,
      'calendar_button_img' => '/images/admin/buttons/date.png',
      'control_name' => 'date_ini',
    )) ?>
    <span id="error_date" style="display:none" class="error">Formato fecha no v&aacute;lido</span>
    </div>
</div>



<div class="form-row">
  <?php echo label_for('direct_id','Streaming:', 'class="required"') ?>
  <div class="content">
    <?php $value = object_select_tag($event, 'getDirectId', array (
      'related_class' => 'Direct',
      'control_name' => 'direct_id',
      'peer_method' => 'doSelectWithI18n',
      'include_blank' => false,
    )); echo $value ? $value : '&nbsp;' ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('enableQuery', 'Consultas:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getEmailQuery', array (
  	// 'onchange' => 'submit()',
    )) ?>
    </div>
</div>


<div class="form-row">
   <?php echo label_for('emailQuery', 'Email consultas:', 'class="required" ') ?>
  <div class="content">
   <?php echo object_input_tag($event, 'getEmailQuery', array (
        'size' => '80',
        'control_name' => 'emailQuery',
	)); ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('display', 'Anunciar:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getDisplay', array (
  	// 'onchange' => 'submit()',
    )) ?>
    </div>
</div>


<div class="form-row">
  <?php echo label_for('secured', 'Proteger con contraseña:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getSecured', array (
  	// 'onchange' => 'submit()',
    )) ?>
    </div>
</div>


<div class="form-row">
   <?php echo label_for('password', 'Contraseña:', 'class="required" ') ?>
  <div class="content">
   <?php echo object_input_tag($event, 'getPassword', array (
        'size' => '80',
        'control_name' => 'password',
	)); ?>
  </div>
</div>




</fieldset>


<ul class="tv_admin_actions">
  <li><?php echo submit_tag('OK','name=OK class=tv_admin_action_save onclick=return comprobar_form_event($("date").value, '. get_js_regexp_timedate($sf_user->getCulture()) . ', $("duration").value)'); ?></li>
  <li><?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li>
</ul>

</form>
</div>

