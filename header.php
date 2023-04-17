<?php
include("db_conn.php"); 
$current_month = date('n');
$current_year = date('Y');
if ($current_month < 4) {
    // If current month is January, February, or March, the current financial year is the previous year
    $current_financial_year = substr(($current_year - 1), -2) . '-' . substr($current_year, -2);
} else {
    // Otherwise, the current financial year is the current year and the next year
    $current_financial_year = substr($current_year, -2) . '-' . substr(($current_year + 1), -2);
}
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
    $url = "https://";
else
    $url = "http://";


 

$url .= $_SERVER['HTTP_HOST'];

$url .= $_SERVER['REQUEST_URI'];

 
if (strpos($url, 'user')) {
    $cls = "active";
    $u = "user";
} else if (strpos($url, 'invoice')) {
    $cls = "active";
    $u = "invoice";
} else if (strpos($url, 'customer')) {
    $cls = "active";
    $u = "customer";
} else if (strpos($url, 'company')) {
    $cls = "active";
    $u = "company";
} else if (strpos($url, 'state')) {
    $cls = "active";
    $u = "state";
} else {
    $cls = "";
    $u = "";
}
?>
<nav class="navbar  navbar-expand-lg navbar-dark" style="background-color:#181D31;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link  dropdown-toggle <?= ($u == 'state') ? $cls : '' ?> " href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Master
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="state.php">State</a></li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link px-2 <?= ($u == 'company') ? $cls : '' ?> " href="company.php">Company</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($u == 'customer') ? $cls : '' ?>  " href="customer.php?fy=<?php echo $current_financial_year ?>">Customer</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($u == 'invoice') ? $cls : '' ?> " href="invoice.php?fy=<?php echo $current_financial_year ?>">Invoice</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($u == 'user') ? $cls : '' ?> " href="user.php">Users</a>
                </li>
            </ul>
            <li class="nav-item dropdown" style="list-style:none;">
                <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php if (!empty($_SESSION['user_name'])) : ?>
                        <span class="form_error">
                            <?= $_SESSION['user_name'] ?>
                        </span>
                    <?php endif; ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>

                </ul>
            </li>
        </div>
    </div>
</nav>
