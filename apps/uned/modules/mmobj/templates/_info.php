<?php 
$umo      = $mm->getUnedMediaOld();
$columnas = UnedMediaOldPeer::getFieldNames(); 
?>

<?php if($umo): ?>
<table class = "table table-striped table-bordered table-condensed" border="1">
  <thead>
    <tr>
      <th>Campo</th>
      <th>Valor</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($columnas as $columna):?>
<?php $getter = "get" . $columna;?>
	<tr>
      <td><?php echo $columna?></td>
      <td><?php echo $umo->$getter()?></td>
    </tr>
<?php endforeach?>
  </tbody>
</table> 
<?php endif?>