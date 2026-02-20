<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'CRM - Modern HRM Platform'; ?></title>
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

                <?php /*?><a class="navbar-brand" href="<?php echo base_url(); ?>">
                    <img src="https://itio.in/assets/img/logo/Logo_dark.png" alt="HRM" class="img-responsive" style="max-width: 150px; margin: 0 auto;">
                </a><?php */?>
				<?php echo get_dark_company_logo(get_admin_uri() . '/', 'v-logo')?>
                <div class="d-none d-md-flex align-items-center gap-4">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#pricing" class="nav-link">Pricing</a>
                    <a href="<?php echo base_url('admin/authentication'); ?>" class="nav-link">Login</a>
                    <a href="<?php echo base_url('/authentication/get_register'); ?>" class="hover1" style="padding: 0.75rem 1.5rem;">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1>Revolutionize Your HR Management</h1>
                        <p class="lead">Streamline recruitment, attendance, sales, and performance management in one powerful, intuitive platform designed for modern teams.</p>
                        <div class="hero-buttons">
                            <a href="<?php echo base_url('/authentication/get_register'); ?>" class="hover2">
                                <i class="fas fa-rocket me-2"></i>Start Free Trial
                            </a>
                            <a href="<?php echo base_url('admin'); ?>" class="btn btn-hero-outline">
                                <i class="fas fa-play me-2"></i>Go to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-illustration">
                        <div class="floating-card">
                            <div class="card-stat">
                                <div class="stat-icon purple">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>1,247</h4>
                                    <p>Active Employees</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon blue">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>98.5%</h4>
                                    <p>On-Time Attendance</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon green">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>+23%</h4>
                                    <p>Productivity Increase</p>
                                </div>
                            </div>
                            <div class="card-stat">
                                <div class="stat-icon pink">
                                    <i class="fas fa-heart"></i>
                                </div>
                                <div class="stat-info">
                                    <h4>4.9/5</h4>
                                    <p>Employee Satisfaction</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section features-section" id="features">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-badge">
                    <i class="fas fa-star me-2"></i>Premium Features
                </span>
                <h2 class="section-title">Everything You Need to Excel</h2>
                <p class="section-subtitle">Powerful tools designed to simplify HR operations and boost team productivity.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-1">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <h4>Smart Recruitment</h4>
                        <p>Track applicants, manage interviews, and streamline your hiring process with AI-powered candidate scoring.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-2">
                            <i class="fas fa-fingerprint"></i>
                        </div>
                        <h4>Attendance Tracking</h4>
                        <p>Monitor check-ins, manage shifts, and track attendance with biometric integration and geo-fencing.</p>
                    </div>
                </div>
				<div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-4">
                            <i class="fas fa-calendar-check"></i>
                        </div>
                        <h4>Leave Management</h4>
                        <p>Handle leave requests, approvals, and balance tracking with an intuitive self-service portal.</p>
                    </div>
                </div>
                
				
				<div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-1">
                            <i class="fa-solid fa-money-bill-trend-up"></i>
                        </div>
                        <h4>Sales Management</h4>
                        <p>Manage deals, track sales pipelines, monitor performance, and boost conversions with actionable insights.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-2">
                            <i class="fa-solid fa-chart-simple"></i>
                        </div>
                        <h4>Leads Management</h4>
                        <p>Capture, assign, and nurture leads efficiently with automated follow-ups and lead status tracking.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-3">
                            <i class="fa-solid fa-at"></i>
                        </div>
                        <h4>Webmail</h4>
                        <p>Access and manage business emails directly from the system with secure SMTP/IMAP integration.</p>
                    </div>
                </div>
				
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-3">
                            <i class="fa-solid fa-ticket"></i>
                        </div>
                        <h4>Ticket Management</h4>
                        <p>Handle internal and customer support tickets efficiently with prioritization, SLAs, and status tracking.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-5">
                            <i class="fa-solid fa-comment-dots"></i>
                        </div>
                        <h4>AI Support</h4>
                        <p>Get instant AI-powered assistance for queries, analytics, and smart recommendations to improve productivity.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 animate-on-scroll">
                    <div class="feature-card">
                        <div class="feature-icon gradient-6">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4>Document Vault</h4>
                        <p>Securely store, organize, and manage all employee documents with role-based access control.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Companies Trust Us</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Active Users</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime Guarantee</div>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="stat-item animate-on-scroll">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Support Available</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section class="section pricing-section" id="pricing">
        <div class="container">
            <div class="section-header animate-on-scroll">
                <span class="section-badge">
                    <i class="fas fa-tags me-2"></i>Pricing Plans
                </span>
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">Choose the perfect plan that scales with your business needs.</p>
            </div>
            <div class="row g-4 justify-content-center">
                <?php if (!empty($pricing_plans)) { ?>
                    <?php 
                    $index = 0;
                    foreach ($pricing_plans as $plan) {
                        $price = number_format((float) $plan['price'], 0);
                        $currency = $plan['currency'] ?? 'INR';
                        $cycle = ucfirst(str_replace('_', ' ', $plan['billing_cycle'] ?? 'monthly'));
                        $duration = isset($plan['duration']) ? (int) $plan['duration'] : 0;
                        $features = [];
                        if (!empty($plan['features'])) {
                            $features = array_filter(array_map('trim', explode(',', $plan['features'])));
                        }
                        $isFeatured = $index === 1;
                        $index++;
                    ?>
                    <div class="col-md-6 col-lg-4 animate-on-scroll">
                        <div class="pricing-card <?php echo $isFeatured ? 'featured' : ''; ?>">
                            <div class="pricing-header">
                                <h4 class="pricing-name"><?php echo e($plan['plan_name']); ?></h4>
                                <div class="pricing-amount">
                                    <span class="pricing-currency"><?php echo e($currency); ?></span>
                                    <span class="pricing-value"><?php echo e($price); ?></span>
                                </div>
                                <p class="pricing-period">
                                    <?php if ($duration > 0) { ?>
                                        <?php echo e($cycle); ?> (<?php echo e($duration); ?> Days) 
                                    <?php } else { ?>
                                        per <?php echo strtolower($cycle); ?>
                                    <?php } ?>
                                </p>
                            </div>
                            <?php if (!empty($features)) { ?>
                                <ul class="pricing-features">
                                    <?php foreach ($features as $feature) { ?>
                                        <li><i class="fas fa-check-circle"></i> <?php echo e($feature); ?></li>
                                    <?php } ?>
                                </ul>
                            <?php } else { ?>
                                <ul class="pricing-features">
                                    <li><i class="fas fa-check-circle"></i> All core features</li>
                                    <li><i class="fas fa-check-circle"></i> Email support</li>
                                    <li><i class="fas fa-check-circle"></i> Basic analytics</li>
                                </ul>
                            <?php } ?>
                            <a href="<?php echo base_url('/authentication/get_register/'.$plan['id']); ?>" class="btn-pricing <?php echo $isFeatured ? 'btn-pricing-primary' : 'btn-pricing-outline'; ?>">
                                Get Started
                            </a>
                        </div>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <!-- Default plans if none exist -->
                    
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <div class="cta-content animate-on-scroll">
                <h2>Ready to Transform Your HR?</h2>
                <p>Join hundreds of companies already using CRM to streamline their operations and empower their teams.</p>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    <a href="<?php echo base_url('/authentication/get_register'); ?>" class="hover2">
                        <i class="fas fa-rocket me-2"></i>Start Your Free Trial
                    </a>
                    <a href="<?php echo base_url('admin'); ?>" class="btn btn-hero-outline">
                        <i class="fas fa-sign-in-alt me-2"></i>Access Dashboard
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <?php /*?><img src="https://itio.in/assets/img/logo/Logo_light.png" alt="HRM" class="img-responsive" style="max-width: 150px;"><?php */?>
						<?php echo get_dark_company_logo(get_admin_uri() . '/', 'v-logo')?>
                    </div>
                    <p class="footer-desc">Empowering businesses with modern HR solutions. Simplify your people management and focus on what matters most.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h5 class="footer-title">Product</h5>
                    <ul class="footer-links">
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
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
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar-custom');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });
		
    </script>
	<script>(function(w, d) { w.CollectId = "698b113711d2bb8d472316fd"; var h = d.head || d.getElementsByTagName("head")[0]; var s = d.createElement("script"); s.setAttribute("type", "text/javascript"); s.async=true; s.setAttribute("src", "https://collectcdn.com/launcher.js"); h.appendChild(s); })(window, document);</script>
</body>
</html>
