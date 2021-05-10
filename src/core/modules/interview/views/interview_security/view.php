<div class="container">
    <div class="card">
        <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
            <h4 class="text-white"><?php echo $term_header ?></h4>
            <a href="#" class="btn btn-sm btn-success float-right btn-create-security"> <i class="fa fa-plus"></i> <?php echo $term_create_new ?></a>
        </div>
        <div class="card-body w-auto">
            <div class="container">

                <table class="table" id="list_interview_security" data-url="<?php echo $base_url?>">
                    <thead>
                    <tr>
                        <th><?php echo $term_tr_principal ?></th>
                        <th><?php echo $term_tr_country ?></th>
                        <th><?php echo $term_tr_checker ?></th>
                        <th></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="interview_security_modal" tabindex="-1" role="dialog" aria-labelledby="interview_security_modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="interview_security_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle"><?php echo $term_create_security ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="md-form">
                        <div class="float-right mr-4">
                            <div id="search_ab_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                        </div>
                        <input type="text" class="form-control" name="" id="search_ab">
                        <label for="search_ab"><?php echo $term_address_book_search?></label>
                        <div class="invalid-feedback">
                            <p class="alert alert-warning"><?php echo $term_address_book_email_not_found?></p>
                        </div>
                    </div>

                    <div class="not-showing" id="div_ab">
                        <div class="md-form">
                            <label for="address_book_id"><?php echo $term_label_checker?></label>
                            <select class="mdb-select md-form" id="address_book_id" name="address_book_id" searchable="Search" required>
                                <option value="" disabled selected>Choose Address Book</option>
                            </select>
                        </div>
                    </div>

                    <div class="md-form">
                        <label for="principal_code"><?php echo $term_label_principal?></label>
                        <select class="mdb-select md-form" name="principal_code" id="principal_code" searchable="Search" required>
                            <option value="" disabled selected>Choose Principal</option>
                            <?php foreach($principals as $key => $item) { ?>
                                <option value="<?php echo $item['code']?>"><?php echo $item['code']?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="md-form">
                        <label for="countryCode_id"><?php echo $term_label_country?></label>
                        <select class="mdb-select md-form" name="countryCode_id" id="countryCode_id" searchable="Search" required>
                            <option value="" disabled selected>Choose Country</option>
                            <?php foreach($countryCodes as $key => $item) { ?>
                                        <option value="<?php echo $key?>" ><?php echo $item?></option>

                            <?php } ?>
                        </select>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" >Save</button>
                </div>
            </div><!-- modal-content -->
        </form>
    </div>
</div>