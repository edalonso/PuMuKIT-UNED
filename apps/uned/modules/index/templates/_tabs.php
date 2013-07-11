<div class="index_tab">
   <div id="index_tab_last" class="index_tab_entry">
      <p class="titulo_widget_mmobj"><?php echo __('Recientes')?></p>
      <?php include_partial('global/mmobj', array('mmobjs' => $last, 'show_ground' => $show_ground))?>  
    <div style="clear:left"></div>
  </div>
  <div id="index_tab_popular" class="index_tab_entry">
      <p class="titulo_widget_mmobj"><?php echo __('Más vistos')?></p>
      <?php include_partial('global/mmobj', array('mmobjs' => $popular, 'show_ground' => $show_ground))?>    
   <div style="clear:left"></div>
  </div>
</div>