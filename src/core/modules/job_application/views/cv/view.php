<div class="container border p-5">
    <h1 class="text-center">Curriculum Vitae</h1>
    <h2>Personal Data</h2>
    <div class="row">

        <div class="col-md-8">

            <table class="table">
                <thead>
                <tr>
                    <td>Name</td><td>:</td><td><?php echo $cv['name']?></td>
                </tr>
                <tr>
                    <td>Date Of Birth</td><td>:</td><td><?php echo $cv['dob']?></td>
                </tr>
                <tr>
                    <td>Address</td><td>:</td><td><?php echo $cv['address']?></td>
                </tr>
                <tr>
                    <td>Nationality</td><td>:</td><td><?php echo $cv['country']?></td>
                </tr>
                <tr>
                    <td>Sex</td><td>:</td><td><?php echo $cv['sex']?></td>
                </tr>

                <tr>
                    <td>Height/Weight</td><td>:</td><td><?php echo $cv['hw']?></td>
                </tr>
                <tr>
                    <td>Phone Number</td><td>:</td><td><?php echo $cv['number']?></td>
                </tr>
                <tr>
                    <td>Email</td><td>:</td><td><?php echo $cv['main_email']?></td>
                </tr>

                </thead>
            </table>
        </div>
        <div class="col-md-4">
            <img src="/ab/show/<?php echo $cv['full_image']; ?>"  class="img-fluid z-depth-1" alt="" title="">
        </div>


    </div>


    <h2>Education Background</h2>
    <table class="table">
        <thead>
        <tr>
            <td>Level</td>
            <td>Institution</td>
            <td>Date</td>
        </tr>
        <?php foreach ($cv['education_list'] as $key => $education) { ?>
            <tr>
                <td><?php echo $education['level']?></td><td><?php echo $education['institution']?></td><td><?php echo $education['from_date']?> - <?php echo $education['to_date']?></td>
            </tr>
        <?php } ?>
        </thead>
    </table>

    <h2>Work Experience</h2>
    <table class="table">
        <thead>
        <tr>
            <td>Company</td>
            <td>Position</td>
            <td>Date</td>
        </tr>
        <?php foreach ($cv['employment_list'] as $key => $employement) { ?>
            <tr>
                <td><?php echo $employement['employer']?></td>
                <td><?php echo $employement['job_title']?><br>
                Job description:
                    <?php echo $employement['description']?>
                </td>
                <td><?php echo $employement['from_date']?> - <?php echo $employement['to_date']?></td>
            </tr>
        <?php } ?>
        </thead>
    </table>
</div>