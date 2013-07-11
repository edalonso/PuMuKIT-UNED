<table cellspacing="0" class="tv_admin_list">
  <thead>
    <tr>
      <th width="1%"></th>
      <th colspan="2" width="10%"></th>
      <th width="3%">Img</th>
      <?php include_partial('list_th') ?> 
      <th width="1%">P&uacute;blico</th>
    </tr>
  </thead>
  
  <tbody>
  <?php if (count($events) == 0):?>
    <tr>
      <td colspan="8">
       No existen eventos con esos valores.
      </td>
    </tr>
  <?php endif; ?>
  <?php $i = 1; foreach ($sf_data->getRaw('events') as $event): $odd = fmod(++$i, 2)?>
    <tr onmouseover="Element.addClassName(this,'tv_admin_row_over')" onmouseout="Element.removeClassName(this,'tv_admin_row_over')" class="tv_admin_row_<?php echo $odd ?><?php if($event->getId() == $sf_user->getAttribute('id', null, 'tv_admin/event')) echo ' tv_admin_row_this'?>" >
      <td><?php echo m_link_to(image_tag('admin/mbuttons/info_inline.gif', 'alt=info title=info'), 'events/info?id='.$event->getId(), array('title' => 'Información del teleacto '.$event->getId()), array('width' => '800')) ?></td>
      <td>
       <?php include_partial("events/edit_menu", array('event' => $event))?>
      </td>
      <td>
        <?php echo link_to('Vídeos de la serie', 'mms/index?serial=' . $event->getSerialId())?>
      </td>
      <td onclick="click_fila_edit('event', this, <?php echo $event->getId() ?>);">
	<?php echo image_tag($event->getFirstUrlPic(), 'class=mini size=30x23')?>
      </td>
      <td onclick="click_fila_edit('event', this, <?php echo $event->getId() ?>);">
        <?php echo $event->getId() ?>
      </td>
      <td onclick="click_fila_edit('event', this, <?php echo $event->getId() ?>);">
        <?php $value = $event->getTitle(); echo $value ? $value : '&nbsp;'  ?>
      </td>
      <td onclick="click_fila_edit('event', this, <?php echo $event->getId() ?>);">
        <?php echo $event->getDate('d/m/Y'); ?>
      </td>    
      <td id="this_tr_td_<?php echo $event->getId()?>" onclick="click_fila_edit('event', this, <?php echo $event->getId() ?>);">
        <?php $value = $event->getDisplay(); echo $value ? $value : '&nbsp;'  ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="13">        
        <div class="float-left" style="padding-right: 5px">
         <!-- <a class="azul" title="Lista" href="<?php echo url_for('events/index')?>" style="color:grey">Lista</a> -->
         <!-- <a class="azul" title="calendario" href="<?php echo url_for('events/index?cal=cal')?>" style="color:blue">Calendario</a> -->
        </div>
        <div class="float-right">
          <?php include_partial('global/pager_ajax', array('name' => 'event', 'page' => $page, 'total' => $total)) ?> 
        </div>
        <?php echo $total_event ?>/<?php echo $total_event_all ?> Eventos
        <?php $aux = ($total_event==$total_event_all?'display:none; ':'')?>
        <?php echo link_to_remote('Cancelar busqueda', array('before' => '$("filter_events").reset();', 'update' => 'list_events', 'url' => 'events/list?filter=filter ', 'script' => 'true'), array('title' => 'Cancelar la busqueda actual', 'style' => 'color:blue; font-weight:normal;'.$aux)) ?>
      </th>
    </tr>
  </tfoot>
</table>


<?php if (isset($msg_alert)) echo m_msg_alert($msg_alert) ?>
