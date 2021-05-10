<section id="app">
    <div class="container">
        <div class="card">
            <div class="card-header gradient-card-header blue-gradient">
                <h4 class="text-white text-center"><?php echo $term_local_principal_header ?></h4>
            </div>
            <!-- Card content -->
            <div class="card-body">
                <!-- Form -->

                <form class="" style="color: #757575;" id="principal_form">

                    <input type="hidden" id="principal_id" value="<?php echo $principal_id ?>">
                    <p>Address book detail.</p>
                    <div class="border m-0 p-3">
                        <button class="btn btn-danger float-right edit-address_book">Edit</button>
                        <p>Name: <span id="ab_name"></span></p>
                        <p>Email: <span id="ab_email"></span></p>
                        
                    </div>
                    <p class="alert alert-info principal_code_format mb-2"><?php echo $term_principal_code_format?></p>
                    <!-- Principal Code -->
                    <div class="md-form mt-3">
                        <div class="float-right mr-4">
                            <div id="principal_code_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                            <div id="principal_code_valid" class="fa fa-lg fa-check mt-2 position-absolute text-success" role="status" aria-hidden="true"></div>
                        </div>   
                        <input type="text" id="principal_code" name="code" class="form-control" required maxlength="10">
                        <label for="principal_code"><?php echo $term_principal_code ?></label>
                        <div class="invalid-feedback">
                            <p class="alert alert-warning"><?php echo $term_principal_code_should_unique?></p>
                        </div>
                    </div>

                    <div class="md-form">
                        <div >
                            <h4><?php echo $term_brand_list_title ?> 
                                <button type="button" class="btn btn-success btn-sm" id="add_brand" @click="addbrand"><i class="fa fa-plus"></i> Add Brand</button>
                            </h4>
                            <ul class="list-group" id="brand_place">
                                   
                            </ul>
                        </div>
                    </div>
                    <hr>
                    <!-- Send button -->
                    <div class="justify-content-center">
                        <div class="row flex-column-reverse flex-lg-row">
                            <div class="col-lg-6 left">
                                <a id="go_back" href="<?php echo $principal_link ?>" class="btn btn-warning font-weight-bold btn-sm-mobile-100 waves-effect" role="button"><i class="fas fa-arrow-circle-left"></i> Back</a>
                            </div>
                            <div class="col-lg-6 right">
                                <button type="submit" class="btn btn-success font-weight-bold btn-sm-mobile-100 waves-effect"><i class="fas fa-save"></i> Save</button>
                            </div>
                        </div>
                        <!-- <a href="<?php echo $principal_link ?>" class="btn back-btn btn-warning btn-rounded waves-effect">Back</a>
                        <button class="btn btn-info btn-rounded waves-effect btn-principal" type="submit">Save</button> -->
                    </div>

                </form>
                <!-- Form -->

                <div class="modal fade" id="brand_form_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <form id="brand_form" v-on:submit.prevent="submitbrand">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold"><?php echo $term_brand_new_title ?></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body mx-3">
                                    <div class="md-form mb-5">
                                        <input type="text" name="name" id="name" class="form-control validate" required>
                                        <label for="name"><?php echo $term_brand_name ?></label>
                                    </div>
                                    <div class="md-form mb-4">
                                        <div class="float-right mr-4 text-black-50">
                                            <div id="code_spinner" class="not-showing spinner-border position-absolute" role="status" aria-hidden="true"></div>
                                            <div id="code_valid" class="not-showing fa fa-lg fa-check mt-2 position-absolute text-success" role="status" aria-hidden="true"></div>
                                        </div>
                                        <input type="text"  name="code" id="code" class="form-control validate" required pattern="[a-zA-Z0-9]+">
                                        <label for="code"><?php echo $term_brand_code ?></label>
                                        <div class="invalid-feedback">
                                            <p class="alert alert-warning"><?php echo $term_brand_code_error?></p>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="submit" class="btn btn-default btn-brand">SAVE</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Update address book modal -->
                <div class="modal fade" id="update_ab_form_modal" tabindex="-1" role="dialog" aria-labelledby="abModal"
                     aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        
                        <form id="update_ab_form" v-on:submit.prevent="submitUpdateAB">
                            <input type="hidden" id="page_link" value="<?php echo $page_link?>">
                            <input type="hidden" id="old_ab">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title w-100 font-weight-bold"><?php echo $term_update_ab_title ?></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body mx-3">
                                    <p><?php echo $term_principal_search_by_address_book?></p>
                                    <div class="row border m-0">
                                        <div class="col-md-12">
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
                                            
                                        </div>
                                        <div class="col-md-12 not-showing" id="div_ab">
                                            <div class="md-form">
                                                <select class="mdb-select md-form" id="new_ab" searchable="<?php echo $term_general_search?>">
                                                    <option value="" disabled selected>Choose Address Book</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer d-flex justify-content-center">
                                    <button type="button" class="btn btn-default btn-edit-address_book" disabled>EDIT</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>