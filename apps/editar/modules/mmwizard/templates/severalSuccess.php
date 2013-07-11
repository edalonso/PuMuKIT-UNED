<?php use_helper('Object') ?>

<div id="tv_admin_container">

<?php echo form_remote_tag(array( 
  'update' => 'list_mms', 
  'url' => 'mmwizard/endseveral',
  'script' => 'true',
)) ?>

<input type="hidden" name="serial_id" value="<?php echo $serial_id ?>" />
<input type="hidden" name="mod" value="<?php echo $mod ?>" />

<fieldset>


<!-- tendria que ser un radio button -->
<div class="form-row">
  <?php echo label_for('profile_id','Master:', 'class="required" ') ?>
  <!-- TODO poner automatico -->
  <div class="content" style="overflow: hidden">
    <?php echo (count($profiles) == 0?"&nbsp;":"") ?>
    <?php foreach($profiles as $profile): ?>
      <span style="display: block; float: left; width: 30%; overflow: hidden">
      <?php echo radiobutton_tag('master', $profile->getId(), 1) ?> <?php echo $profile->getName()?>
      </span>
    <?php endforeach?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('pub_channel_id','Canales de Pub.:', 'class="required" ') ?>

  <div class="content" style="overflow: hidden">
    <?php echo (count($pub_channels) == 0?"&nbsp;":"") ?>
    <?php foreach($pub_channels as $pub_channel): ?>
      <?php if($pub_channel->getEnable() == 0):?>
        <span style="display: block; float: left; width: 30%; overflow: hidden; color: grey">
          <input type="checkbox" disabled="disabled" />  <?php echo $pub_channel->getName()?>
        </span>
      <?php else:?>
        <span style="display: block; float: left; width: 30%; overflow: hidden">
          <?php echo checkbox_tag('pub_channel[]', $pub_channel->getId(), $pub_channel->getDefaultSel()) ?> <?php echo $pub_channel->getName()?>
        </span>
      <?php endif ?>
    <?php endforeach?>
  </div>
</div>



<div class="form-row">
  <?php echo label_for('priority','Prioridad:', 'class="required" ') ?>

  <div class="content">
    <input type="radio" value="1" name="prioridad"/> Low-Priority&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" checked="checked" value="2" name="prioridad"/> Normal-Priority&nbsp;&nbsp;&nbsp;&nbsp;
    <input type="radio" value="3" name="prioridad"/> High-Priority&nbsp;&nbsp;&nbsp;&nbsp;
  </div>
</div>


<div class="form-row">
  <?php echo label_for('language_id','Idioma de narración:', 'class="required" ') ?>

  <div class="content">
    <?php echo select_tag('idioma', objects_for_select(
      LanguagePeer::doSelect(new Criteria()),
      'getId',
      '__toString',
      LanguagePeer::getDefaultSelId()
    )) ?>
  </div>
</div>

<div class="form-row">
  <?php echo label_for('url','Directorio en el inbox:', 'class="required" ') ?>
  <div class="content">
    <input type="text" size="80" id="url" name="url" /> 
    <span id="error_no_url" style="display:none" class="error">Ruta erronea</span>
      <div id="explorer_videoserv_dir" class="videoserv">
      
      <ul class="videoserv_tree">
        <?php foreach(sfConfig::get('app_transcoder_inbox') as $dir):?>
        <li class="collapsed">
          <span onclick="dirServerTree2(this, 'url', '<?php echo $dir?>', 0, 'explorer_videoserv_dir')"><?php echo $dir?></span>
          <ul></ul>
        </li>
        <?php endforeach;?>
      </ul>

    </div>    
  </div>
</div>


</fieldset>


<ul class="tv_admin_actions">
  <li><?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li>
  <li><?php echo submit_tag('OK','name=OK class=tv_admin_action_save onclick=return comprobar_form_mmwizard_several($("url").value)'); ?></li>
</ul>

</form>
</div>
