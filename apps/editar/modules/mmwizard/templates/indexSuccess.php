<?php use_helper('Object') ?>

<div id="tv_admin_container">

<form id="serial_form_wizard">
<input type="hidden" name="serial_id" id="serial_id" value="<?php echo $serial_id ?>" />
<input type="hidden" name="mod" id="mod" value="<?php echo $mod ?>" />


<fieldset>

<div class="form-row">
  <input type="radio" name="type" value="one" id="radio_type_one" checked="checked" /> 
  <label for="radio_type_one" style="display:inline; position: static; padding: 0; float: none; color: #000; ">
    Sólo un archivo multimedia.
  </label>
</div>

<div class="form-row">
  <input type="radio" name="type" value="several" id="radio_type_several" /> 
  <label for="radio_type_several" style="display:inline; position: static; padding: 0; float: none; color: #000; ">
    Varios archivos multimedia.
  </label>
</div>

</fieldset>


<ul class="tv_admin_actions">
  <li><?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li> 
  <li><?php echo button_to_function('Next', "Modalbox.show('".url_for("mmwizard/two"). "',{title:'PASO II:', params:Form.serialize('serial_form_wizard')})", 'class=tv_admin_action_next') ?> </li>
</ul>

</form>
</div>
