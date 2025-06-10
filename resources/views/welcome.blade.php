<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>SGOD Koronadal - Request Mananagement and Tracking System</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link rel="icon" href="{{ asset('img/logo.jpg') }}" type="image/x-icon" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- AOS (Animate on Scroll) -->
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- GLightbox -->
    <link href="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/css/glightbox.min.css" rel="stylesheet">

    <!-- Swiper -->
    <link href="https://cdn.jsdelivr.net/npm/swiper@11.0.5/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    {{-- <link href="assets/css/main.css" rel="stylesheet"> --}}

    @vite(['resources/assets/css/main.css', 'resources/assets/css/table.css'])

</head>

<body class="index-page">

    <header id="header" class="header d-flex align-items-center fixed-top">
        <div class="container-fluid container-xl position-relative d-flex align-items-center">

            <a href="/" class="logo d-flex align-items-center me-auto">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <img src="{{ asset('img/logo.png') }}" alt="" style="width: 45px; height: 45px">
                <h2 class="sitename d-none d-md-block">SGOD - Request Management and Tracking System</h2>
                <h2 class="sitename d-block d-md-none">SGOD - RMTS</h2>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#track_request">Track Request</a></li>

                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            @if (Route::has('login'))

                @if (session()->has('school_id_expires_at') && now()->lessThan(session('school_id_expires_at')))
                    <a href="{{ url('/') }}" class="cta-btn" href="index.html#about">Get Started</a>
                @else
                    @php
                        session()->forget('school_id');
                        session()->forget('school_id_expires_at');
                    @endphp
                @endif
                @auth
                    <a href="{{ url('/dashboard') }}" class="cta-btn" href="index.html#about">Dashboard</a>
                @else
                    <a href="{{ url('/login') }}" class="cta-btn" href="index.html#about">Log in</a>
                @endauth
            @endif

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section dark-background">

            <img src="{{ asset('img/deped-division-2.jpg') }}" alt="" data-aos="fade-in">

            <div class="container d-flex flex-column align-items-center">
                <h2 data-aos="fade-up" data-aos-delay="100" style="color: white; text-align: center;">Efficient Requests, Streamlined Processing</h2>
                <p class="text-center" data-aos="fade-up" data-aos-delay="200">
                    Track and manage requests seamlessly from school to division office, from creation to fund release.
                </p>
                
                <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#track_request" class="btn-get-started">Track My Request</a>
                    {{-- <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8"
                        class="glightbox btn-watch-video d-flex align-items-center"><i
                            class="bi bi-play-circle"></i><span>Watch
                            Video</span></a> --}}
                </div>
            </div>

        </section><!-- /Hero Section -->

        <section id="about" class="about section">

            <div class="container">

                <div class="row gy-4">
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                        <h3>SGOD - Request Management and Tracking System</h3>
                        <img src="{{ asset('img/koronadal-division.jpg') }}" class="img-fluid rounded-4 mb-4" alt="Default Image">
                        <p>The Request Management and Tracking System is a centralized, web-based platform designed to streamline the process of submitting, managing, and monitoring various types of requests within an organization. This system was developed to enhance transparency, accountability, and efficiency by digitizing the entire request lifecycle—from submission to approval and tracking.</p>
                        <p>Built to support both administrative and financial operations, the system allows users to easily submit requests such as fund disbursements, purchase orders, modifications, and payment processing. It ensures that all requests are properly logged, tracked, and transmitted through the appropriate channels.</p>
                    </div>
                    <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
                        <div class="content ps-0 ps-lg-5">
                            
                            <p>
                                Tailored to the needs of the Department of Education, this system helps reduce paperwork, improves coordination between schools and division offices, and ensures timely processing of all requests.
                            </p>

                            <p class="fst-italic">
                                Key features include:
                            </p>
                            <ul>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">User-Friendly Dashboard for quick access to modules and reports.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Real-Time Status Tracking with clearly labeled progress indicators.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Role-Based Access Control to manage user permissions securely.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Search and Filter Functions to quickly locate specific requests or records.</span></li>
                                <li><i class="bi bi-check-circle-fill"></i> <span style="font-size: 14px;">Detailed Transmission Logs that document every step and action taken on a request.</span></li>
                            </ul>

                            <div class="mt-4">
                                <img src="{{ asset('img/deped-division.jpg') }}" class="img-fluid rounded-4 mb-4" alt="Default Image">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /About Section -->

        <!-- About Section -->
        <section id="stats" class="stats section light-background">

            <div class="container-lg" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4">

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-clipboard-data color-blue flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $totalRequests }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Total Request</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-hourglass-split color-orange flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $totalPendingRequests }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Pending Request</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-send-check color-green flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $totalTransmittedRequests }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Transmitted Request</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->

                    <div class="col-lg-3 col-md-6">
                        <div class="stats-item d-flex align-items-center w-100 h-100" style="outline: 1px solid #ccc; padding: 10px; border-radius: 5px;">
                            <i class="bi bi-arrow-return-left color-red flex-shrink-0"></i>
                            <div>
                                <span data-purecounter-start="0" data-purecounter-end="{{ $totalReturnedRequests }}"
                                    data-purecounter-duration="1" class="purecounter"></span>
                                <p>Returned Request</p>
                            </div>
                        </div>
                    </div><!-- End Stats Item -->



                </div>

            </div>

        </section><!-- /Stats Section -->



        <!-- Services Section -->
        <section id="track_request" class="services section ">

            <!-- Section Title -->
            <div class="container-lg section-title pb-5" data-aos="fade-up">
                <h2>Track-a-Request </h2>
                <p>Request Tracking and Monitoring<br></p>
            </div>

            <div class="mb-1 ps-4 container-lg d-flex justify-content-start">
                <input type="text" class="input w-100 me-1 ps-3" placeholder="Search by school / dts / fund source..."
                    id="searchInput" style="max-width: 420px; font-size: 12px;">
            </div>

            <div class="container-lg" data-aos="fade-up" data-aos-delay="100">

                <div class="gy-1">

                    <section class="ftco-section pt-0">
                        <div class="container-lg ">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-wrap">
                                        <table class="table myaccordion table-hover" id="accordion">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">#</th>
                                                    <th style="width: 20%;">School Name</th>
                                                    <th style="width: 10%;">DTS no.</th>
                                                    <th style="width: 12%;">Amount</th>
                                                    <th style="width: 20%;">Nature of Request</th>
                                                    <th style="width: 17%;">Fund Source</th>
                                                    <th style="width: 10%;">Status</th>
                                                    <th style="width: 5%;">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Dynamic rows will be appended here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div id="paginationContainer" class="d-flex justify-content-center">
                        <!-- Pagination will be dynamically appended here -->
                    </div>

                </div>
        </section>

        @livewire('contents.request-tracking')



        <!-- Stats Section -->



    </main>

    <footer id="footer" class="footer dark-background">

        {{-- <div class="container footer-top">
            <div class="row gy-4">
                <div class="col-lg-4 col-md-6 footer-about">
                    <a href="index.html" class="logo d-flex align-items-center">
                        <span class="sitename">Dewi</span>
                    </a>
                    <div class="footer-contact pt-3">
                        <p>A108 Adam Street</p>
                        <p>New York, NY 535022</p>
                        <p class="mt-3"><strong>Phone:</strong> <span>+1 5589 55488 55</span></p>
                        <p><strong>Email:</strong> <span>info@example.com</span></p>
                    </div>
                    <div class="social-links d-flex mt-4">
                        <a href=""><i class="bi bi-twitter-x"></i></a>
                        <a href=""><i class="bi bi-facebook"></i></a>
                        <a href=""><i class="bi bi-instagram"></i></a>
                        <a href=""><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Useful Links</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Home</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">About us</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Services</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Terms of service</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Privacy policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 col-md-3 footer-links">
                    <h4>Our Services</h4>
                    <ul>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Design</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Web Development</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Product Management</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Marketing</a></li>
                        <li><i class="bi bi-chevron-right"></i> <a href="#">Graphic Design</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 col-md-12 footer-newsletter">
                    <h4>Our Newsletter</h4>
                    <p>Subscribe to our newsletter and receive the latest news about our products and services!</p>
                    <form action="forms/newsletter.php" method="post" class="php-email-form">
                        <div class="newsletter-form"><input type="email" name="email"><input type="submit"
                                value="Subscribe">
                        </div>
                        <div class="loading">Loading</div>
                        <div class="error-message"></div>
                        <div class="sent-message">Your subscription request has been sent. Thank you!</div>
                    </form>
                </div>

            </div>
        </div> --}}

        <div class="container copyright text-center mt-4">
            <p>© <span>Copyright 2025</span> <strong class="px-1 sitename"></strong>SGOD - Request Management and Tracking System <span>All
                    Rights Reserved</span>
            </p>
            <div class="credits">
                Designed & Develop by <a href="https://www.facebook.com/brian.gulac/">Brayszz</a>
            </div>
        </div>


    </footer>



    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <!-- Bootstrap Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>

    <!-- PHP Email Form Validation (No official CDN, keep as is or self-host) -->
    <script src="assets/vendor/php-email-form/validate.js"></script>

    <!-- AOS (Animate on Scroll) -->
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <!-- GLightbox -->
    <script src="https://cdn.jsdelivr.net/npm/glightbox@3.3.0/dist/js/glightbox.min.js"></script>

    <!-- PureCounter -->
    <script src="https://cdn.jsdelivr.net/npm/@srexi/purecounterjs@1.5.0/dist/purecounter_vanilla.js"></script>

    <!-- Swiper -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11.0.5/swiper-bundle.min.js"></script>

    <!-- imagesLoaded -->
    <script src="https://unpkg.com/imagesloaded@5/imagesloaded.pkgd.min.js"></script>

    <!-- Isotope Layout -->
    <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    <script src="https://unpkg.com/micromodal/dist/micromodal.min.js"></script>

    @livewireScripts


    <!-- Main JS File -->
    {{-- <script src="assets/js/main.js"></script> --}}

    @vite(['resources/assets/js/main.js', 'resources/assets/js/table.js'])
    @stack('scripts')

</body>


</html>
