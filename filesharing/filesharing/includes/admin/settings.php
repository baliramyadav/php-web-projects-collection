
<div class='row'>

	<div class='col-lg-3 col-sm-4'>
		<div class='panel panel-default'>
			<div class='panel-heading'><h3 class='panel-title'><i class='fa fa-cogs'></i> SFS Settings</h3></div>
			<div class="list-group">
			<?php
				echo "<a href='?as=$adminSection&amp;ass=timezone' class='list-group-item " . (isset($add2subMenClass["timezone"]) ? $add2subMenClass["timezone"] : "") . "'><i class='fa fa-fw fa-clock-o'></i> Timezone Settings</a>
					<a href='?as=$adminSection&amp;ass=download' class='list-group-item " . (isset($add2subMenClass["download"]) ? $add2subMenClass["download"] : "") . "'><i class='fa fa-fw fa-download'></i> Download</a>
					<a href='?as=$adminSection&amp;ass=upload' class='list-group-item " . (isset($add2subMenClass["upload"]) ? $add2subMenClass["upload"] : "") . "'><i class='fa fa-fw fa-upload'></i> Upload</a>
					<a href='?as=$adminSection&amp;ass=mail' class='list-group-item " . (isset($add2subMenClass["mail"]) ? $add2subMenClass["mail"] : "") . "'><i class='fa fa-fw fa-envelope'></i> Mail Settings</a>
					<a href='?as=$adminSection&amp;ass=shorturls' class='list-group-item " . (isset($add2subMenClass["shorturls"]) ? $add2subMenClass["shorturls"] : "") . "'><i class='fa fa-fw fa-link'></i> Short Urls</a>
					<a href='?as=$adminSection&amp;ass=error_log' class='list-group-item " . (isset($add2subMenClass["error_log"]) ? $add2subMenClass["error_log"] : "") . "'><i class='fa fa-fw fa-bars'></i> SFS Error Log</a>";
			?>
			</div>


		</div>
	</div>

	<div class='col-lg-9 col-sm-8'>

<?php
if (isset($error) && $error) {
	echo "<div class='alert alert-error'>$error</div>";
}
if (isset($success) && $success) {
	echo "<div class='alert alert-success'>$success</div>";
}

	switch ($adminSubSection) {
		case 'timezone':
			include("settings-timezone.php");
			break;
		case 'download':
			include("settings-download.php");
			break;
		case 'upload':
			include("settings-upload.php");
			break;
		case 'shorturls':
			include("settings-shorturls.php");
			break;
		case 'mail':
			include("settings-mail.php");
			break;
		case 'misc':
			include("settings-misc.php");
			break;
		case 'error_log':
			include("settings-error_log.php");
			break;
		
		default:
			include("settings-start.php");
			break;
	}


?>




<br /><br />
	</div>
</div> <!-- row -->