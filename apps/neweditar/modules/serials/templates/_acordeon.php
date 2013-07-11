<?php use_helper('Object') ?>
<?php //use_javascript('jquery-ui.js') ?>

<div class="tv_admin_filters">
  <?php echo form_tag('serials/list', 'id=filter_serials') ?>
    <fieldset>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script>
 $(function() {
         $( "#bottom_container" ).accordion({ active: true });
 });
</script>
      <div id="bottom_container" >
    	
        <h2 class="accordion_toggle title">Buscar</h2>
        <div class="accordion_content_title" style="overflow: hidden; display: none; height: none !important;">
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
    
    
        <h2 class="accordion_toggle difusiones"><a href="#" style="text-decoration: none">Difusiones</a></h2>
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


        <h2 class="accordion_toggle channels"><a href="#" style="text-decoration: none">Canales</a></h2>
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
    

        <h2 class="accordion_toggle dates"><a href="#" style="text-decoration: none">Fechas</a></h2>
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

        <h2 class="accordion_toggle others"><a href="#" style="text-decoration: none">Otros</a></h2>
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
  </form>
</div>
<?php //!-- FALTA VER OCULTOS Y ANUNCIADOS ?>