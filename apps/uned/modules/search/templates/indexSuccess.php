<p class="titulo_widget titulo_widget_grande"><?php echo __('Búsqueda')?></p>

<div id="cse" style="width: 100%; margin-left:10px;"><?php echo __('Cargando')?>...</div>
<script src="http://www.google.com/jsapi" type="text/javascript"></script>
<script type="text/javascript">
  google.load('search', '1', {language :' <?php echo $sf_user->getCulture() ?>'});
  google.setOnLoadCallback(function() {
    var customSearchControl = new google.search.CustomSearchControl('004202697780913836603:hmadbqzzryg');
    customSearchControl.setResultSetSize(google.search.Search.FILTERED_CSE_RESULTSET);
    customSearchControl.draw('cse');
    customSearchControl.execute('<?php echo $sf_data->getRaw('search')?>');
  }, true);
</script>
<link rel="stylesheet" href="http://www.google.com/cse/style/look/default.css" type="text/css" /> 
