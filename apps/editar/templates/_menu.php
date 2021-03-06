<!-- cabezera -->
<div id="editar_cab">  
  <div id="ah_img">
    <img style="width:280px; padding-left:5%" src="/images/admin/cab/pumukitDer.png" />
  </div>
  <div id="ah_status" style="">
    <b><?php echo $sf_user->getAttribute('login')?> </b>
    (<?php $aux = array(0 => "Administrador",1 => "Publicador",2 => "FTP"); echo $aux[$sf_user->getAttribute('user_type_id')] ?>) | 
    <?php echo date('d-m-y')?> | 
    <?php echo link_to('logout', 'index/logout') ?>
  </div>  
  <div style="position:absolute; right: 10px; top : 25px; color: #fff; font-weight: bold">
    <?php echo sfConfig::get('app_metas_title')?>
  </div>
</div>



<!-- menu -->
<div id="editar_menu">

  <div class="cab_up_down" style="float:right; font-weight:bolder; font-size: 22px">
    <div style="" 
       href="#" onclick="this.toggleClassName('inv'); Effect.toggle('editar_cab', 'blind'); return false">&nbsp;&nbsp;</div>
  </div>

  <ul id="nav">
    <li class="level0 <?php echo sfConfig::get('dashboard_menu') ?>">
      <a href="<?php echo url_for('dashboard/index')?>" class="<?php echo sfConfig::get('dashboard_menu') ?>">
        <span>Dashboard</span>
      </a>
    </li>

    <li class="level0 <?php echo sfConfig::get('serial_menu') ?>">
      <a href="<?php echo url_for('serials/index?page=' . $sf_user->getAttribute('page', 1, 'tv_admin/serial'))?>" class="<?php echo sfConfig::get('serial_menu') ?>">
        <span>Series Multimedia</span>
      </a>
    </li>

    <li class="level0 <?php echo sfConfig::get('virtual_serial_menu') ?>">
      <a href="<?php echo url_for('virtualserial/index?page=' . $sf_user->getAttribute('page', 1, 'tv_admin/virtualserial'))?>" class="<?php echo sfConfig::get('virtual_serial_menu') ?>">
        <span>Catalogador Unesco</span>
      </a>
    </li>
    
  
    <!--<li onmouseover="Element.addClassName(this,'over')" onmouseout="Element.removeClassName(this,'over')" class="parent level0 <?php echo sfConfig::get('template_menu') ?>">
      <a href="#" onclick="return false" class="<?php echo sfConfig::get('template_menu') ?>">
        <span>Dise&ntilde;o Portal WebTV</span>
      </a>
      <ul>
        <li class="level1">
          <a href="<?php echo url_for('widgets/index')?>" class="">
            <span>Diseño</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('templates/index')?>" class="">
            <span>Plantillas</span>
          </a>
        </li>
        <li class="last level1">
          <a href="<?php echo url_for('navigator/index')?>" class="">
            <span>FileManager</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('virtualgrounds/index')?>" class="">
            <span>Categorias</span>
          </a>
        </li>
        <li class="last level1">
          <a href="<?php echo url_for('notices/index')?>" class="">
            <span>Noticias</span>
          </a>
        </li>
      </ul>
    </li>
    -->
  
    <li onmouseover="Element.addClassName(this,'over')" onmouseout="Element.removeClassName(this,'over')" class="parent level0 <?php echo sfConfig::get('tv_menu') ?>">
      <a href="#" onclick="return false" class="<?php echo sfConfig::get('tv_menu') ?>">
        <span>Teleactos</span>
      </a>
      <ul>
        <li class="level1">
          <a href="<?php echo url_for('events/index')?>" class="">
	  <span>Gesti&oacute;n de teleactos</span>
          </a>
        </li>
        <li class="last level1">
          <a href="<?php echo url_for('directs/index')?>" class="">
            <span>Canales de streaming</span>
          </a>
        </li>
      </ul>
    </li>
  

    <?php if(sfConfig::get('app_transcoder_use')):?>
    <li onmouseover="Element.addClassName(this,'over')" onmouseout="Element.removeClassName(this,'over')" class="parent level0 <?php echo sfConfig::get('transcoder_menu') ?>">
      <a href="#" onclick="return false" class="<?php echo sfConfig::get('transcoder_menu') ?>">
        <span>Transcodificacion</span>
      </a>
      <ul>
        <?php if($sf_user->getAttribute('user_type_id', 1) == 0):?>
        <li class="level1">
          <a href="<?php echo url_for('profiles/index')?>" class="">
            <span>Perfiles de Transcodificación</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('cpus/index')?>" class="">
            <span>Trascodificadores</span>
          </a>
        </li> 
        <?php endif?>
        <li class="level1 last">
          <a href="<?php echo url_for('transcoders/index')?>" class="">
            <span>Lista de tareas</span>
          </a>
        </li> 
      </ul>
    </li>
   <?php endif ?>


    <li class="level0 <?php echo sfConfig::get('timeframes_menu') ?>">
      <a href="<?php echo url_for('timeframes/indexDash')?>" 
         class="<?php echo sfConfig::get('timeframes_menu') ?>">
        <span>Destacados Radio / TV</span>
      </a>
    </li>


    <li onmouseover="Element.addClassName(this,'over')" onmouseout="Element.removeClassName(this,'over')" class="parent level0 <?php echo sfConfig::get('library_menu') ?>">
      <a href="#" onclick="return false" class="<?php echo sfConfig::get('library_menu') ?>">
        <span>Tablas</span>
      </a>
                                       
      <ul>
        <li class="level1 last">
          <a href="<?php echo url_for('persons/index')?>" class="">
            <span>Personas</span>
          </a>
        </li>
        <?php /*
        <li class="level1 last">
          <a href="<?php echo url_for('places/index')?>" class="">
            <span>Lugares y recintos</span>
          </a>
        </li>
        */?>
      </ul>    
    </li>

<?php if($sf_user->getAttribute('user_type_id', 1) == 0) /* A los publicadores no les aparece el menú de administración de usuarios.*/:?>
    <li onmouseover="Element.addClassName(this,'over')" onmouseout="Element.removeClassName(this,'over')" class="parent level0 <?php echo sfConfig::get('config_menu') ?>">
      <a href="#" onclick="return false" class="<?php echo sfConfig::get('config_menu') ?>">
        <span>Administraci&oacute;n</span>
      </a>
      <ul>
        <li class="level1">
          <a href="<?php echo url_for('users/index')?>" class="">
            <span>Usuarios</span>
          </a>
        </li>
        <!-- <li class="level1">
          <a href="#" class="">
            <span>Export</span>
          </a>
        </li> 
        <li class="level1">
          <a href="#" class="">
            <span>Backup</span>
          </a>
        </li> -->
        <li class="level1">
          <a href="<?php echo url_for('categories/index')?>"  class="">
            <span>Categorias</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('genres/index')?>" class="">
            <span>G&eacute;neros</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('mattypes/index')?>" class="">
            <span>Tipos de materiales</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('languages/index')?>"  class="">
            <span>Idiomas</span>
          </a>
        </li>
        <!-- depricated
        <li class="level1">
          <a href="<?php echo url_for('resolutions/index')?>" class="">
            <span>Resolution</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('codecs/index')?>" class="">
            <span>Codec</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('formats/index')?>" class="">
            <span>Format</span>
          </a>
        </li>
       
        <li class="level1">
          <a href="<?php echo url_for('profiles/index')?>" class="">
            <span>Perfiles</span>
          </a>
        </li>
        -->
        <li class="level1">
          <a href="<?php echo url_for('roles/index')?>"  class="">
            <span>Roles</span>
          </a>
        </li>
       <li class="level1">
         <a href="<?php echo url_for('broadcasts/index')?>"  class="">
           <span>Perfiles Acceso</span>
          </a>
        </li>

        <li class="level1">
          <a href="<?php echo url_for('streamservs/index')?>"  class="">
            <span>Servidores de Distribución</span>
          </a>
        </li>  
        <li class="level1">
          <a href="<?php echo url_for('templates/index')?>" class="">
            <span>Plantillas</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('navigator/index')?>" class="">
            <span>Explorador</span>
          </a>
        </li>
        <li class="last level1">
          <a href="<?php echo url_for('featured/index')?>"  class="">
            <span>Zona destacados del menú laterial</span>
          </a>
        </li>  
      </ul>
    </li>  
<?php endif ?>
    
  <?php if (sfConfig::get('app_matterhorn_use')):?>
    <li onmouseover="Element.addClassName(this,'over')" onmouseout="Element.removeClassName(this,'over')" class="parent level0 <?php echo sfConfig::get('ingest_menu') ?>">
      <a href="#"  onclick="return false" class="<?php echo sfConfig::get('ingest_menu') ?>">
        <span>Ingestador</span>
      </a>
      <ul>
        <li class="level1" last>
          <a href="<?php echo url_for('matterhorn/index')?>" class="">
            <span>Ingestador Matterhorn</span>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>
  </ul>
</div>


