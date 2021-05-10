<?php
    $out = '

    <title>'.$data_cv['name'].'</title>
    <script src="https://kit.fontawesome.com/b99e675b6e.js"></script>
    <style type="text/css">
    @import url("https://fonts.googleapis.com/css?family=Montserrat:400,500,700&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      list-style: none;
      
    }
    
    .bold {
      font-weight: 700;
      font-size: 20px;
      text-transform: uppercase;
    }
    
    .semi-bold {
      font-weight: 500;
      font-size: 16px;
    }
    
    .resume {
      width: 100%;
      height: auto;
      display: flex;
      margin: auto;
      font-family: "Montserrat", sans-serif;
      background: #585c68;
      font-size: 14px;
      line-height: 22px;
      color: #555555;
      border: 1px solid #c9c9c9;
    }

    .resume p {
        margin-bottom: 0rem;
    }
    
    .resume .resume_left {
      width: 40%;
      background: #0bb5f4;
    }
    
    .resume .resume_left .resume_profile {
      width: 100%;
      height:auto;
    }
    
    .resume .resume_left .resume_profile img {
      width: 100%;
      height:auto;
    }
    
    .resume .resume_left .resume_content {
      padding: 0 25px;
    }
    
    .resume .title {
      margin-bottom: 20px;
    }
    
    .resume .resume_left .bold {
      color: #fff;
    }
    
    .resume .resume_left .regular {
      color: #b1eaff;
    }
    
    .resume .resume_item {
      padding: 25px 0;
      border-bottom: 2px solid #b1eaff;
    }
    
    .resume .resume_left .resume_item:last-child,
    .resume .resume_right .resume_item:last-child {
      border-bottom: 0px;
    }
    
    .resume .resume_left ul li {
      display: flex;
      margin-bottom: 10px;
      align-items: center;
    }
    
    .resume .resume_left ul li:last-child {
      margin-bottom: 0;
    }
    
    .resume .resume_left ul li .icon {
      width: 35px;
      height: 35px;
      background: #fff;
      color: #0bb5f4;
      border-radius: 50%;
      margin-right: 15px;
      font-size: 16px;
      position: relative;
    }

    .resume .resume_right ul li .icon {
        width: 35px;
        height: 35px;
        background: #0bb5f4;
        color: #fff;
        border-radius: 50%;
        margin-right: 15px;
        font-size: 16px;
        position: relative;
      }
    
    .resume .resume_right .resume_social ul li {
        display: flex;
        margin-bottom: 10px;
        align-items: center;
    }
    
    .resume .icon i,
    .resume .resume_right .resume_hobby ul li i {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }
    
    .resume .resume_left ul li .data {
      color: #b1eaff;
    }
    
    .resume .resume_left .resume_skills ul li {
      display: flex;
      margin-bottom: 10px;
      color: #b1eaff;
      justify-content: space-between;
      align-items: center;
    }
    
    .resume .resume_left .resume_skills ul li .skill_name {
      width: 25%;
    }
    
    .resume .resume_left .resume_skills ul li .skill_progress {
      width: 60%;
      margin: 0 5px;
      height: 5px;
      background: #009fd9;
      position: relative;
    }
    
    .resume .resume_left .resume_skills ul li .skill_per {
      width: 15%;
    }
    
    .resume .resume_left .resume_skills ul li .skill_progress span {
      position: absolute;
      top: 0;
      left: 0;
      height: 100%;
      background: #fff;
    }
    
    .resume .resume_left .resume_social .semi-bold {
      color: #fff;
      margin-bottom: 3px;
    }
    
    .resume .resume_right {
      width: 65%;
      background: #fff;
      padding: 25px;
    }
    
    .resume .resume_right .bold {
      color: #0bb5f4;
    }
    
    .resume .resume_right .resume_work ul,
    .resume .resume_right .resume_education ul {
      padding-left: 40px;
      overflow: hidden;
    }
    
    .resume .resume_right ul li {
      position: relative;
    }
    
    .resume .resume_right ul li .date {
      font-size: 16px;
      font-weight: 500;
      margin-bottom: 15px;
    }
    
    .resume .resume_right ul li .info {
      margin-bottom: 20px;
    }
    
    .resume .resume_right ul li:last-child .info {
      margin-bottom: 0;
    }
    
    .resume .resume_right .resume_work ul li:before,
    .resume .resume_right .resume_education ul li:before {
      content: "";
      position: absolute;
      top: 5px!important;
      left: -25px;
      width: 7px!important;
      height: 7px!important;
      border-radius: 50%;
      border: 2px solid #0bb5f4;
      background-color:#fff
    }
    
    .resume .resume_right .resume_work ul li:after,
    .resume .resume_right .resume_education ul li:after {
      content: "";
      position: absolute;
      top: 14px;
      left: -21px!important;
      width: 2px;
      height: 115px;
      background: #0bb5f4;
    }
    
    
    </style>
    <body class="body_resume">
    <div class="resume">
    <div class="resume_left">
      <div class="resume_profile">
        <img src="'.$img.'" alt="profile_pic">
      </div>
      <div class="resume_content">
        <div class="resume_item resume_info">
          <div class="title">
            <p class="bold">'.$data_cv['name'].'</p>';
            $employment_list = $data_cv['employment_list'];
            if(count($employment_list)>0) {
                $arr_employment = array_keys($employment_list);
                $job = $employment_list[$arr_employment[0]]['job_title'];
            } else {
                $job = 'Fresh Graduate';
            }
            $out .='<p class="regular">'.$job.'</p>
          </div>
          <ul>
            <li>
                <div class="icon">
                    <i class="fas fa-venus-mars"></i>
                </div>
                <div class="data">
                '.ucfirst($data_cv['sex']).'
                </div>
            </li>
            <li>
                <div class="icon">
                    <i class="fas fa-birthday-cake"></i>
                </div>
                <div class="data">
                '.$data_cv['dob'].'
                </div>
            </li>
            <li>
              <div class="icon">
                <i class="fas fa-map-signs"></i>
              </div>
              <div class="data">
              '.$data_cv['address'].' <br /> '.$data_cv['country'].'
              </div>
            </li>
            <li>
              <div class="icon">
                <i class="fas fa-mobile-alt"></i>
              </div>
              <div class="data">
              '.$data_cv['number'].'
              </div>
            </li>
            <li>
              <div class="icon">
                <i class="fas fa-envelope"></i>
              </div>
              <div class="data">
              '.$data_cv['main_email'].'
              </div>
            </li>
            <li>
              <div class="icon">
                <i class="fas fa-weight"></i>
              </div>
              <div class="data">
              '.$data_cv['hw'].'
              </div>
            </li>
          </ul>
        </div>
        <div class="resume_item resume_social">
        </div>

      </div>
   </div>
   <div class="resume_right">
     
     <div class="resume_item resume_work">
         <div class="title">
            <p class="bold">Work Experience</p>
          </div>
         <ul>';

        $employment_list = $data_cv['employment_list'];
        $arr_employment = array_keys($employment_list);
          foreach ($arr_employment as $key => $value) {
              $from = date('Y',strtotime($employment_list[$value]['from_date']));
              $to = date('Y',strtotime($employment_list[$value]['to_date']));
              if($from!=$to) {
                  $exp = $from.' - '.$to;
              } else {
                  $exp = $from;
              }
            $out .= '<li>
                 <div class="date">'.$exp.'</div> 
                 <div class="info">
                      <p class="semi-bold">'.$employment_list[$value]['employer'].'</p> 
                      <p>Work as a '.$employment_list[$value]['job_title'].' from '.$employment_list[$value]['from_date'].' until '.$employment_list[$value]['to_date'].'</p>
                 </div>
             </li>';
          }
             
         $out .='</ul>
     </div>
     <div class="resume_item resume_education">
       <div class="title">
            <p class="bold">Education</p>
          </div>
       <ul>';
       
       $education_list = $data_cv['education_list'];

       $arr_education = array_keys($education_list);
         foreach ($arr_education as $key => $value) {
             $from = $education_list[$value]['from_date'];
             $to = $education_list[$value]['to_date'];
             if($from!=$to) {
                 $edu = $from.' - '.$to;
             } else {
                 $edu = $from;
             }
             $out .='<li>
                 <div class="date">'.$edu.'</div> 
                 <div class="info">
                     <p class="semi-bold">'.$education_list[$value]['institution'].' ('.$education_list[$value]['level'].')</p> 
                 <p>'.$education_list[$value]['description'].'</p>
                 </div>
             </li>';
         }
         $out .='</ul>
     </div>
     <div class="resume_item resume_social">
       <div class="title">
            <p class="bold">SOCIAL</p>
          </div>
          <ul>';

          $internet_list = $data_cv['internet_list'];
          $arr_internet = array_keys($internet_list);
          foreach ($arr_internet as $key => $value) {
                  switch ($value) {
                      case 'skype':
                      $name = 'Skype';
                      $icon = '<i class="fab fa-skype"></i>';
                      break;
                      case 'facebook':
                      $name = 'Facebook';
                      $icon = '<i class="fab fa-facebook-square"></i>';
                      break;
                      case 'youtube-channel':
                      $name = 'Youtube Chanel';
                      $icon = '<i class="fab fa-youtube"></i>';
                      break;
                      case 'youtube-video':
                      $name = 'Youtube Video';
                      $icon = '<i class="fab fa-youtube"></i>';
                      break;
                      case 'twitter':
                      $name = 'Twitter';
                      $icon = '<i class="fab fa-twitter-square"></i>';
                      break;
                      case 'linked-in':
                      $name = 'Linkedin';
                      $icon = '<i class="fab fa-linkedin"></i>';
                      break;
                      case 'instagram':
                      $name = 'Instagram';
                      $icon = '<i class="fab fa-instagram-square"></i>';
                      break;
                      case 'google-plus':
                      $name = 'Google+';
                      $icon = '<i class="fab fa-google-plus"></i>';
                      break;
                      default:
                      $name='';
                      break;
                  }
            $out .= '<li>
              <div class="icon">
                '.$icon.'
              </div>
              <div class="data">
                <p class="semi-bold">'.$name.'</p>
                <p>'.$internet_list[$value].'</p>
              </div>
            </li>';

            }
            
          $out .='</ul>
     </div>
   </div>
 </div>
    </body>
    ';