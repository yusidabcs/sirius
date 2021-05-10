<?php

namespace core\app\classes\geoip;

class geoip {
	
	private $_type;
	private $_geoArray;
	
	public function __construct($type,$ipAddress)
	{
		//we need type to know which database to use
		$acceptableTypes = array('city','country');
		
		if(in_array($type, $acceptableTypes))
		{
			$this->_type = $type;
			
			if($type == 'city')
			{
				$databaseFile = DIR_LIB.'/geoip/GeoLite2-City.mmdb';
			} else {
				$databaseFile = DIR_LIB.'/geoip/GeoLite2-Country.mmdb';
			}
			
		} else {
			$msg = "$type is not a valid type";
			throw new \RuntimeException($msg); 
		}
		
		if(filter_var($ipAddress, FILTER_VALIDATE_IP)) 
		{
		    //now we can load the database
			$reader = new \lib\maxminddb\reader($databaseFile);
			$this->_geoArray = $reader->get($ipAddress);
			$reader->close();
			
		} else {
		    $msg = "$ip is not a valid IP address";
			throw new \RuntimeException($msg); 
		}
	
		
        return;
	}
	
	public function getLocationDetails()
	{
		$out = '';
		
		if( isset($this->_geoArray['country']['names']['en']) )
		{
			$out = $this->_geoArray['country']['names']['en'];
			
			if($this->_type == 'city')
			{
				$out .= isset($this->_geoArray['subdivisions'][0]['names']['en']) ? ', '.$this->_geoArray['subdivisions'][0]['names']['en'] : '' ;
				$out .= isset($this->_geoArray['city']['names']['en']) ? ', '.$this->_geoArray['city']['names']['en'] : '' ;
				$out .= isset($this->_geoArray['location']['accuracy_radius']) ? ' [ accuracy: '.$this->_geoArray['location']['accuracy_radius'].' km ]' : '' ;
			}
			
		} else {
			$out = 'UNKNOWN';
		}
		
		return $out;
		
	}
	
	public function getGeoArray()
	{
		/**
		
		CITY
		
		Array
		(
		    [city] => Array
		        (
		            [geoname_id] => 2169237
		            [names] => Array
		                (
		                    [en] => Deagon
		                )
		
		        )
		
		    [continent] => Array
		        (
		            [code] => OC
		            [geoname_id] => 6255151
		            [names] => Array
		                (
		                    [de] => Ozeanien
		                    [en] => Oceania
		                    [es] => Oceanía
		                    [fr] => Océanie
		                    [ja] => オセアニア
		                    [pt-BR] => Oceania
		                    [ru] => Океания
		                    [zh-CN] => 大洋洲
		                )
		
		        )
		
		    [country] => Array
		        (
		            [geoname_id] => 2077456
		            [iso_code] => AU
		            [names] => Array
		                (
		                    [de] => Australien
		                    [en] => Australia
		                    [es] => Australia
		                    [fr] => Australie
		                    [ja] => オーストラリア
		                    [pt-BR] => Austrália
		                    [ru] => Австралия
		                    [zh-CN] => 澳大利亚
		                )
		
		        )
		
		    [location] => Array
		        (
		            [accuracy_radius] => 100
		            [latitude] => -27.3333
		            [longitude] => 153.0667
		            [time_zone] => Australia/Brisbane
		        )
		
		    [postal] => Array
		        (
		            [code] => 4017
		        )
		
		    [registered_country] => Array
		        (
		            [geoname_id] => 2077456
		            [iso_code] => AU
		            [names] => Array
		                (
		                    [de] => Australien
		                    [en] => Australia
		                    [es] => Australia
		                    [fr] => Australie
		                    [ja] => オーストラリア
		                    [pt-BR] => Austrália
		                    [ru] => Австралия
		                    [zh-CN] => 澳大利亚
		                )
		
		        )
		
		    [subdivisions] => Array
		        (
		            [0] => Array
		                (
		                    [geoname_id] => 2152274
		                    [iso_code] => QLD
		                    [names] => Array
		                        (
		                            [en] => Queensland
		                            [pt-BR] => Queensland
		                            [ru] => Квинсленд
		                        )
		
		                )
		
		        )
		
		)
		**/
		
		/**
			
		COUNTRY 
		
		Array
		(
		    [continent] => Array
		        (
		            [code] => OC
		            [geoname_id] => 6255151
		            [names] => Array
		                (
		                    [de] => Ozeanien
		                    [en] => Oceania
		                    [es] => Oceanía
		                    [fr] => Océanie
		                    [ja] => オセアニア
		                    [pt-BR] => Oceania
		                    [ru] => Океания
		                    [zh-CN] => 大洋洲
		                )
		
		        )
		
		    [country] => Array
		        (
		            [geoname_id] => 2077456
		            [iso_code] => AU
		            [names] => Array
		                (
		                    [de] => Australien
		                    [en] => Australia
		                    [es] => Australia
		                    [fr] => Australie
		                    [ja] => オーストラリア
		                    [pt-BR] => Austrália
		                    [ru] => Австралия
		                    [zh-CN] => 澳大利亚
		                )
		
		        )
		
		    [registered_country] => Array
		        (
		            [geoname_id] => 2077456
		            [iso_code] => AU
		            [names] => Array
		                (
		                    [de] => Australien
		                    [en] => Australia
		                    [es] => Australia
		                    [fr] => Australie
		                    [ja] => オーストラリア
		                    [pt-BR] => Austrália
		                    [ru] => Австралия
		                    [zh-CN] => 澳大利亚
		                )
		
		        )
		
		)
		**/
		
		
		return $out;
	}
	
}
?>