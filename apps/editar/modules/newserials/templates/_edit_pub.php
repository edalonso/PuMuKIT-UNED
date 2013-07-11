<?php use_helper('Object') ?>

<div id="tv_admin_container" style="padding: 4px 20px 20px">

<div style="height:41px"></div>

<fieldset id="tv_fieldset_none" class="">


<?php if($sf_user->getAttribute('user_type_id', 1) == 0) :?>
<div class="form-row">
  <?php echo label_for('status', 'Estado:', 'class="required long" ') ?>
  <div class="content content_long">
    <div style="float:right"> </div>


<!-- SELECT -->
<select name="status" id="filters_anounce" onchange="
new Ajax.Updater('pub_mm_info', '<?php echo url_for('mms/update_pub?id=' . $mm->getId())?>/status/' + this.value, {
  asynchronous:true, 
  evalScripts:true,
})">
  <?php echo (($mm->getStatusId() == -50)?'<option selected="selected" value="-50" >Bloquedo codificando</option>':''); ?>
  <?php echo (($mm->getStatusId() == -49)?'<option selected="selected" value="-49" >Oculto codificando</option>':''); ?>
  <?php echo (($mm->getStatusId() == -48)?'<option selected="selected" value="-48" >Mediateca codificando</option>':''); ?>
  <?php echo (($mm->getStatusId() == -47)?'<option selected="selected" value="-47" >Arca codificando</option>':''); ?>

  <?php echo (($mm->getStatusId() == -20)?'<option selected="selected" value="-20" >Dueno bloq.(Bloquedo)</option>':''); ?>
  <?php echo (($mm->getStatusId() == -19)?'<option selected="selected" value="-19" >Dueno bloq.(Oculto)</option>':''); ?>
  <?php echo (($mm->getStatusId() == -18)?'<option selected="selected" value="-18" >Dueno bloq.(Mediateca)</option>':''); ?>
  <?php echo (($mm->getStatusId() == -17)?'<option selected="selected" value="-17" >Dueno bloq.(Arca)</option>':''); ?>

  <?php echo (($mm->getStatusId() == -10)?'<option selected="selected" value="-10" >Papelera(Bloquedo)</option>':''); ?>
  <?php echo (($mm->getStatusId() == -9)?'<option selected="selected" value="-9" >Papelera(Oculto)</option>':''); ?>
  <?php echo (($mm->getStatusId() == -8)?'<option selected="selected" value="-8" >Papelera(Mediateca)</option>':''); ?>
  <?php echo (($mm->getStatusId() == -7)?'<option selected="selected" value="-7" >Papelera(Arca)</option>':''); ?>

  <option <?php echo (($mm->getStatusId() == 0)?'selected="selected"':''); ?>value="0" >Bloqueado</option>
  <option <?php echo (($mm->getStatusId() == 1)?'selected="selected"':''); ?>value="1" >Oculto</option>
  <option <?php echo (($mm->getStatusId() == 2)?'selected="selected"':''); ?>value="2" >Mediateca</option>
  <option <?php echo (($mm->getStatusId() == 3)?'selected="selected"':''); ?>value="3" >Mediateca y Arca</option>
  <option <?php echo (($mm->getStatusId() == 4)?'selected="selected"':''); ?>value="4" >Mediateca, Arca e iTunes</option>
</select>
<!-- END SELECT -->


								 
</div>
<div id="pub_mm_info" style="width: 99%; padding:10px;"></div>
</div>
<!-- else avisar para publicar-->


<div class="form-row">
  <?php echo label_for('Youtube', 'YouTube:', 'class="required long" ') ?> 
  <div class="content content_long">
  <input type="checkbox" name="youtube" value="1"  id="youtube" <?php echo ($mm->getMmYoutube() === null)? '':'checked="checked"' ?> onchange="
new Ajax.Updater('youtube_mm_info', '<?php echo url_for('mms/update_yt?id=' . $mm->getId())?>/youtube/' + this.checked + '/list/'+ document.getElementById('YtLists').value, {
  asynchronous:true, 
  evalScripts:true,
})"> 
 
  </div>
<br />

 <?php echo label_for('Youtuebelist', 'Lista de reproducci&oacute;n:', 'class="required long" ')?>

<div class="content content_long">
    <div style="float:right"> </div>
    <select name="YtList" id="YtLists" onchange=" if (document.getElementById('youtube').checked) {
        new Ajax.Updater('youtube_mm_info', '<?php echo url_for('mms/update_yt?id=' . $mm->getId())?>/youtube/' + document.getElementById('youtube').checked + '/list/'+ this.value, {
                          asynchronous:true,
                          evalScripts:true,
                          })
        }">
<?php $listas = GroundPeer::doSelectYtList() ?>
  <?php foreach($listas as $lista): ?>
  <option value="<?php echo $lista ?>" <?php if ($mm->getMmYoutube() !== null) echo ($mm->getMmYoutube()->getYoutubePlaylist() == $lista)? 'selected="selected"':'' ?>><?php echo $lista ?></option>
  <? endforeach ?>
</select>
 <div id="youtube_mm_info" style="width: 99%; padding:10px;">
</div>


<!--
<div class="form-row">
  <?php echo label_for('itunesu', 'iTunes U:', 'class="required long" ') ?> 
  <div class="content content_long">
    <?php if(count($mm->getSerial()->getSerialItuness()) == 0):?>
      <a href="#" onclick="
  new Ajax.Updater('itunes_mm_info', '<?php echo url_for('mms/ituneson?id=' . $mm->getId())?>', {asynchronous:true, evalScripts:true}); return false;
">Publicar en itunes U.</a>
    <?php else:?>
      <a href="#" onclick="
  new Ajax.Updater('itunes_mm_info', '<?php echo url_for('mms/ituneson?id=' . $mm->getId())?>', {asynchronous:true, evalScripts:true}); return false;
">Quitar de itunes U.</a>
    <?php endif?>

  </div>
  <div id="itunes_mm_info" style="width: 99%; padding:10px;">
    <?php include_partial('mms/itunes_list', array('itunes' => $mm->getSerial()->getSerialItuness()))?>
  </div>
</div>
-->
<?php endif ?>


</fieldset>

</div>
