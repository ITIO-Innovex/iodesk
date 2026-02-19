<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo isset($title) ? $title : 'Help Center'; ?></title>
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
            /* Removed by Priyanshu */
            /* border-bottom: 1px solid var(--gray-200); */
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
           /* color: var(--gray-600) !important; */
           /* Added by Priyanshu */
           color: #ffffff !important;
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
          /*  background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); */
          /* Added by Priyanshu */
          background: radial-gradient(circle at center, #064d2a 0%, #012313 50%, #000d06 100%);
            overflow: hidden;
            /* Added by Priyanshu */
            border: 1px solid;
    border-left: none;
    border-right: none;
            border-image: linear-gradient(102.05deg, #836dd6 40.6%, #367522 91.37%) 1;
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
		
		/* Added by Priyanshu - Smooth Professional Version */

.hover1 {
    display: inline-block;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    font-weight: 600;
    color: #ffffff;
    padding: 17px 50px;
    border-radius: 50px;
    text-align: center;
    text-decoration: none;

    border: 1px solid transparent; /* Hidden initially */
    z-index: 1;

    transition: color 0.4s ease, border-color 0.4s ease;
}

/* Background Layer */
.hover1::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(90deg, #1d5e2f, #79d100);
    border-radius: 50px;
    z-index: -1;

    transform: scaleX(1);
    transform-origin: left;
    transition: transform 0.6s cubic-bezier(.4,0,.2,1);
}

/* Hover - Smooth Wipe */
.hover1:hover::before {
    transform: scaleX(0);
}

/* Show Border Only On Hover */
.hover1:hover {
    color: #ffffff;
    border-color: #711fe3;
}


/* Added by Priyanshu - Smooth Professional Version */

/* Added by Priyanshu - Smooth Professional Version */

.hover2 {
    display: inline-block;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    font-weight: 600;
    color: #ffffff;
    padding: 17px 50px;
    border-radius: 40px;
    text-align: center;
    text-decoration: none;

    border: 1px solid #711fe3; 
    background: transparent;
    z-index: 1;

    transition: color 0.4s ease, border-color 0.4s ease;
}

/* Background Layer */
.hover2::before {
    content: "";
    position: absolute;
    inset: 0;
    background: linear-gradient(100.57deg, #3006a4 12.93%, #367522 121.02%);
    border-radius: 40px;
    z-index: -1;

    transform: scaleX(0);
    transform-origin: right;
    transition: transform 0.6s cubic-bezier(.4,0,.2,1);
}

/* Hover - Fill Right to Left */
.hover2:hover::before {
    transform: scaleX(1);
}

/* Hide Border on Hover */
.hover2:hover {
    border-color: transparent;
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

        .support-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 2rem;
            max-width: 560px;
            margin: 0 auto;
            color: var(--gray-800);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .support-card p {
            margin-bottom: 0.75rem;
        }

        .support-card a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
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
		.logoadmin {
height: 50px !important;
}
    </style>
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
                <h1>Help Center</h1>
                <p class="lead">Need help? Reach us using the details below.</p>
                <div class="support-card">
                    <p><strong>Support Email:</strong>
                        <a href="mailto:<?php echo e($support_email); ?>"><?php echo e($support_email); ?></a>
                    </p>
                    <p><strong>Contact Number:</strong>
                        <?php if (!empty($support_phone)) { ?>
                            <a href="tel:<?php echo e($support_phone); ?>"><?php echo e($support_phone); ?></a>
                        <?php } else { ?>
                            Not configured
                        <?php } ?>
                    </p>
                </div>
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
