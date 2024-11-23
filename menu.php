<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page file name

echo '
    <header class="head">
        <input type="checkbox" id="check">
        <label for="check" class="icons">
            <i class="fa fa-bars" id="menu-icon"></i>
            <i class="fa fa-times" aria-hidden="true" id="close-icon"></i>
        </label>

        <nav class="topnav" id="myTopnav">
            <a href="index.php" class="' . ($current_page === 'index.php' ? 'active' : '') . '">Home</a>
            <a href="profile.php" class="' . ($current_page === 'profile.php' ? 'active' : '') . '">Profile</a>
            <a href="my_kpi.php" class="' . ($current_page === 'my_kpi.php' ? 'active' : '') . '">KPI Indicator</a>
            <a href="my_activities.php" class="' . ($current_page === 'my_activities.php' ? 'active' : '') . '">List of Activities</a>
            <a href="my_challenge.php" class="' . ($current_page === 'my_challenge.php' ? 'active' : '') . '">Challenge and Future Plan</a>
    </nav>
    </header>';
?>
