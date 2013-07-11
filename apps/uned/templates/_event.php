<dl class="unedtv_mmobj">
  <dt class="thumbnail">
    <figure class="album">
      <a href="<?php echo url_for('directo/index?id=' . $event->getId()) ?>" >
        <div class="picture">
             <div class="img">
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
       Comienzo:
       <?php switch($template):
	 case 'hoy': 
                 echo ($event->getSimilarSessionDate() == null)? $event->getTodaysFirstSession()->getInitDate('d/m/Y H:i') : $event->getSimilarSessionDate()->getInitDate('d/m/Y H:i'); break; ?>
	 <?php case 'futuro': 
                  echo ($event->getFutureSession() == null)? $event->getFirstSession()->getInitDate('d/m/Y H:i') : $event->getFutureSession()->getInitDate('d/m/Y H:i'); break;?>
       <?php endswitch ?>
      </div>
    </div>
  </dd>

</dl>
