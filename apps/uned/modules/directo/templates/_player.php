<?php use_javascript('/swf/jwplayer/jwplayer.js') ?>
<div class="mm_player">
   <div>
      <div id="player1"> 
       <img src="/images/uned/teleactos.jpg" style="width: 620px; height = 465px;">
        <p class="warning">El teleacto a&uacute;n no se ha celebrado</p>
      </div>
   <div style="margin-top: 15px">
   
   <div style="margin-bottom: 10px; overflow:hidden;">
     <div class="num_view">
         <div style="float: right; font-weight: normal;">
	    <?php echo ($session)? $session->getInitDate('d/m/Y') : $event->getFutureSession()->getInitDate('d/m/Y') ?>
         </div>
     </div>


      <div class="title" style="clear: left;">
        <?php echo $event->getTitle() ?>
      </div>
   </div>

   <table style="margin-bottom: 10px; width: 620px;">
     <tr style="vertical-align: top;">
      <td style="width: 65%; border-right: 1px solid #ddd; padding-right: 10px;"> 
        <p class="description">
          <?php echo nl2br($event->getDescription()) ?>
        </p>
       </td>

       <td style="padding-left: 10px;">

         <!-- MATERIAL -->
         <?php $materials = $event->getMaterialsPublic() ?>
             <?php foreach ($materials as $material): $material->setCulture( $sf_user->getCulture() ) ?>
                  <div id="material" style="width: 200px; height: 30px;" class="<?php echo $material->getMatType()->getType() ?>">
                     <a style="margin-left: 25px;" title="<?php echo $material->getName()?>" target="_blank" href="<?php echo $material->getUrl() ?>">
                       <?php echo str_abbr($material->getName(), 20, "...") ?>
                     </a>
                     <span class="size"><?php echo number_format($material->getSize()/1024, 2)?>kB</span>
                  </div>
                 <?php endforeach; ?>
                 <div style="clear:both;"></div>
       </td>
      </tr> 
    </table>

      <?php if(($event->getEnableQuery()) && ($event->getEmailQuery())): ?>
       <a href="#" class="queryButton" onclick="Effect.toggle('consulta','blind');return false;">Consultas</a>
        <div id="consulta" style="display: none">
	    <?php include_partial('consultas', array('event' => $event)); ?>
        </div>
    <?php endif ?>
  </div>
 </div>
</div> 



<?php if ($session != null): ?>

<script language="JavaScript" type="text/javascript">
    jwplayer("player1").setup({
	 height: "465",
	 width: "620",
	 controlbar: "bottom",
         autostart: "true",
	 <?php if($event->getExternal()): ?>
         file: "<?php echo $event->getUrl() ?>"
	 <?php else: ?>
	 file: "<?php echo $event->getDirect()->getUrl() ?>"
         <?php endif ?>
    });


function show_hide(id){
  if (document.getElementById){ 
    var el = document.getElementById(id); 
    el.style.display = (el.style.display == 'none') ? 'block' : 'none';
  }
}
      
</script>

<?php endif ?>

