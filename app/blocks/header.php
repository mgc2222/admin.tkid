<div class="header">
	<div class="logo"><i class="fa fa-briefcase fa-3x"></i><a href="<?php echo _SITE_RELATIVE_URL?>"><?php echo $webpage->HeaderTitle?></a></div>
    <form id="languageForm" method="post" action="">
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="<?php echo _SITE_RELATIVE_URL?>user_settings"><i class="fa fa-gear fa-fw"></i> <?php echo $trans['header.settings']?></a></li>
                    <li class="divider"></li>
                    <li><a href="<?php echo _SITE_RELATIVE_URL?>logout"><i class="fa fa-sign-out fa-fw"></i> <?php echo $trans['header.logout']?></a></li>
                    <?php if($webpage->languagesDdl){
                    ?>
                    <li class="dropdown-submenu">
                        <a id="admin-header-select-language" tabindex="-1" href="#">Select language <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                            <?php
                                foreach ($webpage->languagesDdl as $lang){
                            ?>
                                    <li style="display: block">
                                        <a>
                                            <button type="submit" style="width: 100%; background-color: unset;border: unset;" value="<?php echo $lang->id;?>" name="language"><?php echo $lang->abbreviation_iso;?></button>
                                        </a>
                                    </li>
                            <?php
                                }
                            ?>
                            </ul>
                    </li>
                    <?php
                        }
                    ?>
                </ul>
                <!-- /.dropdown-user -->
            </li>
            <!-- /.dropdown -->
        </ul>
    </form>
</div>
