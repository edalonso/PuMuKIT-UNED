<?php
/*****************************
 *
 *
 *    No funciona un parent.Ajx.update porque no tiene la coockie de id de session
 *    En FIREFOX y todo  LOCALHOST a veces falla
 *
 *
 ****************************/

?>


<script language="javascript" type="text/javascript">
//<![CDATA[
$(function(){
   $.ajax({
      method: 'POST',
      url: '<?php echo url_for('serials/listMaterials')?>',
      data:{mm: "<?php echo $mm?>"},
      success: function(e) {
         parent.$('#materials_mms').html(e);
      }
   });
   $.ajax({
      method: 'POST',
      url: '<?php echo url_for('serials/previewMms')?>',
      data:{ update: 'preview_mm', id: "<?php echo $mm?>"},
      success: function(e) {
         parent.$('#preview').html(e);
      }
   });
   parent.$('#create_material_form').dialog('close');
   parent.$('#edit_material_form-<?php echo $material?>').dialog('close');
   parent.$('#materials_mms').text('Actualice el video para que se muestren los materiales.');
   parent.$('#div_messages_span_info').text('<?php echo $msg_info?>');
   parent.$('#div_messages_info').fadeTo('slow',1);
});
//]]>
</script>