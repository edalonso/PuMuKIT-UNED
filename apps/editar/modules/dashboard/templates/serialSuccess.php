<data wiki-url="<?php echo sfConfig::get('app_info_link') ?>" wiki-section="<?php echo sfConfig::get('app_info_copyright') ?>">
  <?php foreach($serials as $serial):?>
    <event
        start="<?php echo $serial->getPublicdate('M j Y 00:00:00 \G\M\T')?>"
        title="<?php echo str_replace(array('"', "&"), array("'", "&amp;"), $serial->getTitle()) ?>"
        link="/editar.php/mms/index/serial/<?php echo $serial->getId() ?>"
        >
        <?php echo str_replace('&', '&amp;', $serial->getTitle()) ?>
    </event>
  <?php endforeach; ?>
</data>
