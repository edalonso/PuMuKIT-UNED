<p class="titulo_widget titulo_widget_grande" style="min-width: 1210px; margin-right: 10px;">
  <?php echo $m->getTitle()?>
</p>

<?php if($m->getImpacto()):?>
  <?php include_partial('mmobj/msg_impacto')?>
<?php endif?>


<div class="mm_player">
  <div class="date" style="min-width: 1220px; margin-right: 10px;">
    <!-- Fixme falta estilo de esto -->
    <?php echo __('Data de celebración')?>: <?php echo $m->getRecordDate('d/m/Y') ?>
  </div>
</div>


<script type="text/javascript">

//<![CDATA[
function mh_animacion(){

  player = $('mh_player');
  lateral = $('sidebar');

  if(player.hasClassName('fullscreen')){
    player.removeClassName('fullscreen');
    player.setStyle({'margin': '0px 247px 0px 0px'}); 
    lateral.setStyle({'width': '223px'});
    $('mh_toggle_img_1').setStyle({'background': 'transparent  url("/images/tv/iconos/flechas.png") no-repeat -22px 0px'});  
    $('mh_toggle_img_2').setStyle({'background': 'transparent  url("/images/tv/iconos/flechas.png") no-repeat -22px 0px'});  
    $('mh_toggle_img_3').setStyle({'background': 'transparent  url("/images/tv/iconos/flechas.png") no-repeat -22px 0px'});   
  }else{
    player.addClassName('fullscreen')
    lateral.setStyle({'width': '19px'}); 
    player.setStyle({'margin': '0px 40px 0px 0px'}); 
    $('mh_toggle_img_1').setStyle({'background': 'transparent  url("/images/tv/iconos/flechas.png") no-repeat -22px -16px'});  
    $('mh_toggle_img_2').setStyle({'background': 'transparent  url("/images/tv/iconos/flechas.png") no-repeat -22px -16px'});  
    $('mh_toggle_img_3').setStyle({'background': 'transparent  url("/images/tv/iconos/flechas.png") no-repeat -22px -16px'}); 
  }
  
  return false;

}

//]]>
</script>


<div id="bloque" style="width: auto; min-width: 1220px;">
 <div id="unedtv_m_mmobj" class="unedtv_m" style="margin: 0px 1%; padding: 20px 0px;">


  <div id="sidebar" > 
    <div id="mh_toggle_div" onclick="mh_animacion();" >


    <div id="mh_toggle_img_1" class="mh_toggle_img"
         style="top:25%; ">
         &nbsp;
    </div>

    <div id="mh_toggle_img_2" class="mh_toggle_img"
         style="top:50%; ">
         &nbsp;
    </div>

    <div id="mh_toggle_img_3" class="mh_toggle_img"
         style="top:75%; ">
         &nbsp;
    </div>
  </div>
  <div id="sidebar_content" style="width: 100%">
 <?php include_partial('mmobj/other', array('texto' => __('Vídeos da mesma serie:'), 
					      'mmobjs' => PubChannelPeer::getMmsFromSerial(1, $m->getSerialId())))?>
   <?php include_partial('mmobj/other', array('texto' => __('Tamén che interesan:'), 
					      'mmobjs' => $m->getSimilarMms()))?>
  </div>
 </div>    



<?php if($isHTML5): ?>
<?php  include_partial('mmobj/playerhtml5_matterhorn', array('m' => $m, 'roles' => $roles, 'oc'=>$oc))?>
<?php else: ?>
<?php  include_partial('mmobj/player_matterhorn', array('m' => $m, 'roles' => $roles, 'oc' => $oc))?>
<?php endif ?>




 </div>
</div>
