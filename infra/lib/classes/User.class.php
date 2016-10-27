<?php

/* 
 *
 * This program comes with ABSOLUTELY NO WARRANTY.
 *
 * This is free software, placed under the terms of the GNU
 * General Public License, as published by the Free Software
 * Foundation.  Please see the file COPYING for details.  */

class User {

//Attributs
private $user_login = "";
private static $ldap = null;
private $isAdmin = false;

// Constructeur
function __construct($user_login,$without_rights_evaluation = false /* to limit number of ldap requests : new User("toto",true)*/){
			
	$ldap = User::initLdap();
	if($without_rights_evaluation == false){
		$this->isAdmin=$ldap->user()->inGroup($user_login,AD_AUTHORIZED_GROUP);
	}
	
	$result=$ldap->user()->infoCollection($user_login,array("samaccountname"));
	if (!$result){
		return false;
	}
	$this->user_login = utf8_decode($result->samaccountname);
}

static function userConnection() {
	if(isset($_SERVER['REMOTE_USER'])){
		$user_login = substr($_SERVER['REMOTE_USER'],0,strpos($_SERVER['REMOTE_USER'],"@"));
		$_SESSION['USER'] = new User($user_login);
		$login = $_SESSION['USER']->getUser_login();
		return new User($user_login);
	} else {
		return false;
	}
}

function isAdmin() {
	return $this->isAdmin;
}

function isConnected(){
	if ($this->equals($_SESSION['USER'])){
		return true;
	} else {
		return false;
	}
}

function __toString(){
	return ("<table>
			<tr><td>Login :</td><td>".$this->user_login."</td></tr>
			</table>");
}

//Accesseurs
function getUser_login(){
	return $this->user_login;
}

static function initLdap(){
	if (User::$ldap == null){
			$ldap = new adLDAP();
			User::$ldap = $ldap;
	} else {
			$ldap = User::$ldap;
	}
	return $ldap;
}

function equals($user) {
	$user_login = $user->getUser_login();
	if (strcmp($user_login ,$this->user_login)==0) {
		return true ;
	}
	else {
		return false ;
	}
}

}
?>