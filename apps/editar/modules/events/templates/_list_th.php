<?php if ($sf_user->getAttribute('sort', 'id', 'tv_admin/event') == 'id'): ?>
  <th width="1%">
    <?php echo link_to_remote('Id'.($sf_user->getAttribute('type', 'asc', 'tv_admin/event') == 'asc' ? '&nbsp;&#x25BE;' : '&nbsp;&#x25B4;'), array('update' => 'list_events', 'url' => 'events/list?sort=id&type='.($sf_user->getAttribute('type', 'asc', 'tv_admin/event') == 'asc' ? 'desc' : 'asc'), 'script' => 'true')) ?>
  </th>
<?php else: ?>
  <th width="1%">
    <?php echo link_to_remote('Id', array('update' => 'list_events', 'url' => 'events/list?sort=id&type=asc', 'script' => 'true')) ?>
  </th>
<?php endif; ?>



<?php if ($sf_user->getAttribute('sort', 'id', 'tv_admin/event') == 'title'): ?>
  <th>
  <?php echo link_to_remote('Título'.($sf_user->getAttribute('type', 'asc', 'tv_admin/event') == 'asc' ? '&nbsp;&#x25BE;' : '&nbsp;&#x25B4;'), array('update' => 'list_events', 'url' => 'events/list?sort=title&type='.($sf_user->getAttribute('type', 'asc', 'tv_admin/event') == 'asc' ? 'desc' : 'asc'), 'script' => 'true')) ?>
  </th>
<?php else: ?>
  <th>
    <?php echo link_to_remote('Titulo', array('update' => 'list_events', 'url' => 'events/list?sort=title&type=asc', 'script' => 'true')) ?>
  </th>
<?php endif; ?>




<?php if ($sf_user->getAttribute('sort', 'id', 'tv_admin/event') == 'date'): ?>
  <th width="1%">
    <?php echo link_to_remote('Fecha'.($sf_user->getAttribute('type', 'asc', 'tv_admin/event') == 'asc' ? '&nbsp;&#x25BE;' : '&nbsp;&#x25B4;'), array('update' => 'list_events', 'url' => 'events/list?sort=date&type='.($sf_user->getAttribute('type', 'asc', 'tv_admin/event') == 'asc' ? 'desc' : 'asc'), 'script' => 'true')) ?>
  </th>
<?php else: ?>
  <th width="1%">
    <?php echo link_to_remote('Fecha', array('update' => 'list_events', 'url' => 'events/list?sort=date&type=asc', 'script' => 'true')) ?>
  </th>
<?php endif; ?>
