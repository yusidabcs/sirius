<?php
//HTML2PDF by Cl�ment Lavoillotte
//ac.lavoillotte@noos.fr
//webmaster@streetpc.tk
//http://www.streetpc.tk

require('fpdf.php');

// Load data
function LoadData($file)
{
    // Read file lines
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}

// Simple table
function BasicTable($header, $data)
{
    // Header
    foreach($header as $col)
        $this->Cell(40,7,$col,1);
    $this->Ln();
    // Data
    foreach($data as $row)
    {
        foreach($row as $col)
            $this->Cell(40,6,$col,1);
        $this->Ln();
    }
}

//function hex2dec
//returns an associative array (keys: R,G,B) from
//a hex html code (e.g. #3FE5AA)
function hex2dec($couleur = "#000000"){
	$R = substr($couleur, 1, 2);
	$rouge = hexdec($R);
	$V = substr($couleur, 3, 2);
	$vert = hexdec($V);
	$B = substr($couleur, 5, 2);
	$bleu = hexdec($B);
	$tbl_couleur = array();
	$tbl_couleur['R']=$rouge;
	$tbl_couleur['V']=$vert;
	$tbl_couleur['B']=$bleu;
	return $tbl_couleur;
}

//conversion pixel -> millimeter at 72 dpi
function px2mm($px){
	return $px*25.4/72;
}

function txtentities($html){
	$trans = get_html_translation_table(HTML_ENTITIES);
	$trans = array_flip($trans);
	return strtr($html, $trans);
}
////////////////////////////////////

class PDF_HTML extends FPDF
{
//variables of html parser
protected $B;
protected $I;
protected $U;
protected $HREF;
protected $ALIGN='';
protected $fontList;
protected $issetfont;
protected $issetcolor;

function __construct($initial ,$orientation='P', $unit='mm', $format='A4')
{
	//Call parent constructor
	parent::__construct($orientation,$unit,$format);
	//Initialization
	$this->B=0;
	$this->I=0;
	$this->U=0;
	$this->HREF='';
	$this->ALIGN='';
	$this->fontlist=array('arial', 'times', 'courier', 'helvetica', 'symbol');
	$this->issetfont=false;
	$this->issetcolor=false;
	$this->initial=$initial;
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('Times','',10);
	// Page number
	$this->Cell(15);
	$this->Cell(0,10,'Page '.$this->PageNo().' of {nb}',0,0,'L');
	$this->SetX(-80);
	$this->Cell(30,10,'Candidate Initials : ',0,0,'L');
	$this->SetFont('Times','U',10);
	$this->Cell(0,10,$this->initial,'','L');
}

function WriteHTML($html)
{
	//HTML parser
	$html=strip_tags($html,"<b><t><t2><t3><d><u><i><a><img><p><h1><h2><h3><h4><br><br2><strong><em><tr><blockquote><table><td>"); //supprime tous les tags sauf ceux reconnus
	$html=str_replace("\n",' ',$html); //remplace retour � la ligne par un espace
	$a=preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE); //�clate la cha�ne avec les balises
	
	foreach($a as $i=>$e)
	{
		
		if($i%2==0)
		{
			//Text
			
			if($this->HREF){
				$this->PutLink($this->HREF,$e);
			}elseif($this->ALIGN=='center'){
				$this->Cell(0,5,$e,0,1,'C');
			}elseif($this->ALIGN=='right'){
				$this->SetFont('Arial','ib',10);
				$this->Cell(0,5,$e,0,1,'R');
			}elseif($this->ALIGN=='data')
			{
				$this->SetX(70);
				$this->Cell(0,5,$e,'B',1,'L');
			}elseif($this->ALIGN=='fit'){
				$this->SetX(70);
				$this->Cell(30,5,$e,'B',1,'L');
			}elseif($this->ALIGN=='left_signature'){
				$this->SetX(15);
				$this->SetFont('Arial','i',10);
				$this->Cell(30,5,$e,0,0,'L');
			}elseif($this->ALIGN=='right_signature'){
				$this->SetFont('Arial','i',10);
				$this->SetX(-65);
				$this->Cell(30,5,$e,0,1,'C');
			}
			else{
				$this->Write(5,stripslashes(txtentities($e)));
			}
		}
		else
		{
			//Tag
			if($e[0]=='/')
				$this->CloseTag(strtoupper(substr($e,1)));
			else
			{
				//Extract attributes
				$a2=explode(' ',$e);
				$tag=strtoupper(array_shift($a2));
				$attr=array();
				foreach($a2 as $v)
				{
					if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
						$attr[strtoupper($a3[1])]=$a3[2];
				}
				$this->OpenTag($tag,$attr);
			}
		}
	}
}

function OpenTag($tag, $attr)
{
	//Opening tag
	switch($tag){
		case 'STRONG':
			$this->SetStyle('B',true);
			break;

		case 'H1':
			$this->Ln(2);
			$this->SetTextColor(150,0,0);
			$this->SetFontSize(22);
            $this->ALIGN=$attr['ALIGN'];
			break;

		case 'H2':
			$this->SetFontSize(18);
			$this->ALIGN=$attr['ALIGN'];
			break;

		case 'H3':
			$this->SetFontSize(18);
			$this->ALIGN=$attr['ALIGN'];
			break;

		case 'H4':
			$this->SetFontSize(16);
			$this->ALIGN=$attr['ALIGN'];
			break;		

		case 'D':
			$this->ALIGN=$attr['ALIGN'];
			break;

		case 'EM':
			$this->SetStyle('I',true);
			break;
			
		case 'B':
		case 'I':
		case 'U':
			$this->SetStyle($tag,true);
			break;
		case 'A':
			$this->HREF=$attr['HREF'];
			break;
		case 'IMG':
			if(isset($attr['SRC']) && (isset($attr['WIDTH']) || isset($attr['HEIGHT']))) {
				if(!isset($attr['WIDTH']))
					$attr['WIDTH'] = 0;
				if(!isset($attr['HEIGHT']))
					$attr['HEIGHT'] = 0;
				$this->Image($attr['SRC'], $this->GetX(), $this->GetY(), px2mm($attr['WIDTH']), px2mm($attr['HEIGHT']));
			}
			break;
		case 'TR':
		case 'BLOCKQUOTE':
		case 'BR':
			$this->Ln(5);
			break;
		case 'BR2':
			$this->Ln(7);
			break;
		case 'T':
			$this->Cell(10,5,' ',0,0);
			break;			
		case 'T2':
			$this->Cell(17,5,' ',0,0);
			break;		
		case 'T3':
			$this->Cell(20,5,' ',0,0);
			break;			
		case 'P':
			$this->ALIGN=$attr['ALIGN'];
			break;
	}
}

function CloseTag($tag)
{
	//Closing tag
	if ($tag == 'H1' || $tag == 'H2' || $tag == 'H3' || $tag == 'H4' ){
		$this->Ln(2);
		$this->SetFontSize(12);
		$this->ALIGN='';
	}elseif($tag=='STRONG')
		$tag='B';
	elseif($tag=='EM')
		$tag='I';
	elseif($tag=='B' || $tag=='I' || $tag=='U')
		$this->SetStyle($tag,false);
	elseif($tag=='A')
		$this->HREF='';
	elseif($tag=='P')
		$this->ALIGN='';
	elseif($tag=='D'){
		$this->ALIGN='';
		$this->SetFontSize(12);
		$this->SetStyle('I',false);
	}	
}

function SetStyle($tag, $enable)
{
	//Modify style and select corresponding font
	$this->$tag+=($enable ? 1 : -1);
	$style='';
	foreach(array('B','I','U') as $s)
	{
		if($this->$s>0)
			$style.=$s;
	}
	$this->SetFont('',$style);
}

function PutLink($URL, $txt)
{
	//Put a hyperlink
	$this->SetTextColor(0,0,255);
	$this->SetStyle('U',true);
	$this->Cell(0,5,$txt,0,1,'C',false,$URL);
	$this->SetStyle('U',false);
	$this->SetTextColor(0);
}

}//end of class
?>
