<fieldset style="padding: 5px; border: 1px solid #EEE">
<legend style="font-weight: bold">&Uacute;LTIMAS OBJETOS MM. PUBLICADAS</legend>

<ul style="margin-left: 15px;">
<?php foreach($mms as $mm):?>
  <li>
    <a href="<?php echo url_for('mms/index?serial=' . $mm->getSerialId()) ?>"><?php echo $mm->getTitle()?></a>
    <!-- <a href="<?php echo url_for('virtualserial/index?mm_id=' . $mm->getId()) ?>"><?php echo $mm->getTitle()?></a> -->
    (<?php echo $mm->getPublicdate('d/m/Y')?>)
  </li>
<?php endforeach?>
</ul>

</fieldset>