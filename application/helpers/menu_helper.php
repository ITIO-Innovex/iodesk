<?php

defined('BASEPATH') or exit('No direct script access allowed');

function app_init_admin_sidebar_menu_items()
{
    $CI = &get_instance();

    $CI->app_menu->add_sidebar_menu_item('home', [
        'name'     => 'Home',
        'href'     => admin_url(),
        'position' => 1,
        'icon'     => 'fa fa-home',
        'badge'    => [],
    ]);
	
 	//////////////////////////// Leads / Seals Sections //////////////   

if (is_staff_member()&& staff_can('view',  'leads')) {
    $CI->app_menu->add_sidebar_menu_item('LeadsDeal', [
        'collapse' => true,
        'name'     => _l('Leads / Deal'),
        'position' => 20,
        'icon'     => 'fa-solid fa-handshake',
        'badge'    => [],
    ]);
	
	    $CI->app_menu->add_sidebar_children_item('LeadsDeal', [
            'slug'     => 'add_new_leads',
            'name'     => _l('Add New Lead'),
            'href'     => admin_url('leads/add_new_leads'),
            'position' => 15,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('LeadsDeal', [
            'slug'     => 'Leadss',
            'name'     => _l('als_leads'),
            'href'     => admin_url('leads'),
            'position' => 15,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('LeadsDeal', [
            'slug'     => 'Deals',
            'name'     => _l('Deals'),
            'href'     => admin_url('leads/deals'),
            'position' => 15,
            'badge'    => [],
        ]);
		
	}


	//////////////////////////// Sales Sections //////////////
	
	
	 $CI->app_menu->add_sidebar_menu_item('salesD', [
        'collapse' => true,
        'name'     => _l('als_sales'),
        'position' => 20,
        'icon'     => 'fa-solid fa-receipt',
        'badge'    => [],
    ]);
    if ((staff_can('view',  'invoices') || staff_can('view_own',  'invoices'))
        || (staff_has_assigned_invoices() && get_option('allow_staff_view_invoices_assigned') == 1)
    ) {
        $CI->app_menu->add_sidebar_children_item('salesD', [
            'slug'     => 'invoices',
            'name'     => _l('invoices'),
            'href'     => admin_url('invoices'),
            'position' => 15,
            'badge'    => [],
        ]);
    }
    if (staff_can('view',  'items')) {
        $CI->app_menu->add_sidebar_children_item('salesD', [
            'slug'     => 'items',
            'name'     => _l('items'),
            'href'     => admin_url('invoice_items'),
            'position' => 30,
            'badge'    => [],
        ]);
    }
    if (
        staff_can('view',  'payments') || staff_can('view_own',  'invoices')
        || (get_option('allow_staff_view_invoices_assigned') == 1 && staff_has_assigned_invoices())
    ) {
        $CI->app_menu->add_sidebar_children_item('salesD', [
            'slug'     => 'payments_1',
            'name'     => _l('payments'),
            'href'     => admin_url('payments'),
            'position' => 20,
            'badge'    => [],
        ]);
    }
	
	/////////////////////////////// Workspace Sections ///////////

		 $CI->app_menu->add_sidebar_menu_item('Workspace', [
        'collapse' => true,
        'name'     => 'Workspace',
        'position' => 20,
        'icon'     => 'fa fa-briefcase',
        'badge'    => [],
        ]);
    
	    //if (is_staff_member() && staff_can('view',  'web_form')) {
        $CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'web_form_1',
            'name'     => 'Web Form',
            'href'     => admin_url('web_form'),
            'position' => 15,
			'icon'     => 'fa-brands fa-wpforms',
            'badge'    => [],
        ]);
		//}
		
		if (is_admin() || staff_can('view', 'user_utility')) {
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'user_utility_1',
            'name'     => 'Team Document',
            'href'     => admin_url('user_utility'),
            'position' => 15,
			'icon'     => 'fa-solid fa-file-zipper',
            'badge'    => [],
        ]);
		}
		
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'important_document_1',
            'name'     => 'My Document',
            'href'     => admin_url('important_document'),
            'position' => 15,
			'icon'     => 'fa-solid fa-file-contract',
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'drive_1',
            'name'     => 'My Drive',
            'href'     => admin_url('drive'),
            'position' => 15,
			'icon'     => 'fa-brands fa-google-drive',
            'badge'    => [],
        ]);
		
		if (is_admin() || staff_can('adder', 'under_writing') || staff_can('approver', 'under_writing')) {
		
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'underwriting_1',
            'name'     => 'Under Writing',
            'href'     => admin_url('underwriting'),
            'position' => 15,
			'icon'     => 'fa-solid fa-file-pen',
            'badge'    => [],
        ]);
		
      
    }
	
		if (is_staff_member() && staff_can('webmail',  'webmail')) {
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'webmail_2',
            'name'     => 'Webmail',
            'href'     => admin_url('webmail/inbox'),
            'position' => 15,
			'icon'     => 'fa-regular fa-envelope',
            'badge'    => [],
        ]);
		}
		
		if (is_staff_member() && staff_can('view',  'direct_email')) {
		
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'direct_email_1',
            'name'     => 'Direct Email',
            'href'     => admin_url('direct_email'),
            'position' => 15,
			'icon'     => 'fa-solid fa-envelopes-bulk',
            'badge'    => [],
        ]);
    }
		
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'email_template',
            'name'     => 'Email Template',
            'href'     => admin_url('email_template'),
            'position' => 15,
			'icon'     => 'fa-solid fa-envelope-open-text',
            'badge'    => [],
        ]);
		
		
		 if (is_staff_member() && staff_can('view',  'ai_support')) {
		 
		 $CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'ai_content_generator_1',
            'name'     => 'AI Support',
            'href'     => admin_url('ai_content_generator'),
            'position' => 15,
			'icon'     => 'fa-regular fa-newspaper',
            'badge'    => [],
        ]);
        }
		
		if (is_staff_member() && staff_can('webmail_setup',  'webmail')) {
		
		$CI->app_menu->add_sidebar_children_item('Workspace', [
            'slug'     => 'webmail_setup_1',
            'name'     => 'Webmail Setup',
            'href'     => admin_url('webmail_setup'),
            'position' => 15,
			'icon'     => 'fa-solid fa-at',
            'badge'    => [],
        ]);
		
		
    }
	
	//////////////////////////////
	
	    if(isset($_SESSION['company_form_type'])&& $_SESSION['company_form_type']<>1){
	
		if (is_staff_member() && get_staff_rolex()<>3) {
		$CI->app_menu->add_sidebar_menu_item('UW Status', [
			'name'     => _l('UW Status'),
			'href'     => admin_url('leads/uw_status'),
			'icon'     => 'fa-solid fa-file-signature',
			'position' => 20,
			'badge'    => [],
		]);
		}
		
    }
	


    

   
	
	if (is_super()) {
	
	    $CI->app_menu->add_sidebar_menu_item('support-tickets', [
			'name'     => 'Support Ticket',
			'href'     => admin_url('support/web'),
			'icon'     => 'fa-solid fa-ticket',
			'position' => 90,
			'badge'    => [],
		]);
		
		$CI->app_menu->add_sidebar_menu_item('ai-content-generator', [
			'name'     => 'Maintenance Notice',
			'href'     => admin_url('support_maintenance_notice'),
			'icon'     => 'fa-solid fa-circle-info',
			'position' => 45,
			'badge'    => [],
		]);
	
	}
	
////////////////////////////////////////// User Setup Sections ////////////////	
	
	if (is_admin()) {
		$CI->app_menu->add_sidebar_menu_item('crm_setup', [
			'name'     => _l('crm_setup'),
			'href'     => admin_url('crm_setup'),
			'icon'     => 'fa-solid fa-gears',
			'position' => 90,
			'badge'    => [],
		]);
		
		$CI->app_menu->add_sidebar_menu_item('user_documentation', [
			'name'     => _l('user_documentation'),
			'href'     => base_url('user_documentation'),
			'icon'     => 'fa-solid fa-book-open',
			'position' => 90,
			'badge'    => [],
			'href_attributes' => [
				'target' => '_blank',
				'rel'    => 'noopener',
			],
		]);
		
		$CI->app_menu->add_sidebar_menu_item('help_center', [
			'name'     => _l('help_center'),
			'href'     => base_url('help_center'),
			'icon'     => 'fa-solid fa-headset',
			'position' => 90,
			'badge'    => [],
			'href_attributes' => [
				'target' => '_blank',
				'rel'    => 'noopener',
			],
		]);
    }
	
////////////////////////////////////////// Invoice Sections ////////////////
	
	   if (staff_can('view',  'invoices')) {
	   
	   
	    $CI->app_menu->add_sidebar_menu_item('invoice_manager', [
            'collapse' => true,
            'name'     => 'Invoices',
            'position' => 55,
            'icon'     => 'fa-solid fa-file-invoice-dollar',
            'badge'    => [],
        ]);
		
		
		
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'invoices_2',
            'name'     => 'Invoices',
            'href'     => admin_url('invoice_manager/invoices'),
            'position' => 5,
            'badge'    => [],
        ]);
		
		
		
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'payments',
            'name'     => 'Payments',
            'href'     => admin_url('invoice_manager/payments'),
            'position' => 15,
            'badge'    => [],
        ]);
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'products',
            'name'     => 'Products',
            'href'     => admin_url('invoice_manager/products'),
            'position' => 10,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'payments',
            'name'     => 'Payments',
            'href'     => admin_url('invoice_manager/payments'),
            'position' => 15,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'paymentmodes',
            'name'     => 'Payment Modes',
            'href'     => admin_url('paymentmodes'),
            'position' => 15,
            'badge'    => [],
        ]);
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'invoice_company',
            'name'     => 'Manage Company',
            'href'     => admin_url('invoice_manager/invoice_company'),
            'position' => 15,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('invoice_manager', [
            'slug'     => 'invoice_notes',
            'name'     => 'Invoice Notes',
            'href'     => admin_url('invoice_manager/invoice_notes'),
            'position' => 15,
            'badge'    => [],
        ]);
		
		
		}
		
////////////////////////////////////////// End Invoice Sections ////////////////		
		
////////////////////////////////////////// Subscriptions Sections ////////////////
    
    $CI->app_menu->add_sidebar_menu_item('subscriptions_menu', [
            'collapse' => true,
            'name'     => 'Subscriptions',
            'position' => 55,
            'icon'     => 'fa-solid fa-file-invoice-dollar',
            'badge'    => [],
        ]);
		
		if (is_super()) {
		
		$CI->app_menu->add_sidebar_children_item('subscriptions_menu', [
            'slug'     => 'user-subscriptions',
            'name'     => 'Subscriber',
            'href'     => admin_url('services/user_subscriptions'),
            'position' => 5,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('subscriptions_menu', [
            'slug'     => 'subscriptions-invoices',
            'name'     => 'Invoices',
            'href'     => admin_url('services/subscriptions_invoices'),
            'position' => 10,
            'badge'    => [],
        ]);
		
		

        $CI->app_menu->add_sidebar_children_item('subscriptions_menu', [
            'slug'     => 'subscriptions',
            'name'     => 'Subscriptions',
            'href'     => admin_url('services/subscriptions'),
            'position' => 15,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('subscriptions_menu', [
            'slug'     => 'services/sent_renewal_reminder',
            'name'     => 'Renewal Reminder',
            'href'     => admin_url('services/sent_renewal_reminder'),
            'position' => 10,
            'badge'    => [],
        ]);

        

        
    }
	if (is_admin()) {
	
	 $CI->app_menu->add_sidebar_children_item('subscriptions_menu', [
            'slug'     => 'plan_subscriptions',
            'name'     => 'My Subscriptions',
            'href'     => admin_url('services/my_subscriptions'),
            'position' => 15,
            'badge'    => [],
        ]);
	}
	
////////////////////////////////////////// End Subscriptions Sections ////////////////	
	
////////////////////////////////////////// Project Sections ////////////////
	
	 $CI->app_menu->add_sidebar_menu_item('project', [
        'collapse' => true,
        'name'     => _l('project'),
        'position' => 10,
        'icon'     => 'fa-solid fa-chart-gantt',
        'badge'    => [],
    ]);

      if (staff_can('project_dashboard',  'project')){
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'Dashboard',
            'name'     => _l('Dashboard'),
            'href'     => admin_url('project/dashboard'),
            'position' => 10,
            'badge'    => [],
        ]);
    }
   
	 if (staff_can('project_project',  'project')){
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'Project',
            'name'     => _l('Project'),
            'href'     => admin_url('project'),
            'position' => 15,
            'badge'    => [],
        ]);
    }
	
	if (staff_can('project_project',  'project')){
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'Tasks',
            'name'     => _l('Tasks'),
            'href'     => admin_url('project/tasks'),
            'position' => 15,
            'badge'    => [],
        ]);
    }
	if (staff_can('project_project',  'project')){
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'notifications',
            'name'     => 'Notifications',
            'href'     => admin_url('project/notifications'),
            'position' => 15,
            'badge'    => [],
        ]);
    }
	 
    
    if (staff_can('project_collaboration',  'project'))
   {
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'Collaboration',
            'name'     => _l('Collaboration'),
            'href'     => admin_url('project/collaboration'),
            'position' => 25,
            'badge'    => [],
        ]);
    }
	if (staff_can('project_chat',  'project'))
   {
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'Chat',
            'name'     => _l('Chat'),
            'href'     => admin_url('project_chat'),
            'position' => 25,
            'badge'    => [],
        ]);
    }
	
	
	if (staff_can('project_group',  'project')) {
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'project_group',
            'name'     => _l('project_group'),
            'href'     => admin_url('project/project_group'),
            'position' => 30,
            'badge'    => [],
        ]);
    }
	
	if (is_admin()) {
	$CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'project_custom_fields',
            'name'     => 'Custom Fields',
            'href'     => admin_url('project/project_custom_fields'),
            'position' => 30,
            'badge'    => [],
        ]);
	}
	if (is_super()) {
	
        $CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'project_status',
            'name'     => _l('project_status_table_name'),
            'href'     => admin_url('project/project_status'),
            'position' => 30,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_sidebar_children_item('project', [
            'slug'     => 'project_priority',
            'name'     => _l('project_priority'),
            'href'     => admin_url('project/project_priority'),
            'position' => 30,
            'badge'    => [],
        ]);
		
		
		
    }
////////////////////////////////////////// End Project Sections ////////////////	
	
////////////////////////////////////////// HRMS Staff Sections ////////////////
	
	 $CI->app_menu->add_sidebar_menu_item('hr_department', [
        'collapse' => true,
        'name'     => _l('HR_Attendance'),
        'position' => 12,
        'icon'     => 'fa-solid fa-person-booth',
        'badge'    => [],
    ]);

      if (staff_can('view_own',  'hr_department')){
        $CI->app_menu->add_sidebar_children_item('hr_department', [
            'slug'     => 'hrd_dashboard',
            'name'     => _l('Dashboard'),
            'href'     => admin_url('hrd/dashboard'),
            'position' => 10,
            'badge'    => [],
        ]);
      } 
	  if (staff_can('view_own',  'hr_department')){
        $CI->app_menu->add_sidebar_children_item('hr_department', [
            'slug'     => 'self_service',
            'name'     => _l('Self Service'),
            'href'     => admin_url('hrd/self_service'),
            'position' => 10,
            'badge'    => [],
        ]);
      }
	  
	  if (staff_can('view_own',  'hr_department')){
        $CI->app_menu->add_sidebar_children_item('hr_department', [
            'slug'     => 'attendance',
            'name'     => _l('Attendance'),
            'href'     => admin_url('hrd/attendance'),
            'position' => 10,
            'badge'    => [],
        ]);
      }
	  
	  if (staff_can('view_own',  'hr_department')){
        $CI->app_menu->add_sidebar_children_item('hr_department', [
            'slug'     => 'leave_application',
            'name'     => _l('leave_application'),
            'href'     => admin_url('hrd/leave_application'),
            'position' => 10,
            'badge'    => [],
        ]);
      }
	  
////////////////////////////////////////// END HRMS Staff Sections ////////////////	  
	 
////////////////////////////////////////// Conversation Sections ////////////////

    if (is_staff_member() && staff_can('view',  'conversation')) {
		$CI->app_menu->add_sidebar_menu_item('conversion', [
			'collapse' => true,
			'name'     => _l('lead_conversion'),
			'position' => 45,
			'icon'     => 'fas fa-comments',
			'badge'    => [],
		]);
        $CI->app_menu->add_sidebar_children_item('conversion', [
			'slug'     => 'als_telegram',
			'name'     => _l('als_telegram'),
			'href'     => admin_url('leads/telegram'),
			'position' => 1,
			'icon'     => 'fa-brands fa-telegram',
			'badge'    => [],
		]);
        $CI->app_menu->add_sidebar_children_item('conversion', [
			'slug'     => 'als_whatsapp',
			'name'     => _l('als_whatsapp'),
			'href'     => admin_url('whatsapp/chatlist'),
			'position' => 2,
			'icon'     => 'fa-brands fa-whatsapp',
			'badge'    => [],
		]);
        $CI->app_menu->add_sidebar_children_item('conversion', [
			'slug'     => 'als_whatsapp_dm',
			'name'     => 'Whatsapp DMs',
			'href'     => admin_url('whatsapp/dmlist'),
			'position' => 2,
			'icon'     => 'fa-brands fa-whatsapp',
			'badge'    => [],
		]);
    }
	
////////////////////////////////////////// End Conversation Sections ////////////////

    

	

    // Setup menu
	if(get_staff_user_id()==1 && empty($_SESSION['super_view_company_id'])){
	
	 $CI->app_menu->add_setup_menu_item('companies', [
            'name'     => _l('Companies'),
            'href'     => admin_url('staff/companies'),
            'position' => 5,
			'icon'     => 'fa-solid fa-building-user',
            'badge'    => [],
        ]);
	
	}else{
    if (staff_can('view',  'staff')) {
        $CI->app_menu->add_setup_menu_item('staff', [
            'name'     => _l('als_staff'),
            'href'     => admin_url('staff'),
            'position' => 5,
            'badge'    => [],
        ]);
    }
	}
	
	
	
    if(is_admin()) {
    // WhatsApp Configuration 
    $CI->app_menu->add_setup_menu_item('whatsapp', [
        'collapse' => true,
        'name'     => 'Whatsapp',
        'position' => 10,
        'badge'    => [],
    ]);

    $CI->app_menu->add_setup_children_item('whatsapp', [
        'slug'     => 'whatsapp-groups',
        'name'     => 'Whatsapp-Configuration',
        'href'     => admin_url('whatsapp/configuration'),
        'position' => 5,
        'badge'    => [],
    ]);
    // End WhatsApp Configuration
    // Telegram Configuration
    $CI->app_menu->add_setup_menu_item('telegram', [
        'collapse' => true,
        'name'     => 'Telegram',
        'position' => 11,
		'icon'     => 'fa-solid fa-paper-plane',
        'badge'    => [],
    ]);

    $CI->app_menu->add_setup_children_item('telegram', [
        'slug'     => 'telegram-groups',
        'name'     => 'Telegram-Configuration',
        'href'     => admin_url('telegram/configuration'),
        'position' => 5,
        'badge'    => [],
    ]);
    // 

    
        $CI->app_menu->add_setup_menu_item('customers', [
            'collapse' => true,
            'name'     => _l('clients'),
            'position' => 10,
            'badge'    => [],
        ]);

       /* $CI->app_menu->add_setup_children_item('customers', [
            'slug'     => 'customer-groups',
            'name'     => _l('customer_groups'),
            'href'     => admin_url('clients/groups'),
            'position' => 5,
            'badge'    => [],
        ]);*/
        $CI->app_menu->add_setup_menu_item('support', [
            'collapse' => true,
            'name'     => _l('support'),
            'position' => 15,
            'badge'    => [],
        ]);

        /*$CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'departments',
            'name'     => _l('acs_departments'),
            'href'     => admin_url('departments'),
            'position' => 5,
            'badge'    => [],
        ]);*/
		
		/*$CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-predefined-replies',
            'name'     => _l('acs_ticket_predefined_replies_submenu'),
            'href'     => admin_url('tickets/predefined_replies'),
            'position' => 10,
            'badge'    => [],
        ]);*/
		
		$CI->app_menu->add_setup_menu_item('leads', [
            'collapse' => true,
            'name'     => _l('acs_leads'),
            'position' => 20,
            'badge'    => [],
        ]);
        
		}
		
		if (is_super()) {
		
		$CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'leads-sources',
            'name'     => _l('acs_leads_sources_submenu'),
            'href'     => admin_url('leads/sources'),
            'position' => 5,
            'badge'    => [],
        ]);
		
		
        $CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'leads-statuses',
            'name'     => _l('acs_leads_statuses_submenu'),
            'href'     => admin_url('leads/statuses'),
            'position' => 10,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'deal-status',
            'name'     => _l('Deal Status'),
            'href'     => admin_url('leads/deal_status'),
            'position' => 20,
            'badge'    => [],
        ]);
		
		
		
		
		
		
        /*$CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'leads-email-integration',
            'name'     => _l('leads_email_integration'),
            'href'     => admin_url('leads/email_integration'),
            'position' => 15,
            'badge'    => [],
        ]);*/
		
        $CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'web-to-lead',
            'name'     => _l('web_to_lead'),
            'href'     => admin_url('leads/forms'),
            'position' => 20,
            'badge'    => [],
        ]);
		
		$CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'deal-stages',
            'name'     => _l('Deal Stages'),
            'href'     => admin_url('leads/deal_stage'),
            'position' => 21,
            'badge'    => [],
        ]);
		}
		
		if (is_admin()) {
		$CI->app_menu->add_setup_children_item('leads', [
            'slug'     => 'task-status',
            'name'     => _l('Task Status'),
            'href'     => admin_url('leads/task_status'),
            'position' => 20,
            'badge'    => [],
        ]);
		
		}
		
		
		
		if (is_super()) {
        
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-priorities',
            'name'     => _l('acs_ticket_priority_submenu'),
            'href'     => admin_url('tickets/priorities'),
            'position' => 15,
            'badge'    => [],
        ]);
        $CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-statuses',
            'name'     => _l('acs_ticket_statuses_submenu'),
            'href'     => admin_url('tickets/statuses'),
            'position' => 20,
            'badge'    => [],
        ]);

        /*$CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-services',
            'name'     => _l('acs_ticket_services_submenu'),
            'href'     => admin_url('tickets/services'),
            'position' => 25,
            'badge'    => [],
        ]);*/
        /*$CI->app_menu->add_setup_children_item('support', [
            'slug'     => 'tickets-spam-filters',
            'name'     => _l('spam_filters'),
            'href'     => admin_url('spam_filters/view/tickets'),
            'position' => 30,
            'badge'    => [],
        ]);*/

        
         }
        
        if (is_super()) {
		
        $CI->app_menu->add_setup_menu_item('contracts', [
            'collapse' => true,
            'name'     => _l('acs_contracts'),
            'position' => 30,
            'badge'    => [],
        ]);
        $CI->app_menu->add_setup_children_item('contracts', [
            'slug'     => 'contracts-types',
            'name'     => _l('acs_contract_types'),
            'href'     => admin_url('contracts/types'),
            'position' => 5,
            'badge'    => [],
        ]);
		
		}

        
		
        if (is_super()) {
		
		$modulesNeedsUpgrade = $CI->app_modules->number_of_modules_that_require_database_upgrade();

        $CI->app_menu->add_setup_menu_item('modules', [
            'href'     => admin_url('modules'),
            'name'     => _l('modules'),
            'position' => 35,
            'badge'    => [
                'value' => $modulesNeedsUpgrade > 0 ? $modulesNeedsUpgrade : null,
                'type' => 'warning',
            ],
        ]);
		
        $CI->app_menu->add_setup_menu_item('custom-fields', [
            'href'     => admin_url('custom_fields'),
            'name'     => _l('asc_custom_fields'),
            'position' => 45,
            'badge'    => [],
        ]);

        $CI->app_menu->add_setup_menu_item('gdpr', [
            'href'     => admin_url('gdpr'),
            'name'     => _l('gdpr_short'),
            'position' => 50,
            'badge'    => [],
        ]);
		
		

        $CI->app_menu->add_setup_menu_item('roles', [
            'href'     => admin_url('roles'),
            'name'     => _l('acs_roles'),
            'position' => 55,
            'badge'    => [],
        ]);
        // Designation setup page
        $CI->app_menu->add_setup_menu_item('designation', [
            'href'     => admin_url('designation'),
            'name'     => 'Designation',
            'position' => 57,
            'badge'    => [],
        ]);
	 if (staff_can('view',  'settings')) {
        $CI->app_menu->add_setup_menu_item('estimate_request', [
            'collapse' => true,
            'name'     => _l('acs_estimate_request'),
            'position' => 34,
            'badge'    => [],
        ]);
    }

    $CI->app_menu->add_setup_children_item('estimate_request', [
        'slug'     => 'estimate-request-forms',
        'name'     => _l('acs_estimate_request_forms'),
        'href'     => admin_url('estimate_request/forms'),
        'position' => 5,
        'badge'    => [],
    ]);

    $CI->app_menu->add_setup_children_item('estimate_request', [
        'slug'     => 'estimate-request-statuses',
        'name'     => _l('acs_estimate_request_statuses_submenu'),
        'href'     => admin_url('estimate_request/statuses'),
        'position' => 10,
        'badge'    => [],
    ]);
	//if (staff_can('view',  'email_templates')) {
        $CI->app_menu->add_setup_menu_item('email-templates', [
            'href'     => admin_url('emails'),
            'name'     => _l('acs_email_templates'),
            'position' => 40,
            'badge'    => [],
        ]);
   // }

		

        /*             $CI->app_menu->add_setup_menu_item('api', [
                          'href'     => admin_url('api'),
                          'name'     => 'API',
                          'position' => 65,
                  ]);*/
				  
		}
    
	
	 if (is_super()) {
	if (staff_can('view',  'settings')) {
        $CI->app_menu->add_setup_menu_item('settings', [
            'href'     => admin_url('settings'),
            'name'     => _l('acs_settings'),
            'position' => 200,
            'badge'    => [],
        ]);
    }
	}else{
	
	if (staff_can('view',  'settings')) {
        $CI->app_menu->add_setup_menu_item('settings', [
            'href'     => admin_url('customize'),
            'name'     => _l('acs_settings'),
            'position' => 200,
            'badge'    => [],
        ]);
    }
	
	}
	
	    if (staff_can('view_setting',  'hr_department')) {
        $CI->app_menu->add_setup_menu_item('hr_department', [
            'collapse' => true,
            'name'     => _l('hr_department'),
			'icon'     => 'fa-solid fa-person-booth',
            'position' => 25,
            'badge'    => [],
        ]);
       }
	   
	    if (staff_can('view_setting',  'hr_department')) {
		$CI->app_menu->add_setup_children_item('hr_department', [
            'slug'     => 'dashboard',
            'name'     => _l('Dashboard'),
            'href'     => admin_url('hrd/setting/dashboard'),
            'position' => 5,
            'badge'    => [],
        ]);
		}
		
		if (staff_can('view_setting',  'hr_department')) {
		$CI->app_menu->add_setup_children_item('hr_department', [
            'slug'     => 'self_service',
            'name'     => _l('Self Service Management'),
            'href'     => admin_url('hrd/setting/self_service'),
            'position' => 5,
            'badge'    => [],
        ]);
		}
	   
	    if (staff_can('view_setting',  'hr_department')) {
		$CI->app_menu->add_setup_children_item('hr_department', [
            'slug'     => 'staff_manager',
            'name'     => _l('staff_manager'),
            'href'     => admin_url('hrd/staff_manager'),
            'position' => 5,
            'badge'    => [],
        ]);
		}
		
		if (staff_can('view_setting',  'hr_department')) {
		$CI->app_menu->add_setup_children_item('hr_department', [
            'slug'     => 'leave_manager',
            'name'     => _l('leave_manager'),
            'href'     => admin_url('hrd/leave_manager'),
            'position' => 5,
            'badge'    => [],
        ]);
		}
		
		if (staff_can('view_setting',  'hr_department')) {
		$CI->app_menu->add_setup_children_item('hr_department', [
            'slug'     => 'manage_attendance_by_date',
            'name'     => _l('attendance_manager'),
            'href'     => admin_url('hrd/manage_attendance_by_date'),
            'position' => 5,
            'badge'    => [],
        ]);
		}
		
		if (staff_can('view_interviews',  'hr_department')) {
		$CI->app_menu->add_setup_children_item('hr_department', [
            'slug'     => 'interviews',
            'name'     => _l('Interviews'),
            'href'     => admin_url('hrd/interviews'),
            'position' => 5,
            'badge'    => [],
        ]);
		}
		
		
	

    if (staff_can('view',  'finance')) {
        $CI->app_menu->add_setup_menu_item('finance', [
            'collapse' => true,
            'name'     => _l('acs_finance '),
            'position' => 25,
            'badge'    => [],
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'taxes',
            'name'     => _l('acs_sales_taxes_submenu'),
            'href'     => admin_url('taxes'),
            'position' => 5,
            'badge'    => [],
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'currencies',
            'name'     => _l('acs_sales_currencies_submenu'),
            'href'     => admin_url('currencies'),
            'position' => 10,
            'badge'    => [],
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'payment-modes',
            'name'     => _l('acs_sales_payment_modes_submenu'),
            'href'     => admin_url('paymentmodes'),
            'position' => 15,
            'badge'    => [],
        ]);
        $CI->app_menu->add_setup_children_item('finance', [
            'slug'     => 'expenses-categories',
            'name'     => _l('acs_expense_categories'),
            'href'     => admin_url('expenses/categories'),
            'position' => 20,
            'badge'    => [],
        ]);
    }
	

    // Database Backup menu item (Super only)
    if (is_admin()) {
        
		$CI->app_menu->add_setup_menu_item('department', [
            'href'     => admin_url('departments'),
            'name'     => 'departments',
            'position' => 60,
            'icon'     => 'fa-solid fa-address-card',
            'badge'    => [],
        ]);
		$CI->app_menu->add_setup_menu_item('designation', [
            'href'     => admin_url('designation'),
            'name'     => 'Designation',
            'position' => 60,
            'icon'     => 'fa-solid fa-users-rectangle',
            'badge'    => [],
        ]);
		$CI->app_menu->add_setup_menu_item('branch_manager', [
            'href'     => admin_url('hrd/setting/branch_manager'),
            'name'     => 'Branch',
            'position' => 60,
            'icon'     => 'fa-solid fa-code-branch',
            'badge'    => [],
        ]);
		$CI->app_menu->add_setup_menu_item('staff_type', [
            'href'     => admin_url('hrd/setting/staff_type'),
            'name'     => 'Staff Type',
            'position' => 65,
            'icon'     => 'fa-solid fa-users',
            'badge'    => [],
        ]);
		$CI->app_menu->add_setup_menu_item('database_backups', [
            'href'     => admin_url('database_backups'),
            'name'     => 'DB Backup',
            'position' => 65,
            'icon'     => 'fa fa-database',
            'badge'    => [],
        ]);
		if (!is_super()) {
		$CI->app_menu->add_setup_menu_item('smtp_check', [
            'href'     => admin_url('dashboard/smtp_check'),
            'name'     => 'Staff SMTP Status',
            'position' => 65,
            'icon'     => 'fa-solid fa-at',
            'badge'    => [],
        ]);
		}
		
		$CI->app_menu->add_setup_menu_item('staff_type', [
            'href'     => admin_url('menu_setup/main_menu'),
            'name'     => 'Main Menu',
            'position' => 65,
            'icon'     => 'fa-solid fa-bars-staggered',
            'badge'    => [],
        ]);
		
		
    }
	   if (is_super()) {
		$CI->app_menu->add_setup_menu_item('server_info', [
            'href'     => admin_url('dashboard/server_info'),
            'name'     => 'Server Status',
            'position' => 65,
            'icon'     => 'fa-solid fa-at',
            'badge'    => [],
        ]);
		}
}