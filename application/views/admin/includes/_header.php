<?php
defined('BASEPATH') or exit('No direct script access allowed');
$company = $this->session->userdata("company_info");
// prd($this->general_settings['logo']);
$notifications = $this->session->userdata("notifications");
$username = $this->session->userdata("username");
$name = $this->session->userdata("name");
// prd($this->session->userdata());
// prd($data);
// prd($this->general_settings);
// $result = sizeof($notifications);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/x-icon"
        href="<?=$this->general_settings['favicon'] ?: base_url('assets/img/no_image.png')?>">
    <link rel="icon" href="">
    <title><?=isset($title) ? $title . ' - ' : 'Title -'?> <?=$this->general_settings['application_name'];?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?=base_url()?>assets/dist/css/adminlte.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/datepicker/datepicker3.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <!-- DropZone -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/dropzone/dropzone.css">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Jquery ui -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/jQueryUI/jquery-ui.min.css">
    <!-- select -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
    <link rel="stylesheet" href=<?=base_url("assets/plugins/select2/select2.min.css")?>>


    <!-- bootstrap duallist -->
    <link rel="stylesheet" href="<?=base_url()?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

    <script>
    var base_url = '<?php echo base_url(); ?>'
    </script>
    <!-- jQuery -->
    <script src="<?=base_url()?>assets/plugins/jquery/jquery.min.js"></script>

    <style>
    .invalid-feedback {
        display: none;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 100%;
        color: #dc3545;
    }

    .text-grey {
        color: grey;
        font-weight: 400;
    }
    </style>
</head>

<body class="hold-transition sidebar-mini <?=(isset($bg_cover)) ? 'bg-cover' : ''?>">

    <!-- Main Wrapper Start -->
    <div class="wrapper">

        <!-- Navbar -->

        <?php if (!isset($navbar)): ?>

        <nav class="main-header navbar navbar-expand bg-white navbar-light border-bottom">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fa fa-bars"></i></a>
                </li>

            </ul>

            <!-- SEARCH FORM -->
            <!-- <form class="form-inline ml-3">
                <div class="input-group input-group-sm">
                    <input class="form-control form-control-navbar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-navbar" type="submit">
                            <i class="fa fa-search"></i>
                        </button>
                    </div>
                </div>
            </form> -->

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">

                <?php /*?>
                <!-- Messages Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-comments-o"></i>
                        <span class="badge badge-danger navbar-badge">3</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="<?=base_url()?>assets/dist/img/user1-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 mr-3 img-circle">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Brad Diesel
                                        <span class="float-right text-sm text-danger"><i class="fa fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">Call me whenever you can...</p>
                                    <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="<?=base_url()?>assets/dist/img/user8-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        John Pierce
                                        <span class="float-right text-sm text-muted"><i class="fa fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">I got your message bro</p>
                                    <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <!-- Message Start -->
                            <div class="media">
                                <img src="<?=base_url()?>assets/dist/img/user3-128x128.jpg" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        Nora Silvester
                                        <span class="float-right text-sm text-warning"><i class="fa fa-star"></i></span>
                                    </h3>
                                    <p class="text-sm">The subject goes here</p>
                                    <p class="text-sm text-muted"><i class="fa fa-clock-o mr-1"></i> 4 Hours Ago</p>
                                </div>
                            </div>
                            <!-- Message End -->
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                    </div>
                </li>
                <?php */?>
                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="fa fa-bell-o"></i>
                        <span class="badge badge-warning navbar-badge">
                            <?php $data = $notifications;
$data = sizeof($notifications);
echo $data;?>
                        </span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-item dropdown-header"><?=$data . " notifications"?></span>
                        <div class="dropdown-divider"></div>
                        <?php foreach ($notifications as $k => $v) {?>
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-envelope mr-2"></i><?=$v['description']?>
                            <span class="float-right text-muted text-sm"><?php
$date1 = new DateTime($notifications[$k]["created_at"]);
    $date2 = new DateTime('now');

    // prd($data2);
    $diff = date_diff($date2, $date1);
    // prd($diff);

    echo $diff->d != 0 ? $diff->d . " day" : ($diff->h != 0 ? $diff->h . " hour" : $diff->i . " minute")?></span>
                        </a>
                        <?php }?>
                        <!-- <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-users mr-2"></i> 8 friend requests
                            <span class="float-right text-muted text-sm">12 hours</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fa fa-file mr-2"></i> 3 new reports
                            <span class="float-right text-muted text-sm">2 days</span>
                        </a> -->
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                    </div>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a class="nav-link" data-toggle="dropdown" href="#" style="padding-top: 5px;padding-bottom: 5px;">
                        <?php echo $company ? $company->name : $name; ?> <img
                            src="<?=$company ? ($company->logo == "" ? base_url("assets/img/no_image.png") : $company->logo) : ($this->general_settings['logo'] == "" ? base_url("assets/img/no_image.png") : $this->general_settings['logo'])?>"
                            class="img-circle" style="width:40px; height:40px; margin-top:-8px; " alt="User Image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <div class="card card-widget widget-user-2 shadow-sm" style="margin-bottom:0!important;">
                            <div class="widget-user-header bg-warning">
                                <div class="widget-user-image">
                                    <img class="img-circle elevation-2"
                                        src="<?=$company ? ($company->logo == "" ? base_url("assets/img/no_image.png") : $company->logo) : ($this->general_settings['logo'] == "" ? base_url("assets/img/no_image.png") : $this->general_settings['logo'])?>"
                                        style='width:50px;height:50px;' alt="User Avatar">
                                </div>
                                <h3 class="widget-user-username"><?php echo $company ? $company->name : $name; ?>
                                </h3>
                            </div>
                            <div class=" card-footer p-0">
                                <ul class="nav flex-column">
                                    <li class="nav-item">
                                        <a href="<?=base_url('admin/profile')?>" class="nav-link">
                                            Profile
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?=base_url('admin/profile/change_pwd')?>" class="nav-link">
                                            Change Password
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="<?=base_url('admin/auth/logout')?>" class="nav-link">
                                            Logout
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </nav>

        <?php endif;?>

        <!-- /.navbar -->


        <!-- Sideabr -->

        <?php if (!isset($sidebar)): ?>

        <?php $this->load->view('admin/includes/_sidebar');?>

        <?php endif;?>

        <!-- / .Sideabr -->