<?php use_helper('Object', 'JSRegExp') ?>
<!-- Filter -->
<div class="tv_admin_filters">
<?php echo form_remote_tag(array('update' => 'list_events', 'url' => 'events/list', 'script' => 'true' ), 'id=filter_events') ?>
  <fieldset>
    <h2>Buscar</h2>

    <div class="form-row">
     <label for="title">T&iacute;tulo:</label>
      <div class="content">
        <?php echo input_tag('filters[title]', $sf_user->getAttribute('title', null, 'tv_admin/event/filters')) ?>
      </div>
    </div>

<?php
   $from = '';
   $to = '';
   $filters_date = $sf_user->getAttribute('date', null, 'tv_admin/event/filters');
if(isset($filters_date['from']) && $filters_date['from'] != '') {
  list($d, $m, $y) = sfI18N::getDateForCulture($filters_date['from'], $sf_user->getCulture()); 
  $from = $y."-".$m."-" . $d;
 }
if(isset($filters_date['to']) && $filters_date['to'] != '') {
  list($d, $m, $y) = sfI18N::getDateForCulture($filters_date['to'], $sf_user->getCulture()); 
  $to = $y."-".$m."-" . $d;
 }
?>
    <div class="form-row">
     <label for="date"><?php echo 'Fecha:' ?></label>
      <div class="content">
        <?php echo input_date_tag('filters[date][from]', $from, array('rich' => true, 'calendar_button_img' => '/images/admin/buttons/date.png' )) ?>
       </div>
      <br />
      <label for="publicdate"><?php echo 'Hasta:' ?></label>
      <div class="content">
        <?php echo input_date_tag('filters[date][to]', $to, array ('rich' => true, 'calendar_button_img' => '/images/admin/buttons/date.png' )) ?>
      </div>
    </div>

  </fieldset>

  <ul class="tv_admin_actions">
    <li>
      <?php echo button_to_remote('reset', array('before' => 'resetSearchForm();', 'update' => 'list_events', 'url' => 'events/list?filter=filter ', 'script' => 'true'), 'class=tv_admin_action_reset_filter') ?>
    </li>
    <li>
      <?php echo submit_tag('filtrar', 'name=filter class=tv_admin_action_filter onclick=return testDates($("filters_date_from").value, $("filters_date_to").value, '. get_js_regexp_date($sf_user->getCulture()) . ')') ?>
    </li>
  </ul>
</form>
</div>



<?php echo javascript_tag("
  function resetSearchForm() {
    $('filters_title').value = '';
    $('filters_date_from').value = '';
    $('filters_date_to').value = '';
  }
") ?>