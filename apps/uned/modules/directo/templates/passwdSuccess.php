<h1 class="titulo_widget_mmobj2"><?php echo __($title) .$event->getTitle()?></h1>

<div id="unedtv_m_mmobj" class="unedtv_m">
  <div class="grid_10_mmobj">
   <div style="margin: 170px;">
     <div style="padding-bottom: 10px;">Para visualizar este video es necesario introducir una contraseña</div>
       <form name="passwdForm" method="post" action="<?php echo url_for('directo/index') . '?id=' . $event->getId() ?>">
         <input type="password" size="25" maxlength="256" name="passwd">
         <input type="submit" name="Ok" value="Ok">
       </form>
   </div>
  </div>
  <div class="grid_5_mmobj">

   <?php include_partial('other_events', array('texto' => __('Teleactos en directo:'), 
					      'events' => $events))?>
   <?php include_partial('other_sessions', array('texto' => __('Próximas sesiones de este teleacto:'), 
                                               'sessions' => $event->getFutureSessions()))?>

  </div>
</div>
