<?php echo '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0" 
     xmlns:media="http://search.yahoo.com/mrss/" 
     xmlns:g="http://base.google.com/ns/1.0" 
     xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd"
     xmlns:arca="http://arca.uc3m.es/">
<channel>
 <title><?php echo sfConfig::get('app_info_title')?></title> 
 <description><?php echo sfConfig::get('app_info_description')?></description>
 <link><?php echo sfConfig::get('app_info_link')?></link>
 <copyright><?php echo sfConfig::get('app_info_copyright')?></copyright>
 <managingEditor><?php echo sfConfig::get('app_info_mail')?></managingEditor>
 <webMaster><?php echo sfConfig::get('app_info_mail')?></webMaster>
 <category domain="categoria_canal">universidad</category>
 <docs><?php echo url_for('xml/arca' , true) ?></docs>
 <generator>Pumukit V1</generator>
 <ttl>1440</ttl>
 <language>es</language>
 <image>
  <url><?php echo sfConfig::get('app_info_logo')?></url>
  <link><?php echo sfConfig::get('app_info_link')?></link>
  <title><?php echo sfConfig::get('app_info_copyright')?></title>
 </image>
	
 <?php foreach($mms as $v): $s = $v->getSerial() ?>

    <item>

      <?php if($s->getDisplay()):?>
        <arca:course>
        <arca:title><?php echo htmlspecialchars($s->getTitle())?></arca:title>
	   <arca:description><?php echo htmlspecialchars($s->getDescription()) ?></arca:description>
        <arca:image><?php echo $s->getFirstUrlPic(true) ?></arca:image>
	   <arca:order><?php echo $v->getRank() ?></arca:order>
        </arca:course>
      <?php endif?>

      <title><?php echo htmlspecialchars($v->getTitle())?></title>

      <link><?php echo $v->getUrl(true) ?></link>
      <description>
        <?php echo htmlspecialchars($v->getDescription()) ?>
      </description>
      <pubDate><?php echo $v->getPublicdate('r') ?></pubDate>
      <g:publish_date><?php echo $v->getPublicdate('Y-m-d') ?></g:publish_date>
      <author><?php echo sfConfig::get('app_info_mail')?></author>

      <source url="<?php echo url_for('xml/arca', true) ?>"><?php echo sfConfig::get('app_info_title')?></source> 
      <media:thumbnail url="<?php echo $v->getFirstUrlPic(true) ?>" height="82" width="100" />
      <media:copyright><?php echo $v->getCopyright() ?></media:copyright>
      <guid isPermaLink="true"><?php echo $v->getUrl(true) ?></guid>

      <?php foreach($roles as $role):?>
        <?php $actors = $v->getPersons($role->getId()); foreach($actors as $a): ?>
          <media:credit role="<?php echo $role->getXml() ?>"><?php echo $a->getHName()?></media:credit>
        <?php $a->clearAllReferences(true); endforeach?>
      <?php endforeach?>

      <?php foreach($v->getCategorys($cat_raiz_unesco) as $unesco): ?>
        <category domain="campo unesco"><?php echo $unesco->getName()?></category>
      <?php $unesco->clearAllReferences(true); endforeach?>

      <?php $files = $v->getFilesPublic(); $mats = $v->getMaterialsPublic(); ?>
      <?php if((count($files) + count($mats)) > 1) echo '<media:group>' ?>
      <?php  foreach($files as $f): ?>
        <?php if($f->getAudio() ): ?>
          <media:content url="<?php echo $v->getUrl(true)?>"
            type="<?php echo $f->getPerfil()->getMimeType() ?>" 
            medium="audio" isDefault="true" expression="full" 
            channels="1" duration="<?php echo $f->getDuration()?>" 
	    <?php if(intval(substr($f->getLanguage()->getCod(), -1)) == 0):?>
            lang="<?php echo strtolower( $f->getLanguage()->getCod() ) ?>" 
            <?php endif?>
            />
        <?php else: ?>
          <media:content url="<?php echo $v->getUrl(true)?>"
	    <?php if($f->getPerfil()->getMimeType() != ""):?>
            type="<?php echo $f->getPerfil()->getMimeType() ?>" 
            <?php endif?>
            medium="video" isDefault="true" expression="full" framerate="25" 
            channels="1" duration="<?php echo $f->getDuration()?>" 
	    <?php if(intval($f->getResolutionVer()) > 0):?>
            height="<?php echo $f->getResolutionVer() ?>" 
            <?php endif?>
	    <?php if(intval($f->getResolutionHor()) > 0):?>
            width="<?php echo $f->getResolutionHor() ?>"
            <?php endif?>
            <?php if(intval(substr($f->getLanguage()->getCod(), -1)) == 0):?>
            lang="<?php echo strtolower( $f->getLanguage()->getCod() ) ?>"  
            <?php endif?>
            />
        <?php endif ?>
      <?php $f->clearAllReferences(true); endforeach?>
      <?php foreach($mats as $m): ?>
        <media:content
          url="<?php echo $m->getUrl(true)?>"
          type="<?php echo $m->getMatType()->getType()?>"
          medium="document"
          expression="full"
          lang="es" />
      <?php $m->clearAllReferences(true); endforeach?>
      <?php if((count($files) + count($mats)) > 1) echo '</media:group>' ?>

    </item>
<?php $v->clearAllReferences(true);endforeach;?>


</channel>
</rss>
