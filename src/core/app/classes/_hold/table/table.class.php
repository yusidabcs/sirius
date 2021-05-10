<?php
/**
* A Class that makes tables
**/
namespace iow\app\classes\html\table;

final class table {

	private _table = array();
	private _tableData = array();
	private _validId = array();
	private _validDid = array();
	
	public function __construct()
	{
		return;
	}
	
	//div around the table 
	public function addTableDiv($cssName = '', $classOrId = 'c' )
	{
		if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
		{	
			$this->_table['div']['classOrId'] = [$classOrId] 
			$this->_table['div']['cssName'] = $cssName;
		} else {
			$msg = "Table content addTableDiv failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		return;
	}
	
	//table class or id 
	public function addTableCss($cssName ='', $classOrId = 'c')
	{		
		if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
		{	
			$this->_table['table']['classOrId'] = [$classOrId] 
			$this->_table['table']['cssName'] = $cssName;
		} else {
			$msg = "Table content addTableCss failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			    throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		return ;
	}
	
	//table thead
	public function addTableHeadCss($cssName = '', $classOrId = 'c')
	{
		$content = htmlentities();
		
		if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
		{	
			$this->_table['thead']['classOrId'] = [$classOrId] 
			$this->_table['thead']['cssName'] = $cssName;
		} else {
			$msg = "Table content addTableHeadCss failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		return ;
	}
	
	//table caption
	public function addTableCaption($content = '', $cssName = '', $classOrId = 'c')
	{
		$content = htmlentities();
		
		if(empty($cssName))
		{
			$msg = "Table content addTableCaption failed because the content is empty.";
			    throw new \RuntimeException($msg, E_USER_ERROR);
		{
			$this->_table['caption']['content'] = $content;
		}
		
		if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
		{	
			$this->_table['caption']['classOrId'] = [$classOrId] 
			$this->_table['caption']['cssName'] = $cssName;
		} else {
			$msg = "Table content addTableCaption failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		return ;
	}
	
	//table tfoot
	public function addTableFootCss($cssName = '', $classOrId = 'c')
	{
		$content = htmlentities();
		
		if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
		{	
			$this->_table['tfoot']['classOrId'] = [$classOrId] 
			$this->_table['tfoot']['cssName'] = $cssName;
		} else {
			$msg = "Table content addTableFootCss failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		return ;
	}
	
	//table tbody
	public function addTableBodyCss($cssName = '', $classOrId = 'c')
	{
		$content = htmlentities();
		
		if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
		{	
			$this->_table['tbody']['classOrId'] = [$classOrId] 
			$this->_table['tbody']['cssName'] = $cssName;
		} else {
			$msg = "Table content addTableFootCss failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		return ;
	}
	
	//table row
	public function addTableRow($section ='b', $cssName = '', $classOrId = 'c')
	{
		if( $section == 'b' ||  $section == 'h' ||  $section == 'f' || $section == 'a' )
		{
			$id = rand();
			
			switch($section)
				
				case 'h' :
					$tsection = 'thead';
					break;
				case 'f' :
					$tsection = 'tfoot';
					break;
				case 'a' :
					$tsection = 'all';
					break;
				default:
					$tsection = 'tbody';
			endswitch;
			
			$this->_table[$tsection]['rows'][$id]['id'] = $id;
				
		} else {
			$msg = "Table content addTableRow failed because either 'section' is not 'b', 'h' or 'f'.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		if(!empty($cssName))
		{
			if( ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
			{	
				$this->_table[$tsection]['rows'][$id]['classOrId'] = [$classOrId] 
				$this->_table[$tsection]['rows'][$id]['cssName'] = $cssName;
			} else {
				$msg = "Table content addTableRow failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
				throw new \RuntimeException($msg, E_USER_ERROR);
			}
		}
		
		$this->_validId[] = $id;
		return $id;
	}
	
	public function addTableDataContent($id = '', $content = '', $type = 'd')
	{
		if( in_array($id,$this->_validId) )
		{
			$did = rand();
			$content = htmlentities($content);
			$this->_tableData[$id][$did]['content'] = [$content];
			
			if($type == 'h')
			{
				$this->_tableData[$id][$did]['type'] = 'h';
			} else {
				$this->_tableData[$id][$did]['type'] = 'd';
			}
		} else {
			$msg = "Table content addTableData failed because the id '$id' was not valid.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		$this->_validDid[] = $did;
		return $did;
	}
	
	public function addTableDataContentCss($id = '', $did = '', $cssName = '', $classOrId = 'c')
	{
		if( in_array($id,$this->_validId) && in_array($did, $this->_validDid) )
		{
			if(ctype_alnum($cssName) && ($classOrId == 'i' || $classOrId == 'c') )
			{	
			    $this->_tableData[$id][$did]['classOrId'] = [$classOrId] 
			    $this->_tableData[$id][$did]['cssName'] = $cssName;
			} else {
			    $msg = "Table content addTableDataContentCss '$id' '$did' failed because either '$cssName' is not alnum or '$classOrId' is not 'i' or 'c'.";
			        throw new \RuntimeException($msg, E_USER_ERROR);
			}
		} else {
			$msg = "Table content addTableDataContentCss failed because the id '$id' or $did' was not valid.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		return;
	}
	
	public function addTableDataContentColspan($id = '', $did = '', $colspan = '')
	{
		if( in_array($id,$this->_validId) && in_array($did, $this->_validDid) )
		{
			if( ctype_num($colspan) )
			{	
			    $this->_tableData[$id][$did]['colspan'] = [$colspan]
			} else {
			    $msg = "Table content addTableDataContentColspan '$id' '$did' failed because '$colspan' is not a number.";
			        throw new \RuntimeException($msg, E_USER_ERROR);
			}
		} else {
			$msg = "Table content addTableDataContentColspan failed because the id '$id' or $did' was not valid.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		return;
	}
	
	public function addTableDataContentRowspan($id = '', $did = '', $rowspan = '')
	{
		if( in_array($id,$this->_validId) && in_array($did, $this->_validDid) )
		{
			if( ctype_num($rowspan) )
			{	
			    $this->_tableData[$id][$did]['rowspan'] = [$rowspan]
			} else {
			    $msg = "Table content addTableDataContentRowspan '$id' '$did' failed because '$rowspan' is not a number.";
			        throw new \RuntimeException($msg, E_USER_ERROR);
			}
		} else {
			$msg = "Table content addTableDataContentRowspan failed because the id '$id' or $did' was not valid.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		return;
	}
	
	public function getHTML()
	{
		if( !empty($this->_table) && !empty($this->_tableData) )
		{
		
			$html = '';
			$div = false;
			
			if( isset($this->_table['div']['classOrId']) )
			{
				$type = ($this->_table['div']['classOrId'] == 'i') ? 'id' : 'class';
				$html .= "<div $type=\"{$this->_table['div']['cssName']}\">";
				$div = true;
			}
			
			if( isset($this->_table['table']['classOrId']))
			{
				$type = ($this->_table['table']['classOrId'] == 'i') ? 'id' : 'class';
				$html .= "<table $type=\"{$this->_table['table']['cssName']}\">";
			} else {
				$html .= '<table>';
			}
			
			//caption
			if( isset($this->_table['table']['caption']) !empty($this->_table['table']['caption']) )
			{
			
			    if(isset($this->_table['table']['caption']['classOrId']))
			    {
			    	$css = $this->_table['table']['caption']['classOrId'] == 'i' ? "id=\"{$this->_table['table']['caption']['cssName']}\"" : "class=\"{$this->_table['table']['caption']['cssName']}\"";
			    }
			   
			   $html .= "<caption $css>{$this->_table['table']['caption']['content']}</caption>";
			}		
			
			if( isset($this->_table['all']) )
			{				
				foreach( $this->_table['all']['rows'] as $rows)
				{
					$this->_getRows($rows);
				}
				
			} else {
				
				//head
				$html .= "	<thead>";
				foreach( $this->_table['thead']['rows'] as $rows)
				{
				    $this->_getRows($rows);
				}
				$html .= "	</tfoot>";
				
				//foot
				$html .= "	<tfoot>";
				foreach( $this->_table['tfoot']['rows'] as $rows)
				{
				    $this->_getRows($rows);
				}
				$html .= "	</tfoot>";
				
				//body
				$html .= "	<tbody>";
				foreach( $this->_table['tbody']['rows'] as $rows)
				{
				    $this->_getRows($rows);
				}
				$html .= "	</tbody>";
				
			}
			
			$html .= "</table>";
			if($div) $html .= '</div>';
		
		} else {
			$msg = "Can not produce a table with no data.";
			throw new \RuntimeException($msg, E_USER_ERROR);
		}
		
		return;
	}
	
	private getRows($rows)
	{
		$html = '';
		
		foreach($rows as $row)
		{
			$id = $row['id'];
			
			if( isset($row['classOrId']) )
			{
			    $html .= $row['classOrId'] == 'c' ? "		<tr class=\"{$row['cssName']}\">" : "		<tr id=\"{$row['cssName']}\">";
			} else {
			    $html .= "		<tr>";
			}
			
			foreach($this->_tableData[$id] as $data)
			{
				$type = $data['type'] == 'h' ? 'th' : 'td';
				$css = '';
				if(isset($data['classOrId']))
				{
					$css = $data['classOrId'] == 'i' ? " id=\"{$data['cssName']}\"" : " class=\"{$data['cssName']}\"";	
				}
				
				$colspan = isset($data['colspan']) ? " colspan=\"{$data['colspan']}\"" : '';
				$rowspan = isset($data['rowspan']) ? " rowspan=\"{$data['rowspan']}\"" : '';
				
				$html .= "			<{$type}{$css}{$colspan}{$rowspan}>{$data['content']}</{$type}>";
			}
			$html .= "		</tr>";
		}
	}
	
}
?>