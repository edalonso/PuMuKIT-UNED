<table><tdody>
  <?php $t = count($links) ; for( $i=0; $i<$t; $i++): $link = $links[$i] //idea primer y ultimo apate ?>  
    <tr>
      <td><ul><li></li><ul></td>
       <td>
         <a href="#" id="edit_link-<?php echo $link->getId();?>"><img alt="editar" title="editar" src="/images/admin/mbuttons/edit_inline.gif"></a>
         <div id="edit_link_form-<?php echo $link->getId();?>" title="Editar Archivo de Mm <?php echo $link->getId()?>" data-ajax="true" data-url="/neweditar.php/serials/editLinks?id=<?php echo $link->getId()?>&mm=<?php echo $mm?>"></div>
       <td>
       <td>
         <a href="#" id="delete_link-<?php echo $link->getId()?>-<?php echo $i?>"><img alt="borrar" title="borrar" src="/images/admin/mbuttons/delete_inline.gif"></a>
       </td>
      <td>
       <?php if ($i != 0) echo '<a href="#" id="up_link-' . $link->getId() . '-' .$i . '"' . '>&#8593;</a>'?>
      </td>
      <td>
       <?php if ($i != $t-1 ) echo '<a href="#" id="down_link-' . $link->getId() . '-' . $i . '"' . '>&#8595;</a>'?>
      </td>
      <td>&nbsp;<?php echo $link->getId(); ?> - <?php echo $link->getUrl(); ?></td>
    </tr>

<script type="text/javascript">
$( "#edit_link_form-<?php echo $link->getId();?>" ).dialog({
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
$( "#edit_link-<?php echo $link->getId();?>" ).click(function(e) {
     $( "#edit_link_form-<?php echo $link->getId();?>" ).dialog( "open" );
     e.preventDefault();
});
$( "#delete_link-<?php echo $link->getId()?>-<?php echo $i?>" ).click(function(e) {
    if (confirm('Seguro')){
       $.ajax({
          method: 'POST',
          url: '<?php echo url_for('serials/deleteLinks')?>',
          data:{id: "<?php echo $link->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
          success: function(e) {
             $('#links_mms').html(e);
          }
       });
    }
    e.preventDefault();
});
$( "#up_link-<?php echo $link->getId()?>-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/upLinks')?>',
       data:{id: "<?php echo $link->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
       success: function(e) {
          $('#links_mms').html(e);
       }
    });
});
$( "#down_link-<?php echo $link->getId()?>-<?php echo $i?>" ).click(function(e) {
   e.preventDefault();
   $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/downLinks')?>',
       data:{id: "<?php echo $link->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
       success: function(e) {
          $('#links_mms').html(e);
       }
   });
});
</script>
  <?php endfor; ?>
  <tr>
    <td><ul><li></li><ul></td>
    <td colspan="6">
      <a href="#" id="create_link" title="Crear Link">nuevo...</a>
     <div id="create_link_form" title="Crear Link" data-ajax="true" data-url="/neweditar.php/serials/createLinks?mm=<?php echo $mm?>"></div>
  </tr>
</tbody></table>
<script type="text/javascript">
$( "#create_link_form" ).dialog({
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
$( "#create_link" ).click(function(e) {
     $( "#create_link_form" ).dialog( "open" );
     e.preventDefault();
});
</script>

<?php 
if ($sf_request->getParameter('preview')){
  echo javascript_tag(remote_function(array('update' => 'preview_mm', 'url' => 'mms/preview?id='. $mm, 'script' => 'true' )));
}

if (isset($msg_alert)) echo m_msg_alert_jquery($msg_alert)
?>
