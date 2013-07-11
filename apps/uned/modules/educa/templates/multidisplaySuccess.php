<h1 class="titulo_widget"><?php echo __($title)?></h1>

<?php $ii = 0; foreach($serials as $b => $s): $ii++ ?>
  <div class="grid_4_categories alpha_categories">
    <div class="img_perfis_categories">
      <div style="margin-top: 20px">
        <a href="#<?php echo $b?>" >
          <?php echo $b?><br />
          (<?php echo count($s)?>)
        </a>
      </div>
    </div>
    <?php if($ii % 5 == 0):?>
     <div style="clear:left"></div>
    <?php endif?>
  </div>
<?php endforeach ?>
<div style="clear:left"></div>



<?php foreach($serials as $b => $serial):?>
  <a name="<?php echo $b?>"></a>
  <ul>
    <li class="categories_list">
      <p  class="categories_title"><?php echo $b?></p>
    <div class="unedtv_mmobjs unedtv_series">
       <?php foreach($serial as $s):?>
         <?php include_partial('serial', array('serial' => $s))?>
       <?php endforeach?>
    </div>
    <div style="clear:left"></div>
    </li>
  </ul>
<?php endforeach?>

