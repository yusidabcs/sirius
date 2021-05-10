<?php
if($this->_nav_useTopNav)
{
    ?>

    <nav class="mobile-nav d-block d-lg-none">
        <div class="mobile-nav-inner">
            <?php echo $this->_mobile_nav; ?>
        </div>
    </nav>

    <!-- Headers -->
    <header>

        <!-- Nav Bar -->
        <nav class="navbar <?php echo $this->_nav_topNavBar; ?> <?php echo $this->_nav_mainColour; ?>">
            <div class="container-fluid">
                <!-- Nav Brand -->
                <a class="navbar-brand" href="/"><?php echo $this->_siteTitle; ?></a>
                

                <button class="navbar-toggler d-block d-lg-none mobile-toggle">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse d-none d-lg-block" id="navbarContent">

                    <?php echo $this->_main_nav; ?>

                </div>
            </div>


        </nav>
        <!-- Nav Bar -->

    </header>
    <!-- Headers -->

    <?php
} else {
    ?>

    <!-- Nav is Off -->

    <?php
}
?>