<?php //los futuros ?>
<ul>
 <?php foreach($events as $date => $event): ?>
   <li class="categories_list" style="list-style-type: none; margin-top: 0px;">
    <div class="unedtv_mmobjs unedtv_series">
      <div class="unedtv_mmobj_categories" style="width: 100%; background-color: #FFF;">
       <p style="width: 752px" class="categories_title">Otros teleactos futuros</p>
     <?php foreach($event as $m): ?>
        <?php include_partial('global/event', array('event' => $m, 'template' => 'futuro'))?>
     <?php endforeach?>
      </div>
    </div>
    <div style="width:100%"></div>
   </li>
  <?php endforeach?>
</ul>
