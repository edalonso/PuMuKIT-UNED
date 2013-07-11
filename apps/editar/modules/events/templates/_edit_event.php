<?php use_helper('Object', 'JSRegExp') ?>

<div id="tv_admin_container" style="padding: 4px 20px 20px; position: relative;">

<?php echo form_remote_tag(array( 
  'update' => 'list_events', 
  'url' => 'events/update'. $div,
  'script' => 'true',  
  'failure' => visual_effect('opacity', 'event_save_error', array('duration' => '3.0', 'from' => '1.0', 'to' => '0.0')),
  'success' => visual_effect('opacity', 'event_save_ok', array('duration' => '3.0', 'from' => '1.0', 'to' => '0.0'))
)) ?>

<?php echo object_input_hidden_tag($event, 'getId') ?>


<div id="remember_save_event" style="display: none; position: absolute; color:red; border: 1px solid red; padding: 5px; background-color:#fdc; font-weight:bold; right: 20px;">
  <?php echo __('Pulse OK para que el cambio tenga efecto')?>
</div>


<ul class="tv_admin_actions" style="width: 100%; text-align: left;">
  <li><?php echo submit_tag('OK','name=OK class=tv_admin_action_save onclick=if ((comprobar_form_event($("date").value, '. get_js_regexp_timedate($sf_user->getCulture()) . ')) && (check_pass($("password"), $("password2"), ($("secured").value + 1)))){$(\'remember_save_event\').hide(); transfer_info();}else{return false}'); ?></li>
  <li><?php echo reset_tag('Reset','name=reset class=tv_admin_action_delete onclick=$(\'remember_save_event\').hide()'); ?></li>
  <span id="event_save_ok" style="color:blue; opacity:0.0; filter: alpha(opacity=0); ZOOM:1">Guardado OK</span>
  <span id="event_save_error" style="color:red; opacity:0.0; filter: alpha(opacity=0); ZOOM:1">Guardado ERROR</span>
</ul>



<fieldset>

<div class="form-row">
  <?php echo label_for('title', 'Título:', 'class="required" ') ?>
  <div class="content">
      <?php echo object_input_tag($event, 'getTitle', array (
        'size' => '80',
        'control_name' => 'title',
        'onchange' => "$('remember_save_event').show()"
      )); ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('description', 'Descripción:', 'class="required" ') ?>
  <div class="content">
      <?php echo object_textarea_tag($event, 'getDescription', array (
        'size' => '68x2',
        'control_name' => 'description',
        'onchange' => "$('remember_save_event').show()"
      )); ?>
  </div>
</div>



<div class="form-row">
   <?php echo label_for('author', 'Autor:', 'class="required" ') ?>
  <div class="content">
   <?php echo object_input_tag($event, 'getAuthor', array (
        'size' => '80',
        'control_name' => 'author',
        'onchange' => "$('remember_save_event').show()"
	)); ?>
  </div>
</div>


<div class="form-row">
   <?php echo label_for('producer', 'Realizador:', 'class="required" ') ?>
  <div class="content">
   <?php echo object_input_tag($event, 'getProducer', array (
        'size' => '80',
        'control_name' => 'producer',
        'onchange' => "$('remember_save_event').show()"
	)); ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('date', 'Fecha y hora de inicio: ', 'class="required" ') ?>
  <div class="content">
    <?php echo object_input_date_tag($event, 'getDate', array (
      'rich' => true,
      'withtime' => true,
      'calendar_button_img' => '/images/admin/buttons/date.png',
      'control_name' => 'date',
      'onchange' => "$('remember_save_event').show()"
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
      'onchange' => "$('remember_save_event').show()"
    )); echo $value ? $value : '&nbsp;' ?>
  </div>
</div>



<div class="form-row">
  <?php echo label_for('external', 'Otro streaming:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getExternal', array (
        'onchange' => "$('remember_save_event').show(); test_external();"
    )) ?>
   <span style ="padding-left: 20px;">
   <?php echo object_input_tag($event, 'getUrl', array (
        'size' => '80',
        'control_name' => 'url',
        'disabled' => ($event->getExternal() == false)? 'diabled':'',
        'onchange' => "$('remember_save_event').show()",
        'placeholder' => 'Url del streaming...',
	)); ?>
    </span>
    </div>
</div>


<div class="form-row">
  <dl style="margin: 0px">
    <dt>Im&aacute;genes:</dt>
    <dd>  
      <div id="pic_events">
         <?php include_component('pics', 'list', array('event' => $event->getId())) ?>
       </div>
      <div style="clear : left"></div>
    </dd>
  </dl>
</div>


<div class="form-row">
  <?php echo label_for('enableQuery', 'Consultas:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getEnableQuery', array (
        'onchange' => "$('remember_save_event').show(); test_query();"
    )) ?>
    <span style ="padding-left: 20px;">
    <?php echo object_input_tag($event, 'getEmailQuery', array (
        'size' => '80',
        'control_name' => 'emailQuery',
        'disabled' => ($event->getEnableQuery() == false)? 'diabled':'',
        'onchange' => "$('remember_save_event').show()",
        'placeholder' => 'Email para las consultas...'
	)); ?>
     </span>
    </div>
</div>


<div class="form-row">
  <?php echo label_for('display', 'Anunciar:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getDisplay', array (
  	        'onchange' => "$('remember_save_event').show()"
    )) ?>
    </div>
</div>


<div class="form-row">
  <dt>Materiales:</dt>
     <dd>  
         <div id="materials_events">
	    <?php include_component('materialevents', 'list', array('event' => $event->getId())) ?>              
         </div>
     </dd>
</div>


<div class="form-row">
 <?php echo label_for('secured', 'Protegido con contraseña:', 'class="required" ') ?>
  <div class="content">
    <?php echo object_checkbox_tag($event, 'getSecured', array (
        'onchange' => "$('remember_save_event').show(); test_secured();"
    )) ?>

   <span style ="padding-left: 20px;">
   <?php echo object_input_tag($event, 'getPassword', array (
	'type' => 'password',
        'placeholder' => 'Introduce una contraseña...',
        'size' => '40',
        'control_name' => 'password',
        'disabled' => ($event->getSecured() == false)? 'diabled':'',
        'onchange' => "check_pass($('password'), $('password2'), ($('secured').value + 1)); $('remember_save_event').show()"
	)); ?>
   <?php echo object_input_tag($event, 'getPassword', array (
	'type' => 'password',
        'placeholder' => 'Repite la contraseña...',
        'size' => '40',
        'control_name' => 'password2',
        'disabled' => ($event->getSecured() == false)? 'diabled':'',
        'onchange' => "check_pass($('password'), $('password2'), ($('secured').value + 1)); $('remember_save_event').show()"
	)); ?>
    <input type="button" style="background-color: #ffc; border-right: 1px solid #ddd !important; padding-left: 20px;" class="tv_admin_action_save" id="view_passwd" onclick="toggleName(this); replaceType(password); replaceType(password2); return false;" value="Ver contraseña" />
    <input type="button" style="background-color: #ffc; border-right: 1px solid #ddd !important; padding-left: 20px;" class="tv_admin_action_save" id="gen_passwd" onclick="genpasswd($('password'),$('password2')); return false;" title="Genera una contraseña aletaoria" value="Generar contraseña" />
    <span id="passwdFail" style="display: none; color: red; margin: 0px 10px;">Las contraseñas deben ser iguales.</span>
   </span>
   </div>
</div>


</fieldset>
</form>
</div>

<script type="text/javascript">
function transfer_info(){
   if (document.getElementById("title_es")){
     if ((title_es.value == "Nuevo") && (description_es.value == "")) {
       title_es.value = title.value;
       description_es.value = description.value;
       subtitle_es.value = title.value;
     }
   }
 }

function test_query(){
  if ($("enable_query").checked == 1) {
    $("emailQuery").disabled = false;
  }
  else {
    $("emailQuery").disabled = true;
    $("emailQuery").value = '';
  }
}

function test_external(){
  if ($("external").checked == 1) {
    $("url").disabled = false;
  }
  else {
    $("url").disabled = true;
    $("url").value = '';
  }
}

function test_secured(){
  if ($("secured").checked == 1) {
     $("password").disabled = false;
     $("password2").disabled = false;
  }
  else {
    $("password").disabled = true;
    $("password").value = '';
    $("password2").disabled = true;
    $("password2").value = '';
  }
 
}
</script>