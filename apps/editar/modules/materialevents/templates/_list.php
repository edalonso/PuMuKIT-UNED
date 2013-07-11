<table><tdody>
  <?php $t = count($materials) ; for( $i=0; $i<$t; $i++): $material = $materials[$i] //idea primer y ultimo apate ?>  
    <tr>
      <td><ul><li></li><ul></td>
      <td><?php echo m_link_to(image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar'), 'materialevents/edit?id='.$material->getId().'&event='.$event, array('title' => 'Editar Archivo de Event '.$material->getId()), array('width' => '800')) ?><td>
      <td><?php echo link_to_remote(image_tag('admin/mbuttons/delete_inline.gif', 'alt=borrar title=borrar'), array(
														    'update' => 'materials_events', 'url' => 'materialevents/delete?id='.$material->getId().'&event='.$event.'&preview=true', 'script' => 'true', 'confirm' => '&iquest;Seguro?',
														    'complete' => 'updatePreview()'
														    ))?></td>
      <td><?php echo link_to(image_tag('admin/mbuttons/download_inline.gif', 'alt=descargar title=descargar'), $material->getUrl(true), array('target' => '_blank'))?></td>
      <td><?php echo (( $i == 0) ? '&nbsp;' : (link_to_remote('&#8593;', array(
									       'update' => 'materials_events', 'url' => 'materialevents/up?id='.$material->getId().'&event='.$event.'&preview=true', 'script' => 'true',
									       'complete' => 'updatePreview()'
									       ))))   ?></td>
      <td><?php echo (( $i == $t-1)? '&nbsp;' : (link_to_remote('&#8595;', array(
										 'update' => 'materials_events', 'url' => 'materialevents/down?id='.$material->getId().'&event='.$event.'&preview=true', 'script' => 'true',
										 'complete' => 'updatePreview()'
										 )))) //dos espacios para misma anchura que flecha?></td>
      <td>
        &nbsp;
        <?php echo $material->getId(); ?> - 
        <?php echo $material->getName(); ?>
        <span style="font-size: 80%; font-style:italic;"><?php echo ($material->getDisplay())?'':'(Oculto)'?></span>
      </td>
    </tr>
  <?php endfor; ?>
  <tr>
    <td><ul><li></li><ul></td>
    <td colspan="6"><?php echo m_link_to('nuevo...', 'materialevents/create?event='.$event, array('title' => 'Crear Material'), array('width' => '800')) ?></td>
  </tr>
</tbody></table>
