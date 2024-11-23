<?php
$current_page = basename($_SERVER['PHP_SELF']); // Get the current page file name

echo '
    <div id="subkpi">
        <ul class="sidenav">
            <li><a href="my_kpi.php" class="' . ($current_page === 'my_kpi.php' ? 'active' : '') . '">KPI Indicator</a></li>
            <li><a href="activities_management.php" class="' . ($current_page === 'activities_management.php' ? 'active' : '') . '">Activities Management</a></li>
            <li><a href="competition_management.php" class="' . ($current_page === 'competition_management.php' ? 'active' : '') . '">Competition Management</a></li>
            <li><a href="certification_management.php" class="' . ($current_page === 'certification_management.php' ? 'active' : '') . '">Certification Management</a></li>
        </ul>
    </div>
    ';
?>
