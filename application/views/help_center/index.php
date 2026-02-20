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
