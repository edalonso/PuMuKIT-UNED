<script type="text/javascript">
<?php 
if ($sf_request->isXmlHttpRequest()){
  echo "document.location.href='" . url_for('index/index?error=2') . "';";
}else{
  echo "document.location.href='" . url_for('index/index?error=2&url=' . strtr($sf_request->getPathInfo(),'/','+')) . "';";
}
 ?>
</script>


