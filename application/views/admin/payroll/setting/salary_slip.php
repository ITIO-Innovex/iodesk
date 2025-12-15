<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .salary-slip-wrapper {
    max-width: 900px;
    margin: 0 auto;
  }
  .salary-slip-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #fff;
    padding: 30px;
  }
  .slip-header {
    border-bottom: 1px solid #e5e7eb;
    padding-bottom: 20px;
    margin-bottom: 20px;
  }
  .slip-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
  }
  .slip-meta div {
    min-width: 180px;
  }
  .slip-section-title {
    font-size: 16px;
    font-weight: 600;
    margin: 20px 0 10px;
  }
  .totals-box {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 18px;
    background: #f9fafb;
  }
  .totals-box strong {
    font-size: 20px;
  }
  @media print {
    body * {
      visibility: hidden !important;
    }
    #wrapper, #wrapper * {
      visibility: visible !important;
    }
    #wrapper {
      position: absolute !important;
      left: 0;
      top: 0;
      width: 100%;
      margin: 0 !important;
    }
    #side-menu,
    .mobile-menu-toggle,
    .admin #side-menu {
      display: none !important;
    }
	#print-button {
      display: none !important;
    }
	a[href]:after {
        content: none !important;
    }
  }
</style>
<div id="wrapper">

  <div class="content">
    <div class="row">
	
      <div class="col-md-12">
	  
        <div class="salary-slip-wrapper">
		<div class="text-right tw-mb-2" id="print-button">
		<a href="javascript:window.print()" class="btn btn-default btn-sm" title="Print Salary Slip"><i class="fa-solid fa-print"></i></a>
		<a href="<?php echo admin_url('payroll/setting/salary_slip_download');?>?id=<?php echo $staffid; ?>&month=<?php echo $month; ?>" class="btn btn-default btn-sm" title="Download pdf"><i class="fa-solid fa-file-pdf text-danger"></i></a>
        </div>
          <div class="salary-slip-card">
            <div class="slip-header">
              <div class="tw-flex tw-items-start tw-justify-between tw-gap-3 tw-flex-wrap">
                
                <div>
                  <?php echo get_company_logo(get_admin_uri() . '/', 'v-logo')?> 
                </div>
				<div>
                  <h4 class="tw-text-lg tw-font-semibold tw-m-0"><?php echo e(get_staff_company_name()); ?> </h4>
                </div>
				<div>
                  <h4 class="tw-text-lg tw-font-semibold tw-m-0">Salary Slip</h4>
                  <p class="tw-text-neutral-500 tw-m-0"><?php echo html_escape(date('F Y', strtotime($month . '-01'))); ?></p>
                </div>
              </div>
            </div>

            <div class="slip-meta">
              <div>
                <small class="text-muted text-uppercase">Employee Name</small>
                <div class="tw-font-medium"><?php echo html_escape(trim(($slip['firstname'] ?? '') . ' ' . ($slip['lastname'] ?? '')) ?: 'N/A'); ?></div>
              </div>
              <div>
                <small class="text-muted text-uppercase">Employee Code</small>
                <div class="tw-font-medium"><?php echo html_escape($slip['employee_code'] ?? '-'); ?></div>
              </div>
              <div>
                <small class="text-muted text-uppercase">Department</small>
                <div class="tw-font-medium"><?php echo html_escape($slip['department'] ?? '-'); ?></div>
              </div>
              <div>
                <small class="text-muted text-uppercase">Designation</small>
                <div class="tw-font-medium"><?php echo html_escape($slip['designation'] ?? '-'); ?></div>
              </div>
              <div>
                <small class="text-muted text-uppercase">Branch</small>
                <div class="tw-font-medium"><?php echo html_escape($slip['branch_name'] ?? '-'); ?></div>
              </div>
              <div>
                <small class="text-muted text-uppercase">Joining Date</small>
                <div class="tw-font-medium"><?php echo $slip['joining_date'] ? _d($slip['joining_date']) : '-'; ?></div>
              </div>
            </div>

            
			
			<table class="table table-bordered">
              <thead>
                
                <tr>
                  <th class="text-center">Earnings</th>
                  <th class="text-right">Amount</th>
				  <th class="text-center">Deductions</th>
                  <th class="text-right">Amount</th>
                </tr>
              </thead>
			  <tbody>
			     <tr>
                  <td colspan="2"><table class="table" style="margin:unset;">
                  <tr>
				  <?php if (!empty($earnings)) { ?>
                  <?php foreach ($earnings as $earning) { ?>
                    <tr>
                      <td>
                        <strong><?php echo html_escape($earning['label'] ?? '-'); ?></strong>
                      </td>
                      <td class="text-right"><?php echo number_format(($earning['amount'] ?? 0), 2, '.', ''); ?></td>
                    </tr>
                  <?php } ?>
                <?php } ?>
				  </tr>
				  </table></td>
                  <td colspan="2">
				  <table class="table" style="margin:unset;">
                  <tr>
				  <?php if (!empty($deductions)) { ?>
                  <?php foreach ($deductions as $deduction) { ?>
                    <tr>
                      <td>
                        <strong><?php echo html_escape($deduction['label'] ?? '-'); ?></strong>
                      </td>
                      <td class="text-right"><?php echo number_format(($deduction['amount'] ?? 0), 2, '.', ''); ?></td>
                    </tr>
                  <?php } ?>
                <?php } ?>
				  </tr>
				  </table>
				  </td>
                </tr>
				</tbody>
				<tr>
                  <td class="text-right">Amount Total : </td>
                  <td class="text-right"><?php echo number_format(($slip['gross_amount'] ?? 0), 2, '.', ''); ?></td>
				  <td class="text-right">Amount Total : </td>
                  <td class="text-right"><?php echo number_format(($slip['deduction_amount'] ?? 0), 2, '.', ''); ?></td>
                </tr>
				<tr>
                  <td class="text-right">&nbsp;</td>
                  <td class="text-right">&nbsp;</td>
				  <td class="text-right">Net Pay : </td>
                  <td class="text-right"><?php echo number_format(($slip['net_amount'] ?? 0), 2, '.', ''); ?></td>
                </tr>
				<tr>
                  <td  colspan="4">Net Pay : <?php echo amountInWords(number_format(($slip['net_amount'] ?? 0), 2, '.', ''));?></td>
                </tr>
            </table>
			
			
           <div class="tw-mt-5 tw-text-sm tw-text-neutral-500">
              Generated on <?php echo _dt($slip['run_created_at'] ?? date('Y-m-d H:i:s')); ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body>
</html>
