<div class="titulo_widget" style="margin-right: 0px;">
     <?php echo $m->getTitle()?>
</div>

<div id="unedtv_m_mmobj" class="unedtv_m">

  <div class="grid_10_mmobj">
   <?php include_partial('mmobj/passwdForm', array('mmobj' => $m, 'file' => $file, 'roles' => $roles))?>
  </div>
  <div class="grid_5_mmobj">
   <?php include_partial('share', array('mmobj' => $m, 'file' => $file))?>
   <!-- FIXME. mostrar solo objetos multimedia publicos -->
   <?php include_partial('other', array('texto' => __('También te interesan:'), 
					'mmobjs' => $m->getSimilarMmsUnesco($sf_user->getAttribute('cat_code', null, 'uned'))))?>
  </div>
</div