<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>

<!DOCTYPE html>
<html lang="<?php echo e($locale); ?>" dir="<?php echo ($isRTL == 'true') ? 'rtl' : 'ltr' ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title><?php echo isset($title) ? $title : get_option('companyname'); ?></title>

    <?php echo app_compile_css(); ?>
    <?php render_admin_js_variables(); ?>

    <script>
    var totalUnreadNotifications = <?php echo e($current_user->total_unread_notifications); ?>,
        proposalsTemplates = <?php echo json_encode(get_proposal_templates()); ?>,
        contractsTemplates = <?php echo json_encode(get_contract_templates()); ?>,
        billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip', 'billing_country',
            'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'
        ],
        isRTL = '<?php echo e($isRTL); ?>',
        taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone, newsFeedDropzone,
        expensePreviewDropzone, taskTrackingChart, cfh_popover_templates = {},
        _table_api;
    </script>
    <?php app_admin_head(); ?>
</head>

<body <?php echo admin_body_class(isset($bodyclass) ? $bodyclass : ''); ?> style="background-image: url(<?php echo base_url('uploads/bg/iodesk-bg1504.jpg');?>)  !important; ">
<div class="containervg">
    <?php hooks()->do_action('after_body_start'); ?>