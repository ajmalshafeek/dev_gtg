<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <title>Jsuite Cloud - Customer Login</title>
<style>
@import url('https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
body {
    margin: 0;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.45;
    color: #404E67;
    text-align: left;
    background-color: #F5F7FA;
}
h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 {
    margin-bottom: 0.5rem;
    font-family: "Montserrat";

}
 .content .flexbox-container {
    display: -webkit-box;
    display: -webkit-flex;
    display: -moz-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
    -webkit-align-items: center;
    -moz-box-align: center;
    -ms-flex-align: center;
    align-items: center;
    height: 100vh;
}
.fa{
    font-size: 24px;
    border-radius: 50px;
    color:#fff;
}
.fa-facebook{
    background-color: #3b5998;
    padding: 7px 12px;
}
.fa-instagram{

  background: #f09433;
background: -moz-linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
background: -webkit-linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
background: linear-gradient(45deg, #f09433 0%,#e6683c 25%,#dc2743 50%,#cc2366 75%,#bc1888 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f09433', endColorstr='#bc1888',GradientType=1 );

    padding: 7px 9px;
}
.bg-login{
background: url(<?php echo base_url(); ?>crm-assets/images/backgrounds/background-login.jpg);
    background-repeat: no-repeat;
    background-size: cover;
}
.fa-youtube-play{
    background-color: #FF0000;
    padding: 7px 7px;
}
li{
list-style:disc !important;
}
.has-icon-left .form-control {
    padding-left: 1rem;
}
button{
background-color:#219A8F !important;
color:#fff !important;
}
.tcolor{color: #24436D !important;}
.fcolor{color: #FFFFFF !important;}
@media screen and (max-width: 767px) {
.content .flexbox-container{
 height:auto !important;
}
.logtxt{
    font-size: 1.1rem !important;
}
.text-left img{width:inherit !important;}
.col-12.d-flex.align-items-center.justify-content-center {
    padding: 0px;
}
.text-right, .text-left{padding:0px!important}
}
.mt-5{margin-top:5rem!important}
</style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" integrity="sha512-5A8nwdMOWrSz20fDsjczgUidUBR8liPYU+WymTZP1lmY9G6Oc7HlZv156XqnsgNUzTyMefFTcsFH/tnJE/+xBg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
<body data-open="click" data-menu="vertical-menu" data-col="1-column"
      class="vertical-layout vertical-menu 1-column bg-login">
<!-- ////////////////////////////////////////////////////////////////////////////-->
<div class="app-content content container-fluid">
    <div class="content-wrapper">
        <div class="content-header row">
        </div>
        <div class="content-body">
            <section class="flexbox-container">
<div class="col-12 d-flex align-items-center justify-content-center">

<div class="container my-5 align-middle" style="box-shadow: 1px 4px 20px -5px #545454ed;border-radius: 10px;">
    <div class="row" style="background-color:#E2F6EF;padding:15px;border-radius: 10px 10px 0px 0px;">
        <div class="col-5 text-left">
         <?php /*   <!--<img src="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/company/<?php echo $this->config->item('logo'); ?>"
            alt="logo" style="max-height: 10rem;  max-width: 10rem;">-->
<img src="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/theme/logo-header.png"
            alt="logo" style="max-height: 10rem;  max-width: 10rem;"> */ ?>

        </div>
        <div class="col-7 text-right">
            <h5 class="logtxt" style="color:#24436D;
    margin: 0px;"><strong><?php // echo $this->lang->line('Client')  ?> <?php // echo $this->lang->line('login')  ?>Client Login</strong></h5>
    </div>
    </div>

    <div class="row">
        <div class="col-md-6 " style=" background: url(<?php echo base_url(); ?>crm-assets/images/backgrounds/login-side-image.jpg) no-repeat;background-size: cover;background-position: center; ">
            <div style="display: flex; align-items: center; justify-content: center;margin-top: 150px" class="row">
                <div class="col-6"><img src="<?php echo base_url()?>../userfiles/theme/logo-header.png" width="100%"/></div>
                <div class="col-6"> <img src="<?php echo base_url(); ?>../app-assets/images/elements/clientImg.png" width="100%" /></div>
            </div>
    <div class="fcolor" style="margin-bottom:0.5rem;">

    </div>
    <div class="mb-5">
        <a href="<?php echo base_url(); ?>../" class="fcolor">
            <u><em>Login as management</em></u>
        </a>
    </div>
    <div class="mb-2">
        <a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-instagram" aria-hidden="true"></i></a>
        <a href="#"><i class="fa fa-youtube-play" aria-hidden="true"></i></a>
    </div>



    </div>
                    <div class="col-md-6 bg-white">
                        <div class="px-1 py-1 m-0">
                            <div class="card-content">
                                <div class="card-body" style="max-width:400px;margin-right: auto;margin-left: auto;">
                                    <?php
                                  //  $attributes = array('class' => 'form-horizontal form-simple mt-5 mb-2', 'id' => 'login_form');
                                  //  echo form_open('user/checklogin', $attributes);
                                    ?>
                                <form class="form-horizontal form-simple mt-5 mb-2"
                                      action="<?php echo base_url() . 'user/auth_user'; ?>" method="post" id="login_form">
                                    <fieldset class="form-group position-relative has-icon-left">
                                    <small class="tcolor"><strong><?php echo $this->lang->line('Email') ?></strong></small>
                                        <input type="text" class="form-control" id="user-name" name="email"
                                               placeholder="<?php echo $this->lang->line('Your Email') ?>" required>

                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left">
                                    <small class="tcolor"><strong><?php echo $this->lang->line('Password') ?></strong></small>
                                        <input type="password" class="form-control" id="user-password" name="password"
                                               placeholder="<?php echo $this->lang->line('Your Password') ?>" required>

                                    </fieldset>
                    <?php if ($this->session->flashdata("messagePr")) {
                                        echo '<div id="notify" class="alert alert-danger" >
                            <a href="#" class="close" data-dismiss="alert">&times;</a><div class="message">' .$this->session->flashdata("messagePr")  . '</div></div>';
                            unset($_SESSION['messagePr']);
                                    } ?>
                                    <?php if ($captcha_on) {
                                        echo '<script src="https://www.google.com/recaptcha/api.js"></script>
									<fieldset class="form-group position-relative has-icon-left">
                                      <div class="g-recaptcha" data-sitekey="'.$captcha.'"></div>
                                    </fieldset>';
                                    } ?>
                                    <div class="form-group row">
                                        <div class="col-md-6 col-12 text-sm-left">
                                            <fieldset>
                                                <input type="checkbox" id="remember-me" class="chk-remember tcolor"
                                                       name="remember_me">
                                                <label for="remember-me">  <?php echo $this->lang->line('remember_me') ?></label>
                                            </fieldset>
                                        </div>

                                    </div>
                                    <button type="submit" class="btn btn-block"><i
                                                class="ft-unlock"></i> <?php echo $this->lang->line('login') ?></button>
                                    <div class="col-12 text-center mt-3"><a
                                                    href="<?php echo base_url('user/forgot'); ?>"
                                                    class="card-link tcolor"><?php echo $this->lang->line('forgot_password') ?>
                                                ?</a></div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
    </div>
    <div class="row bg-gray" style="background-color:#E2F6EF;padding:15px;border-radius:0px 0px 10px 10px;">
            <div class="col-12 text-center tcolor">
            Copyright &copy; Jsoft Solution Sdn Bhd &nbsp; | &nbsp; <a href="#" class="tcolor">Privacy Policy</a>&nbsp; | &nbsp;<a href="#" class="tcolor" >Terms & Conditions</a>
            </div>
    </div>
</div>

                </div>
<!-- old code
                <div class="col-md-4 offset-md-4 col-xs-10 offset-xs-1 p-1">
                    <div class="card border-grey border-lighten-3 m-0">
                        <div class="card-header no-border">
                            <div class="card-title text-xs-center" >
                                <div class="p-1"><img width="100%"  src="<?php echo substr_replace(base_url(), '', -4); ?>userfiles/company/<?php echo $this->config->item('logo'); ?>"
                                                      alt="Logo"></div>
                            </div>
                            <h4 class="card-subtitle line-on-side text-muted text-xs-center  pt-2"><span><?php echo $this->lang->line('Customers')  ?> <?php echo $this->lang->line('login')  ?></span>
                            </h4>
                        </div>
                        <div class="card-body collapse in">
                            <div class="card-block">
                                <?php if ($this->session->flashdata("messagePr")) { ?>
                                    <div class="alert alert-info">
                                        <?php echo $this->session->flashdata("messagePr") ?>
                                    </div>
                                <?php } ?>
                                <form class="form-horizontal form-simple"
                                      action="<?php echo base_url() . 'user/auth_user'; ?>" method="post">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name();?>" value="<?php echo $this->security->get_csrf_hash();?>">
                                    <fieldset class="form-group position-relative has-icon-left mb-2">
                                        <input type="text" name="email" class="form-control" placeholder="Email" required>
                                    </fieldset>
                                    <fieldset class="form-group position-relative has-icon-left mb-2">
                                        <input type="password" name="password" class="form-control" id="pwd"
                                               placeholder="Password" required>
                                        <div class="form-control-position">
                                            <i class="icon-key3"></i>
                                        </div>
                                    </fieldset>

								<?php if ($captcha_on) {
                                        echo '<script src="https://www.google.com/recaptcha/api.js"></script>
									<fieldset class="form-group position-relative has-icon-left">
                                      <div class="g-recaptcha" data-sitekey="'.$captcha.'"></div>
                                    </fieldset>';
                                    } ?>



                                    <button type="submit" class="btn btn-primary btn-lg btn-block"><i
                                                class="icon-unlock2"></i> Login
                                    </button>
                                </form>
                                <?php if ($this->common->front_end()->register) { ?>
        <br> <div class="row"><span class="col-xs-7">
            <a href="<?php echo base_url('user/registration'); ?>" class="card-link">
            <?php echo $this->lang->line('Register')  ?></a></span><span class="col-xs-5">
                <a href="<?php echo base_url('user/forgot'); ?>" class="card-link">
                    <?php echo $this->lang->line('forgot_password')  ?>?</a></span></div>
                                <?php } ?>
                            </div>
                        </div>

                    </div>
-->
                </div>
            </section>

        </div>
    </div>
</div>
 <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
  </body>
</html>