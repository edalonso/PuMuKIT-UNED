<div id="myModal<?php echo $mm->getId() ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Metadatos de <?php echo $mm->getTitle() ?></h3>
  </div>
  <div class="modal-body">
    <?php include_partial('info', array('mm' => $mm))?>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
  </div>
</div>
