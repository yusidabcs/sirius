

<h3 class="card-header peach-gradient white-text text-center py-4">
    Create and Share Your Engaging CV
</h3>

<div class="card-body">
    <p>Do you have trouble to create beautiful CV that impress the company? Don't worry, you can create your online CV here.</p>

    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
  <div class="carousel-inner">
    <div class="carousel-item active">
      <img src="/core/images/cv.jpg" class="d-block w-100" alt="...">
    </div>
  </div>
  <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>

</div>

<div class="card-footer text-center">
        <?php
            if ( (!empty($verification)) && ($verification['status'] == 'verified') )
            {
        ?>
          <button class="btn btn-success btn-sm waves-effect waves-light" role="button" id="btn_cv"><i class="fas fa-book"></i> Generate CV</button>

          <div class="modal fade" id="modalCV" tabindex="-1" aria-labelledby="modalCV" aria-hidden="true" data-backdrop="static">
              <div class="modal-dialog modal-frame modal-top" role="document">
                  <div class="modal-content transparent-modal">
                      <div class="modal-body cv-modal">
                          <div class="row d-flex">
                              <div class="col-lg-5 col-xs-12 text-left">
                                  <form class="form-inline">
                                      <label class="mr-sm-3">CV Template</label>
                                      <select id="cv-template" class="custom-select form-control mr-sm-3">
                                          <option value="template1">Template 1</option>
                                          <option value="template2">Template 2</option>
                                          <option value="template3">Template 3</option>
                                          <option value="template4">Template 4</option>
                                          <option value="template5">Template 5</option>
                                          <option value="template6">Template 6</option>
                                      </select>
                                      <div style="display: none;" class="spinner" id="loading-cv" ></div>
                                  </form>
                              </div>
                              <div class="col-lg-7 col-xs-12 text-right">
                                  <button id="download-cv" data-link="<?php echo HTTP_TYPE.SITE_WWW?>/ajax/cv/main/export-public-cv/" type="button" class="btn btn-primary btn-md">Download CV</button>
                                  <button id="cp-link-cv" type="button" class="btn btn-success btn-md" data-link="<?php echo $_SERVER['SERVER_NAME']?>/cv/share/" data-hash="">Generate Public Link</button>
                                  <button type="button" class="btn btn-danger btn-md" data-dismiss="modal">Close</button>
                              </div>
                          </div>
                      </div>

                      <div id="cv-content">
                          
                      </div>

                  </div>
                  
              </div>
          </div>
        <?php
            }else{
        ?>
        <p class="text-warning text-center font-italic">Please complete the personal data and verify your data to access CV generator!</p>
        <?php
          }
        ?>
        
</div>
