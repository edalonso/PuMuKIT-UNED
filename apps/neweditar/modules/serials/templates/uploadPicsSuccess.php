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
      url: '<?php echo url_for($sf_data->getRaw('url'), true)?>',
      success: function(e) {
         parent.$('#<?php echo $upload?>').html(e);
      }
   });

   parent.$('#create_pic_form-<?php echo $mm_id?>').dialog('close');
   parent.$('#<?php echo $upload?>').text('Actualize el video para que se muestren los materiales.');
   parent.$('#div_messages_span_info').text('Nueva imagen subida e insertada.');
   parent.$('#div_messages_info').fadeTo('slow',1);
});
//]]>
</script>
