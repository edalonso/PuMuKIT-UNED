<div class="titulo_widget titulo_widget_grande">
  <?php echo __($title)?>
</div>

<div style="margin: 15px 0px 0px 45px; padding-bottom:50px;">
   <form id="form_buscador" name="busqueda" method="get" action="<?php echo url_for('noticias/index')?>">
     <?php include_partial('global/buscador', array('unescos' => $sf_data->getRaw('unescos'), 'years' => $sf_data->getRaw('years'), 'page' => $page, 'module' => 'noticias'))?>
   </form>
</div>


<?php include_partial('global/displaymmsdate', array('page' => $page, 'total' => $total, 'url' => '/noticias/index?', 'mms' => $mms))?>

<?php if ($error != '') :?>
<div id="error_lucene" style="padding: 10px; background-color: #015442; border-radius: 5px; font-size: 15px; font-weight: bold; position:absolute; top: 200px; left: 250px; z-index: 5; width: 300px;"><?php echo "Su búsqueda produjo el error: " . $error . "\n" ?></div>
<?php echo javascript_tag("
  Effect.Fade('error_lucene',{duration: 5.0});
"); ?>
<?php endif;?>