<?php foreach($cats as $c): ?>
 <?php foreach($c as $cat): ?>
  <td id="category_name_<?php echo $cat->getId()?>" class="hU hM" style="background-color: rgb(221, 221, 221); color: rgb(102, 102, 102);border-left: 2px solid #FFF;border-top: 2px solid #FFF;border-bottom: 2px solid #FFF;">
    <div class="hN" name="^i" title="<?php echo $cat->getName()?>" role="button" tabindex="0"><?php echo $cat->getName()?></div>
  </td>
  <td id="category_close_<?php echo $cat->getId()?>" class="hV hM" style="background-color: rgb(221, 221, 221); color: rgb(102, 102, 102);border-right: 2px solid #FFF;border-top: 2px solid #FFF;border-bottom: 2px solid #FFF;">
    <span style="cursor: pointer;" class="hO" name="^i" title="Eliminar la etiqueta <?php echo $cat->getName()?>" role="button" onclick="delete_category(<?php echo $mm->getId()?>,<?php echo $cat->getId()?>)" tabindex="0">x</span>
  </td>
 <?php endforeach ?>
<?php endforeach ?>