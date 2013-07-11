<div>
  <img id="pic_mms_load" src="/images/admin/load/spinner.gif" alt="loading" style="position: relative; top: 50px; float:left; display: none"/>
  <?php $total = count($pics) ; for($i=0; $i < $total; $i++): $pic = $pics[$i] ?>
    <div style="width : 120px; float: left; padding : 10px; ">
     <div style="padding: 10px; float:left; text-align : center">
      <div class="wrap0"><div class="wrap1"><div class="wrap2"><div class="wrap3">
          <img src="<?php echo $pic->getUrl() ?>" width="100" height="82" border="1">
      </div></div></div></div>
     </div>
      <div style="text-align : center">
         Imagen numero <?php echo $pic->getId() ?>  <br />
         <?php if ($i != 0) echo '<a href="#" id="up_pic-' . $pic->getId() . '-' .$i . '"' . '>&#8592;</a>'?>
          <a href="#" id="delete_pic-<?php echo $pic->getId()?>-<?php echo $i?>"><img alt="borrar" title="borrar" src="/images/admin/mbuttons/delete_inline.gif"></a>
         <?php if ($i != $total - 1 ) echo '<a href="#" id="down_pic-' . $pic->getId() . '-' . $i . '"' . '>&#8594;</a>'?>
         
      </div>
    </div>
<script type="text/javascript">
$( "#delete_pic-<?php echo $pic->getId()?>-<?php echo $i?>" ).click(function(e) {
    if (confirm('Seguro')){
       $.ajax({
          method: 'POST',
          url: '<?php echo url_for('serials/deletePics')?>',
          data:{id: "<?php echo $pic->getId()?>", <?php echo $que?>: "<?php echo $object_id?>"},
          success: function(e) {
             $('#pic_<?php echo $que?>s').html(e);
          }
       });
    }
    e.preventDefault();
});
$( "#up_pic-<?php echo $pic->getId()?>-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/upPics')?>',
       data:{id: "<?php echo $pic->getId()?>", <?php echo $que?>: "<?php echo $object_id?>"},
       success: function(e) {
          $('#pic_<?php echo $que?>s').html(e);
       }
    });
});
$( "#down_pic-<?php echo $pic->getId()?>-<?php echo $i?>" ).click(function(e) {
   e.preventDefault();
   $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/downPics')?>',
       data:{id: "<?php echo $pic->getId()?>", <?php echo $que?>: "<?php echo $object_id?>"},
       success: function(e) {
          $('#pic_<?php echo $que?>s').html(e);
       }
   });
});
</script>
  <?php endfor;?>

</div>
  <div style="width : 120px; float: left; padding : 10px; ">
    <div style="padding: 10px; float:left; text-align : center">
     <div class="wrap0"><div class="wrap1"><div class="wrap2"><div class="wrap3">
         <img src="/images/sin_foto.jpg" width="100" height="82" border="1">
      </div></div></div></div>
     </div>

    <div style="text-align : center">
      <a title="Nueva Imagen" id="create_pic-<?php echo $object_id?>"  href="#">nueva imagen...</a>
    </div>
  </div>

  <div id="create_pic_form-<?php echo $object_id?>" title="Nueva Imagen" data-ajax="true" data-url="/neweditar.php/serials/createPics/<?php echo $que ?>/<?php echo $object_id ?>/page/1"></div>

<div style="clear : left"></div>
<script type="text/javascript">
$( "#create_pic_form-<?php echo $object_id?>" ).dialog({
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
$( "#create_pic-<?php echo $object_id?>" ).click(function(e) {
     $( "#create_pic_form-<?php echo $object_id?>" ).dialog( "open" );
     e.preventDefault();
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
                    data:{update: 'preview_<?php echo $que?>', id: '<?php echo  $object_id?>'},
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