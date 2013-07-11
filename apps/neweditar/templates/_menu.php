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
</div>



<!-- menu -->
<div id="editar_menu">
  <ul id="nav">
    <li class="level0 <?php echo sfConfig::get('serial_menu') ?>">
      <a href="<?php echo url_for('serials/index?page=' . $sf_user->getAttribute('page', 1, 'tv_admin/serial'))?>" title="Series Virtuales" class="<?php echo sfConfig::get('serial_menu') ?>">
        <span>Series Virtuales</span>
      </a>
    </li>
    
     <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent level0 <?php echo sfConfig::get('library_menu') ?>">
      <a href="#" title="Librer&iacute;a" onclick="return false" class="<?php echo sfConfig::get('library_menu') ?>">
        <span>Librer&iacute;a</span>
      </a>
                                       
      <ul>
        <li class="level1">
          <a href="/editar.php/persons/index" title="Persons" class="">
            <span>Personas</span>
          </a>
        </li>
        <li class="level1">
          <a href="/editar.php/places/index" title="Places" class="">
            <span>Lugares y recintos</span>
          </a>
        </li>
        <!-- <li class="level1">
          <a href="#" title="Channels" class="">
            <span>Channels</span>
          </a>
        </li> -->
        <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent last level1">
          <a href="#" title="Others" onclick="return false" class="">
            <span>Otros</span>
          </a>
  
          <ul>
            <li class="level2">
              <a href="/editar.php/broadcasts/index" title="Broadcast" class="">
                <span>Difusiones</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/grounds/index" title="Ground" class="">
                <span>&Aacute;reas de conocimiento</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/categories/index" title="Ground" class="">
                <span>Categor&iacute;as</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/genres/index" title="Genre" class="">
                <span>G&eacute;neros</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/mattypes/index" title="matType" class="">
                <span>Tipos de materiales</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/serialtypes/index" title="Serial Type" class="">
                <span>Tipos de series</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/languages/index" title="Language" class="">
                <span>Idiomas</span>
              </a>
            </li>
            <!-- depricated
            <li class="level2">
              <a href="/editar.php/resolutions/index" title="Resolution" class="">
                <span>Resolution</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/codecs/index" title="Codec" class="">
                <span>Codec</span>
              </a>
            </li>
            <li class="level2">
              <a href="/editar.php/formats/index" title="Format" class="">
                <span>Format</span>
              </a>
            </li>
            -->
            <li class="level2">
              <a href="/editar.php/profiles/index" title="Profile" class="">
                <span>Perfiles</span>
              </a>
            </li>
            <li class="last level2">
              <a href="/editar.php/roles/index" title="Rol" class="">
                <span>Roles</span>
              </a>
            </li>
          </ul>
        </li>
      </ul>    
    </li>
  
    <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent level0 <?php echo sfConfig::get('template_menu') ?>">
      <a href="#" title="Dise&nteilde;o" onclick="return false" class="<?php echo sfConfig::get('template_menu') ?>">
        <span>Dise&ntilde;o</span>
      </a>
      <ul>
        <li class="level1">
          <a href="/editar.php/templates/index" title="Templates" class="">
            <span>Estructuras</span>
          </a>
        </li>
        <li class="level1">
          <a href="/editar.php/navigator/index" title="Navegator" class="">
            <span>Navegador</span>
          </a>
        </li>
        <li class="last level1">
          <a href="/editar.php/widgets/index" title="Widgets" class="">
            <span>Widgets</span>
          </a>
        </li>
      </ul>
    </li>
  
    <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent level0 <?php echo sfConfig::get('tv_menu') ?>">
      <a href="#" title="TV" onclick="return false" class="<?php echo sfConfig::get('tv_menu') ?>">
        <span>Tv</span>
      </a>
      <ul>
        <li class="level1">
          <a href="/editar.php/directs/index" title="Direct" class="">
            <span>Directos</span>
          </a>
        </li>
        <li class="last level1">
          <a href="/editar.php/notices/index" title="Notices" class="">
            <span>Noticias</span>
          </a>
        </li>
      </ul>
    </li>
  
    <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent level0 <?php echo sfConfig::get('config_menu') ?>">
      <a href="#" title="Configuraci&oacute;n" onclick="return false" class="<?php echo sfConfig::get('config_menu') ?>">
        <span>Configuraci&oacute;n</span>
      </a>
      <ul>
        <li class="level1">
          <a href="/editar.php/users/index" title="Users" class="">
            <span>Usuarios</span>
          </a>
        </li>
        <li class="level0 last">
          <a href="/editar.php/dashboard/index" title="Dashboard" class="<?php echo sfConfig::get('dashboard_menu') ?>">
            <span>Dashboard</span>
          </a>
        </li>
        <!-- <li class="level1">
          <a href="#" title="Export" class="">
            <span>Export</span>
          </a>
        </li> 
        <li class="last level1">
          <a href="#" title="Backup" class="">
            <span>Backup</span>
          </a>
        </li> -->
  
      </ul>
    </li>




    <?php if(sfConfig::get('app_transcoder_use')):?>
    <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent level0 <?php echo sfConfig::get('transcoder_menu') ?>">
      <a href="#" title="Transcodificador" onclick="return false" class="<?php echo sfConfig::get('transcoder_menu') ?>">
        <span>Transcodificador</span>
      </a>
      <ul>
        <li class="level1">
          <a href="<?php echo url_for('transcoders/index')?>" title="List" class="">
            <span>List</span>
          </a>
        </li>
        <li class="level1">
          <a href="<?php echo url_for('cpus/index')?>" title="Cpus" class="">
            <span>Cpus</span>
          </a>
        </li>
        <li class="last level1">
          <a href="<?php echo url_for('profiles/index')?>" title="Profiles" class="">
            <span>Profiles</span>
          </a>
        </li>
  
      </ul>
    </li>
   <?php endif ?>

  <?php if (sfConfig::get('app_matterhorn_use')):?>
    <li onmouseover="$(this).addClass('over');"  onmouseout="$(this).removeClass('over');" class="parent level0 <?php echo sfConfig::get('ingest_menu') ?>">
      <a href="#" title="Ingestador" onclick="return false" class="<?php echo sfConfig::get('ingest_menu') ?>">
        <span>Ingestador</span>
      </a>
      <ul>
        <li class="level1" last>
          <a href="<?php echo url_for('matterhorn/index')?>" title="Matterhorn" class="">
            <span>Ingestador Matterhorn</span>
          </a>
        </li>
      </ul>
    </li>
  <?php endif ?>
  </ul>
</div>


<! --- MESSAGES ALERT --- !>
<div id="div_messages_error" class="div_messages">
  <span class="div_messages_span"  onclick="$(this).parent().fadeTo('slow',0); return false" >x cerrar</span>
  <span id="div_messages_span_error">Error</span>
</div>


<div id="div_messages_info" class="div_messages">
  <span class="div_messages_span" onclick="$(this).parent().fadeTo('slow',0); return false" >x cerrar</span>
  <span id="div_messages_span_info">Info</span>
</div>

