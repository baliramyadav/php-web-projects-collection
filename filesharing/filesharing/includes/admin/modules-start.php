<div class='btn-blocks row'>
<?php
	if ($SFS->error) {
		echo "<div class='alert alert-danger'>" . $SFS->error . "</div>";
	}


	echo "<div class='col-xs-12'>
		<h3 class='mt0'>SFS Modules</h3>";
	
	echo "<h4>Local modules</h4>";

	if (!$modules) {
		echo "<div class='alert alert-info'>There were no modules found on your SFS installation.</div>";
	} else {
		echo "<table class='table table-bordered table-hover table-striped table-sfs-mods'>
			<thead><tr>
				<th>mod#</th>
				<th>Name</th>
				<th>Version</th>
				<th>Status</th>
			</tr></thead>
			<tbody>";
		foreach ($modules as $row) {
			$row = (object) $row;

			$DB_installed = isset($row->DB_installed) ? $row->DB_installed : 0;

			echo "<tr data-modname='" . $row->mod . "' data-href='?as=$adminSection&amp;ass=" . $row->mod . "'><td>" . $row->mod . "</td>
				<td class='table-cell-modname-link'>";
			if ($DB_installed) echo "<a href='?as=$adminSection&amp;ass=" . $row->mod . "'>" . $row->name . "</a>";
			else echo $row->name;
			echo "</td>
				<td>" . (is_numeric($row->version) ? number_format($row->version,2) : $row->version) . "
					<!--button type='button' class='btn btn-xs btn-warning js-btn-mod-updates-check' data-toggle='tooltip' title='check for Updates'><i class='fa fa-question'></i></button-->
				</td>
				<td><span class='modState'>";
			switch (intval($DB_installed)) {
				case 0:
					echo "<span class='stat_inst'>not installed</span>";
					echo "<span class='js-hide stat_stat text-success'><br /><span>enabled</span></span>";
					break;
				case 1:
					echo "<span class='stat_inst'>installed (V" . number_format($row->DB_installed_version,2) . ")</span>";
					if ($row->DB_status) {
						echo "<span class='stat_stat text-success'><br /><span>enabled</span></span>";
					} else {
						echo "<span class='stat_stat text-danger'><br /><span>disabled</span></span>";
					}
					break;
			}
			echo "</span>
					<div class='btn-group btn-group-xs pull-right'>
					  <button type='button' class='btn btn-default dropdown-toggle' data-toggle='dropdown'>Tools <span class='caret'></span></button>
				    <ul class='dropdown-menu'>
				    	<li><a href='#' class='js-btn-mod-manual'><i class='fa fa-book fa-fw'></i>Mod Manual</a></li>";

			echo "<li" . (!$DB_installed ? " class='js-hide'" : "") . "><a href='#' class='js-btn-mod-uninstall'><i class='fa fa-times fa-fw text-danger'></i>uninstall Mod</a></li>";
			echo "<li" . (!$DB_installed ? " class='js-hide'" : "") . "><a href='#' class='js-btn-mod-healthcheck'><i class='fa fa-medkit fa-fw text-primary'></i>Mod Healthcheck</a></li>";
			echo "<li" . (($DB_installed && !$row->DB_status) || !$DB_installed ? " class='js-hide'" : "") . "><a href='#' class='js-btn-mod-disable' data-status='0'><i class='fa fa-ban fa-fw text-danger'></i>disable Mod</a></li>";
			echo "<li" . (($DB_installed && $row->DB_status) || !$DB_installed ? " class='js-hide'" : "") . "><a href='#' class='js-btn-mod-enable' data-status='1'><i class='fa fa-check fa-fw text-success'></i>enable Mod</a></li>";

			echo "<li" . ($DB_installed ? " class='js-hide'" : "") . "><a href='#' class='js-btn-mod-install'><i class='fa fa-save fa-fw text-success'></i>install Mod</a></li>";
			echo "<li><a href='#' class='js-btn-mod-remove'><i class='fa fa-trash fa-fw text-danger'></i>remove Mod</a></li>";
			
		
			echo "</ul>
				  </div></td>
				</tr>";
		}


		echo "</tbody></table>";

	}

	echo "<h4>Available modules for SFS</h4>";

	echo "<a href='https://codecanyon.net/collections/shared/a9a77a9ec67a43633a6c72d4a0a7f9b301e8afca7188e061c88dedab642de659?ref=themac' class='btn btn-primary open-link-external'><i class='fa fa-external-link'></i> Available modules</a>";

	echo "</div>";
	?>


</div>