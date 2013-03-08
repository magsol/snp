<?php

include_once('networkoauth.php');

/**
 * Provides a basic interface for all social network APIs.
 *
 * @author Shannon Quinn
 * @package
 */

interface SNPAPI {
	
	/* rebuilds the instance */
	public function reset($token = null, $tokenSecret = null);
	
	/* OAuth methods */
	public function getRequestToken($callback = null);
	public function getAuthorizeURL($token, $callback = null);
	public function getAccessToken($verifier = false);
	public function lastHttpCode();
	
	/* Core API calls */
	public function getUsernameAndUID();
	public function getProfile($params, $user = null);
	public function getFriends($params);
	public function getPosts($params, $user = null);
	public function updateStatus($params, $status);
	public function getCurrentStatus($params, $user = null);
	public function getTimeline($params, $user);

}

?>