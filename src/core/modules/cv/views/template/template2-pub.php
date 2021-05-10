<?php
    $out = '
    <title>'.$data_cv['name'].'-CV</title>
    <style type="text/css">
    p {
        margin: 0px;
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
    padding-top : 50px;
    padding-bottom : 30px;
    font-size: 16px;
    line-height: 22px;
    color: #555555;
    box-sizing: border-box;
    font-family: "arial", sans-serif;
    }
    
    .resume .resume_item {
        /*border: 2px solid grey;*/
        padding: 0px 30px 25px 30px;
    }

    .resume .resume_item h3 {
        font-size : 1.25rem;
        width:inherit;
        text-align:left; 
        border-bottom: 2px solid #9de1fc; 
        line-height:0.1; 
        margin:0px 0 20px;
        padding-left: 50px;
        text-transform: uppercase;
        color: #009ddb;
    }
    .resume .resume_item h3 span{
        background:#fff; 
        padding:0 5px;
    }

    .resume .resume_item .item {
        padding-top: 15px;
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
    line-height: 22px;
    color: #555555;
    }

    .tabel_foto {
    width: 100%;
    }

    .tabel_foto td img {
    width: 100%;
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
  <div class="resume_item resume_profil">
    <table class="tabel_foto">
      <tr>
      	<td>
          <div class="div_profile">
				<table class="profile">
                <tr>
                    <td width="35%">Name</td><td>: '.$data_cv['name'].'</td>
                </tr>
                <tr>
                    <td>Date of Birth</td><td>: '.$data_cv['dob'].'</td>
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
                <tr>
                    <td>Phone Number</td><td>: '.$data_cv['number'].'</td>
                </tr>
                <tr>
                    <td>Email</td><td>: '.$data_cv['main_email'].'</td>
                </tr>
				</table>
			</div>
        </td>
        <td width="30%">
          <img src="'.$img.'" alt="img_profil">
        </td>
      </tr>
    </table>
  </div>
  <div class="resume_item resume_education">
  	<h3><span>EDUCATION</span></h3>
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
  	<h3><span>WORK EXPERIENCE</span></h3>
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
  	<h3><span>SOCIAL</span></h3>
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