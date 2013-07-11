<?php

/**
 * extractpic actions.
 *
 * @package    pumukituvigo
 * @subpackage extractpic
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class extractpicActions extends sfActions
{
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
      $this->mm = MmPeer::retrieveByPk($this->getRequestParameter('mm'));
      $this->forward404Unless($this->mm);

      if ($this->hasRequestParameter('mod')) {
	$this->module = $this->getRequestParameter('mod');
      }

      $c = new Criteria();
      $c->add(FilePeer::MM_ID, $this->mm->getId());
      $c->add(FilePeer::AUDIO, false);
      $c->addJoin(FilePeer::PERFIL_ID, PerfilPeer::ID);
      $c->add(PerfilPeer::DISPLAY, true);
      $c->addAscendingOrderByColumn(FilePeer::RANK);
      $this->file = FilePeer::doSelectOne($c);

      if (($this->file) && (file_exists($this->file->getFile()))) {
	$movie = new ffmpeg_movie($this->file->getFile());
	if ($movie->getVideoCodec() != 'h264' ) {
	  return "NoHTML5";
	}
      } else {
	return "NoFile";
      }
  }

  /**
   * --  UPLOAD -- /editar.php/extractpic/upload
   *
   * Parametros: 
   *    -id de mm, serial o channel (tipo de objeto)
   *    -type: url o file (metodo de subida)
   *
   */
  public function executeUpload()
  {
      $currentDir = $this->inicialize(true);
      
      if ($this->hasRequestParameter('img')){
          
          if (strstr($this->getRequestParameter('img'), 'png') == false ){
              return $this->renderText("Publicacion incorrecta");
          }
          
          $absCurrentDir = sfConfig::get('sf_upload_dir').'/pic/' . $currentDir;
          $base_64 = $this->getRequestParameter('img');
          $decodedData = substr($base_64, 22, strlen($base_64));
          $data = base64_decode($decodedData);
          
          $fileName = uniqid() . '.png';
          $absFile = $absCurrentDir .'/' . $fileName;
          while(file_exists($absFile)){
              $fileName = uniqid() . '.png';
              $absFile = $absCurrentDir .'/' . $fileName;
          }
                 
          try{
              $thumbnail = new sfThumbnail(sfConfig::get('app_thumbnail_hor'), sfConfig::get('app_thumbnail_ver'));
              $thumbnail->loadData($data, 'image/png');
              @mkdir($absCurrentDir, 0777, true);
              
              $thumbnail->save($absFile, 'image/png');
          }catch(Exception $e){
             file_put_contents($absFile, $data);
          }

          $aux = 'Pic'.ucfirst($this->que);
          
          $pic = new Pic();
          $pic->setUrl('/uploads/pic/' . $currentDir . '/' . $fileName);
          $pic->save();
          $pic_object = new $aux;
          $pic_object->setPicId($pic->getId());
          $pic_object->setOtherId($this->object_id);
          $pic_object->save();
          
      }
      $this->msg_alert = array('info', "Nueva imagen insertada.");
      return $this->renderText("Publicacion correcta");
  }

  protected function sanitizeFile($file)
  {
    return preg_replace('/[^a-z0-9_\.-]/i', '_', $file);
  }

  protected function inicialize($dir = false)
  {
    if ($this->hasRequestParameter('mm')){ 
      $this->object_id = $this->getRequestParameter('mm');      
      $this->url= 'pics/list?preview=true&mm='.$this->object_id;
      $this->upload = 'pic_mms';
      $this->que = 'mm';
      if($dir){
          $aux = MmPeer::retrieveByPk($this->object_id);
          $currentDir = 'Serial/' . $aux->getSerialId() . '/Video/' . $this->object_id;  
      }else{
          $currentDir = 'Video/' . $this->object_id;  
      }
    }elseif ($this->hasRequestParameter('serial')){
        $this->object_id = $this->getRequestParameter('serial');      
        $this->url= 'pics/list?preview=true&serial='.$this->object_id;
        $this->upload = 'pic_serials';
        $this->que = 'serial';
        $currentDir = 'Serial/' . $this->object_id;
    }elseif ($this->hasRequestParameter('channel')){ 
        $this->object_id = $this->getRequestParameter('channel');      
        $this->url= 'pics/list?channel='.$this->object_id;
        $this->upload = 'pic_channels';
        $this->que = 'channel';
        $currentDir = 'Channel/' . $this->object_id;  
    }else{
        $this->forward404();
    }
    
    return $currentDir;
  }
}
