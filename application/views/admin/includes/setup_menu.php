<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="setup-menu-wrapper" class="sidebar animated <?php if ($this->session->has_userdata('setup-menu-open')
    && $this->session->userdata('setup-menu-open') == true) {
    echo 'display-block';
} ?>">
    <ul class="nav metis-menu" id="setup-menu">
        <li class="tw-mt-[64px] sm:tw-mt-0">
            <a class="close-customizer tw-cursor-pointer"><i class="fa fa-close"></i></a>
            <span class="text-left bold customizer-heading"><?php echo _l('setting_bar_heading'); ?></span>
        </li>
        <?php
        $totalSetupMenuItems = 0;
        foreach ($setup_menu as $key => $item) {
            if (isset($item['collapse']) && count($item['children']) === 0) {
                continue;
            }
            $totalSetupMenuItems++; ?>
        <li class="menu-item-<?php echo e($item['slug']); ?>">
            <a href="<?php echo count($item['children']) > 0 ? '#' : $item['href']; ?>" aria-expanded="false">
                <i class="<?php echo e($item['icon']); ?> menu-icon"></i>
                <span class="menu-text">
                    <?php echo html_purify(_l($item['name'], '', false)); ?>
                </span>
                <?php if (count($item['children']) > 0) { ?>
                <span class="fa arrow"></span>
                <?php } ?>
                <?php if (isset($item['badge'], $item['badge']['value']) && !empty($item['badge'])) {?>
                <span
                    class="badge pull-right
               <?=isset($item['badge']['type']) && $item['badge']['type'] != '' ? "bg-{$item['badge']['type']}" : 'bg-info' ?>" <?=(isset($item['badge']['type']) && $item['badge']['type'] == '') ||
                        isset($item['badge']['color']) ? "style='background-color: {$item['badge']['color']}'" : '' ?>>
                    <?= e($item['badge']['value']) ?>
                </span>
                <?php } ?>
            </a>
            <?php if (count($item['children']) > 0) { ?>
            <ul class="nav nav-second-level collapse" aria-expanded="false">
                <?php foreach ($item['children'] as $submenu) { ?>
                <li class="sub-menu-item-<?php echo e($submenu['slug']); ?>"><a href="<?php echo e($submenu['href']); ?>">
                        <?php if (!empty($submenu['icon'])) { ?>
                        <i class="<?php echo e($submenu['icon']); ?> menu-icon"></i>
                        <?php } ?>
                        <span class="sub-menu-text">
                            <?php echo e(_l($submenu['name'], '', false)); ?>
                        </span>
                    </a>
                    <?php if (isset($submenu['badge'], $submenu['badge']['value']) && !empty($submenu['badge'])) {?>
                    <span
                        class="badge pull-right mright5
                    <?=isset($submenu['badge']['type']) && $submenu['badge']['type'] != '' ? "bg-{$submenu['badge']['type']}" : 'bg-info' ?>"
                        <?=(isset($submenu['badge']['type']) && $submenu['badge']['type'] == '') ||
                        isset($submenu['badge']['color']) ? "style='background-color: {$submenu['badge']['color']}'" : '' ?>>
                        <?= e($submenu['badge']['value']) ?>
                    </span>
                    <?php } ?>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </li>
        <?php hooks()->do_action('after_render_single_setup_menu', $item); ?>
        <?php } ?>
        <?php if (get_option('show_help_on_setup_menu') == 1 && is_admin()) {
            $totalSetupMenuItems++; ?>
			<?php /*?>
        <li>
            <a href="<?php echo hooks()->apply_filters('help_menu_item_link', 'https://help.perfexcrm.com'); ?>"
                target="_blank">
                <?php echo hooks()->apply_filters('help_menu_item_text', _l('setup_help')); ?>
            </a>
        </li><li>
            <a href="#"  target="_blank" class="tw-mx-2">Welcome,  <?php echo get_staff_company_name(); ?></a>
            
        </li>
		<?php */?>
		
        <?php } ?>
    </ul>
</div>
<?php $this->app->set_setup_menu_visibility($totalSetupMenuItems); ?>
