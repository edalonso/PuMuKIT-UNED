<div class="<?php echo $serial->getBroadcastMax()->getBroadcastType()->getName()?>"></div>

<div class="cab_serial">
  
  <?php if ($serial->getSubtitle() !== ""): ?> 
    <h5 class="subtitle">
      <?php echo $serial->getSubtitle()?>
    </h5>
  <?php endif; ?>
  
    
  <?php $precinct = $serial->getPrecinct(); if (($precinct)&&($precinct->getId()>1)): ?>
    <h3 class="place">
      <!-- falta address -->
      <?php echo $precinct->getCompleteName()?>
    </h3>
  <?php endif; ?>

</div>