<?php

/**
 * Dashboard components.
 *
 * @package    fin
 * @subpackage dashboard
 * @author     Your name here
 * @version    SVN: $Id: components.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class dashboardComponents extends sfComponents
{
  /**
   *
   *
   */
  public function executeDiskfree()
  {
    
    $disks = StreamserverPeer::doSelect(new Criteria());
    $return = array();
    foreach($disks as $disk){
      if(file_exists($disk->getDirOut())){
        $a = array($disk->getName(), 
		 sprintf('%.2f', (disk_total_space($disk->getDirOut())/1073741824)), 
		 sprintf('%.2f', (disk_free_space($disk->getDirOut())/1073741824))
		 );
        $return[] = $a;
      }
    }
    $this->disks = $return;
  }


  /**
   *
   *
   */
  public function executeTranscoderinfo()
  {
    $c = new Criteria();
    $c->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_ERROR);
    $this->t_error = TranscodingPeer::doCount($c);

    $c = new Criteria();
    $c->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_PAUSADO);
    $this->t_pausado = TranscodingPeer::doCount($c);

    $c = new Criteria();
    $c->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_EJECUTANDOSE);
    $this->t_ejec = TranscodingPeer::doCount($c);

    $c = new Criteria();
    $c->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_FINALIZADO);
    $this->t_fin = TranscodingPeer::doCount($c);

    $c = new Criteria();
    $c->add(TranscodingPeer::STATUS_ID, TranscodingPeer::STATUS_ESPERANDO);
    $this->t_stop = TranscodingPeer::doCount($c);
    
    $this->cpus = CpuPeer::doSelect(new Criteria());
  }


  /**
   *
   *
   */
  public function executeTotal()
  {
    $this->time_ini = intval((strtotime('01/01/1940')) / 86400);
    $this->time_end = intval((strtotime("+1 month")) / 86400);
  }


  /**
   *
   *
   */
  public function executeTotalinfo()
  {
    
    $dates = array("end" => ($this->end * 86400), "ini" => ($this->ini * 86400));

    $this->horas_pub = FilePeer::doCountDurationPublic($dates);
    $this->horas = FilePeer::doCountDuration($dates);

    $this->num_series = SerialPeer::doCountPublic();
    $this->num_series_total = SerialPeer::doCountPublic(false, false, $dates);

    $this->num_videos = MmPeer::doCountPublic();
    $this->num_videos_total = MmPeer::doCountPublic(false, false, $dates);

  }

  /**
   *
   *
   */
  public function executeLastserial()
  {
    $c = new Criteria();
    $c->setLimit(7);
    $c->addDescendingOrderByColumn(SerialPeer::PUBLICDATE);
    $this->serials = SerialPeer::doSelectWithI18n($c, 'es');
  }


  /**
   *
   *
   */
  public function executeLastserialpublic()
  {
    $c = new Criteria();
    $c->setLimit(7);
    $c->addDescendingOrderByColumn(SerialPeer::PUBLICDATE);
    SerialPeer::addPublicCriteria($c);
    $this->serials = SerialPeer::doSelectWithI18n($c, 'es');
  }


  /**
   *
   *
   */
  public function executeLastmm()
  {
    $c = new Criteria();
    $c->setLimit(7);
    $c->addDescendingOrderByColumn(MmPeer::PUBLICDATE);
    $this->mms = MmPeer::doSelectWithI18n($c, 'es');
  }


  /**
   *
   *
   */
  public function executeLastmmpublic()
  {
    $c = new Criteria();
    $c->setLimit(7);
    $c->addDescendingOrderByColumn(MmPeer::PUBLICDATE);
    SerialPeer::addPublicCriteria($c);
    $this->mms = MmPeer::doSelectWithI18n($c, 'es');
  }

  /**
   *
   *
   *
   */
  public function executeMinibuscador()
  {
    
  }
  
}
