<?php
	if($this->_nav_useTopNav)
	{
?>

<!-- Headers -->
<header>

	<!-- Nav Bar -->
	<nav class="navbar <?php echo $this->_nav_topNavBar; ?> <?php echo $this->_nav_mainColour; ?>">
        <div class="container">
            <!-- Nav Brand -->
            <a class="navbar-brand" href="/"><?php echo $this->_siteTitle; ?></a>

            <!-- Collapse Button -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">

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