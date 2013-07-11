<?php

/**
 * catalog actions.
 *
 * @package    pumukituvigo
 * @subpackage catalog
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class catalogActions extends sfActions
{
    public function preExecute()
    {
        $this->more = '?';
        $this->more .= ($this->hasRequestParameter('search'))?"search=" . $this->getRequestParameter('search') . "&":"";
        $this->more .= ($this->hasRequestParameter('broadcast'))?"broadcast=" . $this->getRequestParameter('broadcast'):"";
    }


    public function executeIndex()
    {
        $this->forward('catalog', 'date');
    }


    /**
     * Place, ordena las series alfabeticamente
     * 
     */
    public function executePlace()
    {
        $serials_org = $this->getSerials();
        $places = PlacePeer::doSelectOrderWithPrecinctWithI18n(new Criteria(), $this->getUser()->getCulture()); /*Solo lugares con series*/
	$this->getUser()->resetPan();
        $this->getUser()->panNivelDos('Mediateca completa ordenada por lugares', 'catalog');
        $this->title = 'Mediateca completa por series ordenada por lugares';

        $placeofs = PlacePeer::getArrayPlaceSeries();
        $serials = array();

        foreach($serials_org as $i1 => $s){
            foreach($places as $i2 =>$p){
                if ($placeofs[$s->getId()] == $p->getId()) {
                    $serials[$p->getName()][] = $s;
                }
            }
        }

        $this->serials = $serials;

        $this->setTemplate('index');
    }

    /**
     * Channel, ordena las series por canal
     *
     */
    public function executeChannel()
    {
        $serials_org = $this->getSerials();
        $channels = SerialTypePeer::doSelectWithI18n( new Criteria(), $this->getUser()->getCulture());
	$this->getUser()->resetPan();
        $this->getUser()->panNivelDos('Mapa de la mediateca completa', 'catalog');
        $this->title = 'Mapa de la mediateca completa por series';

        $serials = array();

        //de la A a la Z          
        foreach($channels as $c){

            $f_str = 'return $a->getSerialTypeId() == ' . $c->getId() . ';';
            $f = create_function('$a', $f_str);
            $temp = array_filter($serials_org, $f);

            if (count($temp) != 0) {
                $serials[$c->getName()] = $temp;
            }
        }

        $this->serials = $serials;

        $this->setTemplate('index');
    }
    /**
     * ABC, ordena las series alfabeticamente
     *
     */
    public function executeAbc()
    {
        $serials_org = $this->getSerials();
        $ord = $this->getAbc();
        $serials = array();
        $this->title = 'Mediateca completa por series ordenada alfabéticamente';
	$this->getUser()->resetPan();
        $this->getUser()->panNivelDos('Mediateca completa ordenada alfabéticamente', 'catalog');
        //de la A a la Z
        foreach($ord as $o){

            $f_str = 'return strtoupper(substr($a->getTitle(), 0, 1)) == \''. $o .'\';';
            $f = create_function('$a', $f_str);
            $temp = array_filter($serials_org, $f);

            if (count($temp) != 0) {
                $serials[$o] = $temp;
            }
        }

        //otros
        $f_str = 'return ereg("^[^a-zA-Z]", $a->getTitle());';
        $f = create_function('$a', $f_str);
        $temp = array_filter($serials_org, $f);

        if (count($temp) != 0) {
            $serials['#'] = $temp;
        }

        $this->serials = $serials;

        $this->setTemplate('index');
    }
    /**
     * DATE,  ordena las series por fecha
     *
     */
    public function executeDate()
    {
        $serials_org = $this->getSerials();
        $resultset = $this->getDates();
        $this->title = "Mediateca completa por series ordenada por fecha";
	$this->getUser()->resetPan();
        $this->getUser()->panNivelDos('Mediateca completa ordenada por fecha', 'catalog');

        $serials = array();

        while($resultset->next()) {
            setlocale(LC_ALL, $this->getUser()->getCulture().'_ES.UTF8');

            $date = strftime("%m-%Y", $resultset->getDate('date', null));

            $f_str = 'return $a->getPublicDate("%m-%Y") == \''. $date .'\';';
            $f = create_function('$a', $f_str);
            $temp = array_filter($serials_org, $f);

            if (count($temp) != 0) {
                $serials[strftime("%B-%Y", $resultset->getDate('date', null))] = $temp;
            }
        }
        $this->serials = $serials;
        $this->setTemplate('index');
    }

    /**
     * Devuleve un ResultSet con las iniciales de los titulos de las series en la cultura adecuada
     *
     */
    protected function getAbc(){
        return array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q',
                     'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    }

    /**
     * Devuleve un ResultSet con las fechas de las series
     *
     */
    protected function getDates(){
        $conexion = Propel::getConnection();
        $consulta = 'SELECT DISTINCT DATE_FORMAT(%s, "%%Y-%%m-01") AS date FROM %s ORDER BY %s DESC';

        $consulta = sprintf($consulta, SerialPeer::PUBLICDATE, SerialPeer::TABLE_NAME, SerialPeer::PUBLICDATE );

        $sentencia = $conexion->prepareStatement($consulta);
        return $sentencia->executeQuery();
    }

    /**
     * Devuleve un ResultSet con las fechas de las series
     *
     */
    protected function getSerials(){
        $c = new Criteria();
        $c->setDistinct(true);
        $c->add(SerialPeer::DISPLAY, true);
        $c->addAscendingOrderByColumn(SerialI18nPeer::TITLE);
        SerialPeer::addPubChannelCriteria($c, 1);
        SerialPeer::addBroadcastCriteria($c, array('pub', 'cor')); 

        if ($this->hasRequestParameter('search')){
            SerialPeer::addSeachCriteria($c, $this->getRequestParameter('search'), $this->getUser()->getCulture());
        }

        return SerialPeer::doSelectWithI18n($c, $this->getUser()->getCulture());
    }
}
