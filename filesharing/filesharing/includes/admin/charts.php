<div class='row'>
  <div class='col-xs-12 SFSCharts'>

    <div class="panel-group" id="charts">

      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="panel-title"><a data-toggle="collapse" data-parent="#charts" href="#updownloadsPanel">Uploads/Downloads</a></h4>
        </div>
        <div id="updownloadsPanel" class="panel-collapse collapse in">
          <div class="panel-body" id="updownloads">
            <div class="flot medium">
              <div class='alert alert-info'>There are not enough data fo a graphical interpretation.<br />Data for at least two days are necessary.</div>
            </div>
            <div class='flot-periodizer'>
<?php

  $months = $years = $halfyears = array();

  $sql = "select date_format(created,'%Y-%m') as issue_date from `" . $config->tablePrefix . "files` where uid = 0 group by issue_date  order by created";
  $res = $SFS->dbquery($sql);
  if (mysqli_num_rows($res)) {
    while ($row = mysqli_fetch_object($res)) {
      $months[] = $row->issue_date;
      $year = preg_replace('/-\d{2}$/','',$row->issue_date);
      if (!in_array($year,$years)) {
        $years[] = $year;
      }
      $halfyear = null;
      $month = intval(preg_replace('/^\d{4}-/','',$row->issue_date));
      $halfyear = $year . ".H" . ($month < 7 ? 1 : 2);
      if (!in_array($halfyear,$halfyears)) {
        $halfyears[] = $halfyear;
      }
    }
  
    if (count($months) > 1) {

      echo "<div class='row'>";

      //all years
      echo "<div class='col-xs-4'>
        <h5>Years</h5>
        <select name='year' class='form-control' data-unit='year'>
        <option value=''>--- please select ---</option>
        <option value='all' selected='selected'>all the years</option>";
      foreach (array_reverse($years) as $year) {
        echo "<option value='$year'>$year</option>";
      }
      echo "</select>
        </div>";


      //all half years
      echo "<div class='col-xs-4'>
        <h5>Halfs of the years</h5>
        <select name='halfyear' class='form-control' data-unit='halfyear'><option value=''>--- please select ---</option>";
      foreach (array_reverse($halfyears) as $halfyear) {
        echo "<option value='$halfyear'>";
        $year = preg_replace('/\..*$/','',$halfyear);
        preg_match_all('/\d$/',$halfyear,$matches);
        if ($matches[0][0] == 1) {
          echo "first half of $year";
        } else {
          echo "second half of $year";
        }
        echo "</option>";
      }
      echo "</select>
        </div>";

      //all months
      echo "<div class='col-xs-4'>
        <h5>Months</h5>
        <select name='month' class='form-control' data-unit='month'><option value=''>--- please select ---</option>";
      foreach (array_reverse($months) as $month) {
        echo "<option value='$month'>" . date("F Y",strtotime($month . "-01")) . "</option>";
      }
      echo "</select>
        </div>";

    }

    echo "</div>";



  }



?>

            </div>
          </div>
        </div>
      </div>
      
      <div class="panel panel-primary">
        <div class="panel-heading">
          <h4 class="panel-title"><a data-toggle="collapse" data-parent="#charts" href="#filetypesPanel">Filetypes</a></h4>
        </div>
        <div id="filetypesPanel" class="panel-collapse collapse in">
          <div class="panel-body" id="filetypes">
            <div class="flot medium">
              <div class='alert alert-info'>There are not enough data fo a graphical interpretation.<br />Data for at least two days are necessary.</div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

  <div class='clearfix'></div>
  <div class='col-xs-12'>
    <div class='alert alert-info'><i class='fa fa-info-circle'></i> <em>only undeleted files are used to calculate these values</em></div>
  </div>
</div>