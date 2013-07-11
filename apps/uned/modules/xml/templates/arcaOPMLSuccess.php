<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<opml version="2.0">
  <head>
    <title><?php echo sfConfig::get('app_info_title')?></title>
    <ownerName><?php echo sfConfig::get('app_info_title')?></ownerName>
    <ownerEmail><?php echo sfConfig::get('app_info_mail')?></ownerEmail>
  </head>
  <body>
<?php foreach($years as $y):?>
    <outline type ="rss"
                text="<?php echo sfConfig::get('app_info_title')?>"
                xmlUrl="<?php echo url_for("@arca_by_year?year=" . $y, true)?>" />
<?php endforeach ?>
  </body>
</opml>
