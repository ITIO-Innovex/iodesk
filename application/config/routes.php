<?php

defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|   example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|   http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|   $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|   $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|   $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|       my-controller/my-method -> my_controller/my_method
*/

$route['default_controller']   = 'landing';
$route['404_override']         = '';
$route['translate_uri_dashes'] = false;

/**
 * Handle /index requests - redirect to default controller
 */
$route['index'] = 'landing';
$route['index/(:any)'] = 'landing/$1';

/**
 * Dashboard clean route
 */
$route['admin'] = 'admin/dashboard';

/**
 * Misc controller routes
 */
$route['admin/access_denied'] = 'admin/misc/access_denied';
$route['admin/not_found']     = 'admin/misc/not_found';



/**
 * Staff Routes
 */
$route['admin/profile']           = 'admin/staff/profile';
$route['admin/profile/(:num)']    = 'admin/staff/profile/$1';
$route['admin/tasks/view/(:any)'] = 'admin/tasks/index/$1';

/**
 * Items search rewrite
 */
$route['admin/items/search'] = 'admin/invoice_items/search';

/**
 * In case if client access directly to url without the arguments redirect to clients url
 */
$route['/'] = 'landing';

/**
 * @deprecated
 */
$route['viewinvoice/(:num)/(:any)'] = 'invoice/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['invoice/(:num)/(:any)'] = 'invoice/index/$1/$2';

/**
 * @deprecated
 */
$route['viewestimate/(:num)/(:any)'] = 'estimate/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['estimate/(:num)/(:any)'] = 'estimate/index/$1/$2';
$route['subscription/(:any)']    = 'subscription/index/$1';

/**
 * @deprecated
 */
$route['viewproposal/(:num)/(:any)'] = 'proposal/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['proposal/(:num)/(:any)'] = 'proposal/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['contract/(:num)/(:any)'] = 'contract/index/$1/$2';

/**
 * @since 2.0.0
 */
$route['knowledge-base']                 = 'knowledge_base/index';
$route['knowledge-base/search']          = 'knowledge_base/search';
$route['knowledge-base/article']         = 'knowledge_base/index';
$route['knowledge-base/article/(:any)']  = 'knowledge_base/article/$1';
$route['knowledge-base/category']        = 'knowledge_base/index';
$route['knowledge-base/category/(:any)'] = 'knowledge_base/category/$1';

$route['wa-server'] = 'WaServer/index';

/**
 * @deprecated 2.2.0
 */
if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'add_kb_answer') === false) {
    $route['knowledge-base/(:any)']         = 'knowledge_base/article/$1';
    $route['knowledge_base/(:any)']         = 'knowledge_base/article/$1';
    $route['clients/knowledge_base/(:any)'] = 'knowledge_base/article/$1';
    $route['clients/knowledge-base/(:any)'] = 'knowledge_base/article/$1';
}

/**
 * @deprecated 2.2.0
 * Fallback for auth clients area, changed in version 2.2.0
 */
$route['clients/reset_password']  = 'authentication/reset_password';
$route['clients/forgot_password'] = 'authentication/forgot_password';
$route['clients/logout']          = 'authentication/logout';
$route['clients/register']        = 'authentication/register';
$route['clients/login']           = 'authentication/login';

// Aliases for short routes
$route['reset_password']  = 'authentication/reset_password';
$route['forgot_password'] = 'authentication/forgot_password';
$route['login']           = 'authentication/login';
$route['logout']          = 'authentication/logout';
$route['register']        = 'authentication/register';

/**
 * Terms and conditions and Privacy Policy routes
 */
$route['terms-and-conditions'] = 'terms_and_conditions';
$route['privacy-policy']       = 'privacy_policy';
$route['help_center']          = 'help_center';
$route['user_documentation']  = 'user_documentation';

/**
 * @since 2.3.0
 * Routes for admin/modules URL because Modules.php class is used in application/third_party/MX
 */
$route['admin/modules']               = 'admin/mods';
$route['admin/modules/(:any)']        = 'admin/mods/$1';
$route['admin/modules/(:any)/(:any)'] = 'admin/mods/$1/$2';

// Public single ticket route
$route['forms/tickets/(:any)'] = 'forms/public_ticket/$1';
/**
 * @since  2.3.0
 * Route for clients set password URL, because it's using the same controller for staff to
 * If user addded block /admin by .htaccess this won't work, so we need to rewrite the URL
 * In future if there is implementation for clients set password, this route should be removed
 */
$route['authentication/set_password/(:num)/(:num)/(:any)'] = 'admin/authentication/set_password/$1/$2/$3';

// For backward compatilibilty
$route['survey/(:num)/(:any)'] = 'surveys/participate/index/$1/$2';

//for discussion via telegram
$route['admin/leads/discussion/(:num)?'] = 'admin/leads/discussion/$1';

//for discussion via webchats
$route['admin/leads/webchat/(:num)?'] = 'admin/leads/webchat/$1';

// Add these for deal stage management
$route['admin/leads/dealstage'] = 'admin/leads/dealstage';
$route['admin/leads/delete_deal_stage/(:num)'] = 'admin/leads/delete_deal_stage/$1';
$route['admin/leads/toggle_deal_stage_status/(:num)'] = 'admin/leads/toggle_deal_stage_status/$1';

// Reports routes
$route['admin/reports/leads_by_stage'] = 'admin/reports/leads_by_stage';
$route['admin/reports/leads_by_source'] = 'admin/reports/leads_by_source';
$route['admin/reports/leads_by_country'] = 'admin/reports/leads_by_country';
$route['admin/reports/leads_by_country_details'] = 'admin/reports/leads_by_country_details';
$route['admin/reports/deals_by_company'] = 'admin/reports/deals_by_company';
$route['admin/reports/activity_by_staff'] = 'admin/reports/activity_by_staff';
$route['admin/reports/sales_by_payments'] = 'admin/reports/sales_by_payments';

if (file_exists(APPPATH . 'config/my_routes.php')) {
    include_once(APPPATH . 'config/my_routes.php');
}

$route['admin/customize/get_deal_stages_customized'] = 'admin/customize/get_deal_stages_customized';
$route['admin/customize/save_deal_stages_customized'] = 'admin/customize/save_deal_stages_customized';
$route['admin/customize/get_form_layout'] = 'admin/customize/get_form_layout';
$route['admin/customize/save_form_layout'] = 'admin/customize/save_form_layout';
$route['admin/customize/get_form_layout_status'] = 'admin/customize/get_form_layout_status';
$route['admin/customize/get_company_deal_form_type'] = 'admin/customize/get_company_deal_form_type';
$route['admin/customize/smtp_setting'] = 'admin/customize/smtp_setting';

// User Utility routes
$route['admin/user_utility'] = 'admin/user_utility/index';
$route['admin/user_utility/create'] = 'admin/user_utility/create';
$route['admin/user_utility/edit/(:num)'] = 'admin/user_utility/edit/$1';
$route['admin/user_utility/view/(:num)'] = 'admin/user_utility/view/$1';
$route['admin/user_utility/delete/(:num)'] = 'admin/user_utility/delete/$1';

// Designations routes
$route['admin/designation'] = 'admin/designations/index';
$route['admin/designations'] = 'admin/designations/index';
$route['admin/designation/manage'] = 'admin/designations/manage';
$route['admin/designation/delete/(:num)'] = 'admin/designations/delete/$1';

// Services - Subscriptions routes
$route['admin/services/subscriptions'] = 'admin/services/subscriptions';
$route['admin/services/subscriptions/manage'] = 'admin/services/subscriptions_manage';
$route['admin/services/subscriptions/delete/(:num)'] = 'admin/services/subscriptions_delete/$1';
$route['admin/services/choose_subscriptions'] = 'admin/services/choose_subscriptions';
$route['admin/services/upgrade_plan'] = 'admin/services/upgrade_plan';
$route['admin/services/upgrated_plan_details/(:num)'] = 'admin/services/upgrated_plan_details/$1';
$route['admin/services/invoice/(:num)'] = 'admin/services/invoice/$1';
$route['admin/services/my_subscriptions'] = 'admin/services/my_subscriptions';
$route['admin/services/payment_status'] = 'admin/services/payment_status';
$route['admin/services/upgrade_staff'] = 'admin/services/upgrade_staff';

// Services - User Subscriptions routes
$route['admin/services/user_subscriptions'] = 'admin/services/user_subscriptions';
$route['admin/services/user_subscriptions/manage'] = 'admin/services/user_subscriptions_manage';
$route['admin/services/user_subscriptions/delete/(:num)'] = 'admin/services/user_subscriptions_delete/$1';

// Services - Subscription Invoices routes
$route['admin/services/subscriptions_invoices'] = 'admin/services/subscriptions_invoices';
$route['admin/services/subscriptions_invoices/manage'] = 'admin/services/subscriptions_invoices_manage';
$route['admin/services/subscriptions_invoices/delete/(:num)'] = 'admin/services/subscriptions_invoices_delete/$1';
$route['admin/services/sent_renewal_reminder'] = 'admin/services/sent_renewal_reminder';

// HRD - Manage Attendance by Date
$route['admin/hrd/manage_attendance_by_date'] = 'admin/hrd/manage_attendance_by_date';
$route['admin/hrd/manage_attendance_master'] = 'admin/hrd/manage_attendance_master';
$route['admin/hrd/manage_attendance_maestro'] = 'admin/hrd/manage_attendance_maestro';
$route['admin/hrd/save_attendance_maestro'] = 'admin/hrd/save_attendance_maestro';
$route['admin/hrd/leave_register'] = 'admin/hrd/leave_register';
$route['admin/hrd/leave_balance_register'] = 'admin/hrd/leave_balance_register';
$route['admin/hrd/approver'] = 'admin/hrd/approver';
$route['admin/hrd/manage_attendance_by_user'] = 'admin/hrd/manage_attendance_by_user';
$route['admin/hrd/attendance_bulk_update_by_date'] = 'admin/hrd/attendance_bulk_update_by_date';
$route['admin/hrd/attendance_update_inout_by_date'] = 'admin/hrd/attendance_update_inout_by_date';
$route['admin/hrd/attendance_lock_by_date'] = 'admin/hrd/attendance_lock_by_date';
$route['admin/hrd/setting/dashboard'] = 'admin/hrd/setting_dashboard';
$route['admin/hrd/setting/self_service'] = 'admin/hrd/setting_self_service';
$route['admin/hrd/setting/shift_wise_employee_count'] = 'admin/hrd/shift_wise_employee_count';
$route['admin/hrd/setting/top_10_employee_having_late_mark'] = 'admin/hrd/top_10_employee_having_late_mark';
$route['admin/hrd/setting/top_10_leave_takers'] = 'admin/hrd/top_10_leave_takers';
$route['admin/hrd/setting/list_of_employee_early_going'] = 'admin/hrd/list_of_employee_early_going';
$route['admin/hrd/setting/employee_count_analysis'] = 'admin/hrd/employee_count_analysis';
$route['admin/hrd/setting/employee_attendance'] = 'admin/hrd/employee_attendance';
$route['admin/hrd/setting/attendance_master'] = 'admin/hrd/attendance_master';
$route['admin/hrd/setting/staff_type'] = 'admin/hrd/staff_type';
$route['admin/hrd/stafftype'] = 'admin/hrd/stafftype';
$route['admin/hrd/delete_staff_type/(:num)'] = 'admin/hrd/delete_staff_type/$1';
$route['admin/hrd/toggle_staff_type/(:num)'] = 'admin/hrd/toggle_staff_type/$1';
$route['admin/hrd/setting/saturday_rule'] = 'admin/hrd/saturday_rule';
$route['admin/hrd/saturdayrule'] = 'admin/hrd/saturdayrule';
$route['admin/hrd/delete_saturday_rule/(:num)'] = 'admin/hrd/delete_saturday_rule/$1';
$route['admin/hrd/toggle_saturday_rule/(:num)'] = 'admin/hrd/toggle_saturday_rule/$1';
$route['admin/hrd/setting/attendance_request'] = 'admin/hrd/attendance_request';
$route['admin/hrd/attendance_request'] = 'admin/hrd/attendance_request';
$route['admin/hrd/attendance_request_update'] = 'admin/hrd/attendance_request_update';
$route['admin/hrd/attendance_update_request_add'] = 'admin/hrd/attendance_update_request_add';
$route['admin/hrd/self_service'] = 'admin/hrd/self_service';
$route['admin/hrd/holidays_list'] = 'admin/hrd/holidays_list';
$route['admin/hrd/my_document'] = 'admin/hrd/my_document';
$route['admin/hrd/my_document_add'] = 'admin/hrd/my_document_add';
$route['admin/important_document'] = 'admin/important_document';
$route['admin/important_document/save'] = 'admin/important_document/save';
$route['admin/important_document/delete/(:num)'] = 'admin/important_document/delete/$1';
$route['admin/hrd/setting/awards'] = 'admin/hrd/setting/awards';
$route['admin/hrd/setting/awards_add'] = 'admin/hrd/awards_add';
$route['admin/hrd/leave_balance'] = 'admin/hrd/leave_balance';
$route['admin/hrd/setting/leave_balance'] = 'admin/hrd/setting_leave_balance';
$route['admin/hrd/setting/leave_balance_add'] = 'admin/hrd/setting_leave_balance_add';
$route['admin/hrd/uploaded_document'] = 'admin/hrd/uploaded_document';
$route['admin/hrd/uploaded_document_by_user'] = 'admin/hrd/uploaded_document_by_user';
$route['admin/hrd/document_update_status'] = 'admin/hrd/document_update_status';
$route['admin/hrd/profile'] = 'admin/hrd/profile';
$route['admin/hrd/profile_update_personal'] = 'admin/hrd/profile_update_personal';
$route['admin/hrd/profile_update_social'] = 'admin/hrd/profile_update_social';
$route['admin/hrd/profile_image_update'] = 'admin/hrd/profile_image_update';
$route['admin/hrd/setting/awards_update/(:num)'] = 'admin/hrd/awards_update/$1';
$route['admin/hrd/setting/awards_delete/(:num)'] = 'admin/hrd/awards_delete/$1';
$route['admin/hrd/setting/delete_award_image/(:num)'] = 'admin/hrd/delete_award_image/$1';
$route['admin/hrd/awards'] = 'admin/hrd/awards_gallery';
$route['admin/hrd/gallery'] = 'admin/hrd/gallery';

// Database Backup routes
$route['admin/database_backups'] = 'admin/database_backups/index';
$route['admin/database_backups/export_backup'] = 'admin/database_backups/export_backup';
$route['admin/database_backups/download/(.+)'] = 'admin/database_backups/download/$1';
$route['admin/database_backups/delete'] = 'admin/database_backups/delete';

$route['admin/hrd/gallery/latest'] = 'admin/hrd/gallery_latest';
$route['admin/hrd/employee_details_form'] = 'admin/hrd/employee_details_form';
$route['admin/hrd/job_application_form'] = 'admin/hrd/job_application_form';

/**
 * Support Ticket Routes
 */
$route['admin/support/web'] = 'admin/Support/Web/index';
$route['admin/support/web/add'] = 'admin/Support/Web/add';
$route['admin/support/web/submit'] = 'admin/Support/Web/submit';
$route['admin/support/web/view/(:num)'] = 'admin/Support/Web/view/$1';
$route['admin/support/web/reply'] = 'admin/Support/Web/reply';
