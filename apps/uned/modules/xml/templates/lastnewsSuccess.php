<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:fn="http://www.w3.org/2005/xpath-functions" xmlns:timeUtils="com.ctv.publisher.uned.utils.TimeUtils">
<channel>
<title>Menú principal</title>
<link><?php echo url_for($link, true) ?></link>
<description></description>


<?php foreach($mms as $mm):?>
<item>
<title><?php echo htmlspecialchars($mm->getTitle()) ?></title>
<description><?php if(strlen($mm->getDescription()) != 0):?><![CDATA[<?php echo $mm->getDescription()?>]]><?php endif?></description>
<pubDate><?php echo $mm->getRecorddate('D, d M Y H:i:s O')?></pubDate>
<?php //TODOUNED test MM getUrl?>
<link><?php echo $mm->getUrl(true)?></link>
</item>
<?php endforeach?>

</channel>
</rss>
