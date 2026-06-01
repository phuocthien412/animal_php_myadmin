<?php
require_once __DIR__ . '/../../config/env.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css" asp-append-version="true"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">
    <style>
        .home-intro-row {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            width: min(1100px, 100%);
            margin: 0 auto;
        }

        .home-logo {
            width: min(380px, 72vw);
            height: auto;
            max-width: 100%;
        }

        .home-hero {
            margin-top: 0 !important;
            padding-top: 1.5rem !important;
            padding-bottom: 2rem !important;
        }

        .home-intro-copy,
        .home-intro-visual {
            flex: 1 1 0;
            min-width: 0;
        }

        .home-intro-copy p,
        .home-intro-copy img {
            max-width: 100%;
        }

        .intro-mobile-text {
            font-size: clamp(18px, 4.5vw, 30px) !important;
            line-height: 1.5;
        }

        @media (max-width: 767.98px) {
            .home-hero {
                margin-top: 0 !important;
                padding-top: 2.5rem !important;
                padding-bottom: 1.5rem !important;
            }

            .home-logo {
                width: min(240px, 72vw);
            }

            .home-intro-row {
                flex-direction: column;
                text-align: center;
            }

            .home-intro-row img {
                margin-left: 0 !important;
            }

            .home-intro-copy {
                width: 100%;
            }

            .home-intro-visual {
                width: 100%;
            }

            .home-intro-row .intro-mobile-text {
                font-size: clamp(18px, 5vw, 24px) !important;
            }

            .support-card {
                padding: 1.25rem !important;
            }

            .support-card .btn {
                width: 100%;
            }
        }


        /* Styles for the static image button */
        .static-button {
            position: fixed; /* Fixes the button in the viewport */
            bottom: 20px; /* Distance from the bottom */
            right: 20px; /* Change left to right for bottom right positioning */
            cursor: pointer; /* Pointer cursor on hover */
            z-index: 1000; /* Ensure it appears above other elements */
            text-align: center; /* Center-align the text below the image */
        }

        .static-button img {
            width: 60px; /* Set the desired width for the image */
            height: auto; /* Maintain aspect ratio */
        }

        .click-me {
            color: white; /* Text color */
            font-size: 14px; /* Font size for "Click Me" */
            margin-top: 5px; /* Space between image and text */
        }
    </style>
</head>

<body>
<?php
include '../header.php';
?>
<section layout:fragment="content" style="padding: 0;">

    <nav class="sticky-top secondary-nav">
        <ul>
            <li><a href="#" class="home-marker active">Home</a></li>
            <li><a href="#about" class="about-marker">About</a></li>
            <li><a href="#explore" class="explore-marker">Explore</a></li>
            <li><a href="#support" class="support-marker">Support</a></li>
        </ul>
    </nav>

    <section id="home" class="home home-hero">
        <img src="<?= $base ?>/images/Home/logo.png" class="logo1 home-logo" data-aos="fade-up" data-aos-duration="1500"/>
        <h1 class="texthome" data-aos="fade-up" data-aos-delay="500" data-aos-duration="1000"> <?= __('welcome_title') ?> </h1>
    </section>

    <section id="about" class="about">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-md-6 text-center text-md-start mb-4 mb-md-0" data-aos="fade-up" data-aos-duration="1000">
                    <img src="<?= $base ?>/images/About/logo.png" alt="Image 1" class="img-fluid" style="max-height: 300px;">
                </div>
                <div class="col-md-6 test1" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <h1 class="textabout lh-base fs-5">
                        <?= __('about_text_1') ?>
                    </h1>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6 test2 mb-4 mb-md-0" data-aos="fade-up" data-aos-duration="1000">
                    <h1 class="textabout lh-base fs-5">
                        <?= __('about_text_2') ?>
                    </h1>
                </div>
                <div class="col-md-6 text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube-nocookie.com/embed/5kozt0uDa4c" title="YouTube video player" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="explore" class="explore py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0 text-center text-lg-start" data-aos="fade-up" data-aos-duration="1000">
                <h1 class="textexplore fw-bold"><?= __('explore_title') ?></h1>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/4" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/bosat.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/bosat.png');">
                            <h1 class="textexplore"><?= __('reptiles') ?></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/5" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/ca.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/ca.png');">
                            <h1 class="textexplore"><?= __('fish') ?></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/3" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/chim.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/chim.png');">
                            <h1 class="textexplore"><?= __('birds') ?></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/2" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/dongvatcovu.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/dongvatcovu.png');">
                            <h1 class="textexplore"><?= __('mammals') ?></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/5" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/khongxuongsong.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/khongxuongsong.png');">
                            <h1 class="textexplore"><?= __('invertebrates') ?></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/6" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/luongcu.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/luongcu.png');">
                            <h1 class="textexplore"><?= __('amphibians') ?></h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block text-center" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300">
                <img src="<?= $base ?>/images/About/logo.png" alt="Image 1" class="img-fluid" style="max-height: 200px;">
            </div>
            </div>
        </div>
    </section>

    <section id="support" class="support">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="support-card p-4 p-md-5 rounded-4 shadow-lg" data-aos="fade-up" data-aos-duration="1200" style="background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                        <i class='bx bx-heart text-danger mb-3' style="font-size: 5rem;"></i>
                        <h2 class="text-white fw-bold mb-4" style="font-family: 'Be Vietnam Pro', sans-serif;"><?= __('support_title') ?></h2>
                        <p class="text-white-50 fs-5 mb-4" style="line-height: 1.8;">
                            <?= __('support_text_1') ?>
                        </p>
                        <p class="text-white-50 fs-5 mb-5" style="line-height: 1.8;">
                            <?= __('support_text_2') ?>
                        </p>
                        <a href="<?= $base ?>/Posts" class="btn btn-primary btn-lg rounded-pill fw-bold px-5 py-3 shadow-sm text-uppercase" data-aos="fade-up" data-aos-delay="300">
                            <i class='bx bx-group me-2'></i> Community!
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: false,
            offset: 50
        });
    </script>
    <script type="text/javascript">

        (function () {

            var navLinks = $('nav ul li a'),
                navH = $('nav').height(),
                section = $('section'),
                documentEl = $(document);

            documentEl.on('scroll', function () {
                var currentScrollPos = documentEl.scrollTop();

                section.each(function () {
                    var self = $(this);
                    if (self.offset().top < (currentScrollPos + navH) && (currentScrollPos + navH) < (self.offset().top + self.outerHeight())) {
                        var targetClass = '.' + self.attr('class') + '-marker';
                        navLinks.removeClass('active');
                        $(targetClass).addClass('active');
                    }
                });
            });
        })();
    </script>

    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <div class="static-button" id="startIntro" style="margin-right: -100px">
        <img src="<?= $base ?>/images/idle.gif" alt="Start Intro"
             style="max-width: 100%; max-height: 250px; height: auto; width: auto;">
        <div class="click-me"
             style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;">
            <?= __('need_help') ?>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        // Your existing JavaScript code

        // Trigger Intro.js when the image is clicked
        document.getElementById('startIntro').onclick = function () {
            var intro = introJs();
            intro.setOptions({
                steps: [
                    {
                        element: document.querySelector('#someElement0'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left; width: 800px; max-width: 90vw;">
                            <div class="home-intro-copy" style="padding: 10px; height: auto;" >
                                <p class="intro-mobile-text" style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;" >
                                    <?= __('intro_1') ?>
                                </p>
                            </div>
                            <div class="home-intro-visual">
                            <img src="<?= $base ?>/images/trailer1.gif" alt="Description of Image" style="max-width: 100%; width: min(500px, 100%); height: auto; object-fit: cover; margin-left: 0;" >
                            </div>
                        </div>
                    `
                    },
                    {
                        element: document.querySelector('#someElement2'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left; width: 800px; max-width: 90vw;">
                            <div class="home-intro-copy" style="padding: 10px; height: auto;" >
                                <p class="intro-mobile-text" style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;" >
                                   <?= __('intro_2') ?>
                                </p>
                            </div>
                            <div class="home-intro-visual">
                            <img src="<?= $base ?>/images/trailer2.png" alt="Description of Image" style="max-width: 100%; width: min(500px, 100%); height: auto; object-fit: cover; margin-left: 0;" >
                            </div>
                        </div>
                    `
                    },
                    {
                        element: document.querySelector('.logo1'),
                        intro: `
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('intro_3') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the logo
                    },
                    {
                        element: document.querySelector('.test1'),
                        intro: `
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('intro_4') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.test2'),
                        intro: `
                                                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                                                     <?= __('intro_4') ?>
                                                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('intro_5') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('intro_6') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.support-card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                     <?= __('intro_7') ?>
                                </p>
                `,
                        position: 'top' // Position tooltip directly above the support card since it is at the bottom of the page
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('intro_8') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.front'),
                        intro: `
                `,
                        position: 'bottom'
                    }
                ],
                tooltipPosition: 'bottom',
                positionPrecedence: ['bottom', 'top', 'left', 'right'],
                // Order of positioning
            }).onchange(function (targetElement) {
                // Check if the current step is the last step
                if (targetElement === document.querySelector('.front')) {
                    localStorage.setItem('introCompleted', 'true');
                    window.location.href = 'http://localhost<?= $base ?>/classanimal/detail/1';
                }
            });
            
            intro.onbeforechange(function() {
                document.body.classList.add('introjs-active');
                setTimeout(function() { intro.refresh(); }, 500);
                setTimeout(function() { intro.refresh(); }, 1100);
            });

            intro.onexit(function() {
                document.body.classList.remove('introjs-active');
            });

            intro.oncomplete(function() {
                document.body.classList.remove('introjs-active');
            });
            
            intro.start();
        };
    </script>

</section>
<?php
include '../footer.php';
?>
</body>
</html>

