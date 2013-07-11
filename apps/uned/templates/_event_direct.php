<dl class="unedtv_mmobj">
  <dt class="thumbnail">
    <figure class="album">
      <a href="<?php echo url_for('directo/index?id=' . $event->getId()) ?>" >
        <div class="picture_direct">
             <div class="img">
                <img class="video-duration" width="25" alt="" src="/images/uned/sombra.png" style="top: 1px; left: 1px; height: 15px;">
                <img class="video-duration" width="15" alt="" src="/images/uned/play.png" style="top: 2px; left: 2px; height: 15px; opacity: 0.9">
                <img src="<?php echo $event->getFirstUrlPic() ?>" />
             </div>
        </div>
      </a>
    </figure>
  </dt> 

  <dd class="info">
    <div class="title" style="padding: 10px 0px 0px 10px;">
      <a href="<?php echo url_for('directo/index?id=' . $event->getId()) ?>">
        <abbr title="<?php echo $event->getTitle()?>">
         <?php echo str_abbr($event->getTitle(), 67, "...") ?>
        </abbr>
      </a>
    </div>
    <div style="margin-top: 10px">
      <div class="date">
   Comienzo: <?php echo (($now = $event->getSessionNow()) == null)? $event->getFirstSession()->getInitDate('d/m/Y H:i') : $now->getInitDate('d/m/Y H:i')?>
      </div>
    </div>
  </dd>

</dl>
