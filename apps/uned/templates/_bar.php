<div id="blq-navegacion-lateral">


<!-- categorias -->

<!-- IE fix -->
<div></div>

<div class="caixa">
  <div class="titulo">
   <div class="titulo_square">&nbsp;&nbsp;</div>
   <div style="float: left; margin-top: 5px;">
      <?php echo __('Menú')?>
   </div>
  </div>
  <div class="menu_left">
    <ul>
      <li><a href="<?php echo url_for('announces/index')?>"><?php echo __("Mediateca por meses")?></a></li>
      <li><a href="<?php echo url_for('catalog/date')?>"><?php echo __("Mediateca por series")?></a></li>
      <li><a href="<?php echo url_for('buscador/index')?>"><?php echo __("Buscador")?></a></li>

      <?php $templates = TemplatePeer::getPageUserTemplates() ?>
      <?php foreach($templates as $template): ?>
      <li><a href="<?php echo url_for('templates/index?temp=' . $template->getName())?>"><?php echo $template->getName() ?></a></li>
      <?php endforeach ?>
    </ul>
  </div>
</div>

<div class="caixa">
  <div class="titulo">
   <div class="titulo_square">&nbsp;&nbsp;</div>
   <div style="float: left; margin-top: 5px;">
      <?php echo __('Categorías')?>
   </div>
  </div>
  <div class="menu_left">
    <ul>
      <li><a href="<?php echo url_for('educa/index')?>"><?php echo __("Recursos educativos")?></a></li>
      <li><a href="<?php echo url_for('destacados_TV/index')?>"><?php echo __("Destacados TV")?></a></li>
      <li><a href="<?php echo url_for('destacados_RADIO/index')?>"><?php echo __("Destacados RADIO")?></a></li>
      <li><a href="<?php echo url_for('teleactos/index')?>"><?php echo __("Teleactos")?></a></li>
      <li><a href="<?php echo url_for('noticias/index')?>"><?php echo __("Noticias")?></a></li>
    </ul>
  </div>
</div>

<!-- Proximos directos -->
<div class="caixa">
  <div class="titulo">
    <div class="titulo_square">&nbsp;&nbsp;</div>
    <div style="float: left; margin-top: 5px;">
      <?php echo __("Teleactos")?>
    </div>
  </div>
  <div style="padding: 2px 5px" class="menu_left">

   <?php $events = EventPeer::getRNEvents(); if($events == null):?>
      <?php echo __("Sin retransmisiones ahora")?>
    <?php else:?>
      <?php foreach($events as $e):?>
        <div style="margin-bottom: 8px;">
           <a href="<?php echo url_for('directo/index?id=' . $e->getId()) ?>" >
          <?php echo (($now = $e->getSessionNow()) == null)? $e->getFirstSession()->getInitDate('H:i') : $now->getInitDate('H:i')?>
          <?php echo str_abbr($e->getTitle(), 20, "...") ?>
          </a>
        </div>
      <?php endforeach?>
    <?php endif?>
  </div>
</div>

<!-- Destacados -->
<?php $text = WidgetTemplatePeer::get(3, $sf_user->getCulture(), ''); ?>

<?php if(strlen($text) != 0) :?>
<div class="caixa">
  <div class="titulo">
    <div class="titulo_square">&nbsp;&nbsp;</div>
    <div style="float: left; margin-top: 5px;">
	  <?php echo __("Destacados")?>
    </div>
  </div>
  <div class="menu_left contacto">
    <?php echo $text ?>
  </div>
</div>
<?php endif ?>


<!-- Total -->
<div class="caixa">
  <div class="titulo">
    <div class="titulo_square">&nbsp;&nbsp;</div>
    <div style="float: left; margin-top: 5px;">
	  <?php echo __("Mediateca")?>
    </div>
  </div>
  <?php include_partial("index/total"); ?>
</div>



<!-- Contactos -->
<div class="caixa">
  <div class="titulo">
    <div class="titulo_square">&nbsp;&nbsp;</div>
    <div style="float: left; margin-top: 5px;">
      <?php echo __("Contacto")?>
    </div>
  </div>
  <div id="contacto" class="menu_left">
    <?php echo mail_to(sfConfig::get('app_info_mail'), _encodeText(sfConfig::get('app_info_mail')), 'encode=true') ?>
  </div>
</div>

<!-- Follow Us -->
<div class="caixa_social">
  <div class="titulo">
    <?php echo __("Síguenos:")?>
  </div>
  <div class="menu_left" style="padding-left: 0px">
    <a style="text-decoration: none;" class="icon_text" href="<?php echo url_for('templates/index?temp=Lista') ?>"></a>

    <a style="text-decoration: none" href="https://www.facebook.com/uned.cemav" target="_blank">
      <img src="/images/uned/social/ico_facebook1.png" alt="Facebook"/>
    </a>
    <a style="text-decoration: none" href="http://www.linkedin.com/company/uned" target="_blank">
      <img src="/images/uned/social/ico_linkedin1.png" alt="LinkedIn"/>
    </a>
    <a style="text-decoration: none" href="https://twitter.com/canaluned" target="_blank">
     <img src="/images/uned/social/ico_twt1.png" alt="Twitter"/>
    </a>
    <a style="text-decoration: none" href="<?php echo url_for('xml/lastnews')?>" target="_blank">
      <img src="/images/uned/social/ico_rss1.png" alt="RSS"/>
    </a>
    <a style="text-decoration: none" href="http://www.youtube.com/user/uned?gl=ES&hl=es" target="_blank">
      <img src="/images/uned/social/ico_youtube1.png" alt="YouTube"/>
    </a>
    <a style="text-decoration: none" href="http://www.flickr.com/photos/uned/collections//" target="_blank">
      <img src="/images/uned/social/ico_flickr1.png" alt="Flickr"/>
    </a>
  </div>
</div>
</div>