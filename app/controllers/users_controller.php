<?php

/**
 * This controller handles user authentication and registration.
 *
 * @author Shannon Quinn
 * @package
 */

class UsersController extends AppController {
	//public $scaffold;
	public $helpers = array('form', 'html');
	public $pageTitle = 'Social Networking Portal | ';
	
	/**
	 * Auth componenet handles this.
	 */
	public function login() {
		$this->pageTitle .= 'Login';
	}
	
	/**
	 * De-authenticates the user.
	 */
	public function logout() {
		$this->redirect($this->Auth->logout());
	}
	
	/**
	 * Shows some handy-dandy information about the site
	 */
	public function about() {
		$this->pageTitle .= 'About';
	}
	
	/**
	 * Performed when a user either accesses the registration page, or
	 * submits their credentials.
	 */
	public function register() {
		$this->pageTitle .= 'Registration';
		// was anything posted?
		if (!empty($this->data)) {
			$this->User->set($this->data);
			if ($this->User->validates()) {
				$this->User->save($this->data);
				$this->Auth->login($this->data);
				$this->redirect('/accounts');
			} else {
				// destroy the passwords so they don't show up again
				$this->data['User']['password'] = null;
				$this->data['User']['passwd'] = null;
			}
		}
		// anything else that could happen is handled by validation
	}
}

?>