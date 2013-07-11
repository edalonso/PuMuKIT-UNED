<div id="myModal<?php echo $mm->getId() ?>" class="modal hide fade" style="display: none;">
  <div class="modal-header">
    <h3 id="myModalLabel">Metadatos de <?php echo $mm->getTitle() ?></h3>
  </div>
  <div class="modal-body">
    <?php include_partial('mmobj/info', array('mm' => $mm))?>
  </div>
  <div class="modal-footer">
    <button class="btn" onclick="Modalbox.hide();">Close</button>
  </div>
</div>
