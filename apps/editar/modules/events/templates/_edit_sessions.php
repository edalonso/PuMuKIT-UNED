<?php use_helper('Object', 'JSRegExp') ?>

<div id="tv_admin_container" style="padding: 4px 20px 20px; position: relative;">

 <ul class="tv_admin_actions" style="width: 100%; text-align: left;">
   <li><?php echo m_link_to('Nueva', 'events/createSession?id='.$event->getId(), array('title' => 'Crear nueva sesion', 'class' => 'tv_admin_action_create'), array('width' => '800')) ?></li>
 </ul>
 <div id="list_sessions" name="list_sessions">
   <?php include_partial('listSessions', array('event' => $event)) ?>
 </div>
</div>
