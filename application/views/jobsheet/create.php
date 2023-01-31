<div class="content-body">
<style>

.sla-option {
    list-style-type: none;
    padding: 0;
    display: flex;
    flex-wrap: wrap;
}

.sla-option label{
    margin-right: 30px;
}

</style>
<?php if(isset($message)){
echo $status;
echo $message;
 echo '<div id="notify" class="alert alert-'.$status.'">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' .$message. '</div>
        </div>';
} ?>

<div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php //echo $this->lang->line('Add New Task') ?>Add New Task
        </h4>

            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <form method="post" class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url();?>/jobsheets/add_task">
                <div class="card">

                    <div class="card-content">
                        <div class="card-body">
                            <div class="tab-content px-1 pt-1">
                                <div class="tab-pane active show" id="tab1" role="tabpanel" aria-labelledby="base-tab1">
                                    <div class="form-group row mt-1">

                                        <label class="col-sm-2 col-form-label"
                                               for="name"><?php echo $this->lang->line('Title') ?></label>

                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Title"
                                                   class="form-control margin-bottom b_input required" name="title"
                                                   id="title">
                                        </div>
                                    </div>
                                    <div class="form-group row">

                                        <label class="col-sm-2 col-form-label"
                                               for="name"><?php echo $this->lang->line('Description') ?></label>

                                        <div class="col-sm-8">
                                            <input type="text" placeholder="Description"
                                                   class="form-control margin-bottom b_input" name="description">
                                        </div>
                                    </div>

                                    <div class="form-group row">

                                        <label class="col-sm-2 col-form-label"
                                               for="phone"><?php //echo $this->lang->line('Time Frame') ?>SLA Time Frame</label>

                                        <div class="col-sm-8">

                                            <ul class="sla-option">
                                                <li>
                                                    <input type="radio" id="a2" name="timeFrame" value="2">
                                                    <label for="a2"> 2hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a4" name="timeFrame" value="4">
                                                    <label for="a4"> 4hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a6" name="timeFrame" value="6">
                                                    <label for="a6"> 6hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a8" name="timeFrame" value="8">
                                                    <label for="a8"> 8hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a12" name="timeFrame" value="12">
                                                    <label for="a12"> 12hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a24" name="timeFrame" value="24">
                                                    <label for="a24"> 24hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a72" name="timeFrame" value="72">
                                                    <label for="a72"> 72hrs</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a1d" name="timeFrame" value="24">
                                                    <label for="a1d"> 1 Day</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a2d" name="timeFrame" value="48">
                                                    <label for="a2d"> 2 Days</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a3d" name="timeFrame" value="72">
                                                    <label for="a3d"> 3 Days</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a1w" name="timeFrame" value="168">
                                                    <label for="a1w"> 1 Week</label>
                                                </li>
                                                <li>
                                                    <input type="radio" id="a2w" name="timeFrame" value="336">
                                                    <label for="a2w"> 2 Weeks</label>
                                                </li>
                                                    <li>
                                                    <input type="radio" id="a3w" name="timeFrame" value="504">
                                                    <label for="a3w">3 Weeks</label>
                                                </li>

                                                <li>
                                                    <input type="radio" id="a4w" name="timeFrame" value="672">
                                                    <label for="a4w"> 4 Weeks</label>
                                                </li>

                                            </ul>


                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label"
                                               for="userfile"><?php echo $this->lang->line('Document') ?></label>
                                        <div class="col-sm-8">
                                            <input id="userfile" class="form-control" type="file" name="userfile" accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" />
                                        </div>
                                    </div>


                                </div>


                                <div id="mybutton">
                                    <input type="hidden" value="jobsheets/add_task" id="action-url">
                                    <input type="submit"
                                           class="btn btn-lg btn btn-primary margin-bottom round float-xs-right mr-2"
                                           value="<?php //echo $this->lang->line('Add customer') ?>Create Task"
                                           data-loading-text="Creating...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
(function($) {
  $.fn.serializeFiles = function() {
    var form = $(this),
        formData = new FormData(),
        formParams = form.serializeArray();

    $.each(form.find('input[type="file"]'), function(i, tag) {
      $.each($(tag)[0].files, function(i, file) {
        formData.append(tag.name, file);
      });
    });

    $.each(formParams, function(i, val) {
      formData.append(val.name, val.value);
    });

    return formData;
  };
})(jQuery);
</script>