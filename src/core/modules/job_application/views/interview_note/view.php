<div class="container border p-5">
    <h1 class="text-center mb-5">Recruiter – Bulk Shipboard Interview Template</h1>

    <table class="table">
        <thead>
        <tr>
            <td>Candidate Name</td><td>:</td><td><?php echo $fullname?></td>
        </tr>
        <tr>
            <td>Recruiter Name</td><td>:</td><td><?php echo $result['interviewer']?></td>
        </tr>
        <tr>
            <td>Date of Interview</td><td>:</td><td><?php echo $result['created_on']?></td>
        </tr>
        <tr>
            <td>Position Interviewed </td><td>:</td><td><?php echo $job_application['job_title']?></td>
        </tr>
        <tr>
            <td colspan="3">
                <p class="text-danger">Thank you for taking time to apply and interview with me today for X POSITION.  We want to be sure to give you the opportunity to demonstrate how you will contribute to our organization and also how being a part of our organization is a journey that will shape your life</p>
                Remember that you are representing our company and the candidate should be treated as a guest – your role as the recruiter is to assess if the candidate would be a good fit for our organization and they share our values and vision: ~ Service with a friendly greeting and a smile ~ Anticipate the needs of our guests and make all efforts to exceed our clients’ expectations ~ All of us take ownership of any problem that is brought to our attention ~ We engage in conduct that enhances our corporate reputation and employee morale ~ We are committed to act in the highest ethical manner and respect the rights and dignity of others ~ We are loyal to our brands and strive for continuous improvement in everything we do
                Advise the candidate that the interview will take between 15-30 minutes and that you will ask questions and that you will be taking notes.  Let them know there will be time at the end for them to ask questions</td>
        </tr>
        </thead>
    </table>

    <h2>General Question</h2>
    <table class="table">
        <thead>
        <tr>
            <td width="5%">No</td>
            <td>Question</td>
            <td>Answer</td>
        </tr>
        <?php foreach ($answer as $key => $item) {
            if($item['type'] == 'general'){
        ?>
            <tr>
                <td><?php echo $key + 1?>.</td>
                <td><?php echo $item['question']?>:</td><td><?php echo $item['text']?></td>
            </tr>
        <?php
            }
        } ?>
        </thead>
    </table>

    <h2>Job Specific Question</h2>
    <table class="table">
        <thead>
        <tr>
            <td width="5%">No</td>
            <td>Question</td>
            <td>Answer</td>
        </tr>
        <?php foreach ($answer as $key => $item) {
            if($item['type'] == 'specific'){
                ?>
                <tr>
                    <td><?php echo $key + 1?>.</td>
                    <td><?php echo $item['question']?>:</td><td><?php echo $item['text']?></td>
                </tr>
                <?php
            }
        } ?>
        </thead>
    </table>

    <br>
    <br>
    <table class="table">
        <thead>
        <tr>
            <td>Recommend for hire?</td><td>:</td><td><?php echo $job_application['status'] == 'not_hired' ? 'Not hired' : 'hired' ?></td>
        </tr>
        <tr>
            <td>COMMUNICATION RATING SCALE:</td><td>:</td><td><?php echo $result['communication_level_skill']?></td>
        </tr>
        <tr>
            <td>Note:</td><td>:</td><td><?php echo $result['interview_comment']?></td>
        </tr>

        <tr>
            <td colspan="3">

                <table class="table  table-bordered table-responsive-sm">
                    <tr>
                        <td width="20%">1</td>
                        <td width="20%">2</td>
                        <td width="20%">3</td>
                        <td width="20%">4</td>
                        <td width="20%">5</td>
                    </tr>
                    <tr>
                        <td>Excellent</td>
                        <td>Favorable</td>
                        <td>Acceptable</td>
                        <td>Unfavorable</td>
                        <td>Highly Unfavorable</td>
                    </tr>
                    <tr>
                        <td >
                            <p>Clearly spoke throughout the interview; Answered questions clearly; indicated he/she was engagement throughout the interview; Provided focused responses.</p>

                            <p>First language may not be English, has a non-native accent, a lack of native slang or expressions, a limited control of deep cultural language and/or an occasional isolated language error may still be present at this level</p>
                        </td>
                        <td></td>
                        <td>
                            <p>Clearly spoke and in an engaged manner for most of the interview; Provided clear responses, the questions were handled concretely and described appropriately using the time frames of past, present, and future.  Spoke in well-constructed paragraphs responding to questions directly and succinctly. He/She is easily understood by native English language speakers, including those unaccustomed to non-native speech.</p>
                        </td>
                        <td></td>
                        <td>
                            <p>Did not articulate responses; Lost focus throughout interview and did not provide logical explanations; was only able to communicate in short sentences; primarily used isolated words and phrases. </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </thead>
    </table>

</div>