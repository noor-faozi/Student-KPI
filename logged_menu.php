<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page file name

$profile_pages = array(
    'profile.php',
    'profile_edit.php',
);

$kpi_pages = array(
    'my_kpi.php',
    'my_kpi_edit.php',
    'activities_management.php',
    'competition_management.php',
    'certification_management.php',
    'activities_management_edit.php',
    'competition_management_edit.php',
    'certification_management_edit.php',
);

$activities_pages = array(
    'my_activities.php',
    'my_activities_edit.php',
    'my_activities_search.php',
);

$challenge_pages = array(
    'my_challenge.php',
    'my_challenge_edit.php',
    'my_challenge_search.php',
);

echo '
    <header class="head">
        <input type="checkbox" id="check">
        <label for="check" class="icons">
            <i class="fa fa-bars" id="menu-icon"></i>
            <i class="fa fa-times" aria-hidden="true" id="close-icon"></i>
        </label>    

        <nav class="topnav" id="myTopnav">
		<a href="index.php" class="' . ($current_page === 'index.php' ? 'active' : '') . '" >Home</a>';

// Check if the current page is in the profile_pages array
if (in_array($current_page, $profile_pages)) {
    echo '<a href="profile.php" class="active">Profile</a>';
} else {
    echo '<a href="profile.php">Profile</a>';
}

// Check if the current page is in the kpi_pages array
if (in_array($current_page, $kpi_pages)) {
    echo '<a href="my_kpi.php" class="active">KPI Indicator</a>';
} else {
    echo '<a href="my_kpi.php">KPI Indicator</a>';
}

// Check if the current page is in the activities_pages array
if (in_array($current_page, $activities_pages)) {
    echo '<a href="my_activities.php" class="active">List of Activities</a>';
} else {
    echo '<a href="my_activities.php">List of Activities</a>';
}

// Check if the current page is in the challenge_pages array
if (in_array($current_page, $challenge_pages)) {
    echo '<a href="my_challenge.php" class="active">Challenge and Future Plan</a>';
} else {
    echo '<a href="my_challenge.php">Challenge and Future Plan</a>';
}

echo '
            <a href="logout.php">Logout</a>
        </nav>
    </header>';
?>
