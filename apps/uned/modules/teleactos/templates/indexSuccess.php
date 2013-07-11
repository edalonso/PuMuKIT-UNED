<div class="titulo_widget titulo_widget_grande"> 
  <?php echo __($title)?>
</div>

<div style="overflow: hidden; margin-left: 10px;">
   <?php if((count($events)==0) && (count($toDayEvents) == 0) && (count($rnEvents) == 0)): ?>
  <div style="overflow: hidden; margin-left: 10px;">
    No existen teleactos programados
  </div>
<?php else: ?>
     <?php include_partial('directos', array('events' => $rnEvents)) ?>   
     <?php include_partial('hoy', array('events' => $toDayEvents)) ?>
     <?php include_partial('futuros', array('events' => $events)) ?>
     <?php if ($pages > 0) include_partial('pager', array('page' => $page, 'total' => $pages)) ?>
  <?php endif?>

</div>
