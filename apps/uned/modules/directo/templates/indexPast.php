<h1 class="titulo_widget_mmobj2"><?php echo __($title) .$event->getTitle()?></h1>

<div id="unedtv_m_mmobj" class="unedtv_m">
  <div class="grid_10_mmobj">
    <div class="mm_player">
        <div>
          <div id="player1"> 
           <img src="/images/uned/teleactos.jpg" style="width: 620px; height = 465px;">
       <p class="warning">El teleacto ya  se ha celebrado</p>
          </div>
       <div>
       <div class="num_view">
           <div style="float: right; font-weight: normal;">
             <?php echo $event->getDate('d/m/Y') ?>
           </div>
       </div>
    
        <div class="title" style="clear: left;">
         <?php echo $event->getTitle() ?>
        </div>
     
        <p class="description">
         <?php echo nl2br($event->getDescription()) ?>
        </p>
        <?php if($event->getEnableQuery()): ?>
        <p>
        <span style="font-weight: bold">Email de consultas: </span><?php echo $event->getEmailQuery() ?>
        </p>
        <?php endif ?>
      </div>
     </div>
    </div> 
    
  </div>
  <div class="grid_5_mmobj">
   <?php include_partial('other_events', array('texto' => __('Teleactos en directo:'), 
					      'events' => $events))?>
   <?php include_partial('other_sessions', array('texto' => __('Proximas sesiones:'), 
                                               'sessions' => $event->getFutureSessions()))?>

  </div>
</div>
