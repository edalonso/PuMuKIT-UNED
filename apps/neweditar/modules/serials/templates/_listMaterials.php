<table><tdody>
  <?php $t = count($materials) ; for( $i=0; $i<$t; $i++): $material = $materials[$i] //idea primer y ultimo apate ?>  
    <tr>
      <td><ul><li></li><ul></td>
       <td>
         <a href="#" id="edit_material-<?php echo $material->getId()?>"><img alt="editar" title="editar" src="/images/admin/mbuttons/edit_inline.gif"></a>
         <div id="edit_material_form-<?php echo $material->getId()?>" title="Editar Archivo de Mm <?php echo $material->getId()?>" data-ajax="true" data-url="/neweditar.php/serials/editMaterials?id=<?php echo $material->getId()?>&mm=<?php echo $mm?>"></div>
       <td>
       <td>
         <a href="#" id="delete_material-<?php echo $material->getId()?>-<?php echo $i?>"><img alt="borrar" title="borrar" src="/images/admin/mbuttons/delete_inline.gif"></a>
       </td>
       <td>
         <a target="_blank" href="<?php echo $material->getUrl(true)?>"><img alt="descargar" title="descargar" src="/images/admin/mbuttons/download_inline.gif"></a>
       </td>

      <td>
       <?php if ($i != 0) echo '<a href="#" id="up_material-' . $material->getId() . '-' .$i . '"' . '>&#8593;</a>'?>
      </td>
      <td>
       <?php if ($i != $t-1 ) echo '<a href="#" id="down_material-' . $material->getId() . '-' . $i . '"' . '>&#8595;</a>'?>
      </td>

      <td>
        &nbsp;
        <?php echo $material->getId(); ?> - 
        <?php echo $material->getName(); ?>
        <?php echo ($material->getDisplay())?'':'(Oculto)'?>
      </td>
    </tr>
<script type="text/javascript">
$( "#edit_material_form-<?php echo $material->getId()?>" ).dialog({
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
$( "#edit_material-<?php echo $material->getId()?>" ).click(function(e) {
     $( "#edit_material_form-<?php echo $material->getId()?>" ).dialog( "open" );
     e.preventDefault();
});
$( "#delete_material-<?php echo $material->getId()?>-<?php echo $i?>" ).click(function(e) {
    if (confirm('Seguro')){
       $.ajax({
          method: 'POST',
          url: '<?php echo url_for('serials/deleteMaterials')?>',
          data:{id: "<?php echo $material->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
          success: function(e) {
             $('#materials_mms').html(e);
          }
       });
    }
    e.preventDefault();
});
$( "#up_material-<?php echo $material->getId()?>-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/upMaterials')?>',
       data:{id: "<?php echo $material->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
       success: function(e) {
          $('#materials_mms').html(e);
       }
    });
});
$( "#down_material-<?php echo $material->getId()?>-<?php echo $i?>" ).click(function(e) {
   e.preventDefault();
   $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/downMaterials')?>',
       data:{id: "<?php echo $material->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
       success: function(e) {
          $('#materials_mms').html(e);
       }
   });
});
</script>

  <?php endfor; ?>
  <tr>
    <td><ul><li></li><ul></td>
    <td colspan="6">
      <a href="#" id="create_material" title="Crear Material">nuevo...</a>
     <div id="create_material_form" title="Crear Material" data-ajax="true" data-url="/neweditar.php/serials/createMaterials?mm=<?php echo $mm?>"></div>
    </td>
  </tr>
</tbody></table>

<script type="text/javascript">
$( "#create_material_form" ).dialog({
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
$( "#create_material" ).click(function(e) {
     $( "#create_material_form" ).dialog( "open" );
     e.preventDefault();
});
</script>
