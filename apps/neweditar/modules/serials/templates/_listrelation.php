<table>
 <tdody>
  <?php $t = count($persons) ; for( $i=0; $i<$t; $i++): $person = $persons[$i] //idea primer y ultimo apate ?>  
    <tr>
      <td><ul><li></li><ul></td>
      <td><a id="edit_person-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" href="#"><img alt="editar" title="editar" class="miniTag" src="/images/admin/mbuttons/edit_inline.gif"></a></td>
      <td><a id="delete_person-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" href="#"><img alt="quitar" title="quitar" class="miniTag" src="/images/admin/mbuttons/delete_inline.gif"></a></td>
      <td><?php echo (( $i == $t-1)? '&nbsp;' : '<a href="#"  id="down_person-' . $role->getId() . '-' . $person->getId() . '">↓</a>')?></td>
     <td><?php echo (( $i == 0)? '&nbsp;' : '<a href="#" id="up_person-' . $role->getId() . '-' . $person->getId() . '">↑</a>')?></td>
      <td>&nbsp;<?php echo $person->getId(); ?> - <?php echo $person->getHName(); ?></td>
    </tr>


    <div id="dialog-modal-form-edit-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" title="Editar Persona <?php echo $person->getHName()?>" data-ajax="true" data-url="/neweditar.php/serials/editPersons?id=<?php echo $person->getId()?>&role=<?php echo $role->getId()?>&mm=<?php echo $mm->getId()?>"></div>

<script type="text/javascript">
$("#delete_person-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>").click(function(e) {
  if (confirm('Seguro que desea borrar la relacion de "<?php echo $person->getHName()?>" ?')) { 
          $.ajax({
              method: 'POST',
              url: '<?php echo url_for('serials/deleterelation')?>',
              data:{id: <?php echo $person->getId()?>, mm: <?php echo $mm->getId()?>, role: <?php echo $role->getId()?>,preview: "true"},
              success: function(e) {
                 $('#' + <?php echo $role->getId()?> + '_person_mms').html(e);
              }
          });
    }
   e.preventDefault();
});
$("#dialog-modal-form-edit-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>").dialog({
         dialogClass: 'dialogNew',
                 hide: "highlight",
                 autoOpen: false,
                 modal: true,
                 minHeight: 170,
                 minWidth: 800,
                 position: [700, 250],
                 buttons: {
                     'Cancel': function() {
                       $(this).dialog('close');
                     }
                 },
                 open: function() {
                   self = $(this);
                   if (self.data("ajax")) {
                       self.load(self.data("url"));
                   }
                 }

});
$( "#edit_person-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" ).click(function(e) {
   $( "#dialog-modal-form-edit-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" ).dialog( "open" );
   e.preventDefault();
});
$( "#down_person-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" ).click(function(e) {
   e.preventDefault();
   $.ajax({
      method: 'POST',
      url: '<?php echo url_for('serials/down')?>',
      data:{id: "<?php echo $person->getId()?>", mm: "<?php echo $mm->getId()?>", role: "<?php echo $role->getId()?>", preview: "true"}, 
      success: function(e) {
         $('#' + <?php echo $role->getId()?> + '_person_mms').html(e);
      }
   });
});
$( "#up_person-<?php echo $role->getId()?>-<?php echo $person->getId(); ?>" ).click(function(e) {
   e.preventDefault();
   $.ajax({
      method: 'POST',
      url: '<?php echo url_for('serials/up')?>',
               data:{id: "<?php echo $person->getId()?>", mm: "<?php echo $mm->getId()?>", role: "<?php echo $role->getId()?>", preview: "true", update: '<?php echo $role->getId()?>_person_mms'}, 
      success: function(e) {
         $('#' + <?php echo $role->getId()?> + '_person_mms').html(e);
      }
   });
});
</script>

  <?php endfor; ?>
  <tr>
    <td><ul><li></li><ul></td>
    <td colspan="6">
      <a id="opener-<?php echo $role->getId()?>" style="cursor: pointer;">nuevo...</a>
    </td>
  </tr>
 </tbody>
</table>

<div id="dialog-modal-new-<?php echo $role->getId()?>" title="Crear <?php echo $role->getName()?>" data-ajax="true" data-url="/neweditar.php/serials/listAutoComplete?role=<?php echo $role->getId()?>&mm=<?php echo $mm->getId()?>"></div>
<script type="text/javascript">
$("#dialog-modal-new-<?php echo $role->getId()?>").dialog({
    dialogClass: 'dialogNew',
    hide: "highlight",
    autoOpen: false,
    modal: true,
    minHeight: 170,
    minWidth: 500,
    position: [700, 250],
    buttons: {
       'Cancel': function() {
           $(this).dialog('close');
        }
    },
    open: function() {
        self = $(this);
        if (self.data("ajax")) {
            self.load(self.data("url"));
        }
    }
});
$( "#opener-<?php echo $role->getId()?>" ).click(function() {
     $( "#dialog-modal-new-<?php echo $role->getId()?>" ).dialog( "open" );
     return false;
});
</script>

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

