<?php 
  error_reporting(E_ALL ^ E_NOTICE);

  $action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : null;
  $adminSection = isset($_REQUEST["as"]) ? $_REQUEST["as"] : null;
  $adminSubSection = isset($_REQUEST["ass"]) ? $_REQUEST["ass"] : null;

  $add2subMenClass[$adminSubSection] = "active";

  //datatables
  if ($adminSection == "files") {
    if (!isset($add2header)) $add2header = "";
    $add2header .= "<!-- datatables [+] -->
      <script src='js/jquery.dataTables.min.js' type='text/javascript'></script>
      <script src='js/jquery.dataTables.bootstrap.js' type='text/javascript'></script>
      <!-- datatables [-] -->\n";
  }

  //charts
  if ($adminSection == "charts") {
    if (!isset($add2header)) $add2header = "";
    $add2header .= "<!-- for the Flot Charts [+] -->  
      <script src='js/flot/jquery.flot.js' type='text/javascript'></script>
      <script src='js/flot/jquery.flot.pie.js' type='text/javascript'></script>
      <!-- for the Flot Charts [-] -->\n";
  }

  $currPage = "admin";




  include("includes/header.php"); 

  //logout
  if ($action == "logout") {
    $sfs_auth = $_SESSION["sfs_auth"] = false;
    $logged_out_success = "Logged out successfully";
  }


  $adminPage = $_SERVER["PHP_SELF"];
?>

<div class='container'>
  <div class='row'>
    <div class='col-xs-12'>


  <div class="page-header">
    <h1><?php echo $config->siteName; ?> <small>protected administration area</small> <sup class="badge">V <?php echo preg_match("/\./",$SFS->config->version) ? $SFS->config->version : number_format($SFS->config->version,1); ?></sup></h1>
  </div>


<?php
/******
 * not authenticated -> Login
 *****/
  if (!$sfs_auth) {
    include("includes/admin/login.php");
  }



/******
 * authenticated -> admin stuff [+]
 *****/
  if ($sfs_auth) {

    //nav selection
    if (!$adminSection) $adminSection = "dashboard";

    $actAdmMen[$adminSection] = " class='active'";


    $envCheckErrors = $SFS->checkEnvironment();


  if ($envCheckErrors) {
    echo "<div class='container mt-3'>
      <div class='alert alert-danger'><h5 class='m-0'>Environment-Issues found</h5>
        <ul class='mb-0'><li>" . implode("</li><li>",$envCheckErrors) . "</li></ul>
      </div>
    </div>";
  }

?>
  <!-- Admin navigation -->
      <ul class="nav nav-tabs mb20 nav-admin">
        <li<?php echo isset($actAdmMen["dashboard"]) ? $actAdmMen["dashboard"] : ""; ?>><a href="<?php echo $adminPage; ?>"><i class='fa fa-tachometer'></i> <span>Dashboard</span></a></li>
        <li<?php echo isset($actAdmMen["files"]) ? $actAdmMen["files"] : ""; ?>><a href="<?php echo $adminPage; ?>?as=files"><i class='fa fa-hdd-o'></i> <span>Files</span></a></li>
        <li<?php echo isset($actAdmMen["charts"]) ? $actAdmMen["charts"] : ""; ?>><a href="<?php echo $adminPage; ?>?as=charts"><i class='fa fa-bar-chart'></i> <span>Statistics</span></a></li>
        <li<?php echo isset($actAdmMen["settings"]) ? $actAdmMen["settings"] : ""; ?>><a href="<?php echo $adminPage; ?>?as=settings"><i class='fa fa-cogs'></i> <span>Settings</span></a></li>
        <li<?php echo isset($actAdmMen["modules"]) ? $actAdmMen["modules"] : ""; ?>><a href="<?php echo $adminPage; ?>?as=modules"><i class='fa fa-leaf'></i> <span>Modules</span></a></li>
        <li class='navbar-right'><a href="<?php echo $adminPage; ?>?action=logout"><i class='fa fa-power-off'></i> <span>Logout</span></a></li>
      </ul>
<?php

  if ($adminSection == "dashboard") {
    include("includes/admin/dashboard.php");
  }

  elseif ($adminSection == "files") {
    include("includes/admin/files.php");
  }

  elseif ($adminSection == "charts") {
    include("includes/admin/charts.php");
  }

  elseif ($adminSection == "settings") {
    include("includes/admin/settings.php");
  }

  elseif ($adminSection == "modules") {
    include("includes/admin/modules.php");
  }



  }
/******
 * authenticated -> admin stuff [-]
 *****/

?>

    </div>
  </div>
</div>

    

<?php include("includes/footer.php"); ?>
