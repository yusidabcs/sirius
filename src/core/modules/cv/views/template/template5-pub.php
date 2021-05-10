<?php
    $out = '
    <title>'.$data_cv['name'].'-CV</title>
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap"/>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap" media="print" onload="this.media=\'all\'"/>
<noscript>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&amp;family=Roboto:wght@300;400;500;700&amp;display=swap"/>
</noscript>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css" integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w==" crossorigin="anonymous" />
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style type="text/css">
    .timeline {
        border-left: 2px solid #E6E9ED;
        padding: 1rem 0; }
      
      .timeline-card {
        position: relative;
        margin-left: 31px;
        border-left: 2px solid;
        margin-bottom: 2rem; }
      
      .timeline-card:last-child {
        margin-bottom: 1rem; }
      
      .timeline-card:before {
        content: "";
        display: inline-block;
        position: absolute;
        background-color: #fff;
        border-radius: 10px;
        width: 12px;
        height: 12px;
        top: 20px;
        left: -41px;
        border: 2px solid;
        z-index: 2; }
      
      .timeline-card:after {
        content: "";
        display: inline-block;
        position: absolute;
        background-color: currentColor;
        width: 29px;
        height: 2px;
        top: 25px;
        left: -29px;
        z-index: 1; }
      
      .timeline-card-primary {
        border-left-color: #4A89DC; }
      
      .timeline-card-primary:before {
        border-color: #4A89DC; }
      
      .timeline-card-primary:after {
        background-color: #4A89DC; }
      
      .timeline-card-success {
        border-left-color: #37BC9B; }
      
      .timeline-card-success:before {
        border-color: #37BC9B; }
      
      .timeline-card-success:after {
        background-color: #37BC9B; }
      
      html {
        scroll-behavior: smooth; }
      
      .border-page {
        border : 1px solid #4A89DC;
      }
      .site-title {
        font-size: 1.25rem;
        line-height: 2.5rem; }
      
      
      .nav-link:hover,
      .nav-link:focus,
      .active .nav-link {
        color: rgba(0, 0, 0, 0.8); }
      
      .nav-item + .nav-item {
        margin-left: 1rem; }
      
      .cover {
        border-radius: 10px; }
      
      .cover-bg {
        background-color: #4A89DC;
        background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'%3E%3Cg fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.12\'%3E%3Cpath opacity=\'.5\' d=\'M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z\'/%3E%3Cpath d=\'M6 5V0H5v5H0v1h5v94h1V6h94V5H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      }
      
      .cover-bg h2 {
        text-transform: uppercase;
      }
      .avatar {
        max-width: 216px;
        max-height: 216px;
        margin-top: 20px;
        text-align: center;
        margin-left: auto;
        margin-right: auto; }
      
      .avatar img {.timeline {
        border-left: 2px solid #E6E9ED;
        padding: 1rem 0; }
      
      .timeline-card {
        position: relative;
        margin-left: 31px;
        border-left: 2px solid;
        margin-bottom: 2rem; }
      
      .timeline-card:last-child {
        margin-bottom: 1rem; }
      
      .timeline-card:before {
        content: "";
        display: inline-block;
        position: absolute;
        background-color: #fff;
        border-radius: 10px;
        width: 12px;
        height: 12px;
        top: 20px;
        left: -41px;
        border: 2px solid;
        z-index: 2; }
      
      .timeline-card:after {
        content: "";
        display: inline-block;
        position: absolute;
        background-color: currentColor;
        width: 29px;
        height: 2px;
        top: 25px;
        left: -29px;
        z-index: 1; }
      
      .timeline-card-primary {
        border-left-color: #4A89DC; }
      
      .timeline-card-primary:before {
        border-color: #4A89DC; }
      
      .timeline-card-primary:after {
        background-color: #4A89DC; }
      
      .timeline-card-success {
        border-left-color: #37BC9B; }
      
      .timeline-card-success:before {
        border-color: #37BC9B; }
      
      .timeline-card-success:after {
        background-color: #37BC9B; }
      
      html {
        scroll-behavior: smooth; }
      
      .border-page {
        border : 1px solid #4A89DC;
      }
      .site-title {
        font-size: 1.25rem;
        line-height: 2.5rem; }
      
      
      .cover {
        border-radius: 10px; }
      
      .cover-bg {
        background-color: #4A89DC;
        background-image: url("data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'100\' height=\'100\' viewBox=\'0 0 100 100\'%3E%3Cg fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.12\'%3E%3Cpath opacity=\'.5\' d=\'M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z\'/%3E%3Cpath d=\'M6 5V0H5v5H0v1h5v94h1V6h94V5H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
      }
      
      .avatar {
        max-width: 216px;
        max-height: 216px;
        margin-top: 20px;
        text-align: center;
        margin-left: auto;
        margin-right: auto; }
      
      .avatar img {
        /* Safari 6.0 - 9.0 */
        filter: grayscale(100%); }
      
      
      @media (min-width: 48em) {
        .page-content .site-title {
          float: left; }
          .page-content .site-nav {
          float: right; }
          .page-content .avatar {
          margin-bottom: -80px;
          margin-left: 0; } }
      
      @media print {
        body {
          background-color: #fff; 
          font-size: 20px;
        }
        .cover, .cover-bg {
          border-radius: 0; }
        .cover.shadow-lg {
          box-shadow: none !important; }
        .cover-bg {
          padding: 5rem !important;
          padding-bottom: 10px !important; }
          }
        /* Safari 6.0 - 9.0 */
        filter: grayscale(100%); }
      
      @media (min-width: 48em) {
        .page-content .site-title {
          float: left; }
          .page-content .site-nav {
          float: right; }
          .page-content .avatar {
          margin-bottom: -80px;
          margin-left: 0; } }
      
      @media print {
        body {
          background-color: #fff; 
          font-size: 20px;}
        .cover, .cover-bg {
          border-radius: 0; }
        .cover.shadow-lg {
          box-shadow: none !important; }
        .cover-bg {
          padding: 5rem !important;
          padding-bottom: 10px !important; }
       }
       
       .page-content {
         font-size : 20px;
       }
        .page-content .text-secondary {
            color: #6c757d!important;
        }

        .page-content .h6 {
          font-weight: 600;
        }
    </style>
    <body>

            <div class="page-content">
            <!-- <div class="container"> -->
            <div class="cover border-page bg-white">
                <div class="cover-bg p-5 text-white">
                <div class="row">
                    <div class="col-md-4">
                    <div class="avatar hover-effect bg-white shadow-sm p-1"><img src="'.$img.'" width="200" /></div>
                    </div>
                    <div class="col-md-8 text-left text-md-start">
                    <h2 class="h1 mt-4">'.$data_cv['name'].'</h2>';
                    $employment_list = $data_cv['employment_list'];
                    if(count($employment_list)>0) {
                        $arr_employment = array_keys($employment_list);
                        $job = $employment_list[$arr_employment[0]]['job_title'];
                    } else {
                        $job = 'Fresh Graduate';
                    }
                    $out .='<p >'.$job.'</p> 
                </div>
                </div>
            </div>
            <div class="about-section pt-0 px-5 mt-3">
                <div class="row">

                <div class="col-md-8 offset-md-4">
                    <div class="row mt-2">
                    <div class="col-sm-4">
                        <div class="pb-1">Gender</div>
                    </div>
                    <div class="col-sm-8">
                        <div class="pb-1 text-secondary">'.ucfirst($data_cv['sex']).'</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="pb-1">Birth Date</div>
                    </div>
                    <div class="col-sm-8">
                        <div class="pb-1 text-secondary">'.$data_cv['dob'].'</div>
                    </div>

                    <div class="col-sm-4">
                        <div class="pb-1">Email</div>
                    </div>
                    <div class="col-sm-8">
                        <div class="pb-1 text-secondary">'.$data_cv['main_email'].'</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="pb-1">Phone</div>
                    </div>
                    <div class="col-sm-8">
                        <div class="pb-1 text-secondary">'.$data_cv['number'].'</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="pb-1">Address</div>
                    </div>
                    <div class="col-sm-8">
                        <div class="pb-1 text-secondary">'.$data_cv['address'].', '.$data_cv['country'].'</div>
                    </div>
                    <div class="col-sm-4">
                        <div class="pb-1">Height/Weight</div>
                    </div>
                    <div class="col-sm-8">
                        <div class="pb-1 text-secondary">'.$data_cv['hw'].'</div>
                    </div>
                    </div>
                </div>
                </div>
            </div>
            <hr class=""/>
            <div class="work-experience-section px-5 pb-4">
                <h2 class="h3 mb-4">WORK EXPERIENCES</h2>
                <div class="timeline">';
                $employment_list = $data_cv['employment_list'];
                $arr_employment = array_keys($employment_list);
                foreach ($arr_employment as $key => $value) {
                    $from = date('M, Y',strtotime($employment_list[$value]['from_date']));
                    $to = date('M, Y',strtotime($employment_list[$value]['to_date']));
                    if($from!=$to) {
                        $exp = $from.' - '.$to;
                    } else {
                        $exp = $from;
                    }
                $out .='<div class="timeline-card timeline-card-primary card shadow-sm">
                    <div class="card-body">
                    <div class="h5 mb-1">'.$employment_list[$value]['job_title'].' <span class="text-muted h6">at '.$employment_list[$value]['employer'].'</span></div>
                    <div class="text-muted text-small mb-2">'.$exp.'</div>
                    <div>'.$employment_list[$value]['description'].'</div>
                    </div>
                </div>';
                }
                $out.='</div>
            </div>
            <hr class=""/>
            <div class="page-break"></div>
            <div class="education-section px-5 pb-4">
                <h2 class="h3 mb-4">EDUCATION</h2>
                <div class="timeline">';

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
                $out .='<div class="timeline-card timeline-card-success card shadow-sm">
                    <div class="card-body">
                    <div class="h5 mb-1">'.strtoupper($education_list[$value]['level']).' <span class="text-muted h6">from '.$education_list[$value]['institution'].'</span></div>
                    <div class="text-muted text-small mb-2">'.$edu.'</div>
                    <div>'.$education_list[$value]['description'].'</div>
                    </div>
                </div>';
                    }
                $out .='</div>
            </div>
            <hr class=""/>
            <div class="contant-section px-5 pb-4" id="contact">
                <h2 class="h3 text mb-3">INTERNET</h2>
                <div class="row">
                  <div class="col-md-7">
                      <div class="my-2">
                        <div class="mt-2">';
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

                          $out .='
                          <div class="pb-3">
                          <span class="h6 mr-1">'.$icon.' '.$name.'</span>
                          <span class="text-secondary">'.$internet_list[$value].'</span>
                          </div>
                          ';
                              
                          }
                        $out .='</div>
                      </div>
                  </div>
        
        
                </div>
              </div>
            </div>
            <!-- </div>     -->
        </div>

    </body>
    ';
?>