<?php 

  $currPage = "faqs";
  include("includes/header.php"); 
?>

<div class='container'>
  <div class="jumbotron">
    <h1><?php echo lang("hl_faqs"); ?></h1>
  </div>

  <div class="panel-group" id="faqs">
    <?php 
    $cntfaqs = count($faqs);
    for ($i=0;$i<$cntfaqs;$i++) {
      echo '<div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="panel-title"><a data-toggle="collapse" data-parent="#faqs" href="#faq'.$i.'">' . $faqs[$i]["q"] . '</a></h4>
        </div>
        <div id="faq'.$i.'" class="panel-collapse collapse' . (!$i ? ' in' : '') . '">
          <div class="panel-body">' . $faqs[$i]["a"] . '</div>
        </div>
      </div>';
    }
    ?>
  </div>

</div> <!-- container -->

    
 <script type="text/javascript">$(document).ready(function () {
  if (location.hash) {
    $('.collapse').removeClass("in");
    $(location.hash + '.collapse').collapse('toggle');
  }
  });
 </script>

<?php include("includes/footer.php"); ?>
