<div id="tv_admin_container" style="padding: 4px 20px 20px">

<div style="height:41px"></div>

<fieldset id="tv_fieldset_none" class="">
  <dl style="margin: 0px">
    <div class="form-row">
      <dt>Im&aacute;genes:</dt>
      <dd>  
        <div id="pic_mms">
            <?php include_component('serials', 'listPics', array('mm' => $mm->getId())) ?> 
        </div>
      </dd>
    </div>

    <div class="form-row">
      <dt>Archivos de V&iacute;deo:</dt>
      <dd>  
        <div id="files_mms">
            <?php include_component('serials', 'listFiles', array('mm' => $mm->getId())) ?>
        </div>
      </dd>
    </div>
   

    <div class="form-row">
      <dt>Materiales:</dt>
      <dd>  
        <div id="materials_mms">
            <?php include_component('serials', 'listMaterials', array('mm' => $mm->getId())) ?>              
        </div>
      </dd>
    </div>

    <div class="form-row">
      <dt>Links:</dt>
      <dd>  
        <div id="links_mms">
            <?php include_component('serials', 'listLinks', array('mm' => $mm->getId())) ?>              
        </div>
      </dd>
    </div>
  </dl>
</fieldset>


</div>