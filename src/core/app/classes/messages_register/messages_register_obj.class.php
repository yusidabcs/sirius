<?php
/**
* This is the actual messages register object itself.  Changes to its behaviour go here.
* I am going to make this a factory model in case I need more logs in the future
*/
namespace core\app\classes\messages_register;

final class messages_register_obj {

	private $_messages = array(); //the array of the message objects
	private $_critical = false;
	private $_acceptableTypes = array('10','20','30'); //names are in site_config $system_register->site_term('MSG_TITLE_ERROR')
	    
    public function __construct()
    {
    	return;
    }
    
    /**
     * addMessage function.
     * 
     * messages->addMessage($info,$typeId = 30,$source = 'system',$section = 'system',$id = 1)
     *
     * @access public
     * @param mixed $info
     * @param int $typeId (default: 30)
     * @param string $source (default: 'system')
     * @param string $section (default: 'system')
     * @param int $id (default: 1)
     * @return void
     */
    public function addMessage($info,$typeId = 30,$source = 'system',$section = 'system',$id = 1)
    {
    	//confirm accurate type
    	try {

			if(!in_array($typeId, $this->_acceptableTypes))
			{
			    $msg = "A message could not be formed because it did not have a valid type [info = '$info', typeId = '$typeId', source = '$source', section = '$section', id = '$id']";
			    throw new \RuntimeException($msg, E_USER_ERROR);
			}
			
		} catch (\Exception $e) {
			//process the error
			$htmlOutput = new \core\app\classes\html\htmlmsg($e,DEBUG);
			header("HTTP/1.0 500 Internal Error");
			echo $htmlOutput->getHtmlOutput();
			exit();
		}
		
	    if($new_message = new message($info,$typeId,$source,$section,$id) )
	    {
	    	$this->_messages[] = $new_message;
	    	
	    	if($typeId == 10)
	    	{
		    	$this->_critical = true;
	    	}
	    	return true;
	    } else {
		    return false;
	    }
    }
    
    public function haveMessages()
    {
	    if(empty($this->_messages))
	    {
		    return false;
	    } else {
		    return true;
	    }
    }
    
    public function messageCount()
    {
    	$out = count($this->_messageCount);
	    return $out;
    }
    
    private function _getEmptyMessagesHTML($system_register)
    {	
	    $out = <<<"EOD"
	    	<div id="messageDiv">
	    	<table id="messageNoInfo">
	    	<caption>No System Messages Recorded</caption>
	    	<thead>
	    		<tr class="heading">
	    			<th>{$system_register->site_term('MSG_TABLE_HEADING')}</th>
	    		</tr>
	    	</thead>
	    	<tbody>
	    		<tr class="messageNoMsg">
	    			<td class="messageInfo">{$system_register->site_term('MSG_TABLE_ROW_NO_INFO_TEXT')}</td>
	    		</tr>
	    	</tbody>
	    	</table>
	    	</div>
EOD;

		return $out;
    }

    public function getAllMessagesByGroup()
    {
    	if(empty($this->_messages))
    	{
	    	$out = array();
    	} else {
	    	foreach ($this->_messages as $message)
	    	{
	    		$typeId = $message->getTypeId();
			    $out[$typeId][] = $message->getMessageArray(DEBUG);
	    	}
	    }
	    ksort($out);
	    return $out;
    }
        
    public function getAllMessagesByGroupHtmlTable()
    {
    	$system_register = \core\app\classes\system_register\system_register::getInstance();
    	
    	if( empty($this->_messages) )
    	{
	    	$out = $this->_getEmptyMessagesHTML($system_register);
    	} else {
    		
    		//Top of the table
    		if(DEBUG)
    		{
    			$top = <<<"EOD"
	    		<div id="messageDiv">
	    		<table id="messageGroup">
	    		<caption>System Messages by Group</caption>
	    		    <thead>
	    		    <tr class="heading">
	    		    	<th colspan="5">{$system_register->site_term('MSG_TABLE_HEADING')}</th>
	    		    </tr>
	    		    </thead>
	    		    <tbody>
EOD;
			} else {
				$top = <<<"EOD"
	    		<div id="messageDiv">
	    		<table id="messageGroup">
	    		<caption>System Messages by Group</caption>
	    		    <thead>
	    		    <tr class="heading">
	    		    	<th colspan="3">{$system_register->site_term('MSG_TABLE_HEADING')}</th>
	    		    </tr>
	    		    </thead>
	    		    <tbody>
EOD;
			}

			//get the array of data in ordre
    		$messagesInGroups = $this->getAllMessagesByGroup();
    		
    		$middle = '';
    		
    		foreach($messagesInGroups as $msgType => $group)
    		{
    			if($msgType < 20)
	    		{
	    			$typeName = $system_register->site_term('MSG_TITLE_ERROR');
	    			$rowClass = 'messageError';
	    		} elseif($msgType < 30) {
	    			$typeName = $system_register->site_term('MSG_TITLE_WARNING');
	    			$rowClass = 'messageWarning';
	    		} else {
	    			$typeName = $system_register->site_term('MSG_TITLE_NOTE');
	    			$rowClass = 'messageNote';
	    		}
    		
    			if(DEBUG)
    			{
	    			$middle .= <<<"EOD"
	    			<tr class="subHeading">
	    				<th  colspan="5">$typeName</th>
	    			</tr>
EOD;
	    		} else {
		    		$middle .= <<<"EOD"
	    			<tr class="subHeading">
	    				<th  colspan="2">$typeName</th>
	    			</tr>
EOD;
	    		}

	    		foreach ($group as $key => $row)
	    		{
	    		    $key++;
	    		    
	    		    if(DEBUG)
	    		    {
	    		    	$td = <<<"EOD"
	    		    	<tr class="{$rowClass}">
	    		    	    <td class="messageCount">{$key}</td>
	    		    	    <td class="messageInfo">{$row['info']}</td>
	    		    	    <td class="messageSource">{$row['source']}</td>
	    		    	    <td class="messageSection">{$row['section']}</td>
	    		    	    <td class="messageId">{$row['id']}</td>
	    		    	</tr>
EOD;
	    		    } else {
	    		    	$td = <<<"EOD"
	    		    	<tr class="{$rowClass}">
	    		    	    <td class="messageCount">{$key}</td>
	    		    	    <td class="messageInfo">{$row['info']}</td>
	    		    	</tr>
EOD;
	    		    }
	    		    
	    		    $middle .= $td;
	    		}
	    		
	    	}
    		
	    	$out = $top.$middle.'
	    		</tbody>
	    	</table>
	    	</div>
	    	';
	    }
	       
	    return $out;
    }

    public function getAllMessagesByOrder()
    {
    	if(empty($this->_messages))
    	{
	    	$out = array();
    	} else {
	    	foreach ($this->_messages as $message)
	    	{
	    		$typeId = $message->getTypeId();
			    $out[] = array('typeId' => $typeId, 'message' => $message->getMessageArray(DEBUG));
	    	}
	    }	    
	    return $out;
    }
    
    public function getAllMessagesByOrderHtmlTable()
    {
    	$system_register = \core\app\classes\system_register\system_register::getInstance();
    	
    	if( empty($this->_messages) )
    	{
	    	$out = $this->_getEmptyMessagesHTML($system_register);
    	} else {
    		
    		//get the array of data in ordre
    		$messagesInOrder = $this->getAllMessagesByOrder();
    		
    		if(DEBUG)
    		{	
	    	    $top = <<<"EOD"
	    	    <div id="messageDiv">
	    	    <table id="messageOrder">
	    	    <caption>System Messages by Order</caption>
	    	    	<thead>
	    	    		<tr  class="heading">
	    	    			<th colspan="6">{$system_register->site_term('MSG_TABLE_HEADING')}</th>
	    	    		</tr>
	    	    		<tr class="subHeading">
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_ROW_COUNT')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_TYPE')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_INFO')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_SOURCE')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_SECTION')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_ID')}</th>
	    	    		</tr>
	    	    	</thead>
EOD;
    		} else {
	    	    $top = <<<"EOD"
	    	    <div id="messageDiv">
	    	    <table id="messageOrder">
	    	    <caption>System Messages by Order</caption>
	    	    	<thead>
	    	    		<tr class="heading">
	    	    			<th colspan="3">{$system_register->site_term('MSG_TABLE_HEADING')}</th>
	    	    		</tr>
	    	    		<tr class="subHeading">
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_ROW_COUNT')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_TYPE')}</th>
	    	    			<th>{$system_register->site_term('MSG_TABLE_HEAD_INFO')}</th>
	    	    		</tr>
	    	    	</thead>
EOD;
			}
    		 		
    		$count = 0;
    		$middle = '
    					<tbody>';
    		
	    	foreach ($messagesInOrder as $row)
	    	{
	    		$count++;
	    		
	    		if($row['typeId']<20)
	    		{
	    			$typeName = $system_register->site_term('MSG_TITLE_ERROR');
	    			$rowClass = 'messageError';
	    		} elseif($row['typeId']<30) {
	    			$typeName = $system_register->site_term('MSG_TITLE_WARNING');
	    			$rowClass = 'messageWarning';
	    		} else {
	    			$typeName = $system_register->site_term('MSG_TITLE_NOTE');
	    			$rowClass = 'messageNote';
	    		}
	    		
	    		if(DEBUG)
	    		{
	    			$td = <<<"EOD"
	    			<tr class="{$rowClass}">
	    				<td class="messageCount">{$count}</td>
	    				<td class="messageType">{$typeName}</td>
	    				<td class="messageInfo">{$row['message']['info']}</td>
	    				<td class="messageSource">{$row['message']['source']}</td>
	    				<td class="messageSection">{$row['message']['section']}</td>
	    				<td class="messageId">{$row['message']['id']}</td>
	    			</tr>
EOD;
	    		} else {
		    		$td = <<<"EOD"
	    			<tr class="{$rowClass}">
	    				<td class="messageCount">{$count}</td>
	    				<td class="messageType">{$typeName}</td>
	    				<td class="messageInfo">{$row['message']['info']}</td>
	    			</tr>
EOD;
	    		}
	    		
	    		$middle .= $td;
	    	}
	    	$out = $top.$middle.'
	    		</tbody>
	    	</table>
	    	</div>
	    	';
	    }
	    return $out;
    }
    
}
?>