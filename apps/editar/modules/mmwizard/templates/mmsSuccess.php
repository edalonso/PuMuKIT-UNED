<?php use_helper('Object') ?>

<div id="tv_admin_container">

<form id="mm_form_wizard">

<input type="hidden" name="serial_id" value="<?php echo $serial_id ?>" />
<input type="hidden" name="mod" value="<?php echo $mod ?>" />

<fieldset>

<div class="form-row">
  <?php echo label_for('title' , 'T&iacute;tulo:', 'class="required long" ') ?>
  <div class="content content_long">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $mm->setCulture($lang);  echo $sep ?>  

      <?php $value = object_input_tag($mm, 'getTitle', array (
        'size' => 80,
        'control_name' => 'title_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('subtitle', 'Subt&iacute;tulo:', 'class="required long" ') ?>
  <div class="content content_long">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $mm->setCulture($lang);  echo $sep ?>  

      <?php $value = object_input_tag($mm, 'getSubtitle', array (
        'size' => 80,
        'control_name' => 'subtitle_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>

      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>

<div class="form-row">
  <?php echo label_for('description', 'Descripci&oacute;n:', 'class="required long" ') ?>
  <div class="content content_long">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $mm->setCulture($lang);  echo $sep ?>  

      <?php $value = object_textarea_tag($mm, 'getDescription', array (
        'size' => '80x4',
        'control_name' => 'description_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>

      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<?php if ($mod == 'virtualserial' && $sf_user->getAttribute('cat_id', null, 'tv_admin/virtualserial') != 0 && $sf_user->getAttribute('cat_id', null, 'tv_admin/virtualserial') != -1):?>
<div class="form-row">
  <?php echo label_for('category_name', 'Categor&iacute;a:', 'class="required long" ') ?>
  <div class="content content_long">
     El objeto multimedia se añadir&aacute; a la categor&iacute;a UNESCO: <span class="label label-info unesco_element"><?php echo $sf_user->getAttribute('name_cat', null, 'tv_admin/virtualserial')?></span>
  </div>
</div>
<?php endif;?>

</fieldset>


<ul class="tv_admin_actions">
  <li><?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li>
  <li><?php echo button_to_function('Next', "Modalbox.show('".url_for("mmwizard/file") . "',{title:'PASO III.', params:Form.serialize('mm_form_wizard')})", 'class=tv_admin_action_next') ?> </li>
</ul>

</form>
</div>
