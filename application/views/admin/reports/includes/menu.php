<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="tw-my-3">
 
<div class="sm:tw-flex tw-space-y-3 sm:tw-space-y-0 tw-gap-6">
	
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-chart-area tw-mx-2"></i> Lead Reports</h4>
        <ul class="reports tw-space-y-1">
        
		  <li> <a href="<?php echo admin_url('reports/leads_by_stage');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leads By Stage</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('reports/leads_by_country');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leads By Country</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('reports/leads_by_source');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Leads by Source</a> 
		  </li>
		
		  
        </ul>
      </div>
	  
      <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-chart-pie tw-mx-2"></i> Deal Reports</h4>
        <ul class="reports tw-space-y-1">
          <!--<li> <a href="#" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>By Company</a> 
		  </li>-->
		  <li> <a href="<?php echo admin_url('reports/deals_by_status');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Deal By Status</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('reports/deals_by_company');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Deal By Company</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('reports/deals_by_staff');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Deal By Staff</a> 
		  </li>
		  
        </ul>
      </div>
	  
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-chart-line tw-mx-2"></i> Sales Reports</h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('reports/sales_by_invoice');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Sales By Invoice</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('reports/sales_by_payments');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Sales By Payments</a> 
		  </li>
		  <li> <a href="<?php echo admin_url('reports/invoice_by_staff');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i>Invoice By Staff</a> 
		  </li>
		  
		  
		
		  
        </ul>
      </div>
	  
	  <div class="sm:tw-border-r sm:tw-border-solid sm:tw-border-neutral-200 tw-pr-10 tw-w-96 tw-p-2" style="box-shadow: -2px -2px 9px #d4d4d4, 0px 0px 0px #ffffff !important;backdrop-filter: saturate(125%) blur(10px);">
        <h4 class="tw-mt-0 tw-font-semibold tw-text-lg tw-text-neutral-700 tw-ml-2.5 tw-inline-flex tw-items-center"><i class="fa-solid fa-chart-simple tw-mx-2"></i> Activity Reports</h4>
        <ul class="reports tw-space-y-1">
          <li> <a href="<?php echo admin_url('reports/activity_by_staff');?>" class="tw-font-medium tw-px-3 tw-py-3 tw-text-neutral-100 hover:tw-text-neutral-800 active:tw-text-neutral-800 focus:tw-text-neutral-800 hover:tw-bg-neutral-200 tw-w-full tw-inline-flex tw-items-center tw-rounded-md"><i class="fa-solid fa-arrow-right-long tw-mx-2"></i> Activity by Staff</a> 
		  </li>
        </ul>
      </div>
	  
    </div>
</div>

