<div class="<?php echo $serial->getBroadcastMax()->getBroadcastType()->getName()?>"></div>

<div style="display: none;">
   <?php echo image_tag($serial->getFirstUrlPic(), 'class=announce') ?>
</div>

<div class="cab_serial">
  <h1 class="widget_title">
    <?php echo $serial->getTitle() ?>
  </h1>

  <?php if ($serial->getSubtitle() !== ""): ?> 
    <h2 class="subtitle">
      <?php echo $serial->getSubtitle()?>
    </h2>
  <?php endif; ?>

  <?php if ($serial->getDescription() !== ""): ?> 
    <div class="description" style="padding: 0px 0px 30px">
      <?php echo nl2br($serial->getDescription())?>
    </div>
  <?php endif; ?>
 
</div>