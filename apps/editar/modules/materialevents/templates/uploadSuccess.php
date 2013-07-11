<?php
/*****************************
 *
 *
 *    No funciona un parent.Ajx.update porque no tiene la coockie de id de session
 *    En FIREFOX y todo  LOCALHOST a veces falla
 *
 *
 ****************************/

?>


<?php echo javascript_tag("
  parent.Modalbox.hide();

  new parent.Ajax.Updater('materials_events', '".url_for('materialevents/list?event='.$event)."', {asynchronous: true, evalScripts: true});
  //new parent.Ajax.Updater('preview_event', '".url_for('events/preview?id='.$event)."', {asynchronous: true, evalScripts: true});
  parent.update_preview(". $event .");
  parent.$('materials_events').innerHTML= 'Actualize el video para que se muestren las materiales.';
  new parent.Ajax.Updater('pic_events_preview', '".url_for('virtualserial/previewEvents2?event='.$event)."', {asynchronous: true, evalScripts: true});

  parent.$('div_messages_span_info').innerHTML ='" . $msg_info . "';
  new parent.Effect.Opacity('div_messages_info', {duration:7.0, from:1.0, to:0.0});
"); ?>

