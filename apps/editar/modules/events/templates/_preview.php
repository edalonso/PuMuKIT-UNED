<!-- Vista previa -->
<?php if( isset($event) ):?>
<div>

  <div style="background-color:#006699; color:#FFFFFF; font-weight:bold; margin-bottom:11px; text-align:center;">
    <?php echo $sf_data->getRaw('event')->getTitle()?>
  </div>

 <div style="background-color:transparent; margin: 3%; overflow: hidden;" VALIGN="MIDDLE" ALIGN="CENTER">
   <?php if(($event->getDirect() != null) ||  ($event->getUrl() != null)): ?> 
       <?php include_partial('player', array('event' => $event, 'w' => 320, 'h' => 180)) ?>
    <?php else: ?>
       <div class="SimPlayer">
          <img WIDTH="30" HEIGHT="30" src="/images/uned/play.png" style="margin-top: 40px;"></img>
          <p class="SimPlayerText" style="background-color: black">El teleacto seleccionado no tiene asignado ning&uacute;n streaming</p>
       </div>
    <?php endif?>
 </div>


 <div style="background-color:transparent; margin: 3%; overflow: hidden;" VALIGN="MIDDLE">
  <p style="overflow:hidden; padding:5px; border:solid 1px #DDD; background:<?php echo ($event->getDisplay() ? '#FFEAD6' : '#DDD') ?>" >
   <span style="font-weight:bold">Inicio: </span> <?php echo $event->getDate('d/m/Y H:i')?> <br />
    <br />
   <?php echo '<a href= "'.sfConfig::get('app_info_link').'/teleacto/'.$event->getId().'.html'.'" target="_blank">Ver en CANALUNED </a>' ?>
  </p>
 </div>
</div>

<?php else:?>
<p>
  Selecione o cree algun teleacto.
</p>
<?php endif?>  
