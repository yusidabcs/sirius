<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-center"><?php echo $term_header ?></h4>

        </div>
        <div class="card-body w-auto">
            <table class="table" id="list_pool">
                <thead>
                    <tr>
                        <th>Job Speedy</th>
                        <th>Our Pool</th>
                        <th>Total Demand</th>
                        <th>Allocated</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="allocation_modal" tabindex="-1" role="dialog" aria-labelledby="allocation_modal"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"
                        id="exampleModalLongTitle"><?php echo $term_allocation_title ?> </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" value="" id="total_pool">
                    <table id="job_demand_table" width="100%" class="table table-bordered" data-limit="100">
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
                </div>
            </div><!-- modal-content -->
    </div>
</div>

<div class="modal fade" id="allocation_candidate" tabindex="-1" role="dialog" aria-labelledby="allocation_candidate"
     aria-hidden="true">
    <div class="modal-dialog modal-lg modal-notify modal-info" role="document">
        <form id="allocation_form" >
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-white"
                        id="exampleModalLongTitle">Candidate Allocation <span id="allocation_modal_title"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="allocation_info" class="text-center text-warning">You can only allocated <span id="allocation_total"></span> candidate for this job.</p>

                    <hr>

                    <table id="candidate_list" width="100%" class="table table-bordered" data-limit="100">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Interview Date</th>
                        </tr>
                        </thead>
                    </table>
                </div>

                <!--Footer-->
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary">Allocate Now </button>
                </div>
            </div><!-- modal-content -->

        </form>
    </div>
</div>