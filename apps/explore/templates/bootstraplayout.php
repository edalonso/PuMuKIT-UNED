<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8" />
        <meta name="robots" content="index, follow, all" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <?php include_title() ?>
        <link href="/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
   </head>
  
  <body>
    <?php echo $sf_data->getRaw('sf_content') ?>
    <script src="/js/jquery-1.9.0.js"></script>
    <script src="/bootstrap/js/bootstrap.min.js"></script>
  </body>
</html>
