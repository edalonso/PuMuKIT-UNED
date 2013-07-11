<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
  <rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:itunesu="http://www.itunesu.com/feed" xml:lang="en" version="2.0">
    <channel>
      <title><![CDATA[UVIGOTV: <?php echo $serial->getTitle() ?>]]></title>
      <link><?php echo url_for($serial->getUrl(), true)?></link>
      <description><![CDATA[<?php echo $serial->getDescription() ?>]]></description>
      <generator>PuMuKiT</generator>
      <lastBuildDate><?php echo $serial->getPublicdate('r')?></lastBuildDate>
      <language>es</language>
      <copyright><?php echo $serial->getCopyright()?></copyright>
      <itunes:image href="<?php echo(sfConfig::get('app_dls2serv_url'))?>/static/itunes/pics/<?php echo $serial->getId()?>.jpg" />
      <image>
        <url><?php echo(sfConfig::get('app_dls2serv_url'))?>/static/itunes/pics/<?php echo $serial->getId()?>.jpg</url>
        <title>Universidade de Vigo Podcast</title>
        <link>http://tv.uvigo.es/podcast/<?php echo $serial->getId() ?>.xml</link>
      </image>
      <itunes:summary><![CDATA[<?php echo $serial->getDescription()?>]]></itunes:summary>
      <itunes:subtitle><![CDATA[<?php echo $serial->getSubtitle() ?>]]></itunes:subtitle>
      <itunes:author>Uvigo-TV</itunes:author>
      <itunes:owner>
        <itunes:name>Uvigo-TV</itunes:name>
        <itunes:email>tv@uvigo.es</itunes:email>
      </itunes:owner>
      <itunes:explicit>no</itunes:explicit>

<?php foreach($serial->getMmsPublicAutonPub() as $v): $f= $v->getFileByPerfil(array(20, 21, 32, 33, 48)); if(is_null($f)) continue; ?>
  <item>
    <title><![CDATA[UVIGOTV: <?php echo (strlen($v->getTitle()) == 0?$serial->getTitle():$v->getTitle())?>]]></title>
    <itunes:subtitle><![CDATA[<?php echo $v->getSubtitle()?>]]></itunes:subtitle>
    <itunes:summary><![CDATA[ <?php echo $v->getDescription() ?> ]]></itunes:summary>
    <description><![CDATA[ <?php echo str_replace('&', '&amp;', $v->getDescription()) ?> ]]></description>
    <itunesu:category itunesu:code="<?php echo $v->getCategoryItunes() ?>" />
    <enclosure url="<?php echo $f->getUrl()?>" length="<?php echo $f->getSize()?>" type="<?php echo $f->getPerfil()->getMimeType()?>"/>
    <guid><?php echo $f->getUrl()?></guid>
    <itunes:duration><?php echo $f->getDurationMin(), ':', $f->getDurationSeg()?></itunes:duration>
    <itunes:author>UVigo.TV</itunes:author>
    <itunes:keywords><![CDATA[<?php echo $v->getKeyword() ?>]]></itunes:keywords>
    <itunes:explicit>no</itunes:explicit>
    <pubDate><?php echo $v->getRecorddate('r') ?></pubDate>
  </item>
  <?php $v->clearAllReferences(true);endforeach;?>
</channel>
</rss>
