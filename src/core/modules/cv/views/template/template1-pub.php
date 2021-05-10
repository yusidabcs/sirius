<?php
    $out = '
    <title>'.$data_cv['name'].'-CV</title>
    <style type="text/css">
    @import url("https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap");

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
    padding: 25px;
    font-size: 14px;
    line-height: 22px;
    color: #555555;
    box-sizing: border-box;
    font-family: "Montserrat", sans-serif;
    }

    .profile {
    line-height: 22px;
    color: #555555;
    }

    .resume .resume_item {
    padding-top: 25px;
    padding-bottom: 0px;
    }

    .resume .resume_profile {
        padding-top: 25px;
        padding-bottom: 25px;
    }
    .resume .resume_social {
        padding-top: 25px;
        padding-bottom: 0px;
    }

    .resume .resume_education, .resume .resume_work, .resume .resume_profile {
    border-bottom: 2px solid #b1eaff;
    }


    .resume .title {
    margin-bottom: 20px;
    color: #0bb5f4;
    }

    .tabel_foto {
    width: 100%;
    }

    .tabel_foto img {
    width: 100%
    }

    .tabel_foto td {
    vertical-align: top;
    }

    .tabel_foto .profile {
        width: 100%;
    }
    .tabel_foto .div_profile, .tabel_foto .title{
    padding-left: 20px;
    padding-top: 0px;
    padding-bottom: 0px;
    }


    .tabel_foto .profile td {
    padding-bottom: 5px;
    }

    .resume_item ul {
        list-style-type: none;
    }

    .resume_item ul li .date {
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 15px;
    }

    .resume_item ul li .info {
        margin-bottom: 25px;
    }

    .resume_item ul li .item {
        padding-left: 20px;
    }
    </style>


<div class="resume">
    <div class="resume_profile">
        <table class="tabel_foto">
        <tr>
            <td width="30%">
            <img src="'.$img.'" alt="profile_pic">
            </td>
            <td>
            <div class="title">
                <p class="bold">Profile</p>
            </div>
            <div class="div_profile">
                    <table class="profile">
                        <tr>
                            <td width="25%">Name</td><td>: '.$data_cv['name'].'</td>
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
        </tr>
        </table>
    </div>
    <div class="resume_item resume_education">
        <div class="title">
        <p class="bold">Education</p>
        </div>
        <ul>';
    $education_list = $data_cv['education_list'];
   $arr_education = array_keys($education_list);
     foreach ($arr_education as $key => $value) {
             $class = '';
             if($key<count($arr_education)-1) {$class="info";}
            $out.='<li>
            <div class="date">'.$education_list[$value]['from_date'].' - '.$education_list[$value]['to_date'].'</div> 
            <div class="item '.$class.'">
                <p class="semi-bold">'.$education_list[$value]['institution'].' ('.$education_list[$value]['level'].')</p> 
                <p>'.$education_list[$value]['description'].'</p>
            </div>
            </li>';
     }
        $out .='</ul>
    </div>
    <div class="resume_item resume_work">
        <div class="title">
        <p class="bold">Work Experience</p>
        </div>
        <ul>';
    $employment_list = $data_cv['employment_list'];
     $arr_employment = array_keys($employment_list);
       foreach ($arr_employment as $key => $value) {
        $class = '';
        if($key<(count($arr_employment)-1)) {$class="info";}
            $out .='<li>
            <div class="date">'.date('Y',strtotime($employment_list[$value]['from_date'])).' - '.date('Y',strtotime($employment_list[$value]['to_date'])).'</div> 
            <div class="item '.$class.'">
                <p>I have been working at '.$employment_list[$value]['employer'].', as a '.$employment_list[$value]['job_title'].' from '.$employment_list[$value]['from_date'].' until '.$employment_list[$value]['to_date'].'</p>
            </div>
            </li>';
       }
        $out .='</ul>
    </div>
    <div class="resume_item resume_social">
        <div class="title">
        <p class="bold">Social</p>
        </div>
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
            <div class="info">
                <p class="semi-bold">'.$name.'</p> 
                <p>'.$internet_list[$value].'</p>
            </div>
            </li>';
          }
        $out.='</ul>
    </div>
</div>';
?>