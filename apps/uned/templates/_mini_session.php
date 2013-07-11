<div class="unedtv_mini_mmobjs">
<?php foreach($sessions as $session):?>
<?php $event = $session->getEvent() ?>
<dl class="unedtv_mini_mmobj">
  <dt class="thumbnail" style="width: 110px">
    <a href="<?php echo url_for('directo/index?id=' . $event->getId())?>" >
      <div style="background-color: #FFF; text-align: center; padding: 4px; box-shadow: 1px solid #000;">
        <div class="border_mmobj" style="background-color: #000; text-align: center; padding: 0px; box-shadow: none;">
             <div class="img" style="max-height: 62px; vertical-align: middle; position: relative">
                <img class="play_icon_shadow" width="25" alt="" src="/images/uned/sombra.png" style="top: 0px; left: 0px; position: absolute;">
                <img class="play_icon" width="15" alt="" src="/images/uned/play.png" style="top: 5px; left: 5px">
                <img class="play_icon_mmobj" alt="" src="/images/uned/play_icon.gif" />
                <img src="<?php echo $event->getFirstUrlPic()  ?>" style="max-height: 62px; max-width: 102px; vertical-align: middle; text-align: center;"/>
             </div>
        </div>
      </div>
    </a>
  </dt> 

  <dd class="info">
    <div class="title" style="margin-left: 115px;">
      <a title="<?php echo $event->getTitle()?>" href="<?php echo url_for('directo/index?id=' . $event->getId())?>" >
        <?php echo str_abbr($event->getTitle(), 50, "...") ?>
      </a>
    </div>
    <div class="comienzo_mini_mmobj_bottom">
       Comienzo: <?php echo $session->getInitDate("d/m H:i") ?>
    </div>
  </dd>

</dl>

<?php endforeach?>
</div>
