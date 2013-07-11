<h3 class="cab_body_div">Servidores de Distribución</h3>

<div id="tv_admin_container">
  <div id="tv_admin_bar">
    <div id="preview_streamserv" style="padding:5px; border: solid 1px #DDD; background: #8eb0bc url(/images/admin/cab/cab.gif) repeat-x scroll 0% 0%">
      <?php include_component('streamservs', 'preview') ?>
    </div>
  </div>

  <div id="tv_admin_content" >
    <div id="list_streamservs" name="list_streamservs" act="/streamservs/list">
      <?php include_component('streamservs', 'list') ?>
    </div>

    <div style="float:right; width:50%">
      <ul class="tv_admin_actions">
        <!-- Falta -->
        <li><?php echo button_to_function('create', 'Modalbox.show("streamservs/create", {title:"Editar Nuevo Servidor", width:800}); return false;', array ('class' => 'tv_admin_action_create')) ?></li>
      </ul>
    </div>


    <select id="options_streamservs" style="margin: 10px 0px; width: 33%" title="Acciones sobre elementos selecionados" onchange="window.change_select('streamserv', this)">
      <option value="default" selected="selected">Seleciona una acci&oacute;n...</option>
      <option disabled="">---</option>
      <option value="delete_sel">Borrar selecionados</option>
      <option value="create">Crear nuevo</option>
    </select>

  </div>    
</div>