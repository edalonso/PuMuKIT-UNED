<dl class="unedtv_mmobj_categories">

  <dt class="thumbnail">
     <div class="border_categories">
       <a href="<?php echo url_for('serial/index?id=' . $serial->getId())?>" >
        <img  style="z-index: 3" class="play_icon_shadow" width="30" src="/images/newtv/sombra.png" alt="">
        <img  style="z-index: 4" class="play_icon" width="15" src="/images/newtv/play.png" alt="">
        <div class="picture">
          <img style="top: 12px" src="<?php echo $serial->getFirstUrlPic() ?>" width="132" height="99"/>
        </div>
        <div class="picture">
          <img style="z-index: 2;" src="<?php echo $serial->getFirstUrlPic() ?>" width="132" height="99"/>
        </div>
       </a>
     </div>
  </dt> 

  <dd class="info">
    <div class="title_categories">
      <a href="<?php echo url_for('serial/index?id=' . $serial->getId())?>">
        <?php echo $serial->getTitle()?>
      </a>
    </div>

    <div class="subtitle_categories">
      <?php echo $serial->getSubTitle()?>
    </div>

    <div class="date_categories">
      <?php echo $serial->getPublicDate('d/m/Y')?>
    </div>

    <div class="mmobj_bottom_categories">
       <?php $numV = $serial->countMmsPublic()?>
      [<?php echo $numV?> <?php echo (($numV == 1)?__('Vídeo'):__('Vídeos'))?>]
    </div>
  </dd>

</dl>
