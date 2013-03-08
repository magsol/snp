<?php

/**
 * Handles most of the grunt work of the application: everything beyond
 * user authentication and registration.
 *
 * @author Shannon Quinn
 * @package
 */

class AccountsController extends AppController {
	//public $scaffold;
	public $components = array('Twitter', 'MySpace', 'LinkedIn');
	public $callback = 'http://localhost/final637/trunk/root/accounts/callback/';
	public $pageTitle = 'Social Networking Portal | ';
	
	/**
	 * Default view. Shows all the accounts the user has set up, as well
	 * as links to create new accounts.
	 */
	public function index() {
		$this->pageTitle .= 'Home';
		$user = $this->Session->read('Auth.User');
		// get all the social network accounts tied to the currently 
		// authenticated user
		$accounts = $this->Account->find('all', array(
									'conditions' => array(
										'Account.userid' => $user['uid']
									)));
		if (count($accounts) > 0) {
			$this->set('hasAccounts', 'yup');
		}
		$this->set('accounts', $accounts);
		
		// THESE NAMES MUST MATCH THOSE IN THE Account MODEL's $PARAMS
		$this->set('twitterid', 'Twitter');
		$this->set('myspaceid', 'MySpace');
		$this->set('linkedinid', 'LinkedIn');
	}
	
	/**
	 * Shows specific information for a single account.
	 * @param int $id
	 */
	public function view($id) {
		if (!isset($id)) {
			$this->redirect('/accounts');
		}
		// get the account
		$user = $this->Session->read('Auth.User');
		$account = $this->findAccount($id, $user['uid']);
		if (!$account) {
			$this->redirect('/accounts');
		}
		$this->pageTitle .= $account['Account']['accountname'] . ' Account';
		// set all the accounts on the sidebar
		$accounts = $this->Account->find('all', array(
									'conditions' => array(
										'Account.userid' => $user['uid']
									)));
		if (count($accounts) > 0) {
			$this->set('hasAccounts', 'yup');
		}
		$this->set('accounts', $accounts);
		
		// now we need to display the basic information
		// set all the page variables
		$var = $account['Account']['accountname'];
		$this->$var->reset($account['Account']['oauth_token'], $account['Account']['oauth_token_secret']);
		$this->set('network', array('name' => $var,
									'username' => $account['Account']['account_username'],
									'id' => $id));
		$this->set('update', $this->$var->getCurrentStatus(array(), 
									$account['Account']['account_uid']));
		$this->set('profile', $this->$var->getProfile(array(), $account['Account']['account_uid']));
		$this->set('friends', $this->$var->getFriends($account['Account']['account_uid']));
	}
	
	/**
	 * Action handler to update a status
	 */
	public function update() {
		// has anything been submitted?
		if (empty($this->data)) {
			$this->redirect('/accounts');
		}
		
		// pull out the account ID and the post content
		$id = $this->data['Account']['uaid'];
		$post = $this->data['Account']['status'];
		
		// get the account properties
		$account = $this->findAccount($id);
		if (!$account) {
			$this->redirect('/accounts');
		} else if (strlen($post) == 0) {
			$this->redirect('view/' . $id);
		}
		// reset the oauth object so we have the OAuth tokens
		$this->$account['Account']['accountname']->reset($account['Account']['oauth_token'], 
														$account['Account']['oauth_token_secret']);
		// submit the post!
		$retval = $this->$account['Account']['accountname']->updateStatus($account['Account']['account_uid'], $post);
		
		// redirect the user back to the view for this network
		$this->redirect(array('action' => 'view', $id));
	}
	
	/**
	 * Creates a new account association.
	 * @param int $id
	 */
	public function create($id) {
		if (!isset($id) || !array_key_exists($id, $this->components)) {
			$this->redirect('/accounts');
		}
		
		$this->pageTitle .= 'Create New Account';
		// we have a valid account to create
		$user = $this->Session->read('Auth.User');
		
		// get a request token
		$requestToken = $this->$id->getRequestToken($this->callback);
		
		// save the request token to the session
		$this->Session->write('oauth_token', $requestToken['oauth_token']);
		$this->Session->write('oauth_token_secret', $requestToken['oauth_token_secret']);
		$this->Session->write('id', $id);
		
		// get the authorization URL
		$url = $this->$id->getAuthorizeURL($requestToken['oauth_token'], $this->callback);
		
		// error checking
		if ($this->$id->lastHttpCode() == 200) {
			// everything's fine, send the redirect
			$this->redirect($url);
		} else {
			// an error occurred!
			$this->Session->setFlash('An error occurred. Returned HTTP status: ' .
									$this->$id->lastHttpCode());
			$this->redirect('/accounts');
		}
	}
	
	/**
	 * Upon successful authentication with the remote OAuth server,
	 * this method is invoked with the oauth_token in tow
	 */
	public function callback() {
		if (!isset($this->params['url']['oauth_token'])) {
			$this->redirect('/accounts');
		}
		$oauth_token = $this->params['url']['oauth_token'];
		// first, check to make sure the token matches the one we have
		if ($this->Session->read('oauth_token') != urldecode($oauth_token)) {
			$this->Session->setFlash('OAuth tokens not equal. Unable to process!');
			$this->redirect('/accounts');
		}
		
		$user = $this->Session->read('Auth.User');
		// perform the final step of OAuth before redirecting
		// the user back 
		$id = $this->Session->read('id');
		$this->$id->reset($this->Session->read('oauth_token'), $this->Session->read('oauth_token_secret'));
		$accessToken = $this->$id->getAccessToken((isset($this->params['url']['oauth_verifier']) ? $this->params['url']['oauth_verifier'] : false));
		
		// obtain the username and uid for this account...convenience
		$names = $this->$id->getUsernameAndUID();
		
		// save the values
		$this->Session->write('access_token', $accessToken);
		$this->Session->delete('oauth_token');
		$this->Session->delete('oauth_token_secret');
		$this->Account->save(array('Account' => array(
										'userid' => $user['uid'],
										'accountname' => $id,
										'oauth_token' => $accessToken['oauth_token'],
										'oauth_token_secret' => $accessToken['oauth_token_secret'],
										'account_uid' => $names['uid'],
										'account_username' => $names['username'],
									)));
		
		// all done!
		$this->redirect('/accounts');
	}
	
	/**
	 * Deletes an existing account association.
	 * @param int $id
	 */
	public function delete($id) {
		if (!isset($id)) {
			$this->redirect('/accounts');
		}
		
		// find the account we're interested in deleting
		$user = $this->Session->read('Auth.User');
		$account = $this->Account->find('first', array('conditions' => array(
										'Account.uaid' => $id,
										'Account.userid' => $user['uid'],
									)));
		if ($account == null || count($account) == 0) {
			// bogus account number
			$this->redirect('/accounts');
		}
		
		// has the delete form been submitted?
		if (!empty($this->data) && $account['Account']['uaid'] == $this->data['Account']['id']) {
			// yes
			$this->Account->delete($this->data['Account']['id']);
			$this->redirect('/accounts');
		} else {
			// just set up the variables
			$this->set('network', array('id' => $id, 
										'name' => $account['Account']['accountname'],
										'username' => $account['Account']['account_username']));
		}
	}
	
	/**
	 * Helper method that returns the Account object with the corresponding
	 * account ID and, optionally, owned by the user with the specified ID
	 * @param $accountid
	 * @param $userid
	 * @return Account
	 */
	private function findAccount($accountid, $userid = null) {
		$account = null;
		if ($userid != null) {
			$account = $this->Account->find('first', array('conditions' => array(
										'Account.uaid' => $accountid,
										'Account.userid' => $userid,
									)));
		} else {
			$account = $this->Account->find('first', array('conditions' => array(
										'Account.uaid' => $accountid,
									)));
		}
		
		// since I'm really not sure how CakePHP's model system returns query
		// results...
		if ($account == null || count($account) == 0) {
			return null;
		} else {
			return $account;
		}
	}
}

?>
