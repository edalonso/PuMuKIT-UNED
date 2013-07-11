<?php // padding-bottom:90px para curarme en salud si el navegador alarga el textbox y usa dos filas para los selects?>
<div style="float:left; width: 87%;">
 <form id="form_buscador" name="busqueda" method="post" onsubmit="new Ajax.Updater('list_mms', '/editar.php/virtualserial/list', {asynchronous:true, evalScripts:true, parameters:Form.serialize(this)}); return false;">

  <div style="float:left; width: 10%; margin-right: -4%;">
    <div>&nbsp;Id.</div>
      <div >
        <input class="box_lupa" style="height:14px; width: 30%;" placeholder="<?php echo __("Id")?>..." name="searchs[search_id]" value="<?php echo $sf_user->getAttribute('search_id', null, 'tv_admin/virtualserial/searchs');?>" maxlength="20" type="text" />
      </div>
      <noscript><?php echo submit_tag('go'); ?></noscript>
  </div>

  <div style="float:left; margin-right: -4%;">
    <div>&nbsp;Palabras clave</div>
      <div >
        <input class="box_lupa" style="height:14px; width: 70%;" placeholder="<?php echo __("Busca")?>..." name="searchs[search]" value="<?php echo $sf_user->getAttribute('search', null, 'tv_admin/virtualserial/searchs');?>" maxlength="20" type="text" />
        <input type="image" src="/images/uned/lupa_buscador.png" style="position: relative; top: 0px; right: 15%; border: none;" name="startsearch" />
      </div>
      <noscript><?php echo submit_tag('go'); ?></noscript>
  </div>
  <div style="width: 20%; margin-right: -4%; float:left; ">
    <div style="padding-left:5px;">&nbsp;Vídeo / Audio</div>
    <?php echo select_tag('searchs[type]',
      options_for_select(
        array('all'   => 'Todos',
              'video' => 'Vídeo',
              'audio' => 'Audio'),
        $sf_user->getAttribute('type', null, 'tv_admin/virtualserial/searchs')),
      array('style' => 'width: 70%; margin: 0px;',
            'onchange' => 'Javascript:new Ajax.Updater(\'list_mms\', \'/editar.php/virtualserial/list\', {asynchronous:true, evalScripts:true, parameters:Form.serialize(\'form_buscador\')});')); ?>
  </div>
  <div style="width:20%; margin-right: -4%; float:left">
    <div style="padding-left:5px;">&nbsp;Duración</div>
    <?php echo select_tag('searchs[duration]',
      options_for_select(
        array('all' => 'Todas',
              '-5'   => 'Hasta&nbsp;&nbsp;&nbsp;5 minutos',
              '-10'  => 'Hasta 10 minutos',
              '-30'  => 'Hasta 30 minutos',
              '-60'  => 'Hasta 60 minutos',
              '+60'  => 'Más de 60 minutos',),
        $sf_user->getAttribute('duration', null, 'tv_admin/virtualserial/searchs')),
      array('style' => 'width: 70%; margin: 0px;',
            'onchange' => 'Javascript:new Ajax.Updater(\'list_mms\', \'/editar.php/virtualserial/list\', {asynchronous:true, evalScripts:true, parameters:Form.serialize(\'form_buscador\')});')); ?>
  </div>
  <div style="width: 14%; margin-right: -4%; float:left">
    <div style="padding-left:5px;">&nbsp;Año</div>
    <?php $opciones_form_years = array('all' => 'Todos') + $sf_data->getRaw('years');
      echo select_tag('searchs[year]',
      options_for_select( $opciones_form_years,
        $sf_user->getAttribute('year', null, 'tv_admin/virtualserial/searchs')),
      array('style' => 'width:55%; margin: 0px;',
            'onchange' => 'Javascript:new Ajax.Updater(\'list_mms\', \'/editar.php/virtualserial/list\', {asynchronous:true, evalScripts:true, parameters:Form.serialize(\'form_buscador\')});')); ?>
  </div>
  <div style="width: 18%; margin-right: -4%; float:left">
    <div style="padding-left:5px;">&nbsp;Género</div>
    <?php $opciones_form_genres = array('all' => 'Todos') + $sf_data->getRaw('genres');
      echo select_tag('searchs[genre]',
      options_for_select( $opciones_form_genres,
        $sf_user->getAttribute('genre', null, 'tv_admin/virtualserial/searchs')),
      array('style' => 'width:70%; margin: 0px;',
            'onchange' => 'Javascript:new Ajax.Updater(\'list_mms\', \'/editar.php/virtualserial/list\', {asynchronous:true, evalScripts:true, parameters:Form.serialize(\'form_buscador\')});')); ?>
  </div>
  <div style="width:15%; float:left">
    <div>Eliminar filtros</div>
      <input type="submit" name="search" value="reset" onclick="$('reset').setValue('rreset')" id="reset" class="btn" />
  </div>
</form>
</div>