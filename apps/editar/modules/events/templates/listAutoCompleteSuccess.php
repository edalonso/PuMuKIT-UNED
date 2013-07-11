<?php use_helper('Object') ?>

<div id="tv_admin_container">

<p>
Escriba el nombre de la serie que desea utilizar. En caso de que ya exista en la base de datos aparecer&aacute; en una lista depegable, donde usted puede selecionarla y <strong>usarla</strong>. Si no existe en la base de datos <strong>cree</strong> una entrada nueva con el nombre escrito.
</p>

<fieldset>
  
  <div class="form-row">
    <?php echo label_for('serie', 'Serie:', 'class="required" ') ?>
    <div class="content">
    
      <input type="text" name="name" id="name" value="serie a buscar" autocomplete="on" size="80" />
      <span id="indicator1" style="display: none"><?php echo image_tag('admin/load/spinner.gif', 'size=18x18 alt=trabajando...') ?></span>
      <div id="name_auto_complete" class="auto_complete" style="display:none"></div>
  
    </div>
  </div>
  
</fieldset>


<ul class="tv_admin_actions">
  <li>
    <input class="tv_admin_action_create" onclick="
      if(/^\d+ - /.test($('name').value)) { 
        new Ajax.Updater( <?php if($edit): ?>
                         'serialEventDiv', 
                         '<?php echo url_for('events/changeSerial')?>/event/<?php echo $event?>/serial/'+parseInt($('name').value), 
                         {asynchronous:true, evalScripts:true, onComplete:click_fila_edit('event', $('this_tr_td_<?php echo $event ?>'), <?php echo $event ?>)}
                         <?php else: ?>
                         'body_div',
                         '<?php echo url_for('events/createFromSerial')?>/serial/'+parseInt($('name').value), 
                         {asynchronous:true, evalScripts:true}
                         <?php endif ?>

        ); 
        Modalbox.hide();   
      }else{
        alert('Selecione antes una serie');
      }
      return false;" 
    type="button" value="Usar" />
  </li>

  <li>
    <?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?>
  </li>
</ul>

</div>
 
<div style="clear:right"></div>






<?php echo javascript_tag("
  if($('MB_content')) $('MB_content').setStyle({ 'position' : 'static' });
  new Ajax.Autocompleter('name', 'name_auto_complete', '/editar.php/events/autoComplete', {minChars: 2, indicator: 'indicator1'});
  $('name').focus();  $('name').select();
") ?>