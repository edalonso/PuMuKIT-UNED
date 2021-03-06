<?php use_helper('Object', 'JSRegExp') ?>

<div id="tv_admin_container" style="position: relative">

<?php if(isset($serial)): ?>

  <div class="background_id">
    <?php echo $serial->getId() ?>
  </div>
  <br />


  <!-- actualizar vista previa -->
  <?php echo form_remote_tag(array( 
    'update' => 'list_serials', 
    'url' => 'serials/update',
    'script' => 'true',
    'failure' => visual_effect('opacity', 'serial_save_error', array('duration' => '3.0', 'from' => '1.0', 'to' => '0.0')),
    'success' => visual_effect('opacity', 'serial_save_ok', array('duration' => '3.0', 'from' => '1.0', 'to' => '0.0'))
                 .remote_function(array('update' => 'preview_serial', 'url' => 'serials/preview?id=' . $serial->getId(), 'script' => 'true' )),
  )) ?>
  
  
  <?php echo object_input_hidden_tag($serial, 'getId') ?>
  
  <div id="remember_save_serial" style="display: none; position: absolute; color:red; border: 1px solid red; padding: 5px; background-color:#fdc; font-weight:bold; right:20px">
    <?php echo __('Pulse OK para que el cambio tenga efecto')?>
  </div>

  <ul class="tv_admin_actions" style="text-align: left">
    <li><?php echo submit_tag('OK','name=OK class=tv_admin_action_save onclick=if(comprobar_form_serial($("publicdate").value, '. get_js_regexp_timedate($sf_user->getCulture()) . ')){$(\'remember_save_serial\').hide();}else{return false}'); ?></li>
    <li><?php echo reset_tag('Cancel','name=reset class=tv_admin_action_delete onclick=$(\'remember_save_serial\').hide()'); ?></li>
    <span id="serial_save_ok" style="color:blue; opacity: 0.0; filter: alpha(opacity=0);ZOOM:1">Guardado OK</span>
    <span id="serial_save_error" style="color:red; opacity: 0.0; filter: alpha(opacity=0); ZOOM:1">Guardado ERROR</span>
  </ul> 
  
  <fieldset id="tv_fieldset_none" class="">
    

  <div class="form-row">
   <?php echo label_for('announce', 'Novedad:', 'class="required long" ') ?>
   <div class="content content_long">
     <?php $value = object_checkbox_tag($serial, 'getAnnounce', array (
       'control_name' => 'announce',
       'onchange' => "$('remember_save_serial').show()",
     )); echo $value ? $value : '&nbsp;' ?>
    </div>
  </div>

  <div class="form-row">
    <label for="hide" class="required long">Oculto:</label>
    <div class="content content_long">
      <input type="checkbox" name="hide" id="hide" value="1" onchange="$('remember_save_serial').show()" <?php if(!$serial->getDisplay()):?> checked="checked" <?php endif ?> >
    </div>
  </div>

  <div class="form-row">
    <?php echo label_for('title' , 'T&iacute;tulo:', 'class="required long" ') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  

        <?php $value = object_input_tag($serial, 'getTitle', array (
          'size' => 80,
          'control_name' => 'title_' . $lang,
          'onchange' => "$('remember_save_serial').show()", 
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
        <?php $sep='<br /><br />'?>
      <?php endforeach; ?>
    </div>
  </div>
  
  
  <div class="form-row">
    <?php echo label_for('subtitle', 'Subt&iacute;tulo:', 'class="required long" ') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  
  
        <?php $value = object_input_tag($serial, 'getSubtitle', array (
          'size' => 80,
          'control_name' => 'subtitle_' . $lang,
          'onchange' => "$('remember_save_serial').show()",
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
        <?php $sep='<br /><br />'?>
      <?php endforeach; ?>
    </div>
  </div>
  
  
  <div class="form-row">
    <?php echo label_for('keyword_', 'Keywords:', 'class="required long" ') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  
  
        <?php $value = object_input_tag($serial, 'getKeyword', array (
          'size' => 80,
          'control_name' => 'keyword_' . $lang,
          'onchange' => "$('remember_save_serial').show()",
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
        <?php $sep='<br /><br />'?>
      <?php endforeach; ?>
    </div>
  </div>
  
  <div class="form-row">
    <?php echo label_for('copyright','Copyright:', 'class="required long" ') ?>
    <div class="content content_long">
      <?php $value = object_input_tag($serial, 'getCopyright', array (
        'size' => 30,
        'control_name' => 'copyright',
	'onchange' => "$('remember_save_serial').show()",
       )); echo $value ? $value : '&nbsp;' ?>
    </div>
  </div>
  
  <!-- <div class="form-row">
    <?php echo label_for('serial_type_id','Canal:', 'class="required long"') ?>
    <div class="content content_long">
      <?php $value = object_select_tag($serial, 'getSerialTypeId', array (
        'related_class' => 'SerialType',
        'control_name' => 'serial_type_id',
	'peer_method' => 'doSelectWithI18n',
        'include_blank' => false,
	'onchange' => "$('remember_save_serial').show()",
      )); echo $value ? $value : '&nbsp;' ?>
    </div>
  </div> -->

  <div class="form-row">
    <?php echo label_for('publicdate', 'Fecha de publicaci&oacute;n:', 'class="required long" ') ?>
    <div class="content content_long">
      <?php $value = object_input_date_tag($serial, 'getPublicdate', array (
        'rich' => true,
        'withtime' => true,
        'calendar_button_img' => '/images/admin/buttons/date.png',
        'control_name' => 'publicdate',
	'onchange' => "$('remember_save_serial').show()",
      )); echo $value ? $value : '&nbsp;' ?>
      <span id="error_date" style="display:none" class="error">Formato fecha no v&aacute;lido</span> 
    </div>
  </div>
  
  <div class="form-row">
    <?php echo label_for('description', 'Descripci&oacute;n:', 'class="required long"') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  
  
        <?php $value = object_textarea_tag($serial, 'getDescription', array (
          'size' => '80x3',
          'control_name' => 'description_' . $lang,
          'onchange' => "$('remember_save_serial').show()",
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
        <?php $sep='<br /><br />'?>
      <?php endforeach; ?>
    </div>
  </div>  
  
  <div class="form-row">
    <dl style="margin: 0px">
      <dt>Im&aacute;genes:</dt>
      <dd>  
        <div id="pic_serials">

          <?php include_component('pics', 'list', array('serial' => $serial->getId())) ?>        
          
        </div>
        <div style="clear : left"></div>
      </dd>
    </dl>
  </div>
  
  <div class="form-row">
   <a style="color:#666; text-decoration:underline" href="#" 
      onclick="$('html_text_cab', 'html_text_pie', 'html_text_arr_1' ,'html_text_arr_2').invoke('toggle'); return false">
     <span id="html_text_arr_1">&#9660</span> <span style="display:none" id="html_text_arr_2">&#9654;</span>
     Mostrar Textos <strong>HTML</strong> de configuracion. 
   </a>
  </div>

  <div class="form-row" style="display:none" id="html_text_cab">
    <?php echo label_for('header', 'Texto de Cabecera: <br/><strong>HTML</strong>', 'class="long"') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  
  
        <?php $value = object_textarea_tag($serial, 'getHeader', array (
          'size' => '80x3',
          'control_name' => 'header_' . $lang,
          'onchange' => "$('remember_save_serial').show()",
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
   
        <?php $sep='<br /><br />'?>
      <?php endforeach; ?>
    </div>
  </div>
  
  <div class="form-row" style="display:none" id="html_text_pie">
    <?php echo label_for('footer', 'Texto de Pie: <br/><strong>HTML</strong>', 'class="long"') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  
  
        <?php $value = object_textarea_tag($serial, 'getFooter', array (
          'size' => '80x3',
          'control_name' => 'footer_' . $lang,
          'onchange' => "$('remember_save_serial').show()",
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
        <?php $sep='<br /><br />'?>
      <?php endforeach; ?>
    </div>
  </div>


  <div class="form-row">
    <?php echo label_for('line2', 'Titular:', 'class="long"') ?>
    <div class="content content_long">
      <?php $sep =''; foreach ($langs as $lang): ?>
        <?php $serial->setCulture($lang);  echo $sep ?>  
        <?php $value = object_input_tag($serial, 'getLine2', array (
          'size' => 80,
          'control_name' => 'line2_' . $lang,
          'onchange' => "$('remember_save_serial').show()",
        )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
        <?php $sep='<br /><br/>'?>
      <?php endforeach; ?>
    </div>
  </div>


  <div class="form-row">
    <?php echo label_for('serial_template_id','Estructura:', 'class="long"') ?>
    <div class="content content_long">
      <?php $value = object_select_tag($serial, 'getSerialTemplateId', array (
        'related_class' => 'SerialTemplate',
        'control_name' => 'serial_template_id',
	'peer_method' => 'doSelect',
        'include_blank' => false,
	'onchange' => "$('remember_save_serial').show()",
      )); echo $value ? $value : '&nbsp;' ?>
    </div>
  </div>

  </fieldset>
  
  </form>
<?php endif?>
</div>








