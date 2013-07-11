<h1 class="titulo_widget_mmobj2"><?php echo __($title) .$event->getTitle()?></h1>

<div id="unedtv_m_mmobj" class="unedtv_m">
  <div class="grid_10_mmobj">
   <?php include_partial('player', array('event' => $event, 'session' => $event->getSessionNow()))?>
  </div>
  <div class="grid_5_mmobj">

   <?php include_partial('other_events', array('texto' => __('Teleactos en directo:'), 
					      'events' => $events))?>
   <?php include_partial('other_sessions', array('texto' => __('Próximas sesiones de este teleacto:'), 
                                               'sessions' => $event->getFutureSessions()))?>

  </div>
</div>
