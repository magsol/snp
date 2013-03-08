<?php

include_once('config/database.php');

/**
 * Provides some custom error-handling, specifically for the case of
 * missing database tables to allow for their initialization when
 * the application is first installed and run.
 *
 * @author Shannon Quinn
 * @package
 */

class AppError extends ErrorHandler {
	
	public $URL = 'http://localhost/final637/trunk/root/';
	
	/**
	 * Overridden missingTable error, used to generate and populate the tables.
	 * 
	 * @param $params
	 * @see trunk/root/cake/console/ErrorHandler#missingTable($params)
	 */
	public function missingTable($params) {
		extract($params, EXTR_OVERWRITE);

		// create and populate the database tables
		if (($result = $this->initializeDatabase()) === true) {
			// success! show the usual stuff
			$this->controller->set(array(
				'title' => __('Application Installed', true),
				'link' => $this->URL,
			));
			$this->_outputMessage('tablesInstalled');
		} else {
			// error occurred while performing the query
			$this->controller->set(array(
				'model' => $className,
				'table' => $table,
				'msg' => $result,
				'title' => __('Missing Database Table ', true)
			));
			$this->_outputMessage('missingTable');
		}
	}
	
	/**
	 * Initializes the database tables and populates them with initial data.
	 * 
	 * @return mixed Boolean true on success, error message on failure.
	 */
	private function initializeDatabase() {
		
		// create the database
		$params = new DATABASE_CONFIG();
		$db = @mysql_pconnect($params->host, $params->login, $params->password);
		if (!$db) {
			return mysql_error();
		}
		
		// we have a connection, set the database
		mysql_select_db('final637');
		
		// setup and perform the queries
		$users = 'CREATE TABLE IF NOT EXISTS `final637`.`snp_users` (' .
				'`uid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,' .
				'`username` VARCHAR( 50 ) NOT NULL ,' .
				'`password` VARCHAR( 50 ) NOT NULL ,' .
				'`created` DATETIME NOT NULL ,' .
				'`last_login` DATETIME NOT NULL ,' .
				'`public` ENUM( \'YES\', \'NO\' ) NOT NULL)';
		/*
		$accounts = 'CREATE TABLE IF NOT EXISTS `final637`.`snp_accounts` (' .
				'`aid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,' .
				'`name` VARCHAR( 50 ) NOT NULL ,' .
				'`customerkey` VARCHAR( 200 ) NOT NULL ,' .
				'`secretkey` VARCHAR( 200 ) NOT NULL ,' .
				'`url` VARCHAR( 100 ) NOT NULL)';
		$populateaccounts = 'INSERT INTO `final637`.`snp_accounts` (' .
				'`name`, `customerkey`, `secretkey`, `url`) VALUES ' .
				'("MySpace", "d456737ce3504b9caf95b4a9e038c342", "2c2b77a727d246949950a8a687dece174ad6cbc77e274e8eb70a1e8b3ec19fe4", "http://"), ';
				'("Facebook", "af02426475d37cb754e52385c2b35530", "43c1ef9177d0562b3dd28b0cd4c3ced6", "http://www.facebook.com/login?")';
		*/
		//$usersaccounts = 'CREATE TABLE IF NOT EXISTS `final637`.`snp_usersaccounts` (' .
		$accounts = 'CREATE TABLE IF NOT EXISTS `final637`.`snp_accounts` (' .
				'`uaid` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, ' .
				'`userid` INT UNSIGNED NOT NULL ,' .
				'`accountname` VARCHAR( 200 ) NOT NULL ,' .
				'`oauth_token` VARCHAR( 400 ) NOT NULL ,' .
				'`oauth_token_secret` VARCHAR( 400 ) NOT NULL ,' .
				'`last_login` DATETIME NOT NULL ,' .
				'`last_view` DATETIME NOT NULL ,' .
				'`last_update` DATETIME NOT NULL';
		/*
		$options = 'CREATE TABLE IF NOT EXISTS `final637`.`snp_options` (' .
				'`key` VARCHAR( 50 ) NOT NULL ,' .
				'`value` VARCHAR( 200 ) NOT NULL ,' .
				'PRIMARY KEY ( `key` ))';
		*/
		// perform the queries
		$result = mysql_query($users) && mysql_query($accounts); /*&&
					mysql_query($populateaccounts) && 
					mysql_query($usersaccounts)  &&
					mysql_query($options); */
		// all done!
		return ($result === true ? $result : mysql_error());
	}
}

?>