<div class="container">
    <!-- start of user admin -->
    <div class="row">

        <div class="col">

            <div class="card">

                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h4 class="m-0 font-weight-bold text-primary"><?php echo $term_page_header_list ?></h4>
                </div>
            
                <div class="card-body">


                    <table class="table table-striped table-bordered table-responsive-sm" id="list_job" summary="Paginated list of master job">
                        <thead>
                        <tr>
                            <th scope="col" class="th-sm">Job Code</th>
                            <th scope="col" class="th-sm">Total Demand</th>
                            <th scope="col" class="th-sm">Allocated</th>
                            <th scope="col" class="th-sm">Job Title</th>
                            <th scope="col" class="th-sm">Available Demand</th>
                            <th scope="col" class="th-sm">Action</th>
                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>


                    <div class="modal fade" id="add_demand" tabindex="-1" role="dialog">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="EditModal"><?php echo $term_add_demand_title ?></h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                </div>
                                <div class="modal-body">

                                    <!-- Form -->
                                    <form id="add_demand_form" class="" method="post" style="color: #757575;" action="<?php echo $myURL; ?>">
                                        <input type="hidden" id="job_speedy_code" name="job_speedy_code">
                                        <table id="add_demand_table" width="100%" class="table table-bordered" data-limit="100">
                                            <thead>
                                                <tr>
                                                    <th>Job</th>
                                                    <th>Principal</th>
                                                    <th>Demand</th>
                                                    <th >Allocated</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                        </table>
                                        <!-- Send button -->
                                        <button class="btn btn-outline-info btn-rounded btn-block z-depth-0 my-4 waves-effect" type="submit">Update </button>
                                    </form>
                                    <!-- Form -->
                                </div>
                            </div>
                        </div>
                    </div><!-- EditModal -->

                </div>
            </div>
        </div>
    </div>

    
    <!-- end of user admin -->

</div>