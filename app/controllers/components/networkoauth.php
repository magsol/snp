<?php
/*
 * Abraham Williams (abraham@abrah.am) http://abrah.am
 *
 * Basic lib to work with Twitter's OAuth beta. This is untested and should not
 * be used in production code. Twitter's beta could change at anytime.
 *
 * Code based on:
 * Fire Eagle code - http://github.com/myelin/fireeagle-php-lib
 * twitterlibphp - http://github.com/jdp/twitterlibphp
 * 
 * ---
 * 
 * Code has been modified to work with MySpace and LinkedIn OAuth in addition
 * to Twitter.
 */

/* Load OAuth lib. You can find it at http://oauth.net */
require_once('OAuth.php');

/**
 * Network OAuth class
 */
class NetworkOAuth {
  /* Contains the last HTTP status code returned. */
  public $http_code;
  /* Contains the last API call. */
  public $url;
  /* Set timeout default. */
  public $timeout = 30;
  /* Set connect timeout. */
  public $connecttimeout = 30; 
  /* Verify SSL Cert. */
  public $ssl_verifypeer = FALSE;
  /* Respons format. */
  public $format = 'json';
  /* Decode returned json data. */
  //public $decode_json = TRUE;
  public $decode_json;
  /* Contains the last HTTP headers returned. */
  public $http_info;
  /* Set the useragnet. */
  public $useragent = 'OAuth v0.2.0-beta2';
  /* Immediately retry the API call if the response was not successful. */
  //public $retry = TRUE;
  
  /* OAuth URLs */
  public $requestTokenURL;
  public $authorizeURL;
  public $accessTokenURL;
  
    /* Set up the API root URL. */
  //public $host = "https://api.linkedin.com/v1/";
  public $host;
  
  /**
   * Set API URLS
   */
  function accessTokenURL()  { return $this->accessTokenURL; }
  function authorizeURL()    { return $this->authorizeURL; }
  function requestTokenURL() { return $this->requestTokenURL; }

  /**
   * Debug helpers
   */
  function lastStatusCode() { return $this->http_status; }
  function lastAPICall() { return $this->last_api_call; }

  /**
   * Constructor
   * @param $consumer_key Customer key for the application
   * @param $consumer_secret Customer secret for the application
   * @param $host This is the base API URL to make REST calls after authentication
   * @param $reqTokURL Full URL to OAuth request token action
   * @param $authURL Full URL to OAuth authenticate/authorize action
   * @param $accTokURL Full URL to Oauth access token action
   * @param $decide_json If set to true, ".json" will be appended on requests
   * @param $oauth_token Authenticated OAuth token
   * @param $oauth_token_secret Authenticated OAuth token secret
   */
  function __construct($consumer_key, $consumer_secret, $host, 
  						$reqTokURL, $authURL, $accTokURL, $decode_json = TRUE,
  						$oauth_token = NULL, $oauth_token_secret = NULL) {
    $this->sha1_method = new OAuthSignatureMethod_HMAC_SHA1();
    $this->consumer = new OAuthConsumer($consumer_key, $consumer_secret);
    if (!empty($oauth_token) && !empty($oauth_token_secret)) {
      $this->token = new OAuthConsumer($oauth_token, $oauth_token_secret);
    } else {
      $this->token = NULL;
    }
    $this->decode_json = $decode_json;
    
    // set up all the URLs
    $this->host = $host;
    $this->requestTokenURL = $reqTokURL;
    $this->authorizeURL = $authURL;
    $this->accessTokenURL = $accTokURL;
  }


  /**
   * Retrieve a request token that will later be authenticated for API REST calls
   *
   * @param $method HTTP method to use in this step (GET, POST)
   * @param $oauth_callback Callback URL
   * @returns a key/value array containing oauth_token and oauth_token_secret
   */
  function getRequestToken($method = 'GET', $oauth_callback = NULL) {
    $parameters = array();
    if (!empty($oauth_callback)) {
      $parameters['oauth_callback'] = $oauth_callback;
    } 
    $request = $this->oAuthRequest($this->requestTokenURL(), $method, $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * Get the authorize URL
   *
   * @param $token Request token we retrieved from the previous step
   * @param $oauth_callback The callback URL to be redirected to after authentication
   * @returns The same request token, except it will be authenticated for API calls
   */
  function getAuthorizeURL($token, $oauth_callback = NULL) {
    if (is_array($token)) {
      $token = $token['oauth_token'];
    }
    return $this->authorizeURL() . "?oauth_token={$token}" . 
    				($oauth_callback ? '&oauth_callback=' . 
    				urlencode($oauth_callback) : '');
  }

  /**
   * Exchange the request token and secret for an access token and
   * secret, to sign API calls.
   * @param $method HTTP method to use in this step (GET, POST)
   * @param $oauth_verifier
   * @returns array("oauth_token" => the access token,
   *                "oauth_token_secret" => the access secret)
   */
  function getAccessToken($method = 'GET', $oauth_verifier = FALSE) {
    $parameters = array();
    if (!empty($oauth_verifier)) {
      $parameters['oauth_verifier'] = $oauth_verifier;
    }
    $request = $this->oAuthRequest($this->accessTokenURL(), $method, $parameters);
    $token = OAuthUtil::parse_parameters($request);
    $this->token = new OAuthConsumer($token['oauth_token'], $token['oauth_token_secret']);
    return $token;
  }

  /**
   * GET wrappwer for oAuthRequest.
   */
  function get($url, $parameters = array(), $auth_header = FALSE) {
    $response = $this->oAuthRequest($url, 'GET', $parameters, $auth_header);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * POST wreapper for oAuthRequest.
   */
  function post($url, $parameters = array(), $auth_header = FALSE) {
    $response = $this->oAuthRequest($url, 'POST', $parameters, $auth_header);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }
  
  /**
   * PUT wrapper for oAuthRequest
   * @param $url
   * @param $parameters
   * @param $auth_header
   * @return unknown_type
   */
  function put($url, $parameters = array(), $oauth_header = FALSE) {
  	$response = $this->oAuthRequest($url, 'PUT', $parameters, $oauth_header);
  	if ($this->format === 'json' && $this->decode_json) {
  	  return json_decode($response); 
  	}
  	return $response;
  }

  /**
   * DELTE wrapper for oAuthReqeust.
   */
  function delete($url, $parameters = array(), $auth_header = FALSE) {
    $response = $this->oAuthRequest($url, 'DELETE', $parameters, $auth_header);
    if ($this->format === 'json' && $this->decode_json) {
      return json_decode($response);
    }
    return $response;
  }

  /**
   * Format and sign an OAuth / API request
   */
  function oAuthRequest($url, $method, $parameters, $auth_header = FALSE) {
    if (strrpos($url, 'https://') !== 0 && strrpos($url, 'http://') !== 0) {
      $url = "{$this->host}{$url}" . ($this->decode_json ? '.' . $this->format : '');
    }
    
    $request = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, $method, $url, $parameters);
    $request->sign_request($this->sha1_method, $this->consumer, $this->token);
    switch ($method) {
    case 'GET':
      return $this->http($request->to_url(), 'GET', NULL, ($auth_header ? $request->to_header() : NULL));
    default:
      return $this->http($request->get_normalized_http_url(), $method, $request->to_postdata(), ($auth_header ? $request->to_header() : NULL));
    }
  }

  /**
   * Make an HTTP request
   *
   * @return API results
   */
  function http($url, $method, $postfields = NULL, $auth_header = NULL) {
    $this->http_info = array();
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
    curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);

    $t_headers = array('Expect:');
    if ($auth_header != NULL) {
      $url = substr_replace($url, '', strpos($url, '?'));
      $t_headers = array($auth_header);
    }
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
    curl_setopt($ci, CURLOPT_HEADERFUNCTION, array($this, 'getHeader'));
    curl_setopt($ci, CURLOPT_HEADER, FALSE);

    $putdata = false;
    switch ($method) {
      case 'POST':
        curl_setopt($ci, CURLOPT_POST, TRUE);
        if (!empty($postfields)) {
          curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
        }
        break;
      case 'DELETE':
        curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
        if (!empty($postfields)) {
          $url = "{$url}?{$postfields}";
        }
        break;
      case 'PUT':
      	$t_headers[] = 'X-HTTP-Method-Override: PUT';
      	break;
    }
    curl_setopt($ci, CURLOPT_HTTPHEADER, $t_headers);
    curl_setopt($ci, CURLOPT_URL, $url);
    $response = curl_exec($ci);
    $this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    $this->http_info = array_merge($this->http_info, curl_getinfo($ci));
    $this->url = $url;
    if ($putdata !== false) { fclose($putdata); }
    curl_close ($ci);
    return $response;
  }

  /**
   * Get the header info to store.
   */
  function getHeader($ch, $header) {
    $i = strpos($header, ':');
    if (!empty($i)) {
      $key = str_replace('-', '_', strtolower(substr($header, 0, $i)));
      $value = trim(substr($header, $i + 2));
      $this->http_header[$key] = $value;
    }
    return strlen($header);
  }
}
