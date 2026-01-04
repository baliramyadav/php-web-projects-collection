<form class='form-horizontal' action='?as=<?php echo $adminSection; ?>' id='fConfigTimezone'>
	<input type='hidden' name='ass' value='<?php echo $adminSubSection; ?>' />

<?php

	$db_timezoneCorrection_direction = $tmpArea = null;
	

	$timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
	$timezone_options = "<option value=''></option>";
	foreach ($timezones as $tz) {
		$thisArea = strstr($tz, '/', true);
		if (!$thisArea) $thisArea = 'Others';
		if ($thisArea != $tmpArea || !$thisArea) {
			if ($tmpArea) $timezone_options .= "</optgroup>\n";
			$timezone_options .= "\n<optgroup label='$thisArea'>";
		}
		$timezone_options .= "<option>$tz</option>";
		$tmpArea = $thisArea;
	}
	$timezone_options .= "</optgroup>";

	if (!$action) {
		$timezone = $SFS->config->timezone;
		$db_timezoneCorrection = $SFS->config->db_timezoneCorrection;
	}


	$db_timezoneCorrection_direction_icon_text = "<sup><small><i class='fa fa-plus'></i></small></sup>/<sub><small><i class='fa fa-minus small'></i></small></sub>";
	if ($db_timezoneCorrection) {
		list($db_timezoneCorrection_direction,$db_timezoneCorrection_hours,$db_timezoneCorrection_minutes) = preg_match('/([\+-])(\d{1,2}):(\d{1,2})/', $db_timezoneCorrection, $db_timezoneCorrection_matches);
		if ($db_timezoneCorrection_matches) {
			list($wholeMatch,$db_timezoneCorrection_direction,$db_timezoneCorrection_hours,$db_timezoneCorrection_minutes) = $db_timezoneCorrection_matches;
			$db_timezoneCorrection_direction_icon_text = "<i class='fa fa-" . ($db_timezoneCorrection_direction == "+" ? "plus" : "minus") . " fa-fw'></i>";
		}
	} else {
		$db_timezoneCorrection_hours = $db_timezoneCorrection_minutes = "00";
	}
	// echo "<pre>" . print_r($SFS->config,true) . "</pre>";

	$timezone_options = str_replace(">$timezone<"," selected='selected'>$timezone<",$timezone_options);

?>
		<div class='panel panel-default panel-sfs-settings'>
		<div class='panel-heading'><h3 class='panel-title'>Timezone Settings<i class='fa fa-chevron-up fa-fw pull-right'></i></h3></div>
		<div class='panel-body'>
			<div class='form-group'>
				<label class='col-md-4 col-lg-3 col-sm-5 control-label col-xs-12'>Timezone</label>
				<div class='col-sm-7 col-md-6 col-lg-9 col-xs-12'>
					<div class='row'>
						<div class='col-xs-9'>
		  				<select name="timezone" class="form-control chosen"><?php echo $timezone_options; ?></select>
		  			</div>
						<div class='col-xs-3 save-status-block'>
				  		<button type='button' class='btn btn-primary js-btn-save-timezone js-hide'><i class='fa fa-save'></i> Save</button>
				  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
			  		</div>
			  	</div>
		  	</div>
		  </div>

			<h4>Database Timezone Correction</h4>
			On some server constellations it could happen, that the webserver is running with a different timezone as the timezone used by the database engine. This could cause some incommensuratenesses (i.e. when counting download seconds).<br />
			if needed please use values with this syntax +HH:MM or -HH:MM, HH...hours, MM...minutes
			<ol><li>save changes you made to the timezone you made with the help of the </li>
				<li>click <a href='#openTZ' class='btn btn-primary btn-xs js-btn-open-tz-helper'>here</a> to verify the different time outputs and get details to correct if needed</li>
				<li>update value below if there are differences and suggested - <a href='#openTZ' class='btn btn-primary btn-xs js-btn-open-tz-helper'>verify again</a></li>
			</ol>
			<div class='form-group'>
				<label class='col-md-4 col-sm-5 col-lg-3 control-label'>Timezone Correction</label>
				<div class='col-sm-7 '>
		  		<div class="btn-group">
		  			<div class="btn-group btn-group-select-timezone-correction" data-field-name="db_timezoneCorrection_direction">

		  				<button type="button" class="btn btn-default dropdown-toggle btn-select-timezone-correction-direction" data-toggle="dropdown"><span class='text-muted selected-option'><?php echo $db_timezoneCorrection_direction_icon_text; ?></span> <span class="caret"></span></button>
		  				<ul class="dropdown-menu">
		  					<?php
		  						$directionActive[$db_timezoneCorrection_direction] = " class='active'"; 
		  					?>
		  					<li data-value='+'<?php echo isset($directionActive["+"]) ? $directionActive["+"] : ""; ?>><a href='#plus'><i class='fa fa-plus fa-fw'></i></a></li>
		  					<li data-value='-'<?php echo isset($directionActive["-"]) ? $directionActive["-"] : ""; ?>><a href='#minus'><i class='fa fa-minus fa-fw'></i></a></li>
		  				</ul>
		  			</div>

		  			<div class="btn-group btn-group-select-timezone-correction" data-field-name="db_timezoneCorrection_hours">
		  				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class='selected-option'><?php echo $db_timezoneCorrection_hours; ?></span> <span class="caret"></span></button>
		  				<ul class="dropdown-menu">
		  					<?php
		  					$hoursActive[$db_timezoneCorrection_hours] = " class='active'"; 
		  					for ($i=0;$i<24;$i++) {
		  						$val = sprintf("%02d", $i);
		  						echo "<li data-value='$val'" . (isset($hoursActive[$val]) ? $hoursActive[$val] : "") . "><a href='#plus'>$val</a></li>\n";
		  					}
		  					?>
		  				</ul>
		  			</div>

		  			<span class='btn btn-default'>:</span>


		  			<div class="btn-group btn-group-select-timezone-correction" data-field-name="db_timezoneCorrection_minutes">
		  				<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class='selected-option'><?php echo $db_timezoneCorrection_minutes; ?></span> <span class="caret"></span></button>
		  				<ul class="dropdown-menu">
		  					<?php
		  					$minutesActive[$db_timezoneCorrection_minutes] = " class='active'"; 
		  					for ($i=0;$i<60;$i+=15) {
		  						$val = sprintf("%02d", $i);
		  						echo "<li data-value='$val'" . (isset($minutesActive[$val]) ? $minutesActive[$val] : "")  . "><a href='#plus'>$val</a></li>\n";
		  					}
		  					?>
		  				</ul>
		  			</div>



		  			<input type='hidden' name="db_timezoneCorrection_direction" class="form-control" value='<?php echo he($db_timezoneCorrection_direction); ?>' />
		  			<input type='hidden' name="db_timezoneCorrection_hours" class="form-control" value='<?php echo he($db_timezoneCorrection_hours); ?>' />
		  			<input type='hidden' name="db_timezoneCorrection_minutes" class="form-control" value='<?php echo he($db_timezoneCorrection_minutes); ?>' />
		  		</div>
		  		<span class='save-status-block'>
			  		<button type='button' class='btn btn-primary js-btn-save-db_timezoneCorrection js-hide'><i class='fa fa-save'></i> Save</button>
			  		<strong class='text-success js-hide'><i class='fa fa-check'></i> saved</strong>
			  	</span>
		  	</div>
		  </div>
		</div>
	</div>


    </form>

