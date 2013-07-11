<?php
/**
 * MODULO FEATUED. 
 * Muestra un textarea para modificar seccion de destacados 
 * del menu lateral del frontend.
 *
 * @package    pumukit
 * @subpackage index
 * @author     Ruben Gonzalez Gonzalez (rubenrua at uvigo dot com)
 * @version    1.0
 */
class featuredActions extends sfActions
{

  public $id = 3;

  public function executeIndex()
  {
    sfConfig::set('config_menu','active');
    $this->text = WidgetTemplatePeer::retrieveByPk($this->id);

    //WidgetTemplatePeer::get(3 ,$this->getUser()->getCulture(), '');
  }

  /**
   * --  UPDATE -- /editar.php/templates/update
   *
   * Parametros por POST: id y text_culture de template a actualizar
   */
  public function executeUpdate()
  {
    $text = WidgetTemplatePeer::retrieveByPk($this->id);
    
    $langs = sfConfig::get('app_lang_array', array('es'));
    foreach($langs as $lang){
      $text->setCulture($lang);
      $text->setText($this->getRequestParameter('text_' . $lang, ' '));
    }
    $text->save();
    return $this->renderText('Ok');
  }
}

