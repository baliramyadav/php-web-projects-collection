<?php
  $user = $pass = null;
  $error = array();
  $success = false;
  if($action == "login") {
    $user = isset($_REQUEST["user"]) ? trim($_REQUEST["user"]) : null;
    $pass = isset($_REQUEST["pass"]) ? trim($_REQUEST["pass"]) : null;
    if (!$user) $error[] = "Please type in your username.";
    if (!$pass) $error[] = "Please type in your password.";
    if ($user != $config->user || $pass != $config->pass) $error[] = "Username and password combination not found.";
    if (!$error) {
      $sfs_auth = $_SESSION["sfs_auth"] = $success = true;
    }
  }

  //not loggedin
  if (!$success) {
?>




    <?php 
      if ($error) {
        echo "<div class='span6 offset3'><div class='alert alert-danger'><strong>Errors occured</strong><ul><li>" . implode("</li><li>",$error) . "</li></ul></div></div>";
      }
      if (isset($logged_out_success) && $logged_out_success) {
        echo "<div class='span6 offset3'><div class='alert alert-success'>$logged_out_success</div></div>";
      }
    ?>

  <div class='col-xs-12 col-xs-offset-0 col-sm-6 col-sm-offset-3 col-md-4 col-md-offset-4'>
    <form method="post" action="<?php echo $adminPage; ?>" class='form-horizontal'>
      <input type="hidden" name="action" value="login" id="loginF" />
      <div class='panel panel-default'>
        <div class='panel-heading'><h3 class='panel-title'>Login</h3></div>
        <div class='panel-body'>
          <div class='form-group'>
            <div class='col-xs-12'>
              <input type="text" name="user" value="<?php echo he($user); ?>" class="form-control" placeholder="Username" required="required" />
            </div>
          </div>
          <div class='form-group'>
            <div class='col-xs-12'>
              <input type="password" name="pass" class="form-control" placeholder="Password" required />
            </div>
          </div>
        </div>
        <div class='panel-footer'>
          <button class="btn btn-primary" type="submit">Sign in</button>
        </div>
      </div>
    </form>
  </div>

<?php 
  }
?>