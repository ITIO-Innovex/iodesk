<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'CRM - Modern HRM Platform'; ?></title>
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

        /* Navbar */
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

        .navbar-brand i {
            font-size: 1.8rem;
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

        /* Hero Section */
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
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .hero .lead {
            font-size: 1.25rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            max-width: 540px;
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            animation: fadeInUp 0.8s ease-out 0.4s both;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hero-primary {
            background: #fff;
            color: var(--primary);
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            color: var(--primary-dark);
        }

        .btn-hero-outline {
            background: transparent;
            color: #fff;
            padding: 1rem 2rem;
            font-weight: 600;
            border-radius: 50px;
            border: 2px solid rgba(255, 255, 255, 0.5);
            transition: all 0.3s ease;
        }

        .btn-hero-outline:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: #fff;
            color: #fff;
        }

        /* Hero Illustration */
        .hero-illustration {
            position: relative;
            animation: fadeInUp 0.8s ease-out 0.6s both;
        }

        .floating-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
        }

        .floating-card .card-stat {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-100);
        }

        .floating-card .card-stat:last-child {
            border-bottom: none;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-icon.purple { background: rgba(99, 102, 241, 0.1); color: var(--primary); }
        .stat-icon.blue { background: rgba(14, 165, 233, 0.1); color: var(--secondary); }
        .stat-icon.pink { background: rgba(244, 63, 94, 0.1); color: var(--accent); }
        .stat-icon.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }

        .stat-info h4 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin: 0;
        }

        .stat-info p {
            color: var(--gray-500);
            margin: 0;
            font-size: 0.875rem;
        }

        /* Brands Section */
        .brands-section {
            padding: 4rem 0;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-200);
        }

        .brands-section p {
            color: var(--gray-400);
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 2rem;
        }

        /* Section Styling */
        .section {
            padding: 100px 0;
        }

        .section-header {
            text-align: center;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(244, 63, 94, 0.1));
            color: var(--primary);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--gray-900);
            margin-bottom: 1rem;
        }

        .section-subtitle {
            font-size: 1.125rem;
            color: var(--gray-500);
        }

        /* Features Section */
        .features-section {
            background: #fff;
        }

        .feature-card {
            background: #fff;
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 2rem;
            height: 100%;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            opacity: 0;
            transition: opacity 0.4s ease;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: transparent;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-icon {
            width: 64px;
            height: 64px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            transition: transform 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            transform: scale(1.1);
        }

        .feature-icon.gradient-1 { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; }
        .feature-icon.gradient-2 { background: linear-gradient(135deg, #0ea5e9, #06b6d4); color: #fff; }
        .feature-icon.gradient-3 { background: linear-gradient(135deg, #f43f5e, #fb7185); color: #fff; }
        .feature-icon.gradient-4 { background: linear-gradient(135deg, #22c55e, #4ade80); color: #fff; }
        .feature-icon.gradient-5 { background: linear-gradient(135deg, #f59e0b, #fbbf24); color: #fff; }
        .feature-icon.gradient-6 { background: linear-gradient(135deg, #8b5cf6, #a78bfa); color: #fff; }

        .feature-card h4 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.75rem;
        }

        .feature-card p {
            color: var(--gray-500);
            margin: 0;
            line-height: 1.7;
        }

        /* Stats Section */
        .stats-section {
            background: linear-gradient(135deg, var(--dark), var(--gray-800));
            padding: 80px 0;
        }

        .stat-item {
            text-align: center;
            padding: 2rem;
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea, #f093fb);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--gray-400);
            font-size: 1rem;
            font-weight: 500;
        }

        /* Pricing Section */
        .pricing-section {
            background: var(--gray-50);
        }

        .pricing-card {
            background: #fff;
            border-radius: 24px;
            padding: 2.5rem;
            height: 100%;
            border: 2px solid var(--gray-200);
            transition: all 0.4s ease;
            position: relative;
        }

        .pricing-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
        }

        .pricing-card.featured {
            border-color: var(--primary);
            background: linear-gradient(180deg, rgba(99, 102, 241, 0.03), #fff);
        }

        .pricing-card.featured::before {
            content: 'POPULAR';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            padding: 0.4rem 1.25rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--gray-200);
        }

        .pricing-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 1rem;
        }

        .pricing-amount {
            display: flex;
            align-items: baseline;
            justify-content: center;
            gap: 0.25rem;
        }

        .pricing-currency {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-600);
        }

        .pricing-value {
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1;
        }

        .pricing-period {
            color: var(--gray-400);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 0 0 2rem;
        }

        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 0;
            color: var(--gray-600);
        }

        .pricing-features li i {
            color: var(--primary);
            font-size: 1rem;
        }

        .btn-pricing {
            display: block;
            width: 100%;
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-pricing-outline {
            background: var(--gray-100);
            color: var(--gray-700);
            border: none;
        }

        .btn-pricing-outline:hover {
            background: var(--gray-200);
            color: var(--gray-900);
        }

        .btn-pricing-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: #fff;
            border: none;
        }

        .btn-pricing-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
            color: #fff;
        }

        /* CTA Section */
        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .cta-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }


        .cta-content h2 {
            font-size: 2.5rem;
            font-weight: 800;
            color: #fff;
            margin-bottom: 1rem;
        }

        .cta-content p {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Footer */
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

        .footer-brand i {
            color: var(--primary);
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

        /* Responsive */
        @media (max-width: 992px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .section-title {
                font-size: 2rem;
            }
        }

        @media (max-width: 768px) {
            .hero {
                padding-top: 100px;
                padding-bottom: 60px;
            }

            .hero h1 {
                font-size: 2rem;
            }

            .hero-illustration {
                margin-top: 3rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .btn-hero-primary,
            .btn-hero-outline {
                width: 100%;
                text-align: center;
            }

            .stat-number {
                font-size: 2.5rem;
            }

            .pricing-value {
                font-size: 2.5rem;
            }

            .footer-bottom {
                flex-direction: column;
                text-align: center;
            }
        }

        /* Scroll animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }

        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar-custom">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">

                <a class="navbar-brand" href="<?php echo base_url(); ?>">
                    <img src="https://itio.in/assets/img/logo/Logo_dark.png" alt="HRM" class="img-responsive" style="max-width: 150px; margin: 0 auto;">
                </a>
                <div class="d-none d-md-flex align-items-center gap-4">
                    <a href="#features" class="nav-link">Features</a>
                    <a href="#pricing" class="nav-link">Pricing</a>
                    <a href="<?php echo base_url('admin/authentication'); ?>" class="nav-link">Login</a>
                    <a href="<?php echo base_url('/authentication/get_register'); ?>" class="btn btn-hero-primary" style="padding: 0.75rem 1.5rem;">Get Started</a>
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
                            <a href="<?php echo base_url('/authentication/get_register'); ?>" class="btn btn-hero-primary">
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
                            <a href="<?php echo base_url('/authentication/get_register'); ?>" class="btn-pricing <?php echo $isFeatured ? 'btn-pricing-primary' : 'btn-pricing-outline'; ?>">
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
                    <a href="<?php echo base_url('/authentication/get_register'); ?>" class="btn btn-hero-primary">
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
                        <img src="https://itio.in/assets/img/logo/Logo_light.png" alt="HRM" class="img-responsive" style="max-width: 150px;">
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
