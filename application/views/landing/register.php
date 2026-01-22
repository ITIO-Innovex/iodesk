<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'HRM'; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/css/bootstrap.min.css'); ?>">
    <style>
        body { color: #1f2937; }
        .hero { padding: 90px 0; background: #f8fafc; }
        .hero h1 { font-weight: 700; margin-bottom: 20px; }
        .hero p { font-size: 18px; color: #4b5563; }
        .section { padding: 70px 0; }
        .feature-card { border: 1px solid #e5e7eb; border-radius: 12px; padding: 24px; height: 100%; }
        .price-card { border: 1px solid #e5e7eb; border-radius: 16px; padding: 28px; }
        .price { font-size: 34px; font-weight: 700; }
        .cta { background: #0f172a; color: #fff; padding: 60px 0; }
        .btn-primary { background-color: #2563eb; border-color: #2563eb; }
    </style>
</head>
<body>
    <header class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <h1>All-in-one HRM for growing teams</h1>
                    <p>Manage recruitment, attendance, payroll, and performance in one secure platform.</p>
                    <div class="mt-4">
                        <a href="<?php echo base_url('admin'); ?>" class="btn btn-primary btn-lg">Go to Admin</a>
                        <a href="<?php echo base_url('/authentication/get_register'); ?>" class="btn btn-default btn-lg">Get Start Now</a>
                    </div>
                </div>
                <div class="col-md-5 text-center">
                    <img src="https://itio.in/assets/img/logo/Logo_dark.png<?php //echo base_url('https://itio.in/assets/img/logo/Logo_dark.png'); ?>" alt="HRM" class="img-responsive" style="max-width: 240px; margin: 0 auto;">
                </div>
            </div>
        </div>
    </header>

    <section class="section">
        <div class="container">
            <div class="text-center mb-5">
                <h2>HRM Features</h2>
                <p class="text-muted">Everything you need to run HR operations smoothly.</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <h4>Recruitment</h4>
                        <p>Track applicants, manage interviews, and streamline hiring workflows.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <h4>Attendance</h4>
                        <p>Monitor check-in/out, attendance status, and shift schedules.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <h4>Payroll</h4>
                        <p>Automate salary processing with earning and deduction components.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <h4>Leave Management</h4>
                        <p>Manage leave requests, approvals, and balances.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <h4>Performance</h4>
                        <p>Track goals, KPIs, and performance reviews.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <h4>Documents</h4>
                        <p>Securely store and manage employee documents.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" style="background: #f9fafb;">
        <div class="container">
            <div class="text-center mb-5">
                <h2>Pricing</h2>
                <p class="text-muted">Simple plans that scale with your business.</p>
            </div>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="price-card text-center">
                        <h4>Starter</h4>
                        <div class="price">₹0</div>
                        <p class="text-muted">For small teams</p>
                        <p>Basic HRM features</p>
                        <a href="<?php echo base_url('clients/register'); ?>" class="btn btn-primary">Get Started</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="price-card text-center">
                        <h4>Growth</h4>
                        <div class="price">₹2,499</div>
                        <p class="text-muted">Per month</p>
                        <p>Advanced HRM + Payroll</p>
                        <a href="<?php echo base_url('clients/register'); ?>" class="btn btn-primary">Choose Plan</a>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="price-card text-center">
                        <h4>Enterprise</h4>
                        <div class="price">Custom</div>
                        <p class="text-muted">For large organizations</p>
                        <p>Custom integrations & support</p>
                        <a href="<?php echo base_url('clients/register'); ?>" class="btn btn-primary">Contact Sales</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cta">
        <div class="container text-center">
            <h2>Ready to modernize your HR?</h2>
            <p>Get started in minutes and streamline your operations today.</p>
            <a href="<?php echo base_url('admin'); ?>" class="btn btn-default btn-lg">Launch HRM</a>
        </div>
    </section>

    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/plugins/bootstrap/js/bootstrap.min.js'); ?>"></script>
</body>
</html>
