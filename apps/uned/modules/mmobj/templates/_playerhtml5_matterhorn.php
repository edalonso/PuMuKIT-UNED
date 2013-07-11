<div id="mh_player">
<script type="text/javascript" src="/js/jwplayer.js"></script>

<?php if ( $m->getFileByPerfil(MmMatterhornPeer::$ARRAY_PERFILES) != null): ?>
  <video 
       id = "player"
       style = "width: 100%"
       controls = "controls"
       poster = "/images/tv/cmar/poster.png" >
      <source src="<?php echo $m->getFileByPerfil(MmMatterhornPeer::$ARRAY_PERFILES)->getUrl(true) ?>" type="video/mp4"/>
  </video>

   <div class="mm_player">
     <div class="num_view">
       <div style="float:left">
         <?php echo __("Idioma del video")?>: <span class="num_view_number"><?php echo $oc->getLanguage() ?></span>
       </div>
       <?php echo __('Visto:')?> 
       <span class="num_view_number"><?php echo $oc->getNumView()?></span>
       <?php echo (($oc->getNumView() == 1)?__(' vez'):__(' veces'))?> &nbsp;&nbsp;
     </div>
   </div>

   <div class="title">
     <?php echo $m->getSubtitle() ?>
   </div>

   <p class="description">
    <?php echo nl2br($m->getDescription()) ?>
   </p>

   <?php include_partial('mmobj/bodyMm', array('mm' => $m, 'roles' => $roles)) ?>


<?php else: ?>

 <h1 class="cS_h1_error">VIDEO NOT AVAILABLE</h1>

<?php endif; ?>


</div>