<div class='row'>
	<div class='col-xs-12'>

<?php
if (isset($error) && $error) {
	echo "<div class='alert alert-error'>$error</div>";
}
if (isset($success) && $success) {
	echo "<div class='alert alert-success'>$success</div>";
}
?>
<div class='text-right'>
	<button class='btn btn-sm btn-primary js-btn-showhide-cleanup-info' data-show='0'>show/hide file removal info</button>
</div>
<div class="table-responsive mt6">
	<table class="table table-bordered table-striped table-hover" id="filesDataTable">
    <thead><tr>
        <th>#</th>
        <th>File Name</th>
        <th>File Size</th>
        <th>Upped</th>
        <th>Downloads</th>
        <th class='tac w100 table-tools-responsive'>Tools</th></tr>
    </thead>
    <tbody>
<?php

		echo "<tr><td>1234</td>
				<td>Sample Name</td>
				<td>1234 MB</td>
				<td>" . date("Y-m-d G:i:s") . "</td>
				<td>123</td>
				<td class='tac'>
					<a class='btn btn-mini btn-success' title='download file directly' href='#'><i class='icon-white icon-download-alt'></i></a>
					<a class='btn btn-mini btn-primary' title='visit Download-Page' href='#' onclick='window.open(this.href); return false;'><i class='icon-white icon-globe'></i></a>
					<a class='btn btn-mini btn-danger delFile' title='remove file from server' href='#'><i class='icon-white icon-remove'></i></a>
				</td></tr>\n";

?>
    </tbody></table>
  	</div><br /><br />
	</div>
</div> <!-- row -->

<input type='checkbox' name='TempbypassConfirming' id='TempbypassConfirming' value='1' class='hide' />