
	<div class='panel panel-default'>
		<div class='panel-heading'><h3 class='panel-title'>SFS Error Log</h3></div>
		<div class='panel-body'>

			<p>These Entries are added to the Database if something unexpected occurs.<br />Warnings, Deprications, missing Declarations and other PHP error messages will be logged with the built in error logger.<br />
				Ideally this should be empty <i class='fa fa-smile-o'></i></p>



		<?php
				$logEntries = $SFS->getErrorLogEntries();


				echo "<div class='alert alert-success " . ($logEntries ? "js-hide" : "") . " alert-no-log-entries'><i class='fa fa-thumbs-o-up'></i> There were no Log Entries found.</div>";


				echo "</div>"; //close panel-body BEFORE TABE OPENING or the LIST OPENING

				echo "<ul class='list-group list-group-error-log'>";
				foreach ($logEntries as $logGroupKey => $row) {
					echo "<li class='list-group-item'>
						<div class='btn-group btn-group-xs wrapper-logitem-buttons'>
							<a href='http://sfs.envato.homac.at/report.php' class='btn btn-primary btn-xs js-btn-report-logitems open-link-external' data-loggroup-key='$logGroupKey'><i class='fa fa-bug'></i> report</a>
							<button type='button' class='btn btn-danger btn-xs js-btn-remove-logitems' data-loggroup-delkey='$logGroupKey'><i class='fa fa-times'></i> remove</button>
						</div>
						<dl class='dl-horizontal mb0'>
							<dt>Occurrences</dt><dd>" . $row->cnt . "</dd>
							<dt>File</dt><dd>" . $row->relFile . "</dd>
							<dt>Mesage</dt><dd>" . $row->message . "</dd>
							<dt>Latest Occurrences</dt><dd>" . $row->latest . "
							</dd>
						</dl>
						<button type='button' class='btn btn-default btn-sm btn-block js-btn-toggle-logitems-view' data-loggroup-target='$logGroupKey' data-text-more='more Details'  data-text-less='less Details'><i class='fa fa-chevron-down'></i> <span>more Details</span> <i class='fa fa-chevron-down'></i></button>
					</li>";

					foreach ($row->logItems as $logItem) {
						echo "<li class='list-group-item list-group-item-warning small' data-loggroup='$logGroupKey'><dl class='dl-horizontal mb0'>
							<dt>File <em class='nob'>full path</em></dt><dd>" . $logItem->file . "</dd>
							<dt>Line</small></dt><dd>" . $logItem->line . "</dd>
							<dt>Occurrence</dt><dd>" . $logItem->created . "</dd>
							<dt>URL</dt><dd>" . $logItem->url . "</dd>
							<dt>Refererer</dt><dd>" . ($logItem->referer?:"-") . "</dd>
							<dt>IP</dt><dd>" . $logItem->ip . "</dd>
							</dl>
						</li>";

					}
					
				}
			  echo "</ul>";



		?>



			</div>


		</div>
