<?php use_helper('Object', 'JSRegExp') ?>


<div id="tv_admin_container">

<?php echo form_remote_tag(array( 
  'update' => 'list_sessions', 
  'url' => 'events/updateSession',
  'script' => 'true',
)) ?>


<?php echo object_input_hidden_tag($session, 'getId') ?>
<input type="hidden" name="event_id" id="event_id" value="<?php echo $session->getEventId() ?>" />
<input type="hidden" name="preview" id="preview" value="true" />


<fieldset>

<div class="form-row">
  <?php echo label_for('init_date', 'Fecha y hora de inicio: ', 'class="required" ') ?>
  <div class="content">
    <?php echo object_input_date_tag($session, 'getInitDate', array (
      'rich' => true,
      'withtime' => true,
      'calendar_button_img' => '/images/admin/buttons/date.png',
      'control_name' => 'init_date'
    )) ?>
    <span id="error_date" style="display:none" class="error">Formato fecha no v&aacute;lido</span>
    </div>
</div>


<div class="form-row">
  <?php echo label_for('end_date', 'Fecha y hora de fin: ', 'class="required" ') ?>
  <div class="content">
    <?php echo object_input_date_tag($session, 'getEndDate', array (
      'rich' => true,
      'withtime' => true,
      'calendar_button_img' => '/images/admin/buttons/date.png',
      'control_name' => 'end_date'
    )) ?>
    <span id="error_date" style="display:none" class="error">Formato fecha no v&aacute;lido</span>
    </div>
</div>


<div class="form-row">
  <?php echo label_for('notes','Notas:', 'class="required" ') ?>
  <div class="content">
      <?php echo object_textarea_tag($session, 'getNotes', array (
        'size' => '68x2',
        'control_name' => 'notes',
      )); ?>
  </div>
</div>




</fieldset>


<ul class="tv_admin_actions">
  <li><?php echo submit_tag('OK','name=OK class=tv_admin_action_save onclick=return testDates($("init_date").value, $("end_date").value, '. get_js_regexp_timedate($sf_user->getCulture()) . ')'); ?></li>
  <li><?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li>
</ul>

</form>
</div>

<script type="text/javascript">
window.scrollTo(0,0);
</script>