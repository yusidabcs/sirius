<?php if($show_job) {?>

<h3 class="card-header peach-gradient white-text text-center py-4">
    <?php echo $term_job_title; ?>
</h3>

<div class="card-body">
    <?php if (!empty($job_info)){?>
    <table class="table table-bordered table-responsive-sm">
        <thead>
        <tr>
            <th>Job Title</th>
            <th>Job Description</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($job_info as $key => $item) {?>
            <tr>
                <td>
                    <?php echo $item['job_title'] ?>
                    <br>
                    (<?php echo $item['job_speedy_code'] ?>)
                </td>
                <td width="300"><?php echo $item['short_description'] ?></td>
                <td class="text-center">
                    <a href="<?php echo $job_application_link.'/applyjob/'.$item['job_speedy_code'] ?>" class="btn btn-sm btn-info"><i class="fa fa-running"></i> Apply Now</a>
                </td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <?php }else{?>
        <div class="alert alert-danger">
            There are no job applications based on your personal data, please complete all minimum requirements to apply for a job.
        </div>
    <?php }?>
    <div class="d-flex justify-content-center">
        <a href="<?php echo $job_application_link.'/home' ?>" class="btn btn-link btn-info btn-sm"><< My Application</a>
        <a href="<?php echo $job_application_link.'/listjob' ?>" class="btn btn-link btn-success btn-sm <?php echo (!empty($job_info))?:'disabled'?>">Show All Job >></a>
    </div>

</div>

<div class="card-footer">
</div>

<?php }?>