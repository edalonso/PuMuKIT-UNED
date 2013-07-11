<fieldset style="padding: 5px; border: 1px solid #EEE">
<legend style="font-weight: bold">USO DE DISCO</legend>

<?php foreach($disks as $d): $porc = sprintf('%.2f', ($d[2]*100)/$d[1]) ?>
  <div>
    <span style="font-weight: bold"><?php echo $d[0] ?></span> 
    (<?php echo $d[2] ?>G/<?php echo $d[1] ?>G)

    <div style="background-image: linear-gradient(left, green 50%, red); background-image: -webkit-linear-gradient(left, green 50%, red);">
      <div style="background-color: #aaa; float: right; text-align: right; width: <?php echo (100 - $porc) ?>%"> &nbsp;
      </div>
      <?php echo $porc?>%
      <div style="clear: right"></div>
    </div>

  </div>
<?php endforeach ?>

</fieldset>