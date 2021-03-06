<?php use_helper('Object') ?>

<div id="tv_admin_container">

<?php echo form_remote_tag(array( 
  'update' => 'list_roles', 
  'url' => 'roles/update',
  'script' => 'true',
)) ?>

<?php echo object_input_hidden_tag($role, 'getId') ?>


<fieldset>

<div class="form-row">
  <?php echo label_for('display', 'Display:', 'class="required" ') ?>
  <div class="content">
  <?php $value = object_checkbox_tag($role, 'getDisplay', array ('control_name' => 'display',
)); echo $value ? $value : '&nbsp;' ?>
    </div>
</div>


<div class="form-row">
  <?php echo label_for('cod', 'Codigo:', 'class="required" ') ?>
  <div class="content">
  <?php $value = object_input_tag($role, 'getCod', array ('size' => 5,  'control_name' => 'cod',
)); echo $value ? $value : '&nbsp;' ?>
    </div>
</div>


<div class="form-row">
  <label for="xml" class="required">Xml <a target="_blank" href="http://www.ebu.ch/metadata/cs/web/ebu_RoleCodeCS_p.xml.htm" title="European Broadcasting Union Role Codes">(?)</a>:</label>
  <div class="content">
  <?php $value = object_input_tag($role, 'getXml', array ('size' => 10,  'control_name' => 'xml',
)); echo $value ? $value : '&nbsp;' ?>
    </div>
</div>


<div class="form-row">
  <?php echo label_for('name', 'Nombre:', 'class="required" ') ?>
  <div class="content">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $role->setCulture($lang);  echo $sep ?>  
  
      <?php $value = object_input_tag($role, 'getName', array ('size' => 80,  'control_name' => 'name_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>


<div class="form-row">
  <?php echo label_for('text', 'Texto:', 'class="required" ') ?>
  <div class="content">
    <?php $sep =''; foreach ($langs as $lang): ?>
      <?php $role->setCulture($lang);  echo $sep ?>  
  
      <?php $value = object_input_tag($role, 'getText', array ('size' => 80,  'control_name' => 'text_' . $lang,
      )); echo $value ? $value.'<span class="lang">'.$lang.'</span>' : '&nbsp;' ?>
  
      <?php $sep='<br /><br />'?>
    <?php endforeach; ?>
  </div>
</div>



</fieldset>


<ul class="tv_admin_actions">
<li><?php echo submit_tag('OK','name=OK class=tv_admin_action_save onclick=Modalbox.hide()'); ?></li>
<li><?php echo button_to_function('Cancel', "Modalbox.hide()", 'class=tv_admin_action_delete') ?> </li>
  </ul>

</form>
</div>

