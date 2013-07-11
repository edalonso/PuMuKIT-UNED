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
 <docs><?php echo url_for('xml/novedades' , true) ?></docs>
 <generator>Pumukit V1</generator>
 <ttl>1440</ttl>
 <language>es</language>
 <image>
  <url><?php echo sfConfig::get('app_info_logo')?></url>
  <link><?php echo sfConfig::get('app_info_link')?></link>
  <title><?php echo sfConfig::get('app_info_copyright')?></title>
 </image>



<?php foreach($announces as $s): $x=get_class($s);?>
   <?php if ($x==Serial) :?>
     <?php $mms= PubChannelPeer::getMmsFromSerial(1, $s->getId())?>
      <item>
        <title><?php echo str_replace('&', '&amp;', $s->getTitle())?></title>
        <link><?php echo url_for('serial/index?id='.$s->getId() , true) ?></link>
        <description>

          <![CDATA[
        <p><b><u>Descripción de la serie: </u></b><br/> <?php $value=substr($s->getDescription(),0,500);?>
        <?php echo str_replace('&', '&amp;', ($value ? $value : $s->getDescription())) ?>
        <?php if (strlen($value)==500) echo " ... " ;?></p><br />
          ]]>
          <?php $i=0 ?>
           <?php foreach($mms as $r):?>
              <?php $files = $r->getFilesPublic();?>
                 <?php  foreach($files as $f): ?>
                    <?php $i++ ?>
                   <b><u><?php echo ('Video ' . $i . ' :') ?></u></b><?php echo ('   ' . str_replace('&', '&amp;', $r->getTitle()))?><br/><br/>
              <?php $escrito=0 ?>
              <?php foreach($roles as $rl):?>
              <?php $actor = $r->getPersons($rl->getId()); foreach($actor as $ac): ?>
               <?php if ($escrito==0) :?>
                <?php echo 'Actores : ' ?><br/>
                <?php $escrito=1 ?>
              <?php endif ?>

              <?php echo $ac->getHName()?><br/>
              <?php $ac->clearAllReferences(true); endforeach?>
               <?php endforeach?>
              <![CDATA[<br/>
              <a href="<?php echo url_for('mmobj/index?file_id=' . $f->getId(), true)?>">
                <img src="<?php echo(sfConfig::get('app_dls2serv_url').'/static/rss/pics/' .$s->getId() .'/'. $r->getId(). '.jpg')?>" style="top:100px;left:50px;width:200px;height:198px;"></a>

             <?php $value = $r->getDescription(); echo str_replace('&', '&amp;', ($value ? $value : $s->getDescription())) ?><br clear="left"><br/>]]>

             <?php $f->clearAllReferences(true); endforeach?>
          <?php $r->clearAllReferences(true);endforeach;?>
       </description>
<pubDate><?php echo $s->getPublicdate('r') ?></pubDate>
       <author><?php echo sfConfig::get('app_info_mail')?></author>
      </item>
  <?php endif ?>
  <?php if ($x!=Serial) :?>
   <?php $files = $s->getFilesPublic();?>
           <?php  foreach($files as $f): ?>
    <item>
      <title><?php echo str_replace('&', '&amp;', $s->getTitle())?></title>
      <link><?php echo url_for('mmobj/index?file_id='.$f->getId() , true) ?></link>
      <description><![CDATA[<a href="<?php echo url_for('mmobj/index?file_id=' . $f->getId(), true)?>">
                <img src="<?php echo(sfConfig::get('app_dls2serv_url').'/static/rss/pics/' .$s->getSerialId() .'/'. $s->getId(). '.jpg')?>" style="top:100px;left:50px;width:200px;height:198px;"></a>


             <?php $value = $s->getDescription(); echo str_replace('&', '&amp;', ($value ? $value : $s->getDescription())) ?><br clear="left"><br/>]]>

      </description>
      <pubDate><?php echo $s->getPublicdate('r') ?></pubDate>
      <author><?php echo sfConfig::get('app_info_mail')?></author>
      <?php foreach($roles as $role):?>
        <?php $actors = $s->getPersons($role->getId()); foreach($actors as $a): ?>
         <media:credit role="<?php echo $role->getXml() ?>"><?php echo $a->getHName()?></media:credit>
        <?php $a->clearAllReferences(true); endforeach?>
      <?php endforeach?>

      <?php $grounds = $s->getGroundsWithI18n(); foreach($grounds as $g): ?>
         <category domain="<?php echo $g->getGroundTypeWithI18n()->getName()?>"><?php echo $g->getName()?></category>
      <?php $g->clearAllReferences(true); endforeach?>
        <!-- Datos característicos de item multimedia -->

      <?php $files = $s->getFilesPublic(); $mats = $s->getMaterialsWithI18n(); ?>
      <?php if(count($files) + count($mats) > 1) echo '<media:group>' ?>
      <?php  foreach($files as $f): ?>
        <?php if($f->getAudio() ): ?>
          <media:content url="<?php echo url_for('mmobj/index?file_id=' . $f->getId(), true)?>"
            type="audio/wma"
            medium="audio" isDefault="true" expression="full"
            channels="1" duration="<?php echo $f->getDuration()?>"
            lang="<?php echo strtolower( $f->getLanguage()->getCod() ) ?>"  />
        <?php else: ?>
           <media:content url="<?php echo url_for('mmobj/index?file_id=' . $f->getId(), true)?>"
            type="video/wmv"
            medium="video" isDefault="true" expression="full" framerate="25"
            channels="1" duration="<?php echo $f->getDuration()?>"
            height="<?php echo $f->getPerfil()->getResolutionVer() ?>" width="<?php echo $f->getPerfil()->getResolutionHor() ?>"
            lang="<?php echo strtolower( $f->getLanguage()->getCod() ) ?>"  />
	     <?php endif ?>
      <?php $f->clearAllReferences(true); endforeach?>
      <?php foreach($mats as $m): if(!$m->getDisplay()) continue ?>
        <media:content
          url="<?php echo $m->getUrl(true)?>"
          type="application/<?php echo $m->getMatType()->getType()?>"
          medium="document"
          expression="full"
          lang="es" />
      <?php $m->clearAllReferences(true); endforeach?>
      <?php if(count($files) + count($mats) > 1) echo '</media:group>' ?>

    </item>
<?php $f->clearAllReferences(true); endforeach?>
  <?php endif;?>
<?php $s->clearAllReferences(true); endforeach;?>

</channel>
</rss>
