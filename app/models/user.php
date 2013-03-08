<?php

include_once('../../cake/libs/controller/components/auth.php');

/**
 * Models the User.
 * 
 * @author Shannon Quinn
 * @package
 */
class User extends AppModel {
	
	/** name of the model */
	public $name = 'User';
	public $primaryKey = 'uid';
	
	/** specifies the data mapping */
	/*
	public $hasAndBelongsToMany = array('Account' => array(
										'className' => 'Account',
										'joinTable' => 'usersaccounts',
										'foreignKey' => 'userid',
										'associationForeignKey' => 'accountid',
										));
	*/
	public $hasMany = array('Account' => array(
								'className' => 'Account',
								'foreignKey' => 'userid',
							));
	/** some validation rules when creating a new user */
	public $validate = array(
							// RULES FOR THE USERNAME
							'username' => array(

								// RULE 1: Must have a length of at least 6 characters
								'rule-1' => array(
									'rule' => array('minLength', '6'),
									'allowEmpty' => false,
									'message' => 'Must be at least 6 characters.',
									'last' => true,
								),
								
								// RULE 2: Must use only alphanumeric characters
								'rule-2' => array(
									'rule' => 'alphaNumeric',
									'allowEmpty' => false,
									'message' => 'Only alphanumeric characters allowed.',
									'last' => true,
								),
								
								// RULE 3: Cannot already exist in the database.
								'rule-3' => array(
									'rule' => 'isUnique',
									'allowEmpty' => false,
									'message' => 'This username has already been taken!',
									'last' => true,
								)
							),
							
							// RULES FOR THE PASSWORD
							'password' => array(
								'identicalFieldValues' => array(
									'rule' => array('identicalFieldValues', 'passwd'),
									'message' => 'Invalid password(s).',
								)
							));

	/**
	 * Performs the comparison against password values to see if they are equal.
	 * @param array $fields
	 * @param string $hashed
	 * @return bool True if they match, false otherwise
	 */
	public function identicalFieldValues($fields = array(), $hashed = null) {
		$password = $fields['password'];
		$passconfirm = $this->data[$this->name][$hashed];

		// do they match?
		return ($password == AuthComponent::password($passconfirm)) && 
				(strlen($passconfirm) >= 6);
	}
}

?>