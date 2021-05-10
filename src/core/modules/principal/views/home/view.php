<section app="">
    <div class="container">
        <div class="card">
            <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between align-items-center">
                <h4 class="text-white text-center"><?php echo $term_local_principal_header ?></h4>

                <a href="<?php echo $create_principal_link ?>" class="btn btn-success btn-sm"> <i class="fa fa-plus"></i> <?php echo $term_create_principal ?></a>
            </div>
            <!-- Card content -->
            <div class="card-body">

                <table id="list_principal" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                    <tr>
                        <th class="th-sm">Name
                        </th>
                        <th class="th-sm">
                        </th>
                    </tr>
                    </thead>
                </table>

            </div>

        </div>
    </div>
</section>