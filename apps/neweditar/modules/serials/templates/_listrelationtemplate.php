<table>
 <tdody>
  <?php $t = count($persons) ; for( $i=0; $i<$t; $i++): $person = $persons[$i] //idea primer y ultimo apate ?>  
    <tr>
      <td><ul><li></li><ul></td>
      <td><?php echo m_link_to(image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar class=miniTag'), 'serials/edit?template=true&id='.$person->getId().'&role='.$role->getId().'&mm='.$mm->getId(), array('title' => 'Editar Persona "'.$person->getHName() .'"'), array('width' => '800')) ?><td>
      <td><?php echo link_to_remote(image_tag('admin/mbuttons/delete_inline.gif', 'alt=quitar title=quitar class=miniTag'), array('update' => $role->getId().'_person_mms', 'url' => 'serials/deleterelation?template=true&id='.$person->getId().'&role='.$role->getId().'&mm='.$mm->getId().'&preview=true', 'script' => 'true', 'confirm' => 'Seguro que desea borrar la relacion de "' . $person->getHName() . '"?'))?></td>
      <td><?php echo (( $i == 0) ? '&nbsp;' : (link_to_remote('&#8593;', array('update' => $role->getId() . '_person_mms', 'url' => 'serials/up?template=true&id='.$person->getId().'&role='.$role->getId().'&mm='.$mm->getId().'&preview=true', 'script' => 'true'))))   ?></td>
      <td><?php echo (( $i == $t-1)? '&nbsp;' : (link_to_remote('&#8595;', array('update' => $role->getId() . '_person_mms', 'url' => 'serials/down?template=true&id='.$person->getId().'&role='.$role->getId().'&mm='.$mm->getId().'&preview=true', 'script' => 'true')))) //dos espacios para misma anchura que flecha?></td>
      <td>&nbsp;<?php echo $person->getId(); ?> - <?php echo $person->getHName(); ?></td>
    </tr>
  <?php endfor; ?>
  <tr>
    <td><ul><li></li><ul></td>
    <td colspan="6"><?php echo m_link_to('nuevo...', 'serials/listAutoComplete?template=true&role='.$role->getId().'&mm='.$mm->getId(), array('title' => 'Crear '.$role->getName() ), array('width' => '800')) ?></td>
  </tr>
 </tbody>
</table>

<?php 
if (($sf_request->getParameter('preview')) || ((isset($preview))&&($preview))){
?>
<script type="text/javascript">
$(function reload_preview(){
   $.ajax({
      method: 'POST',
      url: '<?php echo url_for('serials/previewMms')?>',
      data:{update: 'preview_mm', id: '<?php echo $mm->getId()?>'},
      success: function(e) {
         $('#preview').html(e);
      }
   });
});
</script>
<?php
}
if (isset($msg_alert)) echo m_msg_alert_jquery($msg_alert);
?>