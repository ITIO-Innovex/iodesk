<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'User Documentation'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" id="fontawesome-regular" href="<?php echo base_url('');?>assets/css/codecanvas.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom"  style="background-color: #000;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
			<?php echo get_dark_company_logo(get_admin_uri() . '/', 'v-logo')?>
                <?php /*?><a class="navbar-brand" href="<?php echo base_url(); ?>">
                    <img src="https://itio.in/assets/img/logo/Logo_dark.png" alt="HRM" class="img-responsive" style="max-width: 150px; margin: 0 auto;">
                </a><?php */?>
                <div class="d-none d-md-flex align-items-center gap-4">
                    <a href="<?php echo base_url(); ?>#features" class="nav-link">Features</a>
                    <a href="<?php echo base_url(); ?>#pricing" class="nav-link">Pricing</a>
                    <a href="<?php echo base_url('admin/authentication'); ?>" class="nav-link">Login</a>
                    <a href="<?php echo base_url('/authentication/get_register'); ?>" class="hover1" style="padding: 0.75rem 1.5rem;">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
              <?php /*?>  <h1>User Documentation</h1>
                <p class="lead">Get started quickly with step-by-step guidance and answers to common questions.</p><?php */?>
				<div class="content-card">
				<div class="section-title text-dark">CRM Modules – User Guide</div>

<div class="accordion" id="crmDocsAccordion">

    <!-- Staff Management -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#staff">
                <i class="fa-solid fa-users me-2"></i> Staff Management
            </button>
        </h2>
        <div id="staff" class="accordion-collapse collapse show">
            <div class="accordion-body">
                <ul>
                    <li>Add new staff members</li>
                    <li>Edit staff details</li>
                    <li>Change password</li>
                    <li>Set roles & permissions</li>
                    <li>Enable / Disable staff accounts</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- HRMS -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#hrms">
                <i class="fa-solid fa-id-badge me-2"></i> HRMS
            </button>
        </h2>
        <div id="hrms" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Attendance management</li>
                    <li>Leave application & approval</li>
                    <li>Add Leave Rules before applying leave</li>
                    <li>Add Shift Types before assigning Shift Manager</li>
                    <li>Configure Interview Rules for interviews</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Projects -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#projects">
                <i class="fa-solid fa-diagram-project me-2"></i> Projects
            </button>
        </h2>
        <div id="projects" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Dashboard summary with charts</li>
                    <li>Add / Edit projects</li>
                    <li>Add / Edit project tasks</li>
                    <li>Project group collaboration</li>
                    <li>Chat within project groups</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Team Document -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#documents">
                <i class="fa-solid fa-file-lines me-2"></i> Team Document
            </button>
        </h2>
        <div id="documents" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Create dynamic forms</li>
                    <li>Add custom fields and values</li>
                    <li>Assign documents to multiple staff</li>
                    <li>Comment and reply on documents</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Direct Email -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#directEmail">
                <i class="fa-solid fa-envelope me-2"></i> Direct Email
            </button>
        </h2>
        <div id="directEmail" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Send emails directly from CRM</li>
                    <li>Works only after SMTP setup</li>
                    <li>AI support for email content (if enabled)</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Webmail -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#webmail">
                <i class="fa-solid fa-inbox me-2"></i> Webmail
            </button>
        </h2>
        <div id="webmail" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Send & receive emails</li>
                    <li>Manage multiple email accounts</li>
                    <li>SMTP configuration required</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- AI Support -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#ai">
                <i class="fa-solid fa-robot me-2"></i> AI Support
            </button>
        </h2>
        <div id="ai" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>ChatGPT-powered AI assistance</li>
                    <li>Works after adding API key</li>
                    <li>
                        API Key Guide:
                        <a href="https://platform.openai.com/api-keys" target="_blank">
                            Get ChatGPT API Key
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Subscriptions -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#subscription">
                <i class="fa-solid fa-credit-card me-2"></i> Subscriptions
            </button>
        </h2>
        <div id="subscription" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>View current plan</li>
                    <li>Invoice history</li>
                    <li>Increase staff limit</li>
                    <li>Upgrade subscription plan</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Leads / Deals -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#leads">
                <i class="fa-solid fa-handshake me-2"></i> Leads & Deals
            </button>
        </h2>
        <div id="leads" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Add and manage leads</li>
                    <li>Configure task status before use</li>
                    <li>Convert leads to deals</li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Sales -->
    <div class="accordion-item">
        <h2 class="accordion-header">
            <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#sales">
                <i class="fa-solid fa-chart-line me-2"></i> Sales
            </button>
        </h2>
        <div id="sales" class="accordion-collapse collapse">
            <div class="accordion-body">
                <ul>
                    <li>Manage invoices</li>
                    <li>Products & pricing</li>
                    <li>Payments & reports</li>
                </ul>
            </div>
        </div>
    </div>

</div>

				</div>
                <?php /*?><div class="content-card">
                    <div class="section-title">How to Use Guide</div>
                    <ol class="mb-4">
                        <li>Sign in using your admin credentials from the Login page.</li>
                        <li>Complete CRM Setup to configure departments, designations, and staff types.</li>
                        <li>Create staff members and assign roles and permissions.</li>
                        <li>Configure email, payroll, and HR settings as needed.</li>
                        <li>Choose or upgrade your subscription plan for your company.</li>
                    </ol>

                    <div class="section-title">FAQ</div>
                    <div class="faq-item">
                        <strong>How do I add a new staff member?</strong>
                        <div>Go to Staff &gt; Add New Staff Member and fill in required details.</div>
                    </div>
                    <div class="faq-item">
                        <strong>How do I upgrade my subscription?</strong>
                        <div>Open My Subscriptions and click Upgrade Plan to choose a new plan.</div>
                    </div>
                    <div class="faq-item">
                        <strong>Where can I update SMTP settings?</strong>
                        <div>Go to Customize &gt; SMTP Settings and save the details.</div>
                    </div>
                </div><?php */?>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <?php echo get_dark_company_logo(get_admin_uri() . '/', 'v-logo')?>
                    </div>
                    <p class="footer-desc">Empowering businesses with modern HR solutions. Simplify your people management and focus on what matters most.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Product</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo base_url(); ?>#features">Features</a></li>
                        <li><a href="<?php echo base_url(); ?>#pricing">Pricing</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Company</h5>
                    <ul class="footer-links">
                        <li><a href="https://itio.in/about-us" target="_blank">About Us</a></li>
                        <li><a href="https://itio.in/careers" target="_blank">Careers</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Resources</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo base_url('help_center'); ?>">Help Center</a></li>
                        <li><a href="<?php echo base_url('user_documentation'); ?>">Documentation</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Legal</h5>
                    <ul class="footer-links">
                        <li><a href="<?php echo $privacy_policy_url;?>" target="_blank">Privacy Policy</a></li>
                        <li><a href="<?php echo $terms_of_use_url;?>"  target="_blank">Terms of use</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> CRM. All rights reserved.</p>
<div class="social-links">
<a href="https://x.com/ItioLtd" target="_blank" title="twitter"><i class="fab fa-twitter"></i></a>
<a href="https://www.linkedin.com/company/itio-innovex/" target="_blank" title="linkedin"><i class="fab fa-linkedin-in"></i></a>
<a href="https://www.facebook.com/ITIOInnovex/" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
<a href="https://www.instagram.com/itioinnovex/" target="_blank" title="instagram"><i class="fab fa-instagram"></i></a>
</div>
            </div>
        </div>
    </footer>

    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const navbar = document.querySelector('.navbar-custom');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
