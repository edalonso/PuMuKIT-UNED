<div class="other_mmobjs">
  <div class="label">
    <?php echo $texto ?>
  </div>

  <div class="box Container" style="height: 300px;">
    <?php if(count($events) == 0):?>
      <?php echo __('No existen vídeos de estas caracteristicas')?>.
    <?php else:?>
   <?php include_partial('global/mini_event', array('events' => $events))?>    
    <?php endif?>
  </div>

</div>
