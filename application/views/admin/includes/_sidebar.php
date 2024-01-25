<?php
$cur_tab = $this->uri->segment(2) == '' ? 'dashboard' : $this->uri->segment(2);
$company = $this->session->userdata("company_info");
// prd($this->general_settings);
?>


<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=base_url('admin/dashboard');?>" class="brand-link">
        <img src="<?=$this->general_settings['logo'] == "" ? base_url("assets/img/no_image.png") : $this->general_settings['logo']?>"
            alt="logo" class="brand-image img-circle elevation-3" style="opacity: .8; width:37px;height:37px">
        <span class="brand-text font-weight-light"><?=$this->general_settings['application_name'];?></span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) ->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="<?=base_url()?>assets/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?=ucwords($this->session->userdata('username'));?></a>
      </div>
    </div>

    Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->
                <?php
$menu = get_sidebar_menu();

foreach ($menu as $nav):

    $sub_menu = get_sidebar_sub_menu($nav['module_id']);

    $has_submenu = (count($sub_menu) > 0) ? true : false;
    ?>

	                <?php if ($this->rbac->check_module_permission($nav['controller_name'])): ?>

	                <li id="<?=($nav['controller_name'])?>"
	                    class="nav-item <?=($has_submenu) ? 'has-treeview' : ''?> has-treeview">

	                    <a href="<?=base_url('admin/' . $nav['controller_name'])?>" class="nav-link">
	                        <i class="nav-icon fa <?=$nav['fa_icon']?>"></i>
	                        <p>
	                            <?=trans($nav['module_name'])?>
	                            <?=($has_submenu) ? '<i class="right fa fa-angle-left"></i>' : ''?>
	                        </p>
	                    </a>

	                    <!-- sub-menu -->
	                    <?php
    if ($has_submenu):
    ?>
	                    <ul class="nav nav-treeview ml-4">

	                        <?php foreach ($sub_menu as $sub_nav): ?>
	                        <?php if (!trim($sub_nav['link']) || $this->rbac->Check_subModule_permission($nav['controller_name'], $sub_nav['link'])): ?>
	                        <li class="nav-item">
	                            <a href="<?=base_url('admin/' . $nav['controller_name'] . '/' . $sub_nav['link']);?>"
	                                class="nav-link">
	                                <i class="fa fa-square nav-icon"></i>
	                                <p><?=trans($sub_nav['name'])?></p>
	                            </a>
	                        </li>

	                        <?php endif;?>
                        <?php endforeach;?>

                    </ul>
                    <?php endif;?>
                    <!-- /sub-menu -->
                </li>

                <?php endif;?>

                <?php endforeach;?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>

<script>
$("#<?=$cur_tab?>").addClass('menu-open');
$("#<?=$cur_tab?> > a").addClass('active');
</script>