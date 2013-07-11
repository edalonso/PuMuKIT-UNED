<div class="titulo_widget" style="margin-right: 0px;">
     <?php echo $m->getTitle()?>
</div>

<div id="unedtv_m_mmobj" class="unedtv_m">

  <div class="grid_10_mmobj">
   <?php include_partial('mmobj/player', array('mmobj' => $m, 'file' => $file, 'roles' => $roles))?>
  </div>
  <div class="grid_5_mmobj">
  <?php $serial = $m->getSerial() ?> 
   <?php if ($serial->getDisplay()): ?>
   <?php include_partial('other', array('texto' => __('Videos en la misma serie:'), 
					'mmobjs' => PubChannelPeer::getMmsFromSerial(1, $serial->getId()), 'multiple' => true)) ?>
   <?php endif ?>

   <?php include_partial('other', array('texto' => __('También te interesan:'), 
					'mmobjs' => $m->getSimilarMmsUnesco($sf_user->getAttribute('cat_code', null, 'uned'), $serial->getDisplay()), 
					'multiple' => $serial->getDisplay()))?>
  </div>
</div>