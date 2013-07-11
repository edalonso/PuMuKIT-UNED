<div>
  <div id="tv_admin_container" style="width:100%">

Cambiar la difusion de los objetos multimedia de la serie: 
<div style="margin-bottom: 15p; font-size: 200%; color: #666666">&laquo;<?php echo $serial->getTitle() ?>&raquo;</div>


<fieldset>
<?php if($sf_user->getAttribute('user_type_id', 1) == 0) :?>


  <div class="form-row">
    <?php echo label_for('mm', 'Obj. MM:', 'class="required" ') ?>
    <div class="content" style="height: 80px; overflow-y: scroll">
  
      <table id="table_mms_change_pub" style="width:97%; border: 1px solid #000; padding: 1%;">
        <tbody>
      
          <?php foreach($serial->getMms() as $mm): ?>
          <tr>
            <td><input id="<?php echo $mm->getId()?>" class="change_pub_mms" type="checkbox" checked="checked"> </td>
            <td><?php echo $mm->getId()?> </td>
            <td><?php echo $mm->getTitle() ?> </td> 
            <td><?php echo $mm->getStatusText() ?> </td>
          </tr>
          <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <div style="text-align: right">Seleccionar: 
      <a href="#" onclick="$('table_mms_change_pub').select('input.change_pub_mms').each(function(s){s.checked=false});return false">nada</a> 
      <a href="#" onclick="$('table_mms_change_pub').select('input.change_pub_mms').each(function(s){s.checked=true});return false">todo</a>
    </div>
  </div>


<div class="form-row">
  <?php echo label_for('status', 'Estado:', 'class="required long" ') ?>
  <div class="content content_long">
    <div style="float:right"> </div>


<!-- SELECT -->
<select id="status_select" name="status" id="filters_anounce">
  <option <?php echo (($mm->getStatusId() == 0)?'selected="selected"':''); ?>value="0" >Bloqueado</option>
  <option <?php echo (($mm->getStatusId() == 1)?'selected="selected"':''); ?>value="1" >Oculto</option>
  <option <?php echo (($mm->getStatusId() == 2)?'selected="selected"':''); ?>value="2" >Mediateca</option>
  <option <?php echo (($mm->getStatusId() == 3)?'selected="selected"':''); ?>value="3" >Mediateca y Arca</option>
  <option <?php echo (($mm->getStatusId() == 4)?'selected="selected"':''); ?>value="4" >Mediateca, Arca e iTunes</option>
</select>

<a href="#" onclick="$('#pub_mm_info').load('<?php echo url_for('serials/updatePub')?>', parameters: 'status=' + $('status_select').value + '&ids=' + $$('.change_pub_mms:checked').invoke('getAttribute', 'id').toJSON()); return false">
Aplicar estado a todos los Obj. MM. seleccionados</a>
<!-- END SELECT -->

    
  </div>
  <div id="pub_mm_info" style="width: 99%; padding:10px;"></div>
</div>
<!-- else avisar para publicar-->




<div class="form-row">
  <div style="float: right">
    <?php if(!is_null($serial->getFirstPic())): ?>
      <a target="_blank" href="<?php echo url_for('pics/itunes?id='.$serial->getFirstPic()->getId()) ?>">Descasrgar Imagen iTunesU</a>
    <?php endif ?>

  </div>

  <?php echo label_for('itunesu', 'iTunes U:', 'class="required long" ') ?> 
  <div class="content content_long">
    <?php if(count($serial->getSerialItuness()) == 0):?>
      <a href="#" onclick="
    $('#itunes_mm_info').load('<?php echo url_for('mms/ituneson?id=' . $mm->getId())?>',
    onLoading: $('itunes_mm_info').innerHTML = 'cargando, por favor espere',
  ); 
  return false;
">Publicar en itunes U.</a>
    <?php else:?>
      <a href="#" onclick="$('#itunes_mm_info').load('<?php echo url_for('mms/itunesoff?id=' . $mm->getId())?>'); return false;">Quitar de itunes U.</a>
    <?php endif?>

  </div>
  <div id="itunes_mm_info" style="width: 99%; padding:10px;">
    <?php include_partial('mms/itunes_list', array('itunes' => $serial->getSerialItuness()))?>
  </div>

</div>

<?php endif ?>
</fieldset>


    <ul class="tv_admin_actions">
      <li><?php echo button_to_function('Cerrar', "$('#list_serials').load('".url_for('serials/list')."'); Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li>
    </ul> 

  </div>
</div>