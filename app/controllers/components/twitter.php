<?php

include_once('snpapi.php');

/**
 * Encapsulates the Twitter API.
 *
 * @author Shannon Quinn
 * @package
 */

class TwitterComponent extends Object implements SNPAPI {
	
	/** the oauth object */
	private $oauth;
	
	/** name of this object */
	private $name = 'Twitter';

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
		return $this->oauth->getRequestToken('GET', $callback);
	}
	
	/**
	 * Returns the authorization URL to which the application must redirect the user.
	 * 
	 * @return string
	 * @see trunk/root/app/controllers/components/SNPAPI#getAuthorizeURL($token)
	 */
	public function getAuthorizeURL($token, $callback = null) {
		return $this->oauth->getAuthorizeURL($token);
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
		$this->oauth = TwitterComponent::factory($token, $tokenSecret);
	}
	
	/**
	 * Returns an array with the user's username and account-specific
	 * UID for the social networking site.
	 * @see trunk/root/app/controllers/components/SNPAPI#getUsernameAndUID()
	 */
	public function getUsernameAndUID() {
		$retval = array();
		$arr = (array)$this->oauth->get('account/verify_credentials');
		$retval['username'] = $arr['screen_name'];
		$retval['uid'] = $arr['id'];
		return $retval;
	}
	
	/**
	 * Returns the profile information of the specified user
	 * @see trunk/root/app/controllers/components/SNPAPI#getProfile($params, $user)
	 */
	public function getProfile($params, $user = null) {
		$elements = (array)$this->oauth->get('account/verify_credentials');
		$retval = '<b>Name:</b> ' . $elements['name'] . '<br />';
		$retval .= '<b>Description:</b> ' . $elements['description'] . '<br />';
		$retval .= '<b>Friends</b>: ' . $elements['friends_count'] . '<br />';
		$retval .= '<b>Website</b>: <a href="' . $elements['url'] . '">' .
					$elements['url'] . '</a><br />';
		$retval .= '<b>Location</b>: ' . $elements['location'] . '<br />';
		$retval .= '<b>Profile Image</b>:<br /><img src="' .
					$elements['profile_image_url'] . '" /><br />';
		return $retval; 
	}
	
	/**
	 * Return a list of friends belonging to the user
	 * @param $username
	 * @param $password
	 * @return array of stdClass objects
	 */
	public function getFriends($params) {
		$friends = $this->oauth->get('statuses/friends');
		$retval = '';
		foreach ($friends as $friend) {
			// convert to array; these arrive in stdClass format
			$friend = (array)$friend;
			$retval .= '<a href="http://twitter.com/' . $friend['screen_name'] .
						'">' . $friend['screen_name'] . '</a>: "' . 
						$friend['status']->text . '"<br />';
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
		$retval = (array)$this->oauth->post('statuses/update', array('status' => $status));
		return $retval;
	}
	
	/**
	 * Returns the latest status by this user.
	 * @see trunk/root/app/controllers/components/SNPAPI#getCurrentStatus($params, $user)
	 */
	public function getCurrentStatus($params, $user = null) {
		$response = (array)$this->oauth->get(('users/show/' . $user));
		return '<a href="http://twitter.com/' . $response['screen_name'] . '">' .
				$response['screen_name'] . '</a>: "' . $response['status']->text .'"';
	}
	
	/**
	 * Returns the entire public timeline for this user
	 * @see trunk/root/app/controllers/components/SNPAPI#getTimeline($params, $user)
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
		return new NetworkOAuth('49ojgvfEDzivlc1zi3G7g',
    							'fj1FAOK9DmjiYWweTh1m2VsGZgoZzJpJj4dASDE9g',
    							'http://twitter.com/',
    							'https://twitter.com/oauth/request_token',
    							'https://twitter.com/oauth/authenticate',
    							'https://twitter.com/oauth/access_token',
								true,
								($token ? $token : null),
								($tokenSecret ? $tokenSecret : null));
	}
}

?>