<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom">
<?php header ('Content-type: text/html; charset=utf-8');?>
<channel>
 <title><?php echo sfConfig::get('app_info_title')?></title>
 <description><?php echo sfConfig::get('app_info_description')?></description>
 <link><?php echo sfConfig::get('app_info_link')?></link>
 <copyright><?php echo sfConfig::get('app_info_copyright')?></copyright>
 <managingEditor><?php echo sfConfig::get('app_info_mail')?></managingEditor>
 <webMaster><?php echo sfConfig::get('app_info_mail')?></webMaster>
 <category domain="categoria_canal">universidad</category>
 <docs><?php echo url_for('xml/rssjoomla' , true) ?></docs>
 <generator>Pumukit V1</generator>
 <ttl>1440</ttl>
 <language>es</language>
 <image>
  <url><?php echo sfConfig::get('app_info_logo')?></url>
  <link><?php echo sfConfig::get('app_info_link')?></link>
  <title><?php echo sfConfig::get('app_info_copyright')?></title>
 </image>


<?php $order = 0; foreach($mms as $v): $order++?>
  <?php $files = $v->getFilesPublic();?>
           <?php  foreach($files as $f): ?>
    <item>
      <title><?php echo str_replace('&', '&amp;', $v->getTitle())?></title>
      <link><?php echo url_for('mmobj/index?file_id='.$f->getId() , true) ?></link>
      <description><![CDATA[
         <a href="<?php echo url_for('mmobj/index?file_id=' . $f->getId(), true)?>"><img class="foto" src="<?php echo $v->getFirstUrlPic(true)?>" width="100" height="82" /></a>
 <?php $value = $v->getDescription(); echo str_replace('&', '&amp;', ($value ? $value : $v->getDescription())) ?>]]>
      </description>
      
    </item>
<?php $f->clearAllReferences(true); endforeach?>
 <?php $v->clearAllReferences(true);endforeach;?>

</channel>
</rss>
