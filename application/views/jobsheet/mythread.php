<article class="content-body">
    <div class="card card-block">
        <?php if ($response == 1) {
            echo '<div id="notify" class="alert alert-success">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' . $responsetext . '</div>
        </div>';
        } else if ($response == 0) {
            echo '<div id="notify" class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message">' . $responsetext . '</div>
        </div>';
        } else {
            echo ' <div id="notify" class="alert alert-success" style="display:none;">
            <a href="#" class="close" data-dismiss="alert">&times;</a>

            <div class="message"></div>
        </div>';
        } ?>

        <div class="card-body">
            <h4><?php echo $thread_info['jobName'] ?>
            <?php  if($thread_info['status']==2){ ?>
                <a href="#pop_model" data-toggle="modal" data-remote="false" class="btn btn-sm btn-cyan mb-1" title="Change Status"><span class="icon-tab"></span> <?php echo $this->lang->line('Change Status') ?></a>
            <?php } ?>
        </h4>
            <p class="card card-block">
                <?php echo '<strong>Created on</strong> ' .
                dateformat_time($thread_info['created_at']);
                echo '<br><strong>Customer</strong> ' . $thread_info['cName'];
                echo '<br><strong>Status</strong> <span id="pstatus">';
                $temp="";
                if($thread_info['status']==1){
                    $temp="Completed";
                }elseif($thread_info['status']==2){
                    $temp="Pending";
                }
                elseif($thread_info['status']==3){
                    $temp="unassigned";
                }
                echo $temp; ?></span></p>
            <hr>
            <?php foreach ($thread_list as $row) { ?>
                <div class="form-group row">
                    <div class="col">
                        <div class="card-bordered shadow p-1">
                            <?php
                            if ($row['admin']) echo 'Job manager <strong>' . $row['admin'] . '</strong> Replied<br><br>';
                           // if ($row['custo']) echo 'Customer <strong>' . $row['custo'] . '</strong> Replied<br><br>';
                            if ($row['emp']) echo 'Employee <strong>' . $row['emp'] . '</strong> Replied<br><br>';
                            echo $row['message'] . '';
                            if ($row['attach']) echo '<br><br><strong>Attachment: </strong><a href="' . base_url('userfiles/support/' . $row['attach']) . '">' . $row['attach'] . '</a><br><br>';
                            ?></div>
                    </div>
                </div>
            <?php }
            echo form_open_multipart('jobsheets/mythread?id=' . $thread_info['id']); ?>

            <h5><?php echo $this->lang->line('Your Response') ?></h5>
            <hr>

            <div class="form-group row">

                <label class="col-sm-2 control-label" for="edate"><?php echo $this->lang->line('Reply') ?></label>

                <div class="col-sm-10">
                    <textarea class="summernote" placeholder=" Message" autocomplete="false" rows="10" name="content"></textarea>
                </div>
            </div>

            <div class="form-group row">

                <label class="col-sm-2 col-form-label" for="name">Attach </label>

                <div class="col-sm-6">
                    <input type="file" name="userfile" size="20" /><br>
                    <small>(docx, docs, txt, pdf, xls, png, jpg, gif)</small>
                </div>
            </div>

            <!--Signature start-->
            <div class='form-group row onremarks' id='signatureParent'>
                <label for='signature' class='col-sm-12 col-md-2 col-form-label col-form-label-lg'>Customer's
                    Signature</label>
                <div class='col-sm-12 col-md-10'>
                    <div id="signature" name="signature"></div>
                </div>
            </div>
            <!--Signature end-->

            <!-- (Start)Image Before After -->
            <div class='form-group row onremarks' id='option'>
                <label for='taken' class='col-sm-2 col-form-label col-form-label-lg'>Required</label>
                <div class='col-sm-10'>
                    <label for='taken' class='col-sm-6 col-form-label col-form-label-lg'>
                        <input type="radio" name="taken" value="1" checked/>&nbsp;
                        Before's Sanpshot
                    </label>&nbsp;
                    <label for='taken' class='col-sm-6 col-form-label col-form-label-lg'>
                        <input type="radio" name="taken" value="2"/>&nbsp;
                        After's Sanpshot
                    </label>
                </div>
            </div>
            <!-- (End)Image Before After -->

            <!-- (START)picture -->
            <div class='form-group row onremarks' id='picture'>
                <label for='pictures' class='col-sm-2 col-form-label col-form-label-lg'>Picture's</label>
                <div class='col-sm-10'>
                    <a href="#" class="btn my-2 mx-2 btn-dark" data-toggle="modal" data-target="#imageCaptureModal">TAKE
                        SNAPSHOT</a>
                    <div class="row wrapper mt-4">
                    </div>
                </div>
            </div>
            <!-- (END)picture -->

            <!-- Location -->
            <input id="latitude" type="text" name="latitude" hidden>
            <input id="longitude" type="text" name="longitude" hidden>
            <!-- location end -->

            <div class="form-group row">

                <label class="col-sm-2 col-form-label"></label>

                <div class="col-sm-4">
                    <?php if($thread_info['status']==2){ ?><input type="submit" id="document_add" class="btn btn-success margin-bottom" value="<?php echo $this->lang->line('Update') ?>" data-loading-text="Updating...">
                    <?php } ?>
                </div>
            </div>


            </form>
        </div>
    </div>
</article>
<script type="text/javascript">
    $(function() {
        $('.summernote').summernote({
            height: 250,
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['fullscreen', ['fullscreen']],
                ['codeview', ['codeview']]
            ]
        });
    });
</script>

<div id="pop_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h4 class="modal-title"><?php echo $this->lang->line('Change Status'); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>

            </div>
            <?php  if($thread_info['status']==2){ ?>
            <div class="modal-body">
                <form id="form_model">
                    <div class="container">
                        <div class="row">
                            <div class="col-xs-12 mb-1"><label for="pmethod"><?php echo $this->lang->line('Mark As') ?></label>
                                <select name="status" class="form-control mb-1">
                                    <option value="1"><?php echo $this->lang->line('Completed'); ?>Completed</option>
                                    <option value="2"><?php echo $this->lang->line('Pending'); ?></option>
                                    <option value="3"><?php echo $this->lang->line('unassigned'); ?>Unassigned</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" class="form-control required" name="jid" id="invoiceid" value="<?php echo $thread_info['id'] ?>">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                        <input type="hidden" id="action-url" value="jobsheets/update_status">
                       <button type="button" class="btn btn-primary" id="submit_model"><?php echo $this->lang->line('Change Status'); ?></button>
                    </div>
                </form>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<!-- modal span shot start-->
<!-- (START)CAPTURE IMAGE FORM -->

<div class="modal fade" id="imageCaptureModal" tabindex="-1" role="dialog"
     aria-labelledby="imageCaptureModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Snapshot Screen <a href="#" class="button btn my-2 mx-2"
                                                           id="btnChangeCamera">
                        <span class="icon"><i class="fa fa-refresh" aria-hidden="true"></i></span>
                        <span>Switch camera</span>
                    </a></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <small><i>NOTE: You can take multiple snapshot</i></small>
                <div class="form-group row">
                    <div class="col-md-12">
                        <div id="screenshot" style="width:100%; ">
                            <video id="videoDiv" autoplay style="width:100%; "></video>
                            <img id="imgTaken" src="" hidden>
                            <input id="base64img" type="text" name="base64img" hidden>
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-md-12">
                        <button class="btn btn-primary btn-lg btn-block" type="button" name="button"
                                id="screenshot-button">Take Snapshot
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- modal span shot end-->

<script>
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(showPosition);
        } else {
            x.innerHTML = "Geolocation is not supported by this browser.";
        }
    }

    function showPosition(position) {
     //   document.getElementById("latitude").value = position.coords.latitude;
     //   document.getElementById("longitude").value = position.coords.longitude;
        console.log(position.coords.latitude + "/" + position.coords.longitude);
    }

    (function () {
        getLocation();
    })();
</script>

<script type="text/javascript">
    $(document).ready(function () {
        // get page elements
        const video = document.querySelector('#screenshot video');
        const screenshotButton = document.querySelector('#screenshot-button');
        const btnChangeCamera = document.querySelector("#btnChangeCamera");
        const img = document.querySelector('#screenshot img');
        const canvas = document.createElement('canvas');
        const devicesSelect = document.querySelector("#devicesSelect");

        // video constraints
        const constraints = {

            video: {width: {min: 100}, height: {min: 144}}
        };

        // use front face camera
        let useFrontCamera = true;

        // current video stream
        let stream = null;
        // switch camera

        screenshotButton.onclick = video.onclick = function () {
            //   document.getElementById("shutterEffect").play();
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            // Other browsers will fall back to image/png
            img.src = canvas.toDataURL('image/webp');
            console.log(canvas.toDataURL('image/webp'));
            document.getElementById("base64img").value = img.src;
            // allowed maximum input fields
            var max_input = 10;

            // initialize the counter for textbox
            var x = 1;
            if (x < max_input) { // validate the condition
                x++; // increment the counter
                $('.wrapper').append(`
				<div class="col-md-3">
					<div class="input-box col-sm-11">
					<img id="imgTaken" src="` + img.src + `" width="100%">
					<input type="hidden" name="image[]" class="form-control " value="` + img.src + `"/>
					</div>
					<a href="#" class="remove-lnk text-danger"><i class="fa fa-minus-circle"></i>Delete</a></div>
          `);
                // add input field
                //     $('#count').html("Quantity: "+x);
            }
        };


        function handleSuccess(stream) {
            screenshotButton.disabled = false;
            video.srcObject = stream;
        }

        btnChangeCamera.addEventListener("click", function () {
            useFrontCamera = !useFrontCamera;
            initializeCamera();
        });

        // stop video stream
        function stopVideoStream() {
            if (stream) {
                stream.getTracks().forEach((track) => {
                    track.stop();
                });
            }
        }

        // initialize
        async function initializeCamera() {
            stopVideoStream();
            constraints.video.facingMode = useFrontCamera ? "user" : "environment";

            try {
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
            } catch (err) {
                alert("Could not access the camera");
            }
        }

        initializeCamera();

        // allowed maximum input fields

        var max_input = 20;

        // initialize the counter for textbox
        var x = 1;

        // handle click event of the remove link
        $('.wrapper').on("click", ".remove-lnk", function (e) {
            e.preventDefault();
            $(this).parent('.col-md-3').remove();  // remove input field
            x--; // decrement the counter
        });


    });
// signature pad start
    function updateTabClass(id) {
        if (id == 'update') {
            $('#signatureParent').resize();
        }
    }
    $(document).ready(function () {
        // Initialize jSignature
        var $sigdiv = $("#signature").jSignature({
            'UndoButton': true
        });

        $('#signature').change(function () {
            var data = $sigdiv.jSignature('getData', 'image');
            // Storing in textarea
            //$('#output').val(data);
            // Alter image source
            //  alert(data);
            $('#imageBase64').attr('value', "data:" + data);
            //   $('#sign_prev').show();
        });
    });
    // signature pad end
</script>
<script src="<?php echo assets_url('assets/myjs/jSignature.min.js') . APPVER; ?>"></script>