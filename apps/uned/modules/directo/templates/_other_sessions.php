<div class="other_mmobjs">
  <div class="label">
    <?php echo $texto ?>
  </div>

  <div class="box Container" style="height: 300px;">
    <?php if(count($sessions) == 0):?>
      <?php echo __('No existen próximas sesiones')?>.
    <?php else:?>
   <?php include_partial('global/mini_session', array('sessions' => $sessions))?>    
    <?php endif?>
  </div>

</div>
