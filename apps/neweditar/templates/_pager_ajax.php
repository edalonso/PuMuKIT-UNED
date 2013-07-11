<?php 
  $ver_anterior  = (($page == 1)? array('style' => 'visibility : hidden'): array('style' => 'color : blue; font-weight : normal ') );
  $ver_siguiente = (($page == $total || $total == 0)? array('style' => 'visibility : hidden'): array('style' => 'color : blue; font-weight : normal ') );
?>

  <div id="pager_<?php echo $name?>" style="float:left;">
    <div style="float:left; margin:0em .5em; <?php if ($page == 1) echo 'display:none;'?>">
       <a style="color : blue; font-weight : normal " href="#" onclick="$('#list_<?php echo $name ?>s').load('<?php echo url_for($name.'s/list?page='.($page-1))?>'); $('#slider').slider('option', 'value', <?php echo $page-1?>); return false;">«Anterior</a>
    </div>

    <div style="float:left; margin:0em .5em; width:9em">Pag. <span id="num_pag_<?php echo $name?>"><?php echo ($page)?></span> de <?php echo $total ?></div>

    <div id="slider" style="float: left; height:7px; width: 100px;" class="ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all"></div>

    <div style="float:left; margin:0em .5em;<?php if ($page == $total) echo 'display:none;'?>">
      <a style="color : blue; font-weight : normal " href="#" onclick="$('#list_<?php echo $name ?>s').load('<?php echo url_for($name.'s/list?page='.($page+1))?>'); $('#slider').slider('option', 'value', <?php echo $page+1 ?>); return false;">Siguiente»</a>
    </div>

  </div>

  <?php if ( $total>1 ): ?>
    <script language="javascript" type="text/javascript">
      //<![CDATA[
      $( "#slider" ).slider({
        min: 1,
        max: <?php echo $total ?>,
        value: <?php echo $page ?>,
        slide: function (event, ui){$("num_pag_<?php echo $name?>").innerHTML= (ui.value);},
        change: function (event, ui){$('#list_<?php echo $name ?>s').load('/neweditar.php/<?php echo $name?>s/list/page/'+ui.value);} 
      });
      //]]>
    </script>
  <?php endif;?>


<!--



-->