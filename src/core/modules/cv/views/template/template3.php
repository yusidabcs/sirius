<?php
    $out = '
    <title>'.$data_cv['name'].'-CV</title>
    <style type="text/css">
    p {
        margin: 0px;
        color: #555555;
    }

    .bold {
    font-weight: 700;
    font-size: 20px;
    text-transform: uppercase;
    }

    .semi-bold {
    font-weight: bold;
    font-size: 16px;
    }

    .resume {
    width: 100%;
    height: auto;
    background-color: #fff;
    padding: 50px 20px 30px 20px;
    font-size: 14px;
    line-height: 22px;
    color: #555555;
    font-family: "arial", sans-serif;
    box-sizing: border-box;
    -webkit-box-sizing: border-box;
    -moz-box-sizing: border-box; 
    }
    
    .resume .resume_item {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box; 
        box-sizing: border-box;
        width: inherit;
        /*border: 2px solid grey;*/
        padding: 0px 0px 10px 0px;
        border-top: 3px solid #ff5959;
    }

    .resume .resume_profile {
        -webkit-box-sizing: border-box;
        -moz-box-sizing: border-box; 
        box-sizing: border-box;
        width: inherit;
        /*border: 2px solid grey;*/
        padding: 0px 0px 25px 0px;
    }

    .resume .resume_profile .title {
        font-size: 25px;
        font-weight: 700;
        color : #cc0c0c;
        padding-top: 5px;
        padding-bottom: 5px;
        text-transform: uppercase;
    }

    .resume .resume_profile .div_profile{
        border-top: 3px solid #ff5959;
        width: 97%;
        margin-top: 5px;
        padding-top: 15px;
        
    }


    .resume .resume_item .item_heading {
        color: #fff;
        float: right;
        text-align: center;
        background-color: #ff5959;
        padding: 2px 10px 2px 10px;
        width: 25%;
    }

    .resume .resume_item .item {
        padding-top: 35px;
    }

    .resume .resume_item .item ul{
        margin-bottom: 0px;
        margin-top: 0px;
    }
    .resume .resume_item .item>ul>li{
        margin-bottom: 10px;
    }

    .resume .resume_item .item .table_item {
        width: 100%;
    }

    .resume .resume_item .item .table_item td{
        vertical-align: top;
        font-size: 16px;
        color: #555555;
        padding-bottom: 15px;
    }

    .resume .resume_item .item .table_item td p,.resume .resume_item .item .table_item td ul{
        margin-bottom: 0px;
    }

    .profile {
        width: 100%;
        line-height: 22px;
        color: #555555;
    }

    .tabel_foto {
    width: 100%;
    }

    .tabel_foto td img {
    width: 100%;
    height: auto;
    border: 2px solid #555;
    }

    .tabel_foto td {
    vertical-align: top;
    }

    .tabel_foto .profile {
        width: 100%;
    }


    .tabel_foto .profile td {
    padding-bottom: 5px;
    font-size: 16px;
    }
    </style>

<div class="resume">
  <div class="resume_profile">
    <table class="tabel_foto">
      <tr>
      	<td width="65%">
      	  <p class="title">'.$data_cv['name'].'</p>
          <p>'.$data_cv['main_email'].' | '.$data_cv['number'].'</p>
          <div class="div_profile">
				<table class="profile">
                    <tr>
                        <td width="35%">Date of Birth</td><td>: '.$data_cv['dob'].'</td>
                    </tr>
                    <tr>	
                        <td>Address</td><td>: '.$data_cv['address'].'</td>
                    </tr>
                    <tr>
                        <td>Country</td><td>: '.$data_cv['country'].'</td>
                    </tr>
                    <tr>
                        <td>Sex</td><td>: '.$data_cv['sex'].'</td>
                    </tr>
                    <tr>
                        <td>Height/Weight</td><td>: '.$data_cv['hw'].'</td>
                    </tr>
				</table>
			</div>
        </td>
        <td width="35%">
          <img src="'.$img.'" alt="img_profil">
        </td>
      </tr>
    </table>
  </div>
  <div class="resume_item resume_education">
  	<div class="item_heading semi-bold">EDUCATION</div>
	<div class="item">
        <table class="table_item">';
          $education_list = $data_cv['education_list'];
          $arr_education = array_keys($education_list);
            foreach ($arr_education as $key => $value) {
                $out .='<tr>
				<td width="30%">
					<ul>
						<li>'.$education_list[$value]['from_date'].' - '.$education_list[$value]['to_date'].'</li>
					</ul>
                </td>
				<td>
					<p class="semi-bold">'.$education_list[$value]['institution'].' ('.$education_list[$value]['level'].')</p>
					<p>'.$education_list[$value]['description'].'</p>
				</td>
            </tr>';
            }
		$out .='</table>
	</div>
  </div>
  <div class="resume_item resume_work">
  	<div class="item_heading semi-bold">WORK EXPERIENCE</div>
	<div class="item">
        <table class="table_item">';
        $employment_list = $data_cv['employment_list'];
        $arr_employment = array_keys($employment_list);
          foreach ($arr_employment as $key => $value) {
			$out .= '<tr>
				<td width="30%">
					<ul>
						<li>'.date('Y',strtotime($employment_list[$value]['from_date'])).' - '.date('Y',strtotime($employment_list[$value]['to_date'])).'</li>
					</ul>
                </td>
				<td>
					<p><span class="semi-bold">'.$employment_list[$value]['employer'].'</span> as a '.$employment_list[$value]['job_title'].' from '.$employment_list[$value]['from_date'].' until '.$employment_list[$value]['to_date'].'</p>
				</td>
            </tr>';
          }
		$out .='</table>
		
	</div>
  </div>
  <div class="resume_item resume_social">
  	<div class="item_heading semi-bold">SOCIAL</div>
		<div class="item">
			<ul>';
            $internet_list = $data_cv['internet_list'];
            $arr_internet = array_keys($internet_list);
            foreach ($arr_internet as $key => $value) {
                    switch ($value) {
                        case 'skype':
                        $name = 'Skype';
                        break;
                        case 'facebook':
                        $name = 'Facebook';
                        break;
                        case 'youtube-channel':
                        $name = 'Youtube';
                        break;
                        case 'twitter':
                        $name = 'Twitter';
                        break;
                        case 'linked-in':
                        $name = 'Linkedin';
                        break;
                        case 'instagram':
                        $name = 'Instagram';
                        break;
                        case 'google-plus':
                        $name = 'Google+';
                        break;
                        default:
                        $name='';
                        break;
                    }
                  $out .='<li>
                  <span class="semi-bold">'.$name.'</span><br>'.$internet_list[$value].'
                  </li>';
                }
			$out .='</ul>
		</div>
  </div>
</div>';
?>