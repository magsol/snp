<?php

/**
 * Models the Account entity. This tracks specific and instantiated social
 * network accounts that are tied to a specific user.
 * 
 * @author Shannon Quinn 
 * @package
 */
class Account extends AppModel {
	
	public $name = 'Account';
	public $primaryKey = 'uaid';
	
	/** association mapping */
	public $belongsTo = array('User' => array(
								'className' => 'User',
								'foreignKey' => 'userid',
							));
}

?>