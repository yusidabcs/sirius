<div class="container">
    <div class="row mt-3">
        <div class="col">
            <div class="card border-primary">
                <div class="card-header text-center d-flex justify-content-between">
                    <h3><?php echo $term_page_header ?></h3>
                    <a class="btn btn-sm btn-info" role="button" href="<?php echo $link_add; ?>"><i class="fa fa-plus"></i> <?php echo $term_button_go_add; ?></a>
                </div>

                <div class="card-body">
                    <table class="table table-striped table-responsive-sm" id="user_list_datatable" summary="Paginated list of users"
                           data-edit-link="<?php echo $link_edit?>"
                    >
                        <thead>
                        <tr>
                            <th scope="col" class="row-title"><?php echo $term_table_name; ?></th>
                            <th scope="col" class="row-email"><?php echo $term_table_email; ?></th>
                            <th scope="col" class="row-email"><?php echo $term_table_type; ?></th>
                            <th scope="col" class="row-created"><?php echo $term_table_created_on; ?></th>
                            <th scope="col" class="row-buttons">&nbsp;</th>
                        </tr>
                        </thead>
                    </table>

                    <div class="modal fade" id="address_book_preview" tabindex="-1" role="dialog" ria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog cascading-modal" role="document">

                            <div class="modal-content">

                                <!--Header-->
                                <div class="modal-header light-blue darken-3 white-text">
                                    <h4 class="title"></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="text-center mb-4">
                                    <img src="#" id="ab_avatar" alt="" height="100" width="100">
                                </div>
                                <!-- Information table -->
                                <div class="mx-3">
                                    <table class="table table-sm text-left">

                                        <tbody>
                                            <tr>
                                                <th>Username</th>
                                                <td id="ab_username">Not Set</td>
                                            </tr>
                                            <tr id="ab_gender_tr">
                                                <th>Gender</th>
                                                <td id="ab_gender">Not Set</td>
                                            </tr>
                                            <tr id="ab_born_tr">
                                                <th>Born</th>
                                                <td id="ab_born">Not Set</td>
                                            </tr>
                                            <tr id="ab_cn_tr">
                                                <th>Company Number</th>
                                                <td id="ab_cn">Not Set</td>
                                            </tr>
                                            <tr id="ab_key_contacts_tr">
                                                <th><?php echo $term_modal_main_ent_admin_details; ?></th>
                                                <td id="ab_key_contacts">No Key Contact Listed</td>
                                            </tr>
                                            <tr>
                                                <th>Email</th>
                                                <td id="ab_email">Not Set</td>
                                            </tr>
                                            <tr>
                                                <th>Address</th>
                                                <td id="ab_address">Not Set</td>
                                            </tr>
                                            <tr>
                                                <th>Telephone</th>
                                                <td id="ab_pots">Not Set</td>
                                            </tr>
                                            <tr>
                                                <th>Internet</th>
                                                <td id="ab_internet">Not Set</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>