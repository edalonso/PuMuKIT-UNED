<?php

/**
 * categories actions.
 *
 * @package    pumukituvigo
 * @subpackage categories
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class categoriesActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {      

      //$this->getUser()->panNivelTres('Categorías', 'categories');
      //$this->title = "Categorías";

      $c = new Criteria();
      $c->addAscendingOrderByColumn('rand()');
      $c->setLimit(3);
      $c->addDescendingOrderByColumn(SerialPeer::ID);

      $this->blocks = array();
      $this->blocks['Zoología marina'] = SerialPeer::doSelect($c);
      $this->blocks['Citología animal'] = SerialPeer::doSelect($c);
      $this->blocks['Ecología animal'] = SerialPeer::doSelect($c);
      $this->blocks['Bacteriología'] = SerialPeer::doSelect($c);
      $this->blocks['Bisoestadística'] = SerialPeer::doSelect($c);
      $this->blocks['Composición del cuerpo'] = SerialPeer::doSelect($c);
      $this->blocks['Botánica general'] = SerialPeer::doSelect($c);
      $this->blocks['Anatomía animal'] = SerialPeer::doSelect($c);
      $this->blocks['ingeniería genética'] = SerialPeer::doSelect($c);

      if(count($this->blocks) == 0){
          return "Empty";
      }

      $this->setTemplate('multidisplay');
      $this->displayLine2 = false;
  }

  /**
   * Executes edu action
   *
   *  RECURSOS EDUCATIVOS
   */
  public function executeEdu()
  {

      $type = $this->getRequestParameter('type');
      //$this->forward404Unless($type);


      $aux = ($type . "_title");
      $this->getUser()->panNivelDosYMedio(Educa::$$aux, 'categories/edu?type=' . $type);
      $this->title = Educa::$$aux; //falta

      $c = new Criteria();
      $c->addAscendingOrderByColumn('rand()');
      $c->setLimit(8);
      $c->addDescendingOrderByColumn(SerialPeer::ID);

      $this->blocks = array();
      $this->blocks['Zoología marina'] = SerialPeer::doSelect($c);
      $this->blocks['Citología animal'] = SerialPeer::doSelect($c);
      $this->blocks['Ecología animal'] = SerialPeer::doSelect($c);
      $this->blocks['Bacteriología'] = SerialPeer::doSelect($c);
      $this->blocks['Bisoestadística'] = SerialPeer::doSelect($c);
      $this->blocks['Composición del cuerpo'] = SerialPeer::doSelect($c);
      $this->blocks['Botánica general'] = SerialPeer::doSelect($c);
      $this->blocks['Anatomía animal'] = SerialPeer::doSelect($c);
      $this->blocks['ingeniería genética'] = SerialPeer::doSelect($c);

      //$this->blocks = $this->getSerialsByUnescoArray(Educa::$$type);

      if(count($this->blocks) == 0){
          return "Empty";
      }

      $this->setTemplate('multidisplay');
      $this->displayLine2 = false;
  }
  public function executeClases()//vista de clases y polimedias con 4 o 6 dígitos UNESCO
  {

      $type = $this->getRequestParameter('type');
      //$this->forward404Unless($type);


      $aux = ($type . "_title");
      $this->getUser()->panNivelDosYMedio(Educa::$$aux, 'categories/edu?type=' . $type);
      $this->title = Educa::$$aux; //falta

      $c = new Criteria();
      $c->addAscendingOrderByColumn('rand()');
      $c->setLimit(3);
      $c->addDescendingOrderByColumn(SerialPeer::ID);

      $this->blocks = array();
      $this->blocks['Corporativos'] = SerialPeer::doSelect($c);
      $this->blocks['Rueda de prensa'] = SerialPeer::doSelect($c);
      $this->blocks['Enventos institucionales'] = SerialPeer::doSelect($c);
      $this->blocks['Biblioteca'] = SerialPeer::doSelect($c);
      $this->blocks['Deportes'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Relaciones Internacionales'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Titulaciones y Convergencia Europea'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Relaciones Institucionales'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. de nuevas Tecnologías y Calidad'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Formación e Innovación Educativa'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Extensión Cultural y Estudiantes'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Campus de Orense'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Campus de Pontevedra'] = SerialPeer::doSelect($c);
      $this->blocks['Secretaría General'] = SerialPeer::doSelect($c);
      $this->blocks['Vic. Economía y Planificación'] = SerialPeer::doSelect($c);

      if(count($this->blocks) == 0){
          return "Empty";
      }

      $this->setTemplate('multidisplay');
      $this->displayLine2 = false;

  }

}
