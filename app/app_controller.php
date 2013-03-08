<?php

/**
 * Overrides the default CakePHP AppController to allow transparent
 * application initialization on each request.
 *
 * @author
 * @package
 */

class AppController extends Controller {
	
	/**
	 * Specifies the components that will be used globally throughout
	 * this application
	 * 
	 * @var array
	 */
	public $components = array('Auth', 'Session');
	
	/**
	 * This function is executed each time a request is made. Allows for
	 * default component settings to be overridden.
	 * 
	 * @see trunk/root/cake/libs/controller/Controller#beforeFilter()
	 */
	public function beforeFilter() {
		parent::beforeFilter();
		
		// change the login redirect
		$this->Auth->loginRedirect = array(
									'controller' => 'accounts',
									'action' => 'index',
									);
		
		if (!$this->Auth->user()) {
			// allow unauthenticated users to view login and registration
			$this->Auth->allow('register', 'about');
			$this->Session->delete('Auth.redirect');
		} else {
			// if a user has been authenticated, don't allow them to
			// visit the login or registration pages
			$this->Auth->deny('register', 'login', 'about');
		}
		
		// change the default hashing algorithm to MD5
		Security::setHash('md5');
	}
}

?>