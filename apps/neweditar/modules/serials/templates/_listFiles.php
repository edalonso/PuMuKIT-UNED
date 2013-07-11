<table><tdody>
  <?php if(sfConfig::get('app_transcoder_use')):?>
    <?php foreach($transcodings as $transcoding):?>
      <tr>
        <td><ul><li></li><ul></td>
        <td colspan="9">
          <?php echo $transcoding->getStatusText()?>
          <?php if($transcoding->getStatusId() == TranscodingPeer::STATUS_ERROR) echo '<a href="#" id="transcoders_retrans-' . $transcoding->getId() . '"><img alt="retranscodificar" title="retranscodificar" src="/images/admin/mbuttons/use_inline.gif"></a>'?>
          <?php if($transcoding->getStatusId() == TranscodingPeer::STATUS_ERROR) echo '<a href="#" id="transcoders_delete-' . $transcoding->getId() . '"><img alt="borrar" title="borrar" src="/images/admin/mbuttons/delete_inline.gif"></a>'?>
        </td>
        <td>
          &nbsp;<?php echo $transcoding->getId(); ?> - <strong><?php echo $transcoding->getPerfil()->getName() ?></strong>
	  - <?php echo basename($transcoding->getPathini()) ?>
          - <?php echo $transcoding->getDurationString() ?>
        </td>
      </tr>

       <script type="text/javascript">
          $( "#transcoders_retrans-<?php echo $transcoding->getId()?>" ).click(function(e) {
             $.ajax({
                method: 'POST',
                url: '<?php echo url_for('transcoders/retrans')?>',
                data:{ id: "<?php echo $transcoding->getId()?>", mm: "<?php echo $mm?>", preview: "true" },
                success: function(e) {
                   $('#files_mms').html(e);
                }
             });
             e.preventDefault();
          });
          $( "#transcoders_delete-<?php echo $transcoding->getId()?>" ).click(function(e) {
             $.ajax({
                method: 'POST',
                url: '<?php echo url_for('serials/deletePics')?>',
                data:{ id: "<?php echo $pic->getId()?>", <?php echo $que?>: "<?php echo $object_id?>" },
                success: function(e) {
                   $('#pic_<?php echo $que?>s').html(e);
                }
             });
             e.preventDefault();
          });
       </script>
    <?php endforeach;?>
  <?php endif; ?>
  <?php $t = count($files) ; for( $i=0; $i<$t; $i++): $file = $files[$i] //idea primer y ultimo apate ?>  
    <tr>
      <td><ul><li></li><ul></td>
      <td>
        <a href="#" id="edit_mm-<?php echo $file->getId()?>"><img alt="editar" title="editar" src="/images/admin/mbuttons/edit_inline.gif"></a>
        <div id="edit_mm_form-<?php echo $file->getId()?>" title="Editar Archivo de Mm <?php echo $file->getId()?>" data-ajax="true" data-url="/neweditar.php/serials/editFiles?id=<?php echo $file->getId()?>&mm=<?php echo $mm?>"></div>
      </td>

      <td>
        <a href="#" id="info_mm-<?php echo $file->getId()?>"><img alt="info" title="info" src="/images/admin/mbuttons/info_inline.gif"></a>
        <div id="info_mm_form-<?php echo $file->getId()?>" title="Info Archivo de Mm <?php echo $file->getId()?>" data-ajax="true" data-url="/neweditar.php/serials/infoFiles?id=<?php echo $file->getId()?>&mm=<?php echo $mm?>"></div>
      </td>
      <td>
        <a href="#" id="delete_files-<?php echo $file->getId()?>"><img alt="borrar" title="borrar" src="/images/admin/mbuttons/delete_inline.gif"></a>
      </td>
      <?php if (sfConfig::get('app_videoserv_browser')) echo '<td><a href="#" id="autocomplete_file-' . $file->getId() . '"><img alt="autocompletar" title="autocompletar" src="/images/admin/mbuttons/auto_inline.gif"></a></td>'?>

      <?php if (sfConfig::get('app_videoserv_browser')):?>
      <td>
        <span class="trans_button" onclick="$('#list_picts_<?php echo $file->getId()?>').toggle()"><?php echo image_tag('admin/mbuttons/frame_inline.gif', 'alt=frame title=frame')?>
        <div class="trans_menu" id="list_picts_<?php echo $file->getId()?>" style="display:none">
        
          <div class="mas_info" style="">
            <div class="trans_button_up"><img src="/images/admin/mbuttons/frame_inline.gif" alt="frame" /></div>
            <div class="trans_button_info">Capturar frame de:</div>
          </div>
        
          <div class="list_options">
            <ul style="">
              <li>
                <a href="#" id="update_pic-Auto-<?php echo $i?>">Auto</a>
              </li>
              <li>
                <a href="#" id="update_pic-10-<?php echo $i?>">10%</a>
              </li>
              <li>
                <a href="#" id="update_pic-25-<?php echo $i?>">25%</a>
              </li>
              <li>
                <a href="#" id="update_pic-50-<?php echo $i?>">50%</a>
              </li>
              <li>
                <a href="#" id="update_pic-75-<?php echo $i?>">75%</a>
              </li>
              <li>
                <a href="#" id="update_pic-90-<?php echo $i?>">90%</a>
              </li>
              <li class="cancel"><a href="#" onclick="return false;">Cancelar...</a></li>
            </ul>
        
        
          </div>
        </div>
        </span>
      </td>
<script type="text/javascript">
$( "#update_pic-Auto-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $("#pic_mms_load").show();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/picFiles')?>',
       data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", preview: "true"},
       success: function(e) {
          $('#pic_mms').html(e);
          $("#pic_mms_load").hide();
       }
    });
});
$( "#update_pic-10-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $("#pic_mms_load").show();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/picFiles')?>',
       data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", preview: "true", numframe: "10%"},
       success: function(e) {
          $('#pic_mms').html(e);
          $("#pic_mms_load").hide();
       }
    });
});
$( "#update_pic-25-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $("#pic_mms_load").show();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/picFiles')?>',
       data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", preview: "true", numframe: "25%"},
       success: function(e) {
          $('#pic_mms').html(e);
          $("#pic_mms_load").hide();
       }
    });
});
$( "#update_pic-50-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $("#pic_mms_load").show();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/picFiles')?>',
       data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", preview: "true", numframe: "50%"},
       success: function(e) {
          $('#pic_mms').html(e);
          $("#pic_mms_load").hide();
       }
    });
});
$( "#update_pic-75-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $("#pic_mms_load").show();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/picFiles')?>',
       data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", preview: "true", numframe: "75%"},
       success: function(e) {
          $('#pic_mms').html(e);
          $("#pic_mms_load").hide();
       }
    });
});
$( "#update_pic-90-<?php echo $i?>" ).click(function(e) {
    e.preventDefault();
    $("#pic_mms_load").show();
    $.ajax({
       method: 'POST',
       url: '<?php echo url_for('serials/picFiles')?>',
       data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", preview: "true", numframe: "90%"},
       success: function(e) {
          $('#pic_mms').html(e);
          $("#pic_mms_load").hide();
       }
    });
});
</script>
      <?php endif ?>
         
      <?php if (sfConfig::get('app_videoserv_browser')) echo '<td><a target="_blank" href="' . url_for('serials/downloadFiles?id=' . $file->getId()) . '"><img alt="descargar" title="descargar" src="/images/admin/mbuttons/download_inline.gif"></a></td>'?>
      <?php if(!$file->getPerfil()->getDisplay()): ?>
      <td>
        <span class="trans_button" onclick="$('#list_perfiles_<?php echo $file->getId()?>').toggle()"><img src="/images/admin/mbuttons/use_inline.gif" alt="X" />
        <div class="trans_menu" id="list_perfiles_<?php echo $file->getId()?>" style="display:none">
        
          <div class="mas_info" style="">
            <div class="trans_button_up"><img src="/images/admin/mbuttons/use_inline.gif" alt="X" /></div>
            <div class="trans_button_info">Transcodificar al perfil:</div>
          </div>
        
          <div class="list_options">
            <ul style="">
              <?php foreach(PerfilPeer::doSelectToWizard(true) as $per): ?>
                <li>
                 <a id="retrans_files-<?php echo $per->getId()?>" href="#">$per->getName()</a>

                 <script type="text/javascript">
                    $( "#retrans_files-<?php echo $per->getId()?>" ).click(function(e) {
                       e.preventDefault();
                       $.ajax({
                          method: 'POST',
                          url: '<?php echo url_for('serials/retransFiles')?>',
                          data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $file->getMmId()?>", profile: "<?php echo $per->getId()?>"},
                          success: function(e) {
                             $('#files_mms').html(e);
                          }
                       });
                    });
                 </script>

              <?php endforeach ?>
              <li class="cancel"><a href="#" onclick="return false;">Cancelar...</a></li>
            </ul>
        
        
          </div>
        </div>
        </span>
      </td>

      <?php else: ?>

      <td>        
        <a title="reproducir" href="#" onclick="Shadowbox.open({
            title:      'Vista Previa',
            content:    '<?php echo $file->getUrl() ?>',
            type:       'flv',
            height:     480,
            width:      640
          }); return false;">
          <?php echo image_tag('admin/mbuttons/play_inline.gif', 'alt=reproducir title=reproducir')?>
        </a>
      </td>
      <?php endif ?>
      <td>
         <?php if ($i != 0) echo '<a href="#" id="up_file-' . $file->getId() . '-' .$i . '"' . '>&#8592;</a>'?>
      </td>
      <td>
      <?php if ($i != $total - 1 ) echo '<a href="#" id="down_file-' . $file->getId() . '-' . $i . '"' . '>&#8594;</a>'?>
      <td>
        &nbsp;<?php echo $file->getId(); ?> - <strong><?php echo $file->getPerfil()->getName() ?></strong>
        <?php echo $file->getDescription() ?>
        (<?php echo basename($file->getFile()) ?>/<?php echo $file->getLanguage()->getName() ?>)
         - <?php echo $file->getDurationString() ?>
         - <?php printf("%.2f", ($file->getSize() / 1048576)) ?> MB
         - <?php echo $file->getResolutionHor() ?>x<?php echo $file->getResolutionVer() ?>
         <?php echo ($file->getDisplay())?'':'(Oculto)'?>
      </td>
    </tr>

    <script type="text/javascript">
      $( "#up_file-<?php echo $file->getId()?>-<?php echo $i?>" ).click(function(e) {
         e.preventDefault();
         $.ajax({
            method: 'POST',
            url: '<?php echo url_for('serials/upFiles')?>',
            data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $mm?>"},
            success: function(e) {
               $('#files_mms').html(e);
            }
         });
      });
      $( "#down_file-<?php echo $file->getId()?>-<?php echo $i?>" ).click(function(e) {
         e.preventDefault();
         $.ajax({
            method: 'POST',
            url: '<?php echo url_for('serials/downFiles')?>',
            data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $mm?>"},
            success: function(e) {
               $('#files_mms').html(e);
            }
         });
       });
       $( "#edit_mm_form-<?php echo $file->getId()?>" ).dialog({
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
       $( "#edit_mm-<?php echo $file->getId()?>" ).click(function(e) {
          $( "#edit_mm_form-<?php echo $file->getId()?>" ).dialog( "open" );
          e.preventDefault();
       });
       $( "#info_mm_form-<?php echo $file->getId()?>" ).dialog({
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
       $( "#info_mm-<?php echo $file->getId()?>" ).click(function(e) {
          $( "#info_mm_form-<?php echo $file->getId()?>" ).dialog( "open" );
          e.preventDefault();
       });
       $( "#delete_files-<?php echo $file->getId()?>" ).click(function(e) {
          if (confirm('Seguro')){
             $.ajax({
                method: 'POST',
                url: '<?php echo url_for('serials/deleteFiles')?>',
                data:{ id: "<?php echo $file->getId()?>", mm: "<?php echo $mm?>", preview: "true" },
                success: function(e) {
                   $('#files_mms').html(e);
                }
             });
          }
          e.preventDefault();
       });
       $( "#autocomplete_file-<?php echo $file->getId()?>" ).click(function(e) {
          $.ajax({
             method: 'POST',
                url: '<?php echo url_for('serials/autocompleteFiles')?>',
                data:{id: "<?php echo $file->getId()?>", mm: "<?php echo $mm?>", preview: "true"},
                success: function(e) {
                   $('#files_mms').html(e);
                }
             });
          e.preventDefault();
       });
</script>

  <?php endfor; ?>
  <!-- MATTERHORN -->
  <?php if(sfConfig::get('app_matterhorn_use') && $oc):?>
      <tr>
        <td><ul><li></li><ul></td>
        <td><?php echo m_link_to(image_tag('admin/mbuttons/edit_inline.gif', 'alt=editar title=editar'), 'matterhorn/edit?id='.$oc->getId(), array('title' => 'Editar Matterhorn de Mm '.$oc->getId()), array('width' => '800')) ?></td>
        <td><?php echo m_link_to(image_tag('admin/mbuttons/info_inline.gif', 'alt=info title=info'), 'matterhorn/infomp?id='.$oc->getId(), array('title' => 'Info Archivo OC de Mm '.$oc->getId()), array('width' => '800')) ?></td>

        <td colspan="8">&nbsp;<td>
        <td>
          &nbsp;<?php echo $oc->getId() ?> - <strong>Opencast Matterhorn Recording</strong>
          <a target="_blank" href="<?php echo $oc->getUrl() ?>"><?php echo $oc->getMhId() ?></a>
        </td>
      </tr>
    
  <?php endif ?>
  <?php if(sfConfig::get('app_transcoder_use')): ?>
  <tr>
    <td><ul><li></li><ul></td>
    <td colspan="9">
      <a title="Nuevo master" id="new_master-<?php echo $mm?>"  href="#">nuevo master...</a>
      <div id="new_master_form-<?php echo $mm?>" title="Nuevo master" data-ajax="true" data-url="/neweditar.php/serials/editTranscoders?mm=<?php echo $mm ?>"></div>
    </td>
  </tr>
  <?php endif?>
</tbody></table>
<script type="text/javascript">
$( "#new_master_form-<?php echo $mm?>" ).dialog({
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
$( "#new_master-<?php echo $mm?>" ).click(function(e) {
     $( "#new_master_form-<?php echo $mm?>" ).dialog( "open" );
     e.preventDefault();
});
</script>


<?php 
if ($sf_request->getParameter('preview')){
  echo javascript_tag(remote_function(array('update' => 'preview_mm', 'url' => 'mms/preview?id='. $mm, 'script' => 'true' )));
}

if (isset($msg_alert)) echo m_msg_alert_jquery($msg_alert);
?>
