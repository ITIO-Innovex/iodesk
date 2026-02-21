<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $company = isset($company) ? $company : null; ?>
<div class="row">
    <div class="col-md-12">
        <h4 class="bold"><?php echo _l('customize_company_settings'); ?></h4>
        <hr class="hr-panel-separator" />
        
        <!-- Company Name -->
        <div class="form-group">
            <label for="customize_company_name"><?php echo _l('customize_company_name'); ?></label>
            <input type="text" name="customize_company_name" class="form-control" 
                   value="<?php echo isset($company->companyname) ? e($company->companyname) : ''; ?>" 
                   placeholder="<?php echo _l('customize_company_name_placeholder'); ?>" />
        </div>

        <!-- Company Domain Name -->
        <div class="form-group">
            <label for="customize_company_domain"><?php echo _l('customize_company_domain'); ?></label>
            <input type="text" name="customize_company_domain" class="form-control" 
                   value="<?php echo isset($company->website) ? e($company->website) : ''; ?>" 
                   placeholder="<?php echo _l('customize_company_domain_placeholder'); ?>" />
            <small class="text-muted"><?php echo _l('customize_company_domain_help'); ?></small>
        </div>

        <div class="form-group">
            <label for="customize_firstname">Admin First Name</label>
            <input type="text" name="customize_firstname" class="form-control"
                   value="<?php echo isset($company->firstname) ? e($company->firstname) : ''; ?>"
                   placeholder="First Name" />
        </div>

        <div class="form-group">
            <label for="customize_lastname">Last Name</label>
            <input type="text" name="customize_lastname" class="form-control"
                   value="<?php echo isset($company->lastname) ? e($company->lastname) : ''; ?>"
                   placeholder="Last Name" />
        </div>

        <div class="form-group">
            <label for="customize_email">Admin Email</label>
            <input type="email" name="customize_email" class="form-control"
                   value="<?php echo isset($company->email) ? e($company->email) : ''; ?>"
                   placeholder="Email" />
				   <small class="text-muted">This email receives all important emails such as invoices, subscriptions, and related notifications.</small>
        </div>

        <div class="form-group">
            <label for="email_notification">Email Notification</label>
            <input type="email" name="email_notification" class="form-control"
                   value="<?php echo isset($company->email_notification) ? e($company->email_notification) : ''; ?>"
                   placeholder="Email for notifications" />
            <small class="text-muted">This email receives general system notifications.</small>
        </div>

        <div class="form-group">
            <label for="email_hrd">Email HRD</label>
            <input type="email" name="email_hrd" class="form-control"
                   value="<?php echo isset($company->email_hrd) ? e($company->email_hrd) : ''; ?>"
                   placeholder="HR Department Email" />
            <small class="text-muted">This email receives HR related notifications.</small>
        </div>

        <div class="form-group">
            <label for="email_dar">Email DAR</label>
            <input type="email" name="email_dar" class="form-control"
                   value="<?php echo isset($company->email_dar) ? e($company->email_dar) : ''; ?>"
                   placeholder="DAR Email" />
            <small class="text-muted">This email receives Daily Activity Report (DAR) submissions.</small>
        </div>

        <div class="form-group">
            <label for="email_cc">Email CC</label>
            <input type="text" name="email_cc" class="form-control"
                   value="<?php echo isset($company->email_cc) ? e($company->email_cc) : ''; ?>"
                   placeholder="CC Email addresses (comma separated)" />
            <small class="text-muted">CC email addresses for notifications. Multiple emails can be separated by comma.</small>
        </div>
    </div>

    <div class="col-md-12">
        <h4 class="bold"><?php echo _l('customize_branding'); ?></h4>
        <hr class="hr-panel-separator" />
        
        <!-- Company Logo -->
        <div class="form-group">
            <label for="customize_company_logo"><?php echo _l('customize_company_logo'); ?></label>
            <div class="input-group">
                <input type="file" name="customize_company_logo" class="form-control" accept="image/*" />
                <span class="input-group-addon">
                    <i class="fa fa-image"></i>
                </span>
            </div>
            <?php if (!empty($company->company_logo)) { ?>
                <div class="mt-2">
                    <img src="<?php echo base_url('uploads/company/' . $company->company_logo); ?>" 
                         alt="Current Logo" style="max-height: 50px; max-width: 200px;" class="img-thumbnail" />
                    <br>
                    <small class="text-muted"><?php echo _l('customize_current_logo'); ?></small>
                </div>
            <?php } ?>
            <small class="text-muted"><?php echo _l('customize_company_logo_help'); ?></small>
        </div>

        <!-- Company Logo Dark -->
        <div class="form-group">
            <label for="customize_company_logo_dark">Company Logo Dark</label>
            <div class="input-group">
                <input type="file" name="customize_company_logo_dark" class="form-control" accept="image/*" />
                <span class="input-group-addon">
                    <i class="fa fa-image"></i>
                </span>
            </div>
            <?php if (!empty($company->company_logo_dark)) { ?>
                <div class="mt-2" style="background: #333; padding: 10px; display: inline-block; border-radius: 4px;">
                    <img src="<?php echo base_url('uploads/company/' . $company->company_logo_dark); ?>" 
                         alt="Current Dark Logo" style="max-height: 50px; max-width: 200px;" />
                </div>
                <br>
                <small class="text-muted">Current dark logo (shown on dark background)</small>
            <?php } ?>
            <small class="text-muted">Upload a logo optimized for dark backgrounds (e.g., white or light-colored logo)</small>
        </div>

        <!-- Favicon -->
        <div class="form-group">
            <label for="customize_favicon"><?php echo _l('customize_favicon'); ?></label>
            <div class="input-group">
                <input type="file" name="customize_favicon" class="form-control" accept="image/x-icon,image/png" />
                <span class="input-group-addon">
                    <i class="fa fa-star"></i>
                </span>
            </div>
            <?php if (!empty($company->favicon)) { ?>
                <div class="mt-2">
                    <img src="<?php echo base_url('uploads/company/' . $company->favicon); ?>" 
                         alt="Current Favicon" style="max-height: 32px; max-width: 32px;" class="img-thumbnail" />
                    <br>
                    <small class="text-muted"><?php echo _l('customize_current_favicon'); ?></small>
                </div>
            <?php } ?>
            <small class="text-muted"><?php echo _l('customize_favicon_help'); ?></small>
        </div>
    </div>
</div>

<!-- SMTP Settings Section -->
<div class="row">
    <div class="col-md-12">
	<h4 class="bold mt-4">SMTP Settings</h4>

        <hr class="hr-panel-separator" />
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="smtp_encryption"><?php echo _l('SMTP Email Encryption'); ?></label>
                    <select name="settings[smtp_encryption]" class="form-control">
                        <option value="" <?php echo (empty($smtp_settings['smtp_encryption'] ?? '')) ? 'selected' : ''; ?>><?php echo _l('SMTP Email Encryption'); ?></option>
                        <option value="ssl" <?php echo (isset($smtp_settings['smtp_encryption']) && $smtp_settings['smtp_encryption'] == 'ssl') ? 'selected' : ''; ?>>SSL</option>
                        <option value="tls" <?php echo (isset($smtp_settings['smtp_encryption']) && $smtp_settings['smtp_encryption'] == 'tls') ? 'selected' : ''; ?>>TLS</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="smtp_host"><?php echo _l('SMTP Host'); ?></label>
                    <input type="text" name="settings[smtp_host]" class="form-control" value="<?php echo isset($smtp_settings['smtp_host']) ? e($smtp_settings['smtp_host']) : ''; ?>" placeholder="smtp.example.com" />
                </div>
                <div class="form-group">
                    <label for="smtp_port"><?php echo _l('SMTP Port'); ?></label>
                    <input type="text" name="settings[smtp_port]" class="form-control" value="<?php echo isset($smtp_settings['smtp_port']) ? e($smtp_settings['smtp_port']) : ''; ?>" placeholder="587" />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="smtp_email"><?php echo _l('SMTP Email'); ?></label>
                    <input type="email" name="settings[smtp_email]" class="form-control" value="<?php echo isset($smtp_settings['smtp_email']) ? e($smtp_settings['smtp_email']) : ''; ?>" placeholder="noreply@example.com" />
                </div>
                <div class="form-group">
                    <label for="smtp_username"><?php echo _l('SMTP Username'); ?></label>
                    <input type="text" name="settings[smtp_username]" class="form-control" value="<?php echo isset($smtp_settings['smtp_username']) ? e($smtp_settings['smtp_username']) : ''; ?>" placeholder="SMTP Username" />
                </div>
                <div class="form-group">
                    <label for="smtp_password"><?php echo _l('SMTP Password'); ?></label>
                    <input type="password" name="settings[smtp_password]" class="form-control" value="<?php echo isset($smtp_settings['smtp_password']) ? e($smtp_settings['smtp_password']) : ''; ?>" placeholder="SMTP Password" />
                </div>
            </div>
            <?php /*?><div class="col-md-4">
                <div class="form-group">
                    <label for="smtp_charset"><?php echo _l('SMTP Charset'); ?></label>
                    <input type="text" name="settings[smtp_charset]" class="form-control" value="<?php echo isset($smtp_settings['smtp_charset']) ? e($smtp_settings['smtp_charset']) : 'utf-8'; ?>" placeholder="utf-8" />
                </div>
                <div class="form-group">
                    <label for="smtp_bcc"><?php echo _l('SMTP bcc all emails to'); ?></label>
                    <input type="email" name="settings[smtp_bcc]" class="form-control" value="<?php echo isset($smtp_settings['smtp_bcc']) ? e($smtp_settings['smtp_bcc']) : ''; ?>" placeholder="bcc@example.com" />
                </div>
            </div><?php */?>
        </div>
    </div>
</div>

<!-- Lead Setting Section -->
<div class="row">
    <div class="col-md-12">
        <h4 class="bold mt-4"><?php echo _l('lead_setting'); ?></h4>
        <hr class="hr-panel-separator" />
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="automatically_assign_to_staff"><?php echo _l('automatically_assign_to_staff'); ?></label>
                    <select name="automatically_assign_to_staff" class="form-control">
                        <option value="0" <?php echo (isset($company->automatically_assign_to_staff) && $company->automatically_assign_to_staff == '0') ? 'selected' : ''; ?>><?php echo _l('no'); ?></option>
                        <option value="1" <?php echo (isset($company->automatically_assign_to_staff) && $company->automatically_assign_to_staff == '1') ? 'selected' : ''; ?>><?php echo _l('yes'); ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="lead_auto_assign_to_staff"><?php echo _l('lead_auto_assign_to_staff'); ?></label>
                    <select name="lead_auto_assign_to_staff" class="form-control">
                        <option value="">-- <?php echo _l('select_staff'); ?> --</option>
                        <?php foreach($staff_list as $staff): ?>
                                <option value="<?php echo $staff['staffid']; ?>" <?php echo (isset($company->lead_auto_assign_to_staff) && $company->lead_auto_assign_to_staff == $staff['staffid']) ? 'selected' : ''; ?>><?php echo $staff['firstname'] . ' ' . $staff['lastname']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
			<div class="col-md-4">
                <div class="form-group">
                    <label for="lead_auto_assign_to_staff"><?php echo 'NDA SIGN Link'; ?></label>
                    <input type="nda_url" name="nda_url" class="form-control" value="<?php echo isset($company->nda_url) ? $company->nda_url : ''; ?>" placeholder="NDA Url" />
                </div>
            </div>
        </div>
    </div>
</div> 