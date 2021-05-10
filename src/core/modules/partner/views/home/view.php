<section>
    <div class="container">
        <div class="card">
            <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
                <h4 class="text-white"><?php echo $term_local_partner_header ?></h4>
                <a href="<?php echo $link_create?>" class="btn btn-sm btn-success float-right"> <i class="fa fa-plus"></i> <?php echo $term_create_new ?></a>
            </div>

            <!-- Card content -->
            <div class="card-body table-responsive">

                <input type="hidden" id="register_link" value="<?php echo $_SERVER['SERVER_NAME'].'/'.$register_link?>">
                <table class=" table table-striped table-bordered" id="partner_table" data-link-edit="<?php echo $link_edit?>" data-link-enable="<?php echo $link_enable ?>" data-link-disable="<?php echo $link_disable ?>" data-link-delete="<?php echo $link_delete ?>" >
                    <thead>
                    <tr>
                        <th class="th-sm">Name</th>
                        <th class="th-sm">Code</th>
                        <th class="th-sm">Country</th>
                        <th class="th-sm">Status</th>
                        <th class="th-sm text-center">Action List</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
                
            </div>
        </div>
    </div>
</section>