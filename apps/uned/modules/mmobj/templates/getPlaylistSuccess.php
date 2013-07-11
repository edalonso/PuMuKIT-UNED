<rss version="2.0" xmlns:jwplayer="http://developer.longtailvideo.com/">
  <channel>
    <item>
      <title>Intro</title>
      <enclosure url="http://dls2.uvigo.es/vod/cmar/36/1056.mp4" type="video/mp4" length="756179" />
      <jwplayer:provider>http</jwplayer:provider>
      <jwplayer:http.startparam>start</jwplayer:http.startparam>
    </item>
    <item>
      <title>Video</title>
      <enclosure url="<?php echo $file->getUrl() ?>" type="video/x-flv" length="<?php echo $file->getSize() ?>" />
      <jwplayer:provider>http</jwplayer:provider>
      <jwplayer:http.startparam>start</jwplayer:http.startparam>
    </item>
  </channel>
</rss>