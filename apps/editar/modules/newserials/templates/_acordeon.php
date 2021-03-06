<?php use_helper('Object') ?>

<div class="tv_admin_filters">
  <?php echo form_remote_tag(array('update' => 'list_serials', 'url' => 'serials/list', 'script' => 'true' ), 'id=filter_serials') ?>
    <fieldset>
      <div id="bottom_container" >
    	
        <h2 class="accordion_toggle title"><a href="#" style="text-decoration: none" onclick="$('.accordion_content_title').toggle(200); if ($('.title').hasClass('accordion_toggle_active')){ $('.title').removeClass('accordion_toggle_active')} else {$('.title').addClass('accordion_toggle_active');} return false;">Buscar</a></h2>
        <div class="accordion_content_title" style="overflow: hidden; display: none;">
          <div class="form-row">
            <label for="title">Titulo:</label>
            <div class="content">
              <?php echo input_tag('filters[title]') ?>
            </div>
            <br />
            <label for="person">Persona:</label>
            <div class="content">
              <?php echo input_tag('filters[person]') ?>
            </div>
            <br />
            <label for="place">Lugar:</label>
            <div class="content">
	    <?php $value = select_tag('filters[place]', '<option selected="selected" value="0">Indiferente</option>'.
				      objects_for_select(
							 PlacePeer::doSelectWithI18n(new Criteria(), $sf_user->getCulture()),
							 'getId',
							 '__toString',
                                                         null
							 ),
				      array('style' => 'width: 200px')
				      ); echo $value ? $value : '&nbsp;' ?>
            </div>
          </div>
        </div>
    
    
        <h2 class="accordion_toggle difusiones"><a href="#" style="text-decoration: none" onclick="$('.accordion_content_difusiones').toggle(200); if ($('.difusiones').hasClass('accordion_toggle_active')){ $('.difusiones').removeClass('accordion_toggle_active')} else {$('.difusiones').addClass('accordion_toggle_active');} return false;">Difusiones</a></h2>
        <div class="accordion_content_difusiones" style="overflow: hidden; display: none;">
          <?php foreach ($broadcasts as $broadcast): ?>
            <div class="form-row-ac-2">
              <label for="<?php echo $broadcast->getId()?>"><?php echo $broadcast->getDescription()?>: </label>
              <div class="content">
                <input name="filters[broadcast][<?php echo $broadcast->getId()?>]" id="<?php echo $broadcast->getId()?>" checked="checked" type="checkbox">
              </div>
            </div>
          <?php endforeach; ?>
        </div>


        <h2 class="accordion_toggle channels"><a href="#" style="text-decoration: none" onclick="$('.accordion_content_channels').toggle(200); if ($('.channels').hasClass('accordion_toggle_active')){ $('.channels').removeClass('accordion_toggle_active')} else {$('.channels').addClass('accordion_toggle_active');} return false;">Canales</a></h2>
        <div class="accordion_content_channels" style="overflow: hidden; display: none;">   
          <?php foreach ($serialtypes as $serialtype): ?> 
            <div class="form-row-ac-2">
              <label for="<?php echo $serialtype->getId()?>"><?php echo $serialtype->getName()?>: </label>
              <div class="content">
                <input name="filters[serialtype][<?php echo $serialtype->getId()?>]" id="<?php echo $serialtype->getId()?>" checked="checked" type="checkbox">
              </div>
            </div>
          <?php endforeach; ?>
        </div>
    

        <h2 class="accordion_toggle dates"><a href="#" style="text-decoration: none" onclick="$('.accordion_content_dates').toggle(200); if ($('.dates').hasClass('accordion_toggle_active')){ $('.dates').removeClass('accordion_toggle_active')} else {$('.dates').addClass('accordion_toggle_active');} return false;">Fechas</a></h2>
        <div class="accordion_content_dates" style="overflow: hidden; display: none;">  


          <div class="form-row">
	    <label for="publicdate"><?php echo 'Desde:' ?></label>
            <div class="content">
	      <?php echo input_date_tag('filters[date][from]',  null, array ('rich' => true, 'calendar_button_img' => '/images/admin/buttons/date.png' )) ?>
            </div>
           
            <br />

	    <label for="publicdate"><?php echo 'Hasta:' ?></label>
            <div class="content">
	      <?php echo input_date_tag('filters[date][to]',  null, array ('rich' => true, 'calendar_button_img' => '/images/admin/buttons/date.png' )) ?>
            </div>
          </div>



        </div>

        <h2 class="accordion_toggle others"><a href="#" style="text-decoration: none" onclick="$('.accordion_content_others').toggle(200); if ($('.others').hasClass('accordion_toggle_active')){ $('.others').removeClass('accordion_toggle_active')} else {$('.others').addClass('accordion_toggle_active');} return false;">Otros</a></h2>
        <div class="accordion_content_others" style="overflow: hidden; display: none;">

          <div class="form-row">
 	    <label for="announce">Anunciado:</label>
            <div class="content">
              <select name="filters[announce]" id="filters_anounce">
                <option value="diff" selected="selected">Indiferente</option>
                <option value="true">Si</option>
                <option value="false">No</option>
              </select>
            </div>
    
            <br /> 
 
 	    <label for="status">Estado:</label>
            <div class="content">
              <select name="filters[status]" id="filters_anounce">
                <option value="diff" selected="selected">Indiferente</option>
                <option value="0" >Bloqueado</option>
                <option value="1">Oculto</option>
                <option value="2" >Mediateca</option>
                <option value="3" >Arca</option>
              </select>
            </div>
          </div>

        </div>

      </div>
    </fieldset>

    <ul class="tv_admin_actions">
      <li><?php echo button_to_remote('reset', array('before' => '$("filter_serials").reset()', 'update' => 'list_serials', 'url' => 'serials/list?filter=filter', 'script' => 'true'), 'class=tv_admin_action_reset_filter') ?></li>
      <li><?php echo submit_tag('filter', 'name=filter class=tv_admin_action_filter') ?></li>
    </ul>
  </form>
</div>
<script>

</script>
<?php echo javascript_tag("
  Event.observe(window, 'load', loadAccordions, false);
  //
  //      Set up all accordions
  //
  function loadAccordions() {
    var bottomAccordion = new accordion('bottom_container');
    //bottomAccordion.activate($$('#bottom_container .accordion_toggle')[0]);
  } 
") ?>



<?php //!-- FALTA VER OCULTOS Y ANUNCIADOS ?>