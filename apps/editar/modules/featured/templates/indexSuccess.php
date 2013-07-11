<?php use_helper('Object') ?>
<h3 class="cab_body_div"> Destacados</h3>


<div class="entry-edit">
  <h4 class="icon-head head-edit-form fieldset-legend">Destacados</h4>
</div>

<br />
<p>
Zona de "Destacados" en el menú lateral del frontend: lugar donde se podrán incluir anuncios, enlaces a webs externas o a series internas. Usar código HTML.
</p>
<p>
Dejar en blanco para no mostrar zona de "Destacados" en el frontend.
</p>

<br />

<div id="tv_admin_container">

<?php echo form_remote_tag(array( 
  'url' => 'featured/update',
  'loading' => "Element.show('loading')",
  'complete' => "['loading', 'save'].each(Element.hide);",
)) ?>


<fieldset>

<div class="form-row">
  <?php $sep =''; foreach (sfConfig::get('app_lang_array') as $lang): ?>
  <?php $text->setCulture($lang);  echo $sep ?>  

    <?php echo label_for('text_'. $lang, 'Destacados (' . $lang . '):', '') ?>
    <div class="content">

   
    <?php $value = object_textarea_tag($text, 'getText', array (
        'size' => '100x14',
        'control_name' => 'text_' . $lang,
        'onchange' => "Element.show('save')",
	)); echo $value ? $value : '&nbsp;' ?>
    </div>

    <?php $sep='<br />'?>
  <?php endforeach; ?>
</div>



</fieldset>


<ul class="tv_admin_actions">
  <li><img id="loading" src="/images/admin/load/spinner.gif" alt="Loading..." height="15" style="display:none; position:absolute"/></li>
  <li><input type="submit" class="tv_admin_action_save" value="OK" name="OK"/></li>
  <li><input type="reset"  class="tv_admin_action_delete" value="Cancel" name="Cancel"/></li>
</ul>

</form>
</div>
