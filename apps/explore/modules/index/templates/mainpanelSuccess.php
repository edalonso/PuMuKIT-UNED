<div style="padding: 40px">

<h1 style="padding: 0px 0px 10px">
<?php echo $cat->getName() ?>
  <form style="float: right">
    <select onchange="videoaudiochange(this); return false;">
      <option value="all" <?php echo (($sf_user->getAttribute('only', null, 'explore') == 'all')?"selected":"#")?>>Vídeo y Audio</option>
      <option value="video" <?php echo (($sf_user->getAttribute('only', null, 'explore') == 'video')?"selected":"#")?>>Solo Vídeo</option>
      <option value="audio" <?php echo (($sf_user->getAttribute('only', null, 'explore') == 'audio')?"selected":"#")?>>Solo Audio</option>
    </select>
  </form>
</h1>

<script>
function videoaudiochange(s) {
  
  if (s.value == "all") {
    window.location.href = (window.location.protocol + "//" + window.location.hostname + window.location.pathname + "?only=all")
  }
  if (s.value == "video") {
    window.location.href = (window.location.protocol + "//" + window.location.hostname + window.location.pathname + "?only=video")
  }
  if (s.value == "audio") {
    window.location.href = (window.location.protocol + "//" + window.location.hostname + window.location.pathname + "?only=audio")
  }
}
</script>

<ul class="breadcrumb">
  <?php foreach($cat->getPath() as $sc): ?>
    <li><?php echo $sc->getName()?><span class="divider">/</span></li>
  <?php endforeach ?>
  <li class="active"><?php $cat->getName()?></li>
</ul>


<table class="table table-striped table-bordered table-hover table-condensed">
  <thead>
    <tr>
      <th width="75">Img</th>
      <th>Id</th>
      <th>T&iacute;tulo</th>
      <th>FechaRec</th>
      <th><span class="label label-success">Unesco</span>-<span class="label label-info">Tematicas</span></th>
    </tr>
  </thead>

  <tbody>
  <?php if (count($mms) == 0):?>
    <tr>
      <td colspan="14">
       No existen objetos multimedia con esos valores.
      </td>
    </tr>
  <?php else: ?>
  <?php $i = 0; foreach($mms as $mm): $odd = fmod($i++, 2)?>
      <tr>
      <td>
        <img src="<?php echo $mm->getFirstUrlPic()?>" class="img-polaroid" style="height:46px; width:60px;" />
      </td>
      <td>
        <a style="text-decoration: underline" href="#myModal<?php echo $mm->getId() ?>" data-toggle="modal"><?php echo $mm->getId()?>  </a>
        <?php include_partial('modal', array('mm' => $mm))?>
      </td>
      <td>
        <div style="font-weight: bolder">
          <?php echo $mm->getTitle()?>  
        </div>
        <div>
          <?php echo substr($mm->getDescription(),0,200)?> ...
        </div>
      </td>
      <td>
        <?php echo $mm->getRecorddate("%d/%m/%Y")?>  
      </td>
      <td >
        <?php foreach($mm->getCategorys($cat_raiz_unesco) as $unesco): ?>
          <span style="font-size: 8px; padding: 1px 4px;" class="label label-success"><?php echo $unesco->getName() ?></span> <br />
        <?php endforeach ?>
        <?php foreach($mm->getCategorys($cat_raiz_uned) as $uned): ?>
          <span style="font-size: 8px; padding: 1px 4px;" class="label label-info"><?php echo $uned->getName() ?></span> <br />
        <?php endforeach ?>
      </td>
      </tr>
  <?php endforeach?>
  <?php endif; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="14">
        <?php include_partial('pager', array('id' => $cat->getId(), 'page' => $page, 'total' => $pages)) ?>       </th>
      </th>
    </tr>
  </tfoot>
</table>










</div>