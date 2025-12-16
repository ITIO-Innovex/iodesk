<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .salary-slip-self-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #fff;
    padding: 24px;
  }
  .slip-header {
    border-bottom: 1px solid #f0f0f0;
    padding-bottom: 16px;
    margin-bottom: 20px;
  }
  .slip-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 12px;
  }
  .slip-meta div {
    background: #f9fafb;
    padding: 12px;
    border-radius: 8px;
  }
  .slip-meta span {
    display: block;
    font-size: 11px;
    text-transform: uppercase;
    color: #6b7280;
  }
  .slip-meta strong {
    display: block;
    margin-top: 4px;
    font-size: 14px;
    color: #111827;
  }
  .result-state {
    margin-top: 24px;
  }
  .summary-pill {
    border-radius: 10px;
    background: #f3f4f6;
    padding: 14px;
    text-align: center;
  }
  .summary-pill span {
    font-size: 11px;
    text-transform: uppercase;
    color: #6b7280;
  }
  .summary-pill strong {
    display: block;
    margin-top: 4px;
    font-size: 18px;
    color: #111827;
  }
  .slip-tables {
    margin-top: 20px;
  }
  .slip-tables .table th {
    background: #f9fafb;
    text-transform: uppercase;
    font-size: 11px;
    color: #6b7280;
  }
  .empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #9ca3af;
  }
</style>
<div id="wrapper">
  <div class="content">
    <div class="row">
      <div class="col-md-10 col-md-offset-1">
        <div class="salary-slip-self-card">
          <div class="slip-header">
            <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-gap-4">
              <div>
                <h3 class="tw-text-lg tw-font-semibold tw-m-0">My Salary Slip</h3>
                <p class="tw-mt-1 tw-text-sm tw-text-neutral-500">Select a payroll month to view your generated slip.</p>
              </div>
              <div>
                <label class="control-label tw-text-xs tw-text-neutral-500 tw-uppercase">Payroll Month</label>
                <select id="slip-month" class="form-control">
                  <?php if (!empty($months)) { ?>
                    <?php foreach ($months as $month) { ?>
                      <option value="<?php echo html_escape($month); ?>" <?php echo ($month === $default_month) ? 'selected' : ''; ?>>
                        <?php echo html_escape(date('F Y', strtotime($month . '-01'))); ?>
                      </option>
                    <?php } ?>
                  <?php } else { ?>
                    <option value="">No payroll months available</option>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>

          <div id="slip-content">
            <?php if (empty($months)) { ?>
              <div class="empty-state">
                <i class="fa-regular fa-file-lines fa-2x"></i>
                <p class="tw-mt-3">No salary slips available yet. Once HR generates payroll for you, slips will appear here.</p>
              </div>
            <?php } else { ?>
              <div class="text-right tw-mb-2" id="print-button">
                <a href="#" id="download-slip-btn" class="btn btn-danger btn-sm" title="Download PDF">
                  Export to PDF
                </a>
              </div>
              <div class="empty-state" id="slip-loading-state">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
                <p class="tw-mt-3">Loading your salary slip...</p>
              </div>
              <div id="slip-details" style="display:none;">
                <div class="slip-meta tw-mb-4" id="slip-meta"></div>
                
                <div class="slip-tables row">
				<table class="table table-bordered">
              <thead>
                
                <tr>
                  <th class="tw-font-bold">Earnings</th>
                  <th class="text-right">Amount</th>
				  <th class="">Deductions</th>
                  <th class="text-right">Amount</th>
                </tr>
              </thead>
			  <tbody>
			     <tr>
                  <td colspan="2">
				  <table class="table" style="margin:unset;" id="slip-earnings-table">
                      <tbody></tbody>
                    </table></td>
				  <td colspan="2">
				  <table class="table" style="margin:unset;" id="slip-deductions-table">
                      <tbody></tbody>
                    </table></td>
				  </tr>
				  
				  <tr>
                  <td class="text-right">Amount Total :</td>
				  <td class="text-right"><strong id="slip-gross">--</strong></td>
				  <td class="text-right">Amount Total :</td>
				  <td class="text-right"><strong id="slip-deductions">--</strong></td>
				  </tr>
				  <tr>
                  <td></td>
				  <td></td>
				  <td class="text-right">Net Pay :</td>
				  <td class="text-right"><strong id="slip-net">--</strong></td>
				  </tr>
				  </tbody></table>
				
				
                
				  
				  
				  
                </div>
              </div>
              <div class="empty-state" id="slip-error-state" style="display:none;">
                <i class="fa-regular fa-circle-info fa-2x"></i>
                <p class="tw-mt-3" id="slip-error-message">Unable to load salary slip.</p>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
<script>

function formatmoney(amount) {
    amount = parseFloat(amount);

    if (isNaN(amount)) {
        return '0.00';
    }

    return amount.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

(function(){
  var monthsAvailable = <?php echo json_encode(array_values($months)); ?>;
  if (!monthsAvailable.length) {
    return;
  }

  var $monthSelect = $('#slip-month');
  var $loadingState = $('#slip-loading-state');
  var $details = $('#slip-details');
  var $errorState = $('#slip-error-state');
  var $errorMessage = $('#slip-error-message');

  var $meta = $('#slip-meta');
  var $gross = $('#slip-gross');
  var $deductions = $('#slip-deductions');
  var $net = $('#slip-net');
  var $earningsTable = $('#slip-earnings-table tbody');
  var $deductionsTable = $('#slip-deductions-table tbody');
  var loggedStaffId = <?php echo (int) $staffid; ?>;

  function renderSlips(data) {
    var staff = data.staff || {};

    $meta.html([
      '<div><span>Employee Name</span><strong>' + (staff.name || '-') + '</strong></div>',
      '<div><span>Employee Code</span><strong>' + (staff.code || '-') + '</strong></div>',
      '<div><span>Department</span><strong>' + (staff.department || '-') + '</strong></div>',
      '<div><span>Designation</span><strong>' + (staff.designation || '-') + '</strong></div>',
      '<div><span>Branch</span><strong>' + (staff.branch || '-') + '</strong></div>',
      '<div><span>Joining Date</span><strong>' + (staff.joining_date_formatted || '-') + '</strong></div>',
      '<div><span>Salary Month</span><strong>' + (data.month_formatted || '-') + '</strong></div>'
    ].join(''));

    $gross.text(formatmoney(data.gross || 0));
    $deductions.text(formatmoney(data.deductions || 0));
    $net.text(formatmoney(data.net || 0));

    function buildRows(items) {
      if (!items || !items.length) {
        return '<tr><td colspan="2" class="text-muted text-center">No data</td></tr>';
      }
      return items.map(function(item){ //alert(formatmoney(item.amount || 0));
        return '<tr><td>' + (item.label || '-') + '</td>' +
          '<td class="text-right">' + formatmoney(item.amount || 0) + '</td></tr>';
      }).join('');
    }

    $earningsTable.html(buildRows(data.earnings));
    $deductionsTable.html(buildRows(data.deduction_rows));

    var downloadUrl = admin_url + 'payroll/setting/salary_slip_download?id=' + encodeURIComponent(loggedStaffId) + '&month=' + encodeURIComponent(data.month);
    $('#download-slip-btn').attr('href', downloadUrl);
  }

  function fetchSlip(month) {
    $loadingState.show();
    $details.hide();
    $errorState.hide();

    $.ajax({
      url: admin_url + 'payroll/salary_slip_fetch',
      data: { month: month },
      dataType: 'json'
    }).done(function(resp){
      if (!resp || !resp.success) {
        $errorMessage.text(resp && resp.message ? resp.message : 'Unable to load salary slip.');
        $errorState.show();
        return;
      }
	   //alert(JSON.stringify(resp));
      renderSlips(resp);
      $details.show();
    }).fail(function(){
      $errorMessage.text('Server error while loading salary slip.');
      $errorState.show();
    }).always(function(){
      $loadingState.hide();
    });
  }

  $monthSelect.on('change', function(){
    var month = $(this).val();
    if (month) {
      fetchSlip(month);
    }
  });

  fetchSlip($monthSelect.val());
})();
</script>
</body></html>
