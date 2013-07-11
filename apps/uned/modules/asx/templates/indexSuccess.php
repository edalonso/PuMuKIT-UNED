<asx version = "3.0">
<?php if(!$sf_request->hasParameter('nointro')): ?>
  <entry>
    <title><?php echo sfConfig::get('app_info_copyright')?></title>
    <ref href = "http://videodownload.uvigo.es/new/VoDiPod/453/11635.flv" />
  </entry>
<?php endif ?> 
  <entry>
    <title>"<?php echo utf8_decode($sf_data->getRaw('file')->getTitleASX() )?>"</title>
    <ref href = "<?php echo $sf_data->getRaw('file')->getUrl()?>" />
  </entry>
</asx>
