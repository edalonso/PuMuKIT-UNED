<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
   <head>
      <meta charset="UTF-8" />
      <?php include_metas() ?>
   
      <?php include_title() ?>
  
   </head>
   <body>
      <?php echo $sf_data->getRaw('sf_content') ?>
      <?php include_partial('global/googleanalytics')?>
   </body>
</html>