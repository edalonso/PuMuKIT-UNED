<div id="tv_admin_container">
<form>
<fieldset>
<h2><?php echo "<strong>URL:</strong>"; ?></h2>
<div class="form-row">
  <?php echo label_for('embed', 'Teleacto:', 'class="required" ') ?>
  <div class="content">
    <input type="text" onclick="this.select()" size="80" value="<?php echo sfConfig::get('app_info_link').'/teleacto/'.$event->getId().'.html' ?>" />
  </div>
</div>

</fieldset>


<ul class="tv_admin_actions">
  <li><?php echo button_to_function('OK', "Modalbox.hide()", 'class=tv_admin_action_save') ?> </li>
</ul>

</form>
</div>
