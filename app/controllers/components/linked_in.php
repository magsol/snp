<?php

include_once('snpapi.php');

/**
 * Encapsulates the LinkedIn API.
 *
 * @author Shannon Quinn
 * @package
 */

class LinkedInComponent extends Object implements SNPAPI {
	
	/** the oauth object */
	private $oauth;
	
	/** name of this object */
	private $name = 'LinkedIn';

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
		return $this->oauth->getRequestToken('POST', $callback);
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
		return $this->oauth->getAccessToken('POST', $verifier);
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
		$this->oauth = LinkedInComponent::factory($token, $tokenSecret);
	}
	
	/**
	 * Returns an array with the user's username and account-specific
	 * UID for the social networking site.
	 * @see trunk/root/app/controllers/components/SNPAPI#getUsernameAndUID()
	 */
	public function getUsernameAndUID() {
		$retval = array();
		$xml = $this->oauth->get('people/~', array(), true);
		// since linkedin is a BITCH and returns everything in XML,
		// we need to parse this shit out
		$name = $this->getContentInTag($xml, 'first-name');
		$retval['uid'] = $retval['username'] = ($name ? $name : '(ERROR)');
		return $retval;
	}
	
	/**
	 * Returns the profile information of the specified user
	 * @see trunk/root/app/controllers/components/SNPAPI#getProfile($params, $user)
	 */
	public function getProfile($params, $user = null) {
		$xml = $this->oauth->get('people/~:(first-name,last-name,location,' .
											'headline,' .
											'member-url-resources,picture-url)', 
											array(), true);
		$retval = '<b>Name</b>: ' . $this->getContentInTag($xml, 'first-name') . 
					' ' . $this->getContentInTag($xml, 'last-name') . '<br />';
		$retval .= '<b>Headline</b>: ' . $this->getContentInTag($xml, 'headline') . '<br />';
		$location = $this->getContentInTag($xml, 'name');
		$retval .= '<b>Location</b>: ' . $location . '<br />';
		$website = $this->getContentInTag($xml, 'url');
		$retval .= '<b>Website</b>: <a href="' . $website . '">' . $website .
					'</a><br />';
		$retval .= '<b>Profile Picture</b>: <br /><img src="' . 
					$this->getContentInTag($xml, 'picture-url') . '" /><br />';
		return $retval;
	}
	
	/**
	 * Return a list of friends belonging to the user
	 * @param $username
	 * @param $password
	 * @return array of stdClass objects
	 */
	public function getFriends($params) {
		$xml = $this->oauth->get('people/~/connections', array(), true);
		
		// we need all the matches here...
		$matches = array();
		preg_match_all('/<person>([\s\S\w\W]*?)<\/person>/', $xml, $matches);
		$retval = '';
		foreach ($matches[1] as $person) {
			// now we can pull out individual elements from each
			// get the person's name
			$name = $this->getContentInTag($person, 'first-name') . ' ' . 
					$this->getContentInTag($person, 'last-name');
			
			// get the profile link
			$match = array();
			preg_match('/<site-standard-profile-request>[^<]*?<url>(.*?)<\/url>/', $person, $match);
			$link = $match[1];
			
			// get their headline
			$headline = $this->getContentInTag($person, 'headline');
			
			// build the reference
			$retval .= '<a href="' . $link . '">' . $name . '</a>: ' . $headline . '<br />';
		}
		
		// all done
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
		// NOTE: The LinkedIn API for this functionality is all but entirely
		// unhelpful, so in the time remaining this cannot be finished
		/*
		return $this->oauth->put('people/~/current-status', 
								(array('<?xml version="1.0" encoding="UTF-8"?>' .
								'<current-status>' .  $status . '</current-status>')), 
								true);
		*/
	}
	
	/**
	 * Returns the current status. Will need to append a name on here...
	 * @see trunk/root/app/controllers/components/SNPAPI#getCurrentStatus($params, $user)
	 */
	public function getCurrentStatus($params, $user = null) {
		$xml = $this->oauth->get('people/~', array(), true);
		$result = $this->oauth->get('people/~:(current-status)', array(), true);
		$url = urldecode($this->getContentInTag($xml, 'url'));
		return '<a href="' . $url . '">' . $this->getContentInTag($xml, 'first-name') .
					' ' . $this->getContentInTag($xml, 'last-name') . '</a> ' .
					$this->getContentInTag($result, 'current-status');
					
	}
	
	/**
	 * Returns the public timeline for all friends of this user.
	 * @see trunk/root/app/controllers/components/SNPAPI#getTimeline($params, $user)
	 */
	public function getTimeline($params, $user) {
		// UNIMPLEMENTED
	}
	
	/**
	 * Private helper function, given that LinkedIn likes to screw everyone
	 * and provide XML return values whether you like it or not. This 
	 * extracts information from base tags using RegEx.
	 * 
	 * @param $xml
	 * @param $tagname
	 * @return
	 */
	private function getContentInTag($xml, $tagname) {
		$matches = array();
		$num = preg_match('/<' . $tagname . '>(.*?)<\/' . $tagname . '>/',
							$xml, $matches);
		return ($num > 0 ? $matches[1] : null);
	}
	
	/**
	 * Private factory method for churning out new NetworkOAuth instances
	 * @param $token
	 * @param $tokenSecret
	 * @return object
	 */
	private static function factory($token = null, $tokenSecret = null) {
		return new NetworkOAuth('KZMJ0mNNiphY9puY2EAkJdTgitau_mc3N0N8jI7J50m0wCJ3G5k4tTi7Zk3j1xbE',
    							'6h43EVi5XlVL4jbLg3AxJY05dQEh5C19ssBAFk-WjokDETO8ew-UNuGMSeKQ3Bg1',
    							'https://api.linkedin.com/v1/',
    							'https://api.linkedin.com/uas/oauth/requestToken',
    							'https://api.linkedin.com/uas/oauth/authorize',
    							'https://api.linkedin.com/uas/oauth/accessToken',
								false,
								($token ? $token : null),
								($tokenSecret ? $tokenSecret : null));
	}
}

?>