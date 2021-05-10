<?php
namespace core\app\classes\file_manager;

/**
 * Final file_manager_image class.
 *
 * Processes Images
 *
 * @final
 * @package 	file manger
 * @author		Martin O'Dee <martin@iow.com.au>
 * @copyright	Martin O'Dee 17 August 2019
 */
final class file_manager_image {
	
	private $messages_register;
	
	//local variables
	protected $saveDir; //the directory to save all the images
	
	private $_src_file;
	private $_dst_file;
	
	private $_max_x;
	private $_max_y; 
	private $_max_qlty; 
	private $_mime_type;
	private $_dst_file_mime_type;

	private $_src_x;
	private $_src_y;
	
	private $_dst_x;
	private $_dst_y;
	
	private $_is_annimated_gif = false;
	
	private $_im; //holds the image for processing

	function __construct()
	{
		return;
	}
	
	function __destruct()
	{
		if(is_resource($this->_im)) 
		{ 
	      imagedestroy($this->_im); 
	    }
	}

    /**
     * @param $src_file
     * @param $mime_type
     * @return bool
     */
    public function setSourceFile($src_file, $mime_type)
	{
		//check it first
        //check if file exist and write able
		if ( !file_exists($src_file) || !is_writable($src_file) )  
		{
			return false;
		}
		
		if(is_resource($this->_im)) 
		{
			imagedestroy($this->_im); 
	    }
	    
	    //now make the image resource based on the mime_type (unless it is an animated gif)
	    $mime_type_a = array('image/jpeg','image/gif','image/png','image/vnd.wap.wbmp');
	    
		if( in_array($mime_type, $mime_type_a ) )
		{
			switch ($mime_type)
			{
		        case 'image/jpeg':
					$this->_im = @imagecreatefromjpeg($src_file);
					$this->_is_annimated_gif = false;
					break;
	
		        case 'image/gif':
		        	//different if it is animated or not!
	    			if($this->_is_animated($src_file))
	    			{
	        			$this->_is_annimated_gif = true;
	    			} else {        
	        			$this->_im = @imagecreatefromgif($src_file);
	        			$this->_is_annimated_gif = false;
	        		}
		        	break;
	
				case 'image/png':
					$this->_im = @imagecreatefrompng($src_file);
					$this->_is_annimated_gif = false;
					break;
					
				case 'image/vnd.wap.wbmp':
					$this->_im = @imagecreatefromwbmp($src_file);
					$this->_is_annimated_gif = false;
					break;
	
		        default:
					return false;
			}
		} else {
			return false;
		}
		
		//ok set _src_file and its parameters.
		$this->_src_file = $src_file;
		
		//check if it even needs to be resized
		list( $this->_src_x, $this->_src_y) = getimagesize($src_file);

		return true;
	}

    /**
     * @param $dst_file
     * @param $mime_type
     * @return bool
     */
    public function setDestinationFile($dst_file, $mime_type)
	{
		$this->_dst_file = $dst_file;
		$this->_dst_file_mime_type = $mime_type;

		return true;
	}

    /**
     * @param $maxX
     * @param $maxY
     * @param $maxQlty
     * @return bool
     */
    public function setParameters($maxX, $maxY, $maxQlty)
	{
		settype($maxX,"integer");
		settype($maxY,"integer");
		settype($maxQlty,"integer");

		$this->_max_x = $maxX;
		$this->_max_y = $maxY;
		$this->_max_qlty = $maxQlty;
		
		$out = true;
		
		if( $this->_max_x < 1 || $this->_max_x > 2560 ) {
			$out = false;
		}

		if( $this->_max_y < 1 || $this->_max_y > 1600 ) {
			$out = false;
		}

		if( $this->_max_qlty < 1 || $this->_max_qlty > 100 ) {
			$out = false;
		}
		
		return $out;
	}
	
	
	/**
	 * Controls the make process
	 *
	 * @return true or false
	 */
	public function makeImage()
	{
		//image does not need to be changed
		if ($this->_src_x <= $this->_max_x && $this->_src_y <= $this->_max_y)
		{
			//no need to change anything (forget about quality)
			if( copy($this->_src_file,$this->_dst_file) )
			{
				//make sure the file has the right settings
				if (is_dir($this->_dst_file.'/www')) {
					@chgrp($this->_dst_file,'www');
					@chmod($this->_dst_file,0660);
				}
				
				return true;
			} else {
				return false;
			}
		} else {
			//the file needs to be resized so we need new dimensions
			$proportion_x = $this->_src_x / $this->_max_x;
			$proportion_y = $this->_src_y /$this->_max_y;
	
			if($proportion_x < $proportion_y)
			{
				$proportion = $proportion_y;
			} else {
				$proportion = $proportion_x;
			}
	
			$this->_dst_x = $this->_src_x / $proportion;
			$this->_dst_y = $this->_src_y / $proportion;

			if($this->_is_annimated_gif)
			{
				if( $this->_reduceAnimatedGif() )
				{
					@chmod($this->_dst_file,0660);
					return true;
				} else {
					return false;
				}
			} else {
				//image needs to be resized
				if( $this->_resizeImage() )
				{
					@chmod($this->_dst_file,0660);
					return true;
				} else {
					return false;
				}
			}
		}
		return false;
	}
	
	private function _resizeImage()
	{
		//check there is an actual memory image
		if(!$this->_im) 
		{
			return false;
		}
		
		//create the new image base
		$new_im = imagecreatetruecolor($this->_dst_x, $this->_dst_y);
		imagealphablending( $new_im, false );
		imagesavealpha( $new_im, true );

		imagecopyresampled($new_im, $this->_im, 0, 0, 0, 0, $this->_dst_x, $this->_dst_y, $this->_src_x, $this->_src_y);
		
		//build the right image
		switch ($this->_dst_file_mime_type)
		{
	        case 'image/jpeg':
				imagejpeg($new_im, $this->_dst_file, $this->_max_qlty);
				break;

	        case 'image/gif':
	        	imagegif($new_im, $this->_dst_file);
	        	break;

			case 'image/png':
				//convert max quality
				$maxQlty = $this->_max_qlty / 10;
				settype($maxQlty, 'integer');
				if($maxQlty > 9) $maxQlty = 9;
				if($maxQlty < 1) $maxQlty = 1;
				imagepng($new_im, $this->_dst_file, $maxQlty);
				break;
				
			case 'image/vnd.wap.wbmp':
				imagewbmp($new_im, $this->_dst_file);
				break;

	        default:
				return false;
		}
		
		imagedestroy($new_im);
		
		if(is_file($this->_dst_file))
		{
			//make sure the file has the right settings
			//@chgrp($this->_dst_file,'www');
			@chmod($this->_dst_file,0660);
			return true;
		}
		
		return false;
	}

	private function _reduceAnimatedGif()
	{
		$image = new \Imagick($this->_src_file); 
	
		$image = $image->coalesceImages(); 
		
		foreach ($image as $frame) { 
		  //$frame->cropImage($crop_w, $crop_h, $crop_x, $crop_y); 
		  $frame->thumbnailImage($this->_dst_x, $this->_dst_y, true); 
		  //$frame->setImagePage($size_w, $size_h, 0, 0); 
		} 
		
		$image = $image->deconstructImages(); 
		$image->writeImages($this->_dst_file, true);
		
		if(is_file($this->_dst_file))
		{
			//make sure the file has the right settings
			@chgrp($this->_dst_file,'www');
			@chmod($this->_dst_file,0660);
			return true;
		}
		
		return false;
	}
	
	private function _is_animated($src_file)
	{
        $filecontents = file_get_contents($src_file);

        $str_loc = 0;
        $count = 0;
        while ($count < 2) # There is no point in continuing after we find a 2nd frame
        {
            $where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
            if ($where1 === FALSE)
            {
                break;
            } else {
                $str_loc=$where1+1;
                $where2=strpos($filecontents,"\x00\x2C",$str_loc);
                if ($where2 === FALSE)
                {
                    break;
                } else {
                    if ($where1+8 == $where2)
                    {
                        $count++;
                    }
                    $str_loc=$where2+1;
                }
            }
        }

        if ($count > 1)
        {
            return true;

        } else {
            return false ;
        }
	}

	
}
?>