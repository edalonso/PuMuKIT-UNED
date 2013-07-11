<?php

/**
 * index actions.
 *
 * @package    uned
 * @subpackage index
 * @author     Ruben Glez <rubenrua at uvigo.es>
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class indexActions extends sfActions
{

    private $defaultAction = "serials/index"; //'dashboard/index' 
  /**
   * Executes index action
   *
   */
  public function executeIndex()
  {
    if ($this->getUser()->isAuthenticated()){
      return $this->redirect($defaultAction);
    }
 
    $this->url = $this->getRequestParameter('url');
  }

  /**
   * --  LOGIN -- /editar.php/index/login
   * Si el formulario de registro es correcto redireciona a la acion por defecto
   *
   * Accion asincriona. Acceso publico .Parametros (strings) login y passwd.
   */
  public function executeLogin()
  {
    $user = UserPeer::isUser($this->getRequestParameter('login',''), $this->getRequestParameter('passwd',''));
    if ($user){
      if ($user->getRoot() == 0) return 'Fail';
      $this->getUser()->setAuthenticated(true);
      $this->getUser()->addCredential('admin');
      $this->getUser()->setAttribute('login', $user->getName() );
      $this->getUser()->setAttribute('user_id', $user->getId() );
      $this->getUser()->setAttribute('user_type_id', $user->getUserTypeId());
      $this->getUser()->setAttribute('email', $user->getEmail() );
      $this->url = $this->getRequestParameter('url', 'serials/index');
      return 'Success';
    }
    $this->setLayout(false);
    return 'Fail';
  }

  /**
   * --  LOGOUT -- /editar.php/index/logout
   * Accion que realiza el Logout.
   */
  public function executeLogout(){
    $this->getUser()->setAuthenticated(false);
    $this->getUser()->removeCredential('admin');
    return $this->redirect('index', 'index');
  }

  /**
   * --  LOGIN404 -- /editar.php/index/login404
   * Accion por defecto al no estar registrado.
   */
  public function executeLogin404(){
    $this->setLayout(false);
  }


  /**
   * --  ERROR404 -- /editar.php/index/error404
   * Accion por defecto cuando se producce un error
   */
  public function executeError404(){
    $this->setLayout(false);
  }
}
