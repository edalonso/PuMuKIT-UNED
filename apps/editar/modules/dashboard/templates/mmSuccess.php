<data wiki-url="<?php echo sfConfig::get('app_info_link') ?>" wiki-section="<?php echo sfConfig::get('app_info_copyright') ?>">
  <?php foreach($mms as $mm):?>
    <event
        start="<?php echo $mm->getRecorddate('M j Y 00:00:00 \G\M\T')?>"
        title="<?php echo str_replace(array('"', "&"), array("'", "&amp;"), $mm->getTitle()) ?>"
        link="/editar.php/mms/index/serial/<?php echo $mm->getSerialId() ?>"
        >
        <?php echo str_replace('&', '&amp;', $mm->getTitle()) ?>
    </event>
  <?php endforeach; ?>
</data>
