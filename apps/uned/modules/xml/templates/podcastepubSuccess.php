<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
  <rss xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:itunesu="http://www.itunesu.com/feed" xml:lang="en" version="2.0">
    <channel>
      <title><![CDATA[<?php echo $serial->getTitle() ?>]]></title>
      <link><?php echo url_for($serial->getUrl(), true)?></link>
      <description><![CDATA[<?php echo $serial->getDescription() ?>]]></description>
      <generator>PuMuKiT</generator>
      <lastBuildDate><?php echo $serial->getPublicdate('r')?></lastBuildDate>
      <language>es</language>
      <copyright><?php echo $serial->getCopyright()?></copyright>
      <itunes:image href="<?php echo(sfConfig::get('app_dls2serv_url'))?>/static/itunes/pics/<?php echo $serial->getId()?>.jpg" />
      <image>
        <url><?php echo(sfConfig::get('app_dls2serv_url'))?>/static/itunes/pics/<?php echo $serial->getId()?>.jpg</url>
        <title>Universidade de Vigo Epub</title>
        <link><?php echo('http://tv.uvigo.es/podcastepub/' . $serial->getId() . '.xml')?></link>
      </image>
      <itunes:summary><![CDATA[<?php echo $serial->getDescription()?>]]></itunes:summary>
      <itunes:subtitle><![CDATA[<?php echo $serial->getSubtitle() ?>]]></itunes:subtitle>
      <itunes:author>Uvigo-TV</itunes:author>
      <itunes:owner>
        <itunes:name>Uvigo-TV</itunes:name>
        <itunes:email>tv@uvigo.es</itunes:email>
      </itunes:owner>
      <itunes:explicit>no</itunes:explicit>

  <item>
    <title><![CDATA[<?php echo $serial->getTitle() ?>]]></title>
    <itunes:subtitle><![CDATA[<?php echo $serial->getSubtitle() ?>]]></itunes:subtitle>
    <itunes:summary><![CDATA[ <?php echo $serial->getDescription() ?> ]]></itunes:summary>
    <description><![CDATA[ <?php echo $serial->getDescription() ?> ]]></description>
    <itunesu:category itunesu:code="<?php echo $mm->getCategoryItunes() ?>" />
    <link><?php echo url_for($serial->getUrl(), true)?></link>
    <enclosure url="<?php echo(sfConfig::get('app_dls2serv_url').'/static/itunes/epub/' . $serial->getId() . '.epub')?>" type="application/epub+zip" length="<?php echo (filesize('/mnt/dls2/static/itunes/epub/'. $serial->getId() . '.epub'))?>"/>
    <guid><?php echo(sfConfig::get('app_dls2serv_url').'/static/itunes/epub/'. $serial->getId() . '.epub')?></guid>
    <itunes:author>UVigo.TV</itunes:author>
    <itunes:keywords></itunes:keywords>
    <itunes:explicit>no</itunes:explicit>
    <pubDate><?php echo $serial->getPublicdate('r')?></pubDate>
  </item>

</channel>
</rss>
