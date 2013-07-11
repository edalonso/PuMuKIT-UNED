<dl class="unedtv_mmobj">
  <dt class="thumbnail">
    <figure class="album">
      <a href="<?php echo url_for('mmobj/index?id=' . $mm->getId()) ?>" >
        <div class="picture">
             <div class="img">
                <img src="<?php echo $mm->getFirstUrlPic() ?>" />
             </div>
          <span class="video-duration"><?php echo $mm->getDurationMin()?>:<?php echo $mm->getDurationSeg()?></span>
        </div>
      </a>
    </figure>
  </dt> 

  <dd class="info">
    <div class="title" style="padding: 10px 0px 0px 10px;">
      <a href="<?php echo url_for('mmobj/index?id=' . $mm->getId()) ?>">
       <?php if(strlen($mm->getTitle()) < 47):?>
         <?php echo $mm->getTitle() ?>
       <?php else: ?>
         <abbr title="<?php echo $mm->getTitle()?>">
          <?php echo str_abbr($mm->getTitle(), 47, "...") ?>
         </abbr>
       <?php endif ?>
      </a>
    </div>

    <div class="subtitle" style="margin-left: 10px;">
       <?php echo str_abbr($mm->getSubTitle(), 57, "...") ?>
    </div>

    <div style="float: left; margin-top: 10px">
      <div class="date">
        <?php //$mm = PubChannelPeer::getFirstMmFromSerial(1, $mm->getId())?>
        <?php echo $mm->getRecordDate('d/m/Y') ?>
        <?php // echo is_null($mm)? $mm->getPublicDate('d/m/Y'): $mm->getRecordDate('d/m/Y')?>
      </div>

      <div class="mmobj_bottom">
        <?php echo __('Visto: ') ?><span style="font-weight:bold;"><?php echo $mm->getNumView() ?></span> <?php echo (($mm->getNumView() == 1)? __(' vez') : __(' veces'))?>
      </div>
    </div>
  </dd>

</dl>
