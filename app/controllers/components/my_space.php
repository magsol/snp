<?php

include_once('snpapi.php');

/**
 * Encapsulates the MySpace API.
 *
 * @author Shannon Quinn
 * @package
 */

class MySpaceComponent extends Object implements SNPAPI {
	
	/** the oauth object */
	private $oauth;
	
	/** name of this object */
	private $name = 'MySpace';

	/**
	 * Constructor
	 */
    public function __construct() {
    	$this->reset();
	}
	
	public function __destruct() {
		
	}

	/**
	 * Performs the first step of the OAuth 3-legged process
	 * 
	 * @return array('oauth_token', 'oauth_token_secret')
	 * @see trunk/root/app/controllers/components/SNPAPI#getRequestToken($callback)
	 */
	public function getRequestToken($callback = null) {
		return $this->oauth->getRequestToken();
	}
	
	/**
	 * Returns the authorization URL to which the application must redirect the user.
	 * 
	 * @return string
	 * @see trunk/root/app/controllers/components/SNPAPI#getAuthorizeURL($token)
	 */
	public function getAuthorizeURL($token, $callback = null) {
		return $this->oauth->getAuthorizeURL($token, $callback);
	}
	
	/**
	 * Performs the final step of the OAuth 3-legged processs
	 * 
	 * @return array('oauth_token', 'oauth_token_secret')
	 * @see trunk/root/app/controllers/components/SNPAPI#getAccessToken($verifier)
	 */
	public function getAccessToken($verifier = false) {
		return $this->oauth->getAccessToken('GET', $verifier);
	}
	
	/**
	 * Returns the HTTP status code from the last call.
	 * @see trunk/root/app/controllers/components/SNPAPI#lastHttpCode()
	 */
	public function lastHttpCode() {
		return $this->oauth->http_code;
	}
	
	/**
	 * Essentially calls the constructor again
	 * @see trunk/root/app/controllers/components/SNPAPI#reset($token, $tokenSecret)
	 */
	public function reset($token = null, $tokenSecret = null) {
		$this->oauth = MySpaceComponent::factory($token, $tokenSecret);
	}
	
	/**
	 * Returns an array with the user's username and account-specific
	 * UID for the social networking site.
	 * @see trunk/root/app/controllers/components/SNPAPI#getUsernameAndUID()
	 */
	public function getUsernameAndUID() {
		$retval = array();
		$arr = (array)$this->oauth->get('user');
		$retval['username'] = $arr['name'];
		$retval['uid'] = $arr['userId'];
		return $retval;
	}
	
	/**
	 * Returns the profile information of the specified user
	 * @see trunk/root/app/controllers/components/SNPAPI#getProfile($params, $user)
	 */
	public function getProfile($params, $user = null) {
		$elements = (array)$this->oauth->get('users/' . $user . '/profile');
		$retval = '<b>Name</b>: ' . $elements['basicprofile']->name . '<br />';
		$retval .= '<b>Location</b>: ' . $elements['city'] . ', ' . $elements['region'] . '<br />';
		$retval .= '<b>Gender</b>: ' . $elements['gender'] . '<br />';
		$retval .= '<b>Age</b>: ' . $elements['age'] . '<br />';
		$retval .= '<b>Profile Picture</b>: <br /><img src="' .
					$elements['basicprofile']->image . '" /><br />';  
		return $retval;
	}
	
	/**
	 * Return a list of friends belonging to the user
	 * @param $username
	 * @param $password
	 * @return array of stdClass objects
	 */
	public function getFriends($params) {
		$friends = (array)$this->oauth->get('users/' . $params . '/friends');
		$retval = '';
		$obj = $friends['Friends'];
		foreach ($obj as $friend) {
			$retval .= '<a href="' . $friend->uri . '">' . $friend->name .
						'</a><br /><img src="' . $friend->image . '" /><br /><br />';
		}
		return $retval;
	}

	/**
	 * Returns the latest posts by the current user
	 * @param $params
	 * @param $user
	 * @return array of stdClass objects
	 */
	public function getPosts($params, $user = null) {
		// UNIMPLEMENTED
	}
	
	/**
	 * Posts a new update for the user.
	 * @param $params
	 * @param $status
	 * @return unknown_type
	 */
	public function updateStatus($params, $status) {
		return null;
		//return $this->oauth->put('users/' . $params . '/status', array('status' => $status));
	}
	
	/**
	 * Retrieves the user's most current status.
	 * @see trunk/root/app/controllers/components/SNPAPI#getCurrentStatus($params, $user)
	 */
	public function getCurrentStatus($params, $user = null) {
		$status = (array)$this->oauth->get('users/' . $user . '/status');
		return '<a href="' . $status['user']->uri . '">' . $status['user']->name .
					'</a>: "' . $status['status'] . '"';
	}
	
	/**
	 * As far as I know, MySpace does not have a public timeline, so
	 * we instead redirect to getCurrentStatus
	 * @see trunk/root/app/controllers/components/SNPAPI#getTimeline($params)
	 */
	public function getTimeline($params, $user) {
		// UNIMPLEMENTED
	}
	
	/**
	 * Private factory method for churning out new NetworkOAuth instances
	 * @param $token
	 * @param $tokenSecret
	 * @return object
	 */
	private static function factory($token = null, $tokenSecret = null) {
		return new NetworkOAuth('d456737ce3504b9caf95b4a9e038c342',
    							'2c2b77a727d246949950a8a687dece174ad6cbc77e274e8eb70a1e8b3ec19fe4',
    							'http://api.myspace.com/v1/',
    							'http://api.myspace.com/request_token',
    							'http://api.myspace.com/authorize',
    							'http://api.myspace.com/access_token',
								true,
								($token ? $token : null),
								($tokenSecret ? $tokenSecret : null));
	}
}

?>