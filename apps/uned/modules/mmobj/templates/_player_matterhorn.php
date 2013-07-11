  <div id="mh_player">
    <?php $is_html5 = (preg_match('/(Safari)|(Chrome)/i', $_SERVER['HTTP_USER_AGENT']) == 1)?>

    <iframe src="<?php echo $oc->getIframeUrl($m->getBroadcast()->getBroadcastType(),$is_html5,true)?>" 
            id="mh_iframe"
            style="border:0px #FFFFFF none; width:100%; height:850px;" 
            name="Opencast Matterhorn - Media Player" 
            scrolling="no" frameborder="0" marginheight="0px" marginwidth="0px" 
            webkitallowfullscreen="true" mozallowfullscreen="true" allowfullscreen="true"
            >
    </iframe>

    <div class="mm_player">
      <div class="num_view">
        <div style="float:left">
          <?php echo __("Idioma del video")?>: <span class="num_view_number"><?php echo $oc->getLanguage() ?></span>
        </div>
        <?php echo __('Visto:')?> 
        <span class="num_view_number"><?php echo $oc->getNumView()?></span>
        <?php echo (($oc->getNumView() == 1)?__(' vez'):__(' veces'))?> &nbsp;&nbsp;
      </div>
    </div>

    <div class="title">
      <?php echo $m->getSubtitle() ?>
    </div>

    <p class="description">
     <?php echo nl2br($m->getDescription()) ?>
    </p>

    <?php include_partial('mmobj/bodyMm', array('mm' => $m, 'roles' => $roles)) ?>

  </div>

 </div>
</div>


