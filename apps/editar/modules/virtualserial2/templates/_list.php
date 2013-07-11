<div style="width: 100%; padding: 2px;">
<div style="overflow: hidden;">
    <select id="options_mms" style="margin: 10px 2px; width: 9%; float:left; background-color: #ffc; overflow: hidden;" title="Acciones sobre elementos selecionados" onchange="window.change_select('mm', $('options_mms'))">
      <option value="default" selected="selected">Seleciona una acci&oacute;n...</option>
      <option disabled="">---</option>

      <option value="delete_sel">Borrar selecionados</option>
      <option value="inv_announce_sel">Anunciar/Desanunciar selecionados</option>
      <option disabled="">---</option>
      <option disabled=""value="set_status_0_sel">Bloquear selecionados</option> 
      <option disabled=""value="set_status_1_sel">Ocultar selecionados</option> 
      <option disabled=""value="set_status_2_sel">Publicar selecionados</option> 
      <option disabled=""value="set_status_3_sel">Publicar totalmente selecionados</option>
    </select>
<div id="faceted_search">
<?php include_partial('search', array('years' => $sf_data->getRaw('years'),
                                      'genres' => $sf_data->getRaw('genres'))) ?>
</div>
    <div style="float:right;">
      <ul class="tv_admin_actions">
        <li style="float: left;">
          <?php echo link_to_function('Wizard', "Modalbox.show('".url_for("wizard/serial")."',{width: 800, title:'PASO I: Series Virtuales'})", 'class=tv_admin_action_next') ?>
        </li>
      </ul>
    </div>
</div>

<table cellspacing="0" class="tv_admin_list" style="float:left; margin-bottom: 5px;">
  <thead>
    <tr>
      <th width="1%">
        <input type="checkbox" onclick="window.click_checkbox_all('mm', this.checked)">
      </th>
      <th colspan="4" width="5%"></th>
      <?php if(sfConfig::get('app_mail_use')):?>
      <th width="2%"></th>
      <?php endif?>
      <th width="1%">Audio/Video</th>
      <th width="1%">Img</th>
      <?php include_partial('list_th') ?>
    </tr>
  </thead>

  <tbody>
  <?php if (count($mms) == 0):?>
    <div style="position: absolute; top: 250px; left: 33%; font-size: 20px; width: 25%; font-weight: bold;">
       <p>No existen objetos multimedia con esos valores.</p>
    </div>
  <?php endif; ?>
  <?php $t = count($mms) ; for( $i=0; $i<$t; $i++): $mm = $mms[$i]; $odd = fmod($i, 2) ?>
     <tr onmouseover="Element.addClassName(this,'tv_admin_row_over')" onmouseout="Element.removeClassName(this,'tv_admin_row_over')" class="tv_admin_row_<?php echo $odd ?><?php if($mm['id'] == $sf_user->getAttribute('id', null, 'tv_admin/virtualserial')) echo ' tv_admin_row_this'?>">
      <td style="padding: 0.2%;">
        <input id="<?php echo $mm['id']?>" class="mm_checkbox" type="checkbox">
      </td>
      <td style="padding: 0.2%;">
        <?php echo image_tag('admin/bbuttons/mm'.$mm['status'].'_inline.gif', 'alt='.$mm['status'].' title=estado class=miniTag id=table_mms_status_' . $mm['id']) ?>
      </td>
      <td style="padding: 0.2%;">
        <?php echo ($mm['announce']?'<span style="color: blue">A</span>':'&nbsp;') ?>
      </td> 
       <td style="padding: 0.2%;">
        <?php echo link_to_remote(image_tag('admin/mbuttons/delete_inline.gif', 'alt=borrar title=borrar class=miniTag'), array('update' => 'list_mms', 'url'=> 'mms/delete?id='.$mm['id'], 'script' => 'true', 'confirm' => 'Seguro que desea borrar el objeto multimedia?', 'success' => '$("vista_previa_mm").innerHTML=""; $("edit_mms").innerHTML="" '));?>
      </td>
      <td style="padding: 0.2%;">
        <?php echo link_to_remote(image_tag('admin/mbuttons/copy_inline.gif', 'alt=copiar title=copiar class=miniTag'), array(
                   'update' => 'list_mms', 
                   'url' => 'mms/copy?id='.$mm['id'], 
                   'script' => 'true'))?>
      </td>
      <?php if(sfConfig::get('app_mail_use')):?>
      <td style="padding: 0.2%;">
        <?php include_partial("mms/edit_announce", array('mm' => $mm))?>
      </td>
      <?php endif?>
      <td style="padding: 0.2%;">
         <span><?php echo ($mm['audio']) ? 'Audio':'Video'?></span>
      </td>


      <td onclick="click_fila_virtualserial(this, <?php echo $mm['id'] ?>)" style="padding: 0.2%;">
        <?php echo image_tag($mm['pic_url'], 'class=mini size=30x23')?>
      </td>
      <td onclick="click_fila_virtualserial(this, <?php echo $mm['id'] ?>)" style="padding: 0.2%;">
        <?php echo $mm['id']?>
      </td>
      <td onclick="click_fila_virtualserial(this, <?php echo $mm['id'] ?>)" style="padding: 0.2%;">
        <?php $value = $mm['title']; echo $value ? $value : '&nbsp;'  ?>
      </td>
      <!--<td style="padding: 0.2%;">
         <?php $category_name = $sf_user->getAttribute('name_cat', null, 'tv_admin/virtualserial');?>
         <abbr title="<?php echo $category_name?>"><?php //echo (strlen($category_name)>10)? substr($category_name, 0, 10):$category_name;?></abbr>
      </td>-->
      <td style="padding: 0.2%;">
        <?php echo FilePeer::getDurationString($mm['duration']); ?>
      </td>
      <td onclick="click_fila_virtualserial(this, <?php echo $mm['id'] ?>)" style="padding: 0.2%;">
        <?php echo $mm['publicdate']; ?>
      </td>
      <td onclick="click_fila_virtualserial(this, <?php echo $mm['id'] ?>)" style="padding: 0.2%;">
        <?php echo $mm['recorddate']; ?>
      </td>
    </tr>
  <?php endfor; ?>
  <?php if ($t<11): ?>
    <?php for ($i=0;$i<(11-$t); $i++): $odd = fmod($i, 2)?>
       <tr onmouseover="Element.addClassName(this,'tv_admin_row_over')" onmouseout="Element.removeClassName(this,'tv_admin_row_over')" class="tv_admin_row_<?php echo $odd ?>"><td style="height: 23px; padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td><td style="padding: 0.2%;"></td></tr>
    <?php endfor; ?>
  <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="14">
        <div class="float-right">
          <?php include_partial('virtual_pager_ajax', array('name' => 'mm', 'page' => $page, 'total' => $total)) ?> 
        </div>
        <?php echo $total_mm ?> Obj. Mm.
      </th>
    </tr>
  </tfoot>
</table>
</div>
<?php if (isset($msg_alert)) echo m_msg_alert($msg_alert) ?>

<?php if (isset($reload_pub_channel)): ?>
  <?php echo javascript_tag("
    new Ajax.Updater('list_pub_" . $mm_sel->getId() . "', '" . url_for('mms/updatelistpub?id=' . $mm_sel->getId()) . "')
  "); ?>
<?php endif ?>

<?php if(isset($reloadEditAndPreview)): ?>
<?php echo javascript_tag("
     new Ajax.Updater('edit_mms','". url_for('virtualserial/edit') . "', { asynchronous:true, evalScripts:true, parameters: {id: " . $sf_user->getAttribute('id', null, 'tv_admin/virtualserial') . "}});
     new Ajax.Updater('preview_mm','". url_for('virtualserial/preview') . "', { asynchronous:true, evalScripts:true, parameters: {id: " . $sf_user->getAttribute('id', null, 'tv_admin/virtualserial') . "}});
"); ?>
<?php endif ?>


<?php if (isset($enBloq)): ?>
  <?php echo javascript_tag("
    $('list_pub_channel').setStyle('background-color: #f2f2f2');
    $$('.pub_channel_input_checkbox').invoke('disable');
  "); ?>
<?php endif ?>


<?php if (isset($desBloq)): ?>
  <?php echo javascript_tag("
    $('list_pub_channel').setStyle('background-color: transparent');
    $$('.pub_channel_input_checkbox').invoke('enable');
  "); ?>
<?php endif ?>

