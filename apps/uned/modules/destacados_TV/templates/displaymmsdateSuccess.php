<div class="titulo_widget titulo_widget_grande">
  <?php echo __($title)?>
</div>
<div margin-left: 10px;">
<?php if(count($mms)==0): ?>
  <div style="overflow: hidden; margin-left: 10px;">
    No existen videos vídeos de estas características
  </div>
<?php else: ?>
 <ul>
  <?php foreach($mms as $date => $mm): ?>
   <li class="categories_list" style="list-style-type: none; margin-top: 0px;">
    <div class="unedtv_mmobjs unedtv_series">
      <div class="unedtv_mmobj_categories" style="width: 100%; background-color: #FFF;">
       <p style="width: 752px" class="categories_title"><?php echo $date ?></p>
        <?php foreach($mm as $m): ?>
          <?php include_partial('global/mmobj_uned', array('mm' => $m))?>
        <?php endforeach?>
      </div>
    </div>
    <div style="widht:100%"></div>
   </li>
  <?php endforeach?>
 </ul>
<?php endif?>
</div>
