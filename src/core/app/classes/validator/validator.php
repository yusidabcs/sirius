<?php namespace core\app\classes\validator;
class validator {

    protected $objToValidate;
    private $validationErrors = [];
    private $defaultErrorMsg;
    private $request;

    // types of predefined validators:
    const REQUIRED = 'required';
    const INT = 'int';
    const EMAIL = 'email';
    const NUMBER = 'number';
    const POSITIVE_NUMBER = 'positive_number';
    const ALPANUM = 'alphanum';
    const LENGTH = 'length';
    const MIN = 'min';
    const MAX = 'max';

    public function __construct($request, $rules = [], $messages= []) {

        //we need system regsiter in multiple methods
        $system_register_ns = NS_APP_CLASSES.'\\system_register\\system_register';
        $this->system_register = $system_register_ns::getInstance();

        $this->defaultErrorMsg = array(
            self::REQUIRED => "The :attribute must be filled in",
            self::INT => "The :attribute must integer",
            self::EMAIL => "The : attribute must in e-mail address format",
            self::NUMBER => "The :attribute Number is required",
            self::POSITIVE_NUMBER => "The :field must be positive number",
            self::ALPANUM => "The :attribute must be alphabet and number only",
            self::LENGTH => "The size of :attribute should be :length ",
            self::MIN => "The size of :attribute should be more than :length",
            self::MAX => "The size of :attribute should be max :length",
        );

        $this->request = $request;
        foreach ($rules as $key => $rule){
            $this->validateField($key,$rule);
        }
    }


    public function validateField($fieldName, $type, $message = null, $params = null) {
        if(!isset($this->request[$fieldName])){
            $this->addError($fieldName,$this->defaultErrorMsg[self::REQUIRED], $params);
            return;
        }else{
            $data = $this->request[$fieldName];
            $types = explode('|',$type);
            foreach ($types as $type){
                $rule = explode(':',$type);
                $type = $rule[0];
                $params = isset($rule[1]) ? $rule[1] : null;
                if(method_exists($this,$type)){
                    $rs = $this->$type($data, $params);
                    if(!$rs){
                        $this->addError($fieldName,$message == null ? $this->defaultErrorMsg[$type] : $message, $params);
                    }
                }

            }
        }
    }

    private function required($value, $params){
        if(is_array($value))
            return count($value) > 0;
        else
            return $value != '';

    }
    private function int($value, $params){
        $regex = '/^([0-9]+)$/u';
        return ($value != '' && preg_match($regex, $value));
    }
    private function email($value, $params){
        $regex = '/^([a-zA-Z0-9_.-]+@[a-zA-Z0-9-]+.[a-zA-Z0-9-.]+[.]+[a-z-A-Z])$/u';
        return ($value != '' && preg_match($regex, $value));
    }
    private function aplhanum($value, $params){
        $regex = '/^([\p{L}0-9]+)$/u';
        return ($value != '' && preg_match($regex, $value));
    }
    private function length($value, $params){
        $length = $this->stringLength($value);
        return ($length !== false) && $length == $params;
    }
    private function min($value, $params){
        if(is_array($value))
            $length = count($value);
        else
            $length = $this->stringLength($value);
        return ($length !== false) && $length >= $params;
    }
    private function max($value, $params){
        $length = $this->stringLength($value);
        return ($length !== false) && $length <= $params;
    }

    public function addError($fieldName, $message, $params) {

        $humanread_label = $this->system_register->site_term($fieldName);
        if(strpos($humanread_label, 'TERM_NOT_SET') !== false){
            $humanread_label = $fieldName;
        }
        if(empty($this->request[$fieldName])){
            $message = str_replace(':value', '', $message);
        }else{
            $message = str_replace(':value', $this->request[$fieldName], $message);
        }

        $message = str_replace(':attribute', $humanread_label, $message);
        $message = str_replace(':length', $params, $message);
        $this->validationErrors[$fieldName] = $message;
    }

    public function getValidationErrors() {
        return [
          'errors' => $this->validationErrors
        ];
    }

    public function hasErrors() {
        return count($this->validationErrors) > 0 ? true : false;
    }

    protected function stringLength($value)
    {
        if (is_int($value)) {
            return $value;
        } elseif (function_exists('mb_strlen')) {
            return mb_strlen($value);
        }

        return strlen($value);
    }
}