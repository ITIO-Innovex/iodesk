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
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #0ea5e9;
            --accent: #f43f5e;
            --dark: #0f172a;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--gray-800);
            line-height: 1.6;
            overflow-x: hidden;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .navbar-custom.scrolled {
            padding: 0.75rem 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary) !important;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link {
            color: var(--gray-600) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            position: relative;
            padding-top: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(1deg); }
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: #fff;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
        }

        .hero .lead {
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            max-width: 900px;
            margin: 0 auto;
            color: var(--gray-800);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            text-align: left;
        }

        .section-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .faq-item {
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .footer {
            background: var(--dark);
            padding: 60px 0 30px;
        }

        .footer-brand {
            font-size: 1.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .footer-desc {
            color: var(--gray-400);
            max-width: 300px;
            line-height: 1.7;
        }

        .footer-title {
            color: #fff;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--gray-400);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #fff;
        }

        .footer-bottom {
            border-top: 1px solid var(--gray-800);
            margin-top: 3rem;
            padding-top: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-bottom p {
            color: var(--gray-500);
            margin: 0;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--gray-800);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--primary);
            color: #fff;
        }
    </style>
</head>
<body>
    <nav class="navbar-custom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a class="navbar-brand" href="<?php echo base_url(); ?>">
                    <img src="https://itio.in/assets/img/logo/Logo_dark.png" alt="HRM" class="img-responsive" style="max-width: 150px; margin: 0 auto;">
                </a>
                <div class="d-none d-md-flex align-items-center gap-4">
                    <a href="<?php echo base_url(); ?>#features" class="nav-link">Features</a>
                    <a href="<?php echo base_url(); ?>#pricing" class="nav-link">Pricing</a>
                    <a href="<?php echo base_url('login'); ?>" class="nav-link">Login</a>
                    <a href="<?php echo base_url('/authentication/get_register'); ?>" class="btn btn-hero-primary" style="padding: 0.75rem 1.5rem;">Get Started</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>User Documentation</h1>
                <p class="lead">Get started quickly with step-by-step guidance and answers to common questions.</p>
                <div class="content-card">
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
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="footer-brand">
                        <img src="https://itio.in/assets/img/logo/Logo_dark.png" alt="HRM" class="img-responsive" style="max-width: 150px; margin: 0 auto;">
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
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
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
