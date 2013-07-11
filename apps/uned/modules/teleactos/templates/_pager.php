<div class="pager" style="text-align:center;">
<?php if ($page > 1 && $total != 0):?>
  <div style="float:left;" class="previous <?php if($page == 1):?>disabled<?php endif ?>">
    <a href="<?php echo url_for("teleactos?page=". ($page - 1)) ?>">&larr; Más recientes</a>
  </div>
<?php endif ?>

<?php if ($page < $total && $total != 0):?>
  <div style="float:right;" class="next <?php if($page == $total):?>disabled<?php endif ?>">
     <a href="<?php echo url_for("teleactos?page=" . ($page + 1)) ?>">Futuros&rarr;</a> 
  </div>
<?php endif?>
<?php if ($total !=0):?>
  <div>
    <?php echo $page ?> de <?php echo $total ."\n"?>
  </div>
<?php endif ?>  
</div>