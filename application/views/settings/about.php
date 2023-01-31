<div class="card card-block">
    <div id="notify" class="alert alert-success" style="display:none;">
        <a href="#" class="close" data-dismiss="alert">&times;</a>
        <div class="message"></div>
    </div>
    <form method="post" id="product_action" class="card-body">
        <div class="card-body">
            <h5>About</h5>
            <hr>
            <div class="form-group row">
                <div class="col-sm-12 text-center">
                    <h3>JE POS</h3>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    $("#time_update").click(function (e) {
        e.preventDefault();
        var actionurl = baseurl + 'settings/dtformat';
        actionProduct(actionurl);
    });
</script>
