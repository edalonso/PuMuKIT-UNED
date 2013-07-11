  <div id="pager_mm" style="float:left;">
    <div style="float:left; margin:0em .5em; <?php if ($page == 1) echo 'display:none;'?>">
       <a style="color : blue; font-weight : normal;" href="#" onclick="$('#list_mms').load('<?php echo url_for('serials/mmsList?id=' . $id . '&page=' . ($page-1))?>'); return false;">«Anterior</a>
    </div>

    <div style="float:left; margin:0em .5em; width:9em">Pag. <span id="num_pag_mm"><?php echo ($page)?></span> de <?php echo $total ?></div>

    <div id="slider_<?php echo $id?>" style="float: left; height:7px; width: 100px;" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>

    <div style="float:left; margin:0em .5em;<?php if ($page == $total) echo 'display:none;'?>">
        <a style="color : blue; font-weight : normal;" href="#" onclick="$('#list_mms').load('<?php echo url_for('serials/mmsList?id=' . $id . '&page=' . ($page+1))?>'); return false;">Siguiente»</a>
    </div>

  </div>

  <?php if ( $total>1 ): ?>
    <script language="javascript" type="text/javascript">
    $(function() {
        $('#slider_<?php echo $id?>').slider({
          min: 1,
          max: <?php echo $total ?>,
          value: <?php echo $page ?>,
          slide: function (event, ui){$('#num_pag_mm').innerHTML= (ui.value);},
          change: function (event, ui){$('#list_mms').load('mmsList?id=' + <?php echo $id?> + '&page=' + ui.value);} 
        });
     });
    </script>
  <?php endif;?>