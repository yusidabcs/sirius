<!-- flight tab -->
<div id="flight" class="tab-pane fade in" role="tabpanel">

    <div class="card card-cascade mb-4">

        <div class="card-body card-body-cascade">
            <h4 class="card-header-title mb-3">Flight Documents</h4>
            <table id="flight_data" class="table table-responsive-md w-100">
                <thead>
                <tr>
                    <td>&nbsp;</td>
                    <td>Flight Number</td>
                    <td>Departure Date</td>
                    <td>Status</td>
                    <td>Flight</td>
                    <td>&nbsp;</td>
                </tr>
                </thead>
                <tfoot>
                &nbsp;
                </tfoot>
            </table>

        </div>

        <div class="card-footer text-center">
            <a href="<?php echo $flight_link.'/new'; ?>" class="btn btn-sm btn-info" type="button"  data-tooltip="true" data-placement="right" title="<?php echo $term_flight_add; ?>"><i class="far fa-plus-square"></i> <?php echo $term_flight_add; ?></a>
        </div>

    </div>
</div>
<!-- end flight tab -->