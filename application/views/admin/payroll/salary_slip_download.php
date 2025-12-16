<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<style>
  .download-slip-card {
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    background: #fff;
    padding: 24px;
  }
  .month-list-table thead th {
    text-transform: uppercase;
    font-size: 12px;
    color: #6b7280;
    background: #f9fafb;
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
      <div class="col-md-8 col-md-offset-2">
        <div class="download-slip-card">
          <div class="tw-flex tw-flex-col md:tw-flex-row tw-justify-between tw-gap-3 tw-mb-4">
            <div>
              <h3 class="tw-text-lg tw-font-semibold tw-m-0">Download Salary Slips</h3>
              <p class="tw-mt-1 tw-text-sm tw-text-neutral-500">Pick any generated payroll month to download your salary slip PDF.</p>
            </div>
          </div>

          <?php if (empty($months)) { ?>
            <div class="empty-state">
              <i class="fa-regular fa-calendar-xmark fa-2x"></i>
              <p class="tw-mt-3">You don't have any generated salary slips yet.</p>
            </div>
          <?php } else { ?>
            <div class="table-responsive">
              <table class="table table-striped table-bordered month-list-table">
                <thead>
                  <tr>
                    <th>Download for the Month</th>
                    <th class="text-center" style="width:180px;">Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($months as $month) { 
                    $downloadUrl = admin_url('payroll/setting/salary_slip_download') . '?month=' . urlencode($month);
                  ?>
                    <tr>
                      <td><i class="fa-solid fa-file-pdf" style="color: #eb1455;"></i> Salary Slips for <?php echo html_escape(date('F Y', strtotime($month . '-01'))); ?></td>
                      <td class="text-center">
                        <a href="<?php echo $downloadUrl; ?>" class="btn btn-sm btn-primary" title="Download Salary Slip">
                          <i class="fa-solid fa-file-arrow-down" style="color: #FFD43B;"></i> Download
                        </a>
                      </td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php init_tail(); ?>
</body></html>
