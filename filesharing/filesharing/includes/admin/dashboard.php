<?php
	//retrieve infos for small dashboard stats
	$sql = "select * from `" . $config->tablePrefix . "overall_stats` where id = 1";
	$res = $SFS->dbquery($sql);
	$row = mysqli_fetch_object($res);
	$downloads = $row->downloads;
	$d_traffic = $row->d_size;
	$uploads = $row->uploads;
	$u_traffic = $row->u_size;

	$sql = "select * from `" . $config->tablePrefix . "files` where uid = 0 order by id desc limit 1";
	$res = $SFS->dbquery($sql);
	$lastFile = mysqli_fetch_object($res);

	$sql = "select count(id) as cnt, sum(fsize) as byteSum from `" . $config->tablePrefix . "files`";
	$res = $SFS->dbquery($sql);
	$row = mysqli_fetch_object($res);
	$numFiles = intval($row->cnt);
	$byteSum = intval($row->byteSum);

?>


<div class='row'>

	<div class='col-sm-6 col-lg-4 col-md-5'>
		<ul class='list-group'>
			<li class="list-group-item"><strong>Files</strong> <small class='text-muted'>current</small> <span class='badge'><?php echo $numFiles; ?></span></li>
			<li class="list-group-item"><strong>Files</strong> <small class='text-muted'>ever</small> <span class='badge'><?php echo $uploads; ?></span></li>
			<li class="list-group-item"><strong>Used Diskspace</strong> <small class='text-muted'>current</small> <span class='badge'><?php echo fsize($byteSum); ?></span></li>
			<li class="list-group-item"><strong>Traffic by Uploads</strong> <small class='text-muted'>ever</small> <span class='badge'><?php echo fsize($u_traffic); ?></span></li>
			<li class="list-group-item"><strong>Downloads</strong> <small class='text-muted'>ever</small> <span class='badge'><?php echo $downloads; ?></span></li>
			<li class="list-group-item"><strong>Traffic by Downloads</strong> <small class='text-muted'>ever</small> <span class='badge'><?php echo fsize($d_traffic); ?></span></li>
		</ul>




	</div>

	<div class="col-sm-6 col-lg-4 col-lg-offset-4 col-md-5 col-md-offset-2">
      <div class="well">
        <h3>The Last File</h3>

       	<?php
	       	if ($lastFile) {	
	       		list($fileKey,$delKey) = $SFS->genFileKeys($lastFile->id);
        		$downloadPage = $config->baseDownloadUrl . $fileKey . ".html";
        		if ($lastFile->shortkey) {
	        		$downloadPage = $config->instUrl . "/" . $lastFile->shortkey;
        		}
	       		echo "<table class='table table-responsive'>
							<tr><td>Name</td><td class='filename-truncate'><strong>" . $lastFile->descr . "</strong></td></tr>
							<tr><td>Size</td><td class='js-truncate-width'><strong>" . fsize($lastFile->fsize) . "</strong></td></tr>
							<tr><td>Upped</td><td><strong>" . $lastFile->created . "</strong></td></tr>
							<tr><td>Downloads</td><td><strong>" . $lastFile->downloads . "</strong></td></tr>
							<tr><td>Download-Page</td><td><strong><a href='$downloadPage' onclick='window.open(this.href); return false;'>follow Link <i class='fa fa-external-link'></i></a></strong></td></tr>
						</table>";
        	} else {
        		echo "<div class='alert alert-info'>no files found</div>";
        	}
        	?>
       </div>
   </div>

</div>