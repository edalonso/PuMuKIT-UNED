<h3 class="cab_body_div"><img src="/images/admin/cab/config_ico.png"/>Timeframes</h3>

<div id="tv_admin_container">
  <div id="tv_admin_bar">
    <?php // TO DO - descomentar
     //include_partial('filters') ?>
    <div id="preview_role" style="display:none; padding:5px; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%">
      <?php // No uso preview - include_component('timeframes', 'preview') ?>
    </div>
  </div>
  <div id="tv_admin_content" >
    <div id="list_timeframes" name="list_timeframes" act="/timeframes/list">
      <?php include_component('timeframes', 'list') ?>
    </div>
    <div style="float:right; width:50%">
      <ul class="tv_admin_actions">
        <!-- Falta -->
        <li><?php echo button_to_function('Nuevo', 'Modalbox.show("' . url_for('timeframes/create') . '", {title:"Editar nuevo timeframe", width:800}); return false;', array ('class' => 'tv_admin_action_create')) ?></li>
      </ul>
    </div>
    <select id="options_timeframes" style="margin: 10px 0px; width: 33%" title="Acciones sobre elementos selecionados" onchange="window.change_select('timeframe', $('options_timeframes'))">
      <option value="default" selected="selected">Seleciona una acci&oacute;n...</option>
      <option disabled="">---</option>
      <option value="delete_sel">Borrar selecionados</option>
      <?php // Eliminado porque crea url erronea /timeframes/timeframes/create <option value="create">Crear nuevo</option>?>
    </select>
  </div>
</div>