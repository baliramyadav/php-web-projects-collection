<?php
	$modules = $SFS->getModules();
?>

<div class='row'>

	<div class='col-lg-3 col-sm-4'>
		<div class='panel panel-default'>
			<div class='panel-heading'><h3 class='panel-title'><a href='?as=<?php echo $adminSection; ?>'><i class='fa fa-leaf'></i> SFS Modules</a></h3></div>
			<div class="list-group list-group-modules-sidemen">
			<?php
				if ($modules) {
					foreach ($modules as $row) {
						$row = (object) $row;
						$DB_installed = isset($row->DB_installed) ? $row->DB_installed : 0;
						echo "<a href='?as=$adminSection&amp;ass=" . $row->mod . "' data-modname='" . $row->mod . "' class='list-group-item " . (isset($add2subMenClass[$row->mod]) ? $add2subMenClass[$row->mod] : "") . " " . (!$DB_installed ? "disabled" : "") . "'><i class='fa fa-fw " . $row->icon . "'></i> " . $row->name . "</a>\n";
					}
				} else {

				}
			?>
			</div>


		</div>
	</div>

	<div class='col-lg-9 col-sm-8'>

<?php

if (isset($error) && $error) {
	echo "<div class='alert alert-danger'>" . $error . "</div>";
}
if (isset($success) && $success && $success != 1) {
	echo "<div class='alert alert-success'>$success</div>";
}

if ($adminSubSection && file_exists("modules/" . $adminSubSection . "/admin/index.php") && in_array($adminSubSection,$SFS->mods_installed())) {
	include("modules/" . $adminSubSection . "/admin/index.php");
} else {
	include("modules-start.php");	
}

?>




<br /><br />
	</div>
</div> <!-- row -->