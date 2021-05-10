<?php
	
	require_once 'main.php';
	
	$output_type = $_GET['t'];
	$file_manager_id = $_GET['f'];
	$item_size = isset($_GET['s']) ? $_GET['s'] : '';
	$given_name = isset($_GET['n']) ? $_GET['n'] : '';
	
	$acceptable_types_a = array('image','file','icon');
	
	if( !in_array($output_type,$acceptable_types_a) ) 
	{
		die('That just does not do it for me!');
	}
	
	/**
	 * File means output the file as a downloadable file (whatever it is)
	 * Image means output the file as an image to the screen (only good for images or course)
	 * Icon means output the icon of whatever the thing is .. so an icon for jpg, txt, pdf etc.
	 **/
	 
	//start the session
	session_start();
	
	try {
		
		//special output just for gencode
		if($file_manager_id == 'gencode')
		{
			require DIR_LIB.'/gencode/gencode.php';
			exit(0);
		}
		
		//load file manager object
		$file_manager_ns = NS_APP_CLASSES.'\\file_manager\\file_manager';
		$file_manager = $file_manager_ns::getInstance();
		
		//should throw error if bad
		$fileInfo_a = $file_manager->getFileInfo($file_manager_id);
		
		//pull out if there is not fileInfo_a
		if( empty($fileInfo_a) )
		{
			$htmlpage_ns = NS_HTML.'\\htmlpage';
			$htmlpage = new $htmlpage_ns(404);
			exit();
		}
		
		//set the mime_type of the file on the system
		$mime_type = $fileInfo_a['mime_type'];
		
		/**
		 * Common and quick ones that I have better icons for
		 **/
		$mime_map = array(
            'text/x-comma-separated-values'                                             => 'csv',
            'text/comma-separated-values'                                               => 'csv',
            'application/vnd.msexcel'                                                   => 'csv',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
            'image/gif'                                                                 => 'gif',
            'application/x-gzip'                                                        => 'gz',
            'image/x-icon'                                                              => 'ico',
            'image/x-ico'                                                               => 'ico',
            'image/vnd.microsoft.icon'                                                  => 'ico',
            'text/calendar'                                                             => 'ics',
            'image/jpg'                                                                 => 'jpg',
            'image/jpeg'                                                                => 'jpg',
            'image/pjpeg'                                                               => 'jpg',
            'text/x-log'                                                                => 'log',
            'audio/midi'                                                                => 'midi',
            'video/quicktime'                                                           => 'mov',
            'audio/mpeg'                                                                => 'mp3',
            'audio/mpg'                                                                 => 'mp3',
            'audio/mpeg3'                                                               => 'mp3',
            'audio/mp3'                                                                 => 'mp3',
            'video/mp4'                                                                 => 'mp4',
            'video/mpeg'                                                                => 'mpeg',
            'application/pdf'                                                           => 'pdf',
            'application/octet-stream'                                                  => 'pdf',
            'image/png'                                                                 => 'png',
            'image/x-png'                                                               => 'png',
            'application/powerpoint'                                                    => 'ppt',
            'application/vnd.ms-powerpoint'                                             => 'ppt',
            'application/vnd.ms-office'                                                 => 'ppt',
            'application/msword'                                                        => 'ppt',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
            'application/x-photoshop'                                                   => 'psd',
            'image/vnd.adobe.photoshop'                                                 => 'psd',
            'audio/x-realaudio'                                                         => 'ra',
            'audio/x-pn-realaudio'                                                      => 'ram',
            'application/x-rar'                                                         => 'rar',
            'application/rar'                                                           => 'rar',
            'application/x-rar-compressed'                                              => 'rar',
            'audio/x-pn-realaudio-plugin'                                               => 'rpm',
            'application/x-pkcs7'                                                       => 'rsa',
            'video/vnd.rn-realvideo'                                                    => 'rv',
            'application/x-tar'                                                         => 'tar',
            'application/x-gzip-compressed'                                             => 'tgz',
            'image/tiff'                                                                => 'tiff',
            'text/plain'                                                                => 'txt',
            'audio/x-wav'                                                               => 'wav',
            'audio/wave'                                                                => 'wav',
            'audio/wav'                                                                 => 'wav',
            'video/x-ms-wmv'                                                            => 'wmv',
            'video/x-ms-asf'                                                            => 'wmv',
            'application/excel'                                                         => 'xls',
            'application/msexcel'                                                       => 'xls',
            'application/x-msexcel'                                                     => 'xls',
            'application/x-ms-excel'                                                    => 'xls',
            'application/x-excel'                                                       => 'xls',
            'application/x-dos_ms_excel'                                                => 'xls',
            'application/xls'                                                           => 'xls',
            'application/x-xls'                                                         => 'xls',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
            'application/vnd.ms-excel'                                                  => 'xlsx',
            'application/xml'                                                           => 'xml',
            'text/xml'                                                                  => 'xml',
            'application/x-zip'                                                         => 'zip',
            'application/zip'                                                           => 'zip',
            'application/x-zip-compressed'                                              => 'zip',
            'application/s-compressed'                                                  => 'zip',
            'multipart/x-zip'                                                           => 'zip'
        );
        		
		//set wp_icon extension (if it exists)
		if(isset($mime_map[$mime_type]))
		{
			$wp_icon = $mime_map[$mime_type];
		} else {
			$wp_icon = false;
		}
		
		switch ($output_type) 
		{
		    case 'image':
		        
		        $acceptable_size_a = array('thumb','page','large');
		        
				if( in_array($item_size, $acceptable_size_a) )
				{
					$file_to_output = DIR_LOCAL_UPLOADS.'/file_manager/'.$fileInfo_a['dir'].'/'.$file_manager_id.'-'.$item_size;
				} else {
					$file_to_output = DIR_LOCAL_UPLOADS.'/file_manager/'.$fileInfo_a['dir'].'/'.$file_manager_id;
				}
					        
		        break;
		        
		    case 'icon':
		    
		    	if($wp_icon)
		    	{
			    	$acceptable_size_a = array('16x16','24x24','48x48','64x64','128x128');
			    	
			    	if( in_array($item_size, $acceptable_size_a) )
			    	{
				    	$file_to_output = DIR_GENERAL_LIB.'/icons_extensions_wp/'.$wp_icon.'-icon-'.$item_size.'.png';	
			    	} else {
			    		$file_to_output = DIR_GENERAL_LIB.'/icons_extensions_wp/'.$wp_icon.'-icon-64x64.png';
			    	}
		    	
		    	} else {
		        
					//replace the / with a - in the mime type to get the file name
					$icon_file_name = str_replace('/', '-', $mime_type);
					
					//file location
					$acceptable_size_a = array('16x16','22x22','24x24','32x32','64x64');
					
					//First we see if it is in the wp set
					if( in_array($item_size, $acceptable_size_a) )
					{
						$file_to_output = DIR_GENERAL_LIB.'/icons_mimetypes/'.$item_size.'/'.$icon_file_name.'.png';
					} else {
						$file_to_output = DIR_GENERAL_LIB.'/icons_mimetypes/64x64/'.$icon_file_name.'.png';
					}
					
					//if we have a mime_type icon then we will output it
					if(!is_file($file_to_output))
					{
						$file_to_output = DIR_GENERAL_LIB.'/icons_extensions/png/'.$fileInfo_a['original_ext'].'.png';
					}
						
				}
				
				//ok final fallback
				if(!is_file($file_to_output))
				{
					$file_to_output = DIR_GENERAL_LIB.'/icons_picol/png/document15.png';
				}
				
				//The mime type is always png
				$mime_type = 'image/png';
			
		        break;
		        
		    case 'file':
		    
		    	//let the given name overwrite the name on file 
				if( empty($given_name) )
				{	
					$full_file_name = $fileInfo_a['file_name'];
				} else {
					$full_file_name = $given_name;
				}
				
				$file_to_output = DIR_LOCAL_UPLOADS.'/file_manager/'.$fileInfo_a['dir'].'/'.$file_manager_id;
			
		        break;
		}
	
		//output the file header
		header("Content-Type: $mime_type");
		
		//if outputing as a file then we need to say attachment and name
		if($output_type == 'file')
		{
			header("Content-Disposition: attachment; filename=\"$full_file_name\"");
		}
		
		//last check if the file is readable (it should be!
		if(is_readable($file_to_output))
		{
			readfile($file_to_output);
		} else {
			$msg = "Trying to output file but it was not readable! \n\nFile Information Array<pre>\n";
			$msg .= print_r($fileInfo_a,true);
			$msg .= print_r($_GET,true);
			$msg .= print_r($_POST,true);
			$msg .= "\n</pre>\n<strong>The File Is NOT Readable!</strong>\n";
			throw new \RuntimeException($msg); 
		}
		
	} catch (Exception $e) {
        //process the error
        $htmlmsg_ns = NS_HTML.'\htmlmsg';
        $htmlOutput = new $htmlmsg_ns ($e,DEBUG);
        header("HTTP/1.0 500 Internal Error");
        echo $htmlOutput->getHtmlOutput();
        exit();
    }
    
?>