    <footer class="main-footer">
        <strong><?=$generalsetting->copyright_by?></strong>
        <div class="pull-right hidden-xs">
            <b>Version</b> <?=$this->config->item('appversion');?>
        </div>
    </footer>
    <div class="control-sidebar-bg"></div>
</div>

<script type="text/javascript">
    if ($.fn.datepicker) {
        $('.datepicker').datepicker({
            autoclose: true,
            format : 'dd-mm-yyyy',
        });
    }
    var globalFilebrowse = "<?=$this->lang->line('filebrowse')?>";
</script>

<!-- jQuery UI 1.11.4 -->
<script src="<?=base_url('assets/plugins/jquery-ui/jquery-ui.min.js')?>"></script>

<!-- Bootstrap 3.3.7 -->
<script src="<?=base_url('assets/plugins/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/toastr/toastr.min.js')?>"></script>

<!-- All Controllers Js files Load Here -->
<?php 
    if(isset($headerassets['js']) && calculate($headerassets['js'])) {
      foreach ($headerassets['js'] as $js) { ?>
        <script src="<?=base_url($js)?>"></script>
<?php } } ?>


<script src="<?=base_url('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')?>"></script>
<script src="<?=base_url('assets/plugins/fastclick/lib/fastclick.js')?>"></script>
<script src="<?=base_url('assets/dist/js/adminlte.min.js')?>"></script>
<script src="<?=base_url('assets/custom/js/custom.js')?>"></script>
<script type="text/javascript">
    $.widget.bridge('uibutton', $.ui.button);
    $('[data-toggle="tooltip"]').tooltip();

    <?php 
    $success = $this->session->flashdata('success');
    $error   = $this->session->flashdata('error');
    if($success) { ?>
        toastr.success('<?=$success?>');
    <?php } elseif($error) { ?>
        toastr.error('<?=$error?>');
    <?php } ?>

    $( document ).ready(function() {
        if ($.fn.DataTable) {
            $('#example1').DataTable({
                'pageLength':15,
                'ordering': false
            });

            $('.mainpermission').DataTable({
                paging: false
            });
        }
    });

</script>
</body>
</html>
