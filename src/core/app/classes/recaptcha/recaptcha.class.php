<?php

namespace core\app\classes\recaptcha;

/*
 * Recaptcha class to handle recaptcha token
 *
 */
class recaptcha {
	
	private $_recaptcha_Obj;
	private $_success;
	private $_challenge_ts;
	private $_hostname;
	private $_action;
	private $_score;
	private $_errors;

	/*
	 * Constructor methods
	 * accept recaptcha token and process the token is it valid or not.
	 */
	public function __construct($recaptcha_token)
	{
		$this->_setRecaptchaDetails($recaptcha_token);
        return;
	}
	
	private function _setRecaptchaDetails($recaptcha_token)
	{
		$system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
		$system_register = $system_register_ns::getInstance();
		
		// Build POST request:
	    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
	    $recaptcha_secret = $system_register->site_info('SITE_RECAPTCHA_SECRET');
	    
	    // Make and decode POST request:
	    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_token);
	    $recaptcha_Obj = json_decode($recaptcha);
	    
	    if($recaptcha_Obj->success)
	    {
		    $this->_success = $recaptcha_Obj->success;
			$this->_challenge_ts = $recaptcha_Obj->challenge_ts;
			$this->_hostname = $recaptcha_Obj->hostname;
			$this->_action = $recaptcha_Obj->action;
			$this->_score = $recaptcha_Obj->score;
	    } else {
		    $this->_success = $recaptcha_Obj->success;
			$this->_challenge_ts = '';
			$this->_hostname = '';
			$this->_action = '';
			$this->_score = '';
			$key = 'error-codes';
			$this->_errors = $recaptcha_Obj->$key;
	    }
	    	
	    return;
	}
	
	public function getSucess()
	{
		return $this->_success;
	}
	
	public function getChallengeTs()
	{
		return $this->_challenge_ts;
	}
	
	public function getHostname()
	{
		return $this->_hostname;
	}
	
	public function getAction()
	{
		return $this->_action;
	}
	
	public function getScore()
	{
		return $this->_score;
	}
	
	public function getErrorArray()
	{
		return $this->_errors;
	}
	
}
?>