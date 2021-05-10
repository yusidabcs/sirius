<section>
    <div class="container">
        <div class="card ">
            <div class="card-header gradient-card-header blue-gradient d-flex justify-content-between">
                <h4 class="text-white "><?php echo $term_page_header ?></h4>
            </div>

            <div class="card-body">
                    <?php 
                        $html = '';
                        if ($status == 'confirmed'){
                            $html = $term_confirmed;
                        }elseif ($status == 'accept'){
                            $html = $term_confirm_agree;
                        }elseif ($status == 'reject'){
                            $html = $term_confirm_disagree;
                        }
                        echo $html;
                    ?>
            </div>

        </div>
    </div>
</section>