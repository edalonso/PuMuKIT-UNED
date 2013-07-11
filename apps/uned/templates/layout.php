<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>

  <?php include_http_metas() ?>
  <?php include_metas() ?>
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  
  <?php include_title() ?>
  
  <link rel="shortcut icon" href="/images/uned/iconos/favicon.png" />
  <link rel="stylesheet" type="text/css" media="screen" href="/css/uned/serial.css" />
  
<script type="text/javascript">jwplayer.key="ZyC5srA0OTduAy6BNMuMuImOzm/IoI2eUzJiAYRqEcc=";</script>

</head>
<body>
   <div id="tvuned" class="container_15">
     <?php include_partial('global/cab')?>
     <div class="unedtv_cab">
        <?php include_partial('global/cab_pan')?>
        <?php include_partial('global/cab_menu')?>
     </div>
     <div class="">
        <div class="grid_3" style="margin-right: 0px;">
          <?php include_partial('global/bar')?>
        </div>
        <div class="grid_12" style="margin-right: 0px; width: 780px;">
          <?php echo $sf_data->getRaw('sf_content') ?>
        </div>
        <?php include_partial('global/pie')?>
     </div>
     <?php include_partial('global/googleanalytics')?>
   </div>
</body>
</html>
