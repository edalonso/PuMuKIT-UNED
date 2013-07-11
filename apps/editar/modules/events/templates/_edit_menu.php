<span class="trans_button" onclick="$('list_pics_<?php echo $event->getId()?>').toggle()"><?php echo image_tag('admin/mbuttons/edit_inline.gif', 'alt=opciones title=opciones')?>
<div class="trans_menu" id="list_pics_<?php echo $event->getId()?>" style="display:none">

  <div class="mas_info" style="">
    <div class="trans_button_up"><img src="/images/admin/mbuttons/edit_inline.gif" alt="opciones" /></div>
    <div class="trans_button_info">Opciones:</div>
  </div>

  <div class="list_options">
    <ul style="">

      <li class="normal">
       <?php echo m_link_to(image_tag('admin/mbuttons/edit_inline.gif', 'alt="cambiar serie" title="cambiar serie" class=miniTag'). 'Cambiar serie asociada', 'events/listAutoComplete?edit=1&event='.$event->getId(), array(
         	   'title' => 'Cambiar serie asociada al teleacto'),
		   array('width' => '800'))?>

      </li>

      <li class="normal">
        <?php echo link_to_remote(image_tag('admin/mbuttons/copy_inline.gif', 'alt=copiar title=copiar class=miniTag'). 'Clonar teleacto', array(
                   'update' => 'list_events', 
                   'url' => 'events/copy?id='.$event->getId(), 
                   'script' => 'true'))?>

      </li>

      <li class="normal">
        <?php echo link_to(image_tag('admin/mbuttons/delete_inline.gif', 'alt=borrar title=borrar class=miniTag'). ' Borrar teleacto', 
			   'events/delete?id='.$event->getId(), 
			   'post=true&confirm=¿Seguro que desea borrar el teleacto?'
			   )?>
      </li>

      <li class="normal">
        <?php echo link_to(image_tag('admin/mbuttons/delete_inline.gif', 'alt=borrar title=borrar class=miniTag'). ' Borrar teleacto y serie asociada',
				  'events/deleteS?id='.$event->getId(),
				  'post=true&confirm=¿Seguro que desea borrar el teleacto y su serie asociada?'
                   )?>
      </li>

      <li class="cancel"><a href="#" onclick="return false;">Cancelar...</a></li>

    </ul>
  </div>
 </div>
</span>
