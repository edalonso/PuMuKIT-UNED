<abbr title="<?php $sep = ""; foreach($grounds as $g):?><?php echo $sep ?><?php echo $g->getName()?><?php $sep = ", "; endforeach?>">

  <?php if($show_ground):?>
    <?php $sep = ""; $ii = 0; foreach($grounds as $g): $ii++?>
      <?php if($ii == 2):?>
        <span style="font-weight: bold; font-size: 100%">[+]</span>
      <?php break; endif?>
          <?php echo $sep . str_abbr($g->getName(), 15, "...") ?>
    <?php $sep = ", "; endforeach?>
  <?php else:?>
    <span style="font-weight: bold; font-size: 100%">[+]</span>
  <?php endif ?>

</abbr>