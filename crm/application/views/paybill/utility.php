<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <?php if ($this->session->flashdata("messagePr")) { ?>
                <div class="alert alert-info">
                    <?php echo $this->session->flashdata("messagePr");
                    unset($_SESSION['messagePr']);
                    ?>
                </div>
            <?php } ?>
            <div class="card card-block">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $this->lang->line('Pay Bill') ?></h3>
                    <p><br></p>

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
   /* $(document).ready(function () {

        var table = $('#invoices').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "ajax": {
                "url": "<?php echo site_url('invoices/ajax_list')?>",
                "type": "POST",
                'data': {'<?=$this->security->get_csrf_token_name()?>': '<?=$this->security->get_csrf_hash(); ?>'}
            },
            "columnDefs": [
                {
                    "targets": [0],
                    "orderable": false,
                },
            ],

        });

    });
    */
</script>
