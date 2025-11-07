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

$route['default_controller']   = 'clients';
$route['404_override']         = '';
$route['translate_uri_dashes'] = false;

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
$route['/'] = 'clients';

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

// HRD - Manage Attendance by Date
$route['admin/hrd/manage_attendance_by_date'] = 'admin/hrd/manage_attendance_by_date';
$route['admin/hrd/attendance_bulk_update_by_date'] = 'admin/hrd/attendance_bulk_update_by_date';
$route['admin/hrd/attendance_update_inout_by_date'] = 'admin/hrd/attendance_update_inout_by_date';
$route['admin/hrd/setting/dashboard'] = 'admin/hrd/setting_dashboard';
$route['admin/hrd/setting/staff_type'] = 'admin/hrd/staff_type';
$route['admin/hrd/stafftype'] = 'admin/hrd/stafftype';
$route['admin/hrd/delete_staff_type/(:num)'] = 'admin/hrd/delete_staff_type/$1';
$route['admin/hrd/toggle_staff_type/(:num)'] = 'admin/hrd/toggle_staff_type/$1';
$route['admin/hrd/attendance_update_request_add'] = 'admin/hrd/attendance_update_request_add';
$route['admin/hrd/self_service'] = 'admin/hrd/self_service';
$route['admin/hrd/holidays_list'] = 'admin/hrd/holidays_list';
$route['admin/hrd/my_document'] = 'admin/hrd/my_document';
$route['admin/hrd/my_document_add'] = 'admin/hrd/my_document_add';
$route['admin/hrd/leave_balance'] = 'admin/hrd/leave_balance';
$route['admin/hrd/setting/leave_balance'] = 'admin/hrd/setting_leave_balance';
$route['admin/hrd/setting/leave_balance_add'] = 'admin/hrd/setting_leave_balance_add';
$route['admin/hrd/uploaded_document'] = 'admin/hrd/uploaded_document';
$route['admin/hrd/document_update_status'] = 'admin/hrd/document_update_status';
$route['admin/hrd/profile'] = 'admin/hrd/profile';
$route['admin/hrd/profile_update_personal'] = 'admin/hrd/profile_update_personal';
$route['admin/hrd/profile_update_social'] = 'admin/hrd/profile_update_social';
$route['admin/hrd/profile_image_update'] = 'admin/hrd/profile_image_update';

// Database Backup routes
$route['admin/database_backups'] = 'admin/database_backups/index';
$route['admin/database_backups/export_backup'] = 'admin/database_backups/export_backup';
$route['admin/database_backups/download/(.+)'] = 'admin/database_backups/download/$1';
$route['admin/database_backups/delete'] = 'admin/database_backups/delete';
