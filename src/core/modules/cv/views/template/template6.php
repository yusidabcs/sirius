<?php 
    $out = '
    <title>'.$data_cv['name'].'-CV</title>
    <link rel="stylesheet" href="https://www.rugstudio.com/Shared/Themes/RugStudio2015/css/deputy.css">
    <style type="text/css">
    .cv {
      background-color : #fff;
      // border: 1px solid #000;
      box-shadow: 0 1px #FFFFFF inset, 0 1px 3px rgba(34, 25, 25, 0.4);
    }
    
    .cv .avatar {
      background: #ffffff;
      background: -moz-linear-gradient(top, #ffffff 0%, #ffffff 50%, #454545 50%, #454545 100%);
      background: -webkit-linear-gradient(top, #ffffff 0%, #ffffff 50%, #454545 50%, #454545 100%);
      background: linear-gradient(to bottom, #ffffff 0%, #ffffff 50%, #454545 50%, #454545 100%);
      filter: progid: DXImageTransform.Microsoft.gradient( startColorstr="#ffffff", endColorstr="#454545", GradientType=0);
      height: auto;
      text-align: center;
    }
    
    .cv .avatar img {
      padding: 5px;
      border-radius: 50%;
      margin-top: 25px;
      background: #fff;
      max-width:250px;
    }
    
    .cv .gray {
      background: #454545;
    }
    
    .cv .bio {
      background: #454545;
      color: #fff;
    }
    
    .cv .bio hr {
      border: 0;
      height: 1px;
      background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(255, 255, 255, 0.75), rgba(0, 0, 0, 0));
    }
    
    .cv .about h3,
    .cv .contact h3 {
      background-color: #4886a5;
      box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
      box-sizing: border-box;
      color: #fff;
      font-size: 1.4em;
      margin: 0 0 24px 15px;
      padding: 4px 0 4px 12px;
      position: relative;
      width: 105%;
    }
    
    .cv .about h3:after,
    .cv .contact h3:after {
      border-color: #263746 transparent transparent #263746;
      border-style: solid;
      border-width: 6px;
      bottom: -12px;
      content: "";
      position: absolute;
      right: 0;
    }
    
    .cv .about h3:before,
    .cv .contact h3:before {
      color: #fff;
      content: "";
      font: 26px sans-serif;
      position: absolute;
      right: 12px;
      top: -2px;
    }
    
    .cv .contact {
      font-size: 1.2em;
    }

    .cv .about {
      font-size: 0.9em;
    }

    .cv .about .list-icon {
      padding-bottom : 10px;
      display : flex;
    }

    .cv .about .list-icon .icon {
      margin-right: 10px;
      float : left
    }

    .cv .about .data {
    }
    
    .cv .circle {
      padding: 9px;
      border: 2px solid #fff;
      border-radius: 50%;
    }

    .cv .social .circle {
      padding: 10px;
      border: 2px solid #454545;
      border-radius: 50%;
      color: #454545;
    }
    
    .cv .experience .fas,
    .cv .education .fas,
    .cv .social .fas,
    .cv .work-skills .fas,
    .cv .personal-skills .fas {
      font-size: 1.5em;
    }

    
    .cv .experience hr,
    .cv .education hr,
    .cv .social hr,
    .cv .work-skills hr,
    .cv .personal-skills hr {
      border-top: 1px dashed #000;
    }
    
    .cv .experience .date,
    .cv .education .date,
    .cv .work-skills .date,
    ,
    .cv .personal-skills .date {
      font-size: 1em;
    }

    .cv .experience .date .date-range,
    .cv .education .date  .date-range {
      font-size: 0.9em;
    }
    
    .cv .experience .title,
    .cv .education .title,
    .cv .social .title,
    .cv .work-skills .title,
    .cv .personal-skills .title {
      color: #4886a5;
      font-weight: bold;
    }
    
    .cv .name {
        text-transform: uppercase;
        font-size : 20px;
    }

    .cv h4 {
      font-size : 18px;
    }
    
    body {
      
    }
    
    @media (max-width: 768px) {
    .mobile-no-pad {
      padding-left:0!important;
      padding-right:0!important;
    }
      body {padding: 0;}
      
      .about h3,
    .contact h3 {
      margin: 0 0 10px 0;
      padding: 4px 0 4px 12px;
    }
      .about h3:after,
    .contact h3:after {
      display:none;
    }
      
     .about h3:before,
      .contact h3:before {
        right: 0;
        top: -2px;
      }
    }

    .right-content {
        // background-color : #ffffff
    }
    
    
    </style>
    
    <body>
    <div class="cv col-md-12">
      <div class="row">
        <!-- Left Column -->
        <div class="col-md-4 matchHeight no-pad-lr gray"> 
          <div class="col-md-12 avatar">
            <img src="'.$img.'">
          </div>
          <div class="col-md-12 bio pad-b-20 pt-4">
            <div class="center">
              <h3 class="name">'.$data_cv['name'].'</h3>
              <hr>';
              $employment_list = $data_cv['employment_list'];
                if(count($employment_list)>0) {
                    $arr_employment = array_keys($employment_list);
                    $job = $employment_list[$arr_employment[0]]['job_title'];
                } else {
                    $job = 'Fresh Graduate';
                }
                $out .='<h4>'.$job.'</h4>
            </div>
            <div class="about m-t-40">
              <h3>ABOUT</h3>
              <div class="pad-l-15">

                <div class="list-icon">
                  <div class="icon">
                    <i class="fas fa-venus-mars circle"></i>
                  </div> 
                  <div class="justify-content-center align-self-center">
                    '.ucfirst($data_cv['sex']).'
                  </div>
                </div>
  
                <div class="list-icon">
                  <div class="icon">
                    <i class="fas fa-birthday-cake circle"></i>
                  </div>
                  <div class="justify-content-center align-self-center">
                    '.$data_cv['dob'].'
                  </div>
                </div>
                <div class="list-icon">
                  <div class="icon">
                    <i class="fas fa-phone-square-alt circle"></i>
                  </div>
                  <div class="justify-content-center align-self-center">
                    '.$data_cv['number'].'
                  </div>
                </div>
                <div class="list-icon">
                  <div class="icon">
                    <i class="fas fa-map-signs circle"></i> 
                  </div>
                  <div class="justify-content-center align-self-center">
                    '.$data_cv['address'].',</br>'.$data_cv['country'].'
                  </div>
                </div>
                <div class="list-icon">
                  <div class="icon">
                    <i class="fas fa-envelope circle"></i>
                  </div>
                  <div class="justify-content-center align-self-center">
                      '.$data_cv['main_email'].'</span>
                  </div>
                </div>
                <div class="list-icon">
                  <div class="icon">
                    <i class="fas fa-weight circle"></i>
                  </div> 
                  <div class="justify-content-center align-self-center">
                    '.$data_cv['hw'].'</span>
                  </div>
                </div>
              
              </div>
            </div>
            
          </div>
        </div>
        <!-- End Left Column -->
        
        <!-- Right Column -->
        <div class="col-md-8 matchHeight no-pad-r right-content">
          
          <!-- Start Experience -->
          <div class="col-md-12 mt-5">
            <div class="experience">
              <p><i class="fas fa-briefcase"></i><span class="h3"> EXPERIENCE</span></p>
              <hr>';
              
              $employment_list = $data_cv['employment_list'];
                $arr_employment = array_keys($employment_list);
                foreach ($arr_employment as $key => $value) {
                    $from = date('M Y',strtotime($employment_list[$value]['from_date']));
                    $to = date('M Y',strtotime($employment_list[$value]['to_date']));
                    if($from!=$to) {
                        $exp = $from.' - '.$to;
                    } else {
                        $exp = $from;
                    }

              $out .='<div class="row m-b-10"> 
                <div class="col-md-4">
                  <p class="date"><span class="font-weight-bold">'.$employment_list[$value]['employer'].'</span><br><span class="date-range"><i>'.$exp.'</i><span></p>
                </div>
                <div class="col-md-7 col-md-offset-1">
                  <p class="title">'.$employment_list[$value]['job_title'].'</p>
                  <p>'.$employment_list[$value]['description'].'</p>
                </div>
              </div>';
            
                }
                
            $out .='</div>
          </div>
          <!-- End Experience -->
          
          <!-- Start Education -->
          <div class="col-md-12 mt-5">
            <div class="education">
              <p><i class="fas fa-graduation-cap"></i><span class="h3"> EDUCATION</span></p>
              <hr>';

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

              $out .='<div class="row m-b-10">
                <div class="col-md-4">
                  <p class="date"><span class="font-weight-bold">'.strtoupper($education_list[$value]['level']).'</span><br><span class="date-range"><i>'.$edu.'</i></span></p>
                </div>
                <div class="col-md-7 col-md-offset-1">
                  <p class="title">'.$education_list[$value]['institution'].'</p>
                  <p>'.$education_list[$value]['description'].'</p>
                </div>
              </div>';
                }
                
            $out .='</div>
          </div>
          <!-- End Education -->

          <!-- Start Social -->
          <div class="col-md-12 mt-5">
            <div class="social">
              <p><i class="fas fa-atlas"></i><span class="h3"> SOCIAL</span></p>
              <hr>
                <div class="">';
                $internet_list = $data_cv['internet_list'];
                        $arr_internet = array_keys($internet_list);
                        foreach ($arr_internet as $key => $value) {
                                switch ($value) {
                                    case 'skype':
                                    $name = 'Skype';
                                    $icon = '<i class="fab fa-skype circle"></i>';
                                    break;
                                    case 'facebook':
                                    $name = 'Facebook';
                                    $icon = '<i class="fab fa-facebook-square circle"></i>';
                                    break;
                                    case 'youtube-channel':
                                    $name = 'Youtube Chanel';
                                    $icon = '<i class="fab fa-youtube circle"></i>';
                                    break;
                                    case 'youtube-video':
                                    $name = 'Youtube Video';
                                    $icon = '<i class="fab fa-youtube circle"></i>';
                                    break;
                                    case 'twitter':
                                    $name = 'Twitter';
                                    $icon = '<i class="fab fa-twitter-square circle"></i>';
                                    break;
                                    case 'linked-in':
                                    $name = 'Linkedin';
                                    $icon = '<i class="fab fa-linkedin circle"></i>';
                                    break;
                                    case 'instagram':
                                    $name = 'Instagram';
                                    $icon = '<i class="fab fa-instagram-square circle"></i>';
                                    break;
                                    case 'google-plus':
                                    $name = 'Google+';
                                    $icon = '<i class="fab fa-google-plus circle"></i>';
                                    break;
                                    default:
                                    $name='';
                                    break;
                                }
              
              $out .='<p>'.$icon.' '.$internet_list[$value].'</p>';
                              }
            
            $out .='
            </div>
            </div>
          </div>
          
        </div>
        <!-- End Right Column -->
      </div>
    </div>
    </body>
    
    ';

?>