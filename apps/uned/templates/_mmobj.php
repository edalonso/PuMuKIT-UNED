<div class="unedtv_mmobjs">
<?php foreach($mmobjs as $ii => $mmobj):?>

<div class="unedtv_mmobj">

  <?php if($mmobj->getSerialId() != $mmobj->getId()): ?>
    <div class="thumbnail">
      <a href="<?php echo $mmobj->getUrl()?>" >
        <div class="picture">
          <div class="img">
            <img alt="serial_pic" src="<?php echo $mmobj->getFirstUrlPic() ?>" />
          </div>
          <span class="video-duration"><?php echo $mmobj->getDurationMin()?>:<?php echo $mmobj->getDurationSeg()?></span>
        </div>
      </a>
    </div>

  <?php else: ?>
    <?php $url_pic = $mmobj->getFirstUrlPic() ?>
    <div class="thumbnail" style="height: 110px; margin-right: 10px;">
      <a href="<?php echo $mmobj->getUrl()?>" >
        <div class="picture" style="position: relative">
          <div class="img" style="max-width: 172px;">
            <img alt="serial_pic" src="<?php echo $url_pic ?>" />
          </div>
        </div>
        <div class="picture" style="position: relative; top: -102px;">
          <div class="img" style="max-width: 172px;">
            <img alt="serial_pic" src="<?php echo $url_pic ?>" />
          </div>
        </div>
        <div class="picture" style="position: relative; top: -205px;">
          <div class="img" style="max-width: 172px;">
            <img alt="serial_pic" src="<?php echo $url_pic ?>" />
          </div>
        </div>
      </a>
    </div>
  <?php endif ?>

  <div class="info">
    <div class="title">
      <a title="<?php echo  $mmobj->getTitle() ?>" href="<?php echo $mmobj->getUrl()?>" >
        <?php echo str_abbr($mmobj->getTitle(), 69, "...") ?>
      </a>
    </div>

    <div class="serial_title">
      <?php if(strlen($mmobj->getLine2()) > 1): ?>
        <?php $aux = $mmobj->getLine2() ?>
      <?php else:?>
        <?php $aux = $mmobj->getSubTitle() ?>
      <?php endif ?>

      <abbr title="<?php echo $aux ?>">
        <?php echo str_abbr($aux, 27, "...") ?>
      </abbr>
    </div>

    <?php if($mmobj->getSerialId() != $mmobj->getId()): ?>
      <div class="language">
        <?php echo $mmobj->getRecordDate('d/m/Y')?>
      </div>
    <?php endif ?>

    <div class="mmobj_bottom">
      <?php if($mmobj->getSerialId() == $mmobj->getId()): ?>
        <?php echo $mmobj->getPublicDate('d/m/Y') ?>
      <?php else: ?>
        <?php echo __('Visto: ') ?><span style="font-weight:bold;"><?php echo $mmobj->getNumView() ?></span><?php echo (($mmobj->getNumView() == 1)? __(' vez') : __(' veces'))?>
      <?php endif ?>
    </div>
  </div>

</div>
<?php endforeach?>
</div>

