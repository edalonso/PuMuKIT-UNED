<h3 class="cab_body_div">
Teleactos
</h3>

<div id="tv_admin_container">
  <div id="tv_admin_bar">
    <div id="preview_event" style="margin-bottom: 10px; padding:5px; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%">
      <?php include_component('events', 'preview') ?>
    </div>
    <?php include_partial('filters') ?>
  </div>

  <div id="tv_admin_content" >
    <div id="list_events" name="list_events" act="/events/list">
      <?php include_component('events', 'array') ?>
    </div>

    <div style="float:right; width:50%">
      <ul class="tv_admin_actions">
          <li><a href="<?php echo url_for('events/create') ?>" title="Crear nuevo teleacto y nueva serie" class="tv_admin_action_create">Nuevo teleacto y serie</a></li>
          <li><?php echo m_link_to('Nuevo teleacto desde serie', 'events/listAutoComplete', array('title' => 'Crear nuevo teleacto', 'class' => 'tv_admin_action_create'), array('width' => '800')) ?></li>
      </ul>
    </div>

  </div>
  <div style="clear:both"></div>
</div>

</div>

<div id="edit_events" class="tv_admin_edit">  
      <?php include_component('events', 'edit')?>
</div>

