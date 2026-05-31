<?php
require_once __DIR__ . '/../config/env.php';
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
        .introjs-tooltip {
            background-color: transparent !important; /* Semi-transparent background */
            border: none !important; /* Remove border */
            box-shadow: none !important; /* Remove shadow */
            padding: 5px; /* Minimal padding to keep it close */
            width: max-content;
            height: max-content;
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

    <section id="home" class="home" style="margin-top: -100px;">
        <img src="<?= $base ?>/images/Home/logo.png" class="logo1"/>
        <h1 class="texthome"> ChÃ o má»«ng báº¡n Ä‘áº¿n vá»›i cá»•ng thÃ´ng tin vá» cÃ¡c loÃ i Ä‘á»™ng váº­t cá»§a NEKOPARA! </h1>
    </section>

    <section id="about" class="about">
        <div class="container py-5">
            <div class="row align-items-center mb-5">
                <div class="col-md-6 text-center text-md-start mb-4 mb-md-0">
                    <img src="<?= $base ?>/images/About/logo.png" alt="Image 1" class="img-fluid" style="max-height: 300px;">
                </div>
                <div class="col-md-6 test1">
                    <h1 class="textabout lh-base fs-5">
                        Táº¡i Ä‘Ã¢y, chÃºng tÃ´i cung cáº¥p má»™t nguá»“n tÃ i nguyÃªn phong phÃº vÃ  Ä‘a dáº¡ng vá» cÃ¡c loÃ i Ä‘á»™ng váº­t . Vá»›i Ä‘á»™i
                        ngÅ© chuyÃªn gia Ä‘á»™ng váº­t vÃ  nhÃ  nghiÃªn cá»©u, chÃºng tÃ´i Ä‘Ã£ tá»•ng há»£p thÃ´ng tin chi tiáº¿t vÃ  thÃº vá»‹ vá» cÃ¡c
                        loáº¡i Ä‘á»™ng váº­t tá»« nhá»¯ng con cÃ¡ nhá» bÃ© Ä‘áº¿n cÃ¡c loÃ i thÃº hoang dÃ£ to lá»›n.
                    </h1>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-6 test2 mb-4 mb-md-0">
                    <h1 class="textabout lh-base fs-5">
                        Qua trang NEKOPARA, chÃºng tÃ´i muá»‘n chia sáº» kiáº¿n thá»©c vÃ  thÃ´ng tin Ä‘Ã¡ng tin cáº­y vá»
                        Ä‘á»™ng váº­t, giÃºp má»i ngÆ°á»i hiá»ƒu rÃµ hÆ¡n vá» cuá»™c sá»‘ng vÃ  sá»± Ä‘a dáº¡ng cá»§a chÃºng. ChÃºng tÃ´i hy vá»ng ráº±ng
                        thÃ´ng qua viá»‡c tÄƒng cÆ°á»ng nháº­n thá»©c vÃ  kiáº¿n thá»©c vá» Ä‘á»™ng váº­t, chÃºng ta cÃ³ thá»ƒ thÃºc Ä‘áº©y nhá»¯ng ná»— lá»±c
                        báº£o vá»‡ mÃ´i trÆ°á»ng vÃ  duy trÃ¬ sá»± cÃ¢n báº±ng tá»± nhiÃªn.
                    </h1>
                </div>
                <div class="col-md-6 text-center">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube-nocookie.com/embed/5kozt0uDa4c" title="YouTube video player" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="explore" class="explore py-5">
        <div class="container">
            <div class="row g-4 align-items-center">
            <div class="col-lg-3 col-md-12 mb-4 mb-lg-0 text-center text-lg-start">
                <h1 class="textexplore fw-bold">KhÃ¡m phÃ¡ cÃ¡c nhÃ³m Ä‘á»™ng váº­t cÃ¹ng NEKOPARA</h1>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/1" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/bosat.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/bosat.png');">
                            <h1 class="textexplore">Äá»™ng váº­t bÃ² sÃ¡t</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/2" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/ca.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/ca.png');">
                            <h1 class="textexplore">CÃ¡</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/3" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/chim.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/chim.png');">
                            <h1 class="textexplore">Chim</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/4" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/dongvatcovu.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/dongvatcovu.png');">
                            <h1 class="textexplore">Äá»™ng váº­t cÃ³ vÃº</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/5" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/khongxuongsong.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/khongxuongsong.png');">
                            <h1 class="textexplore">Äá»™ng váº­t khÃ´ng xÆ°Æ¡ng sá»‘ng</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="flip-container">
                    <a href="<?= $base ?>/classanimal/detail/6" class="card">
                        <div class="front" style="background-image: url('<?= $base ?>/images/Explore/luongcu.png');"></div>
                        <div class="back" style="background-image: url('<?= $base ?>/images/Explore/luongcu.png');">
                            <h1 class="textexplore">Äá»™ng váº­t lÆ°á»¡ng cÆ°</h1>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 d-none d-lg-block text-center">
                <img src="<?= $base ?>/images/About/logo.png" alt="Image 1" class="img-fluid" style="max-height: 200px;">
            </div>
            </div>
        </div>
    </section>

    <section id="support" class="support">
        <div class="container py-5">
            <div class="row align-items-center text-center text-md-start">
                <div class="col-md-6 mb-4 mb-md-0 d-flex justify-content-center">
                    <h1 class="textsupport fs-5 lh-base" style="margin: 0; max-width: 500px;">
                        ChÃºng tÃ´i tÃ¬m kiáº¿m sá»± há»— trá»£ vÃ  Ä‘Ã³ng gÃ³p cá»§a
                        cá»™ng
                        Ä‘á»“ng Ä‘á»ƒ giÃºp phÃ¡t triá»ƒn vÃ  má»Ÿ rá»™ng trang web thÃ´ng tin vá» Ä‘á»™ng váº­t. Viá»‡c thu tháº­p vÃ  cáº­p nháº­t
                        ná»™i
                        dung cá»§a cÃ¡c loÃ i váº­t Ä‘Ã²i há»i má»™t lÆ°á»£ng lá»›n thÃ´ng tin khá»•ng lá»“.
                    </h1>
                </div>
                <div class="col-md-6 d-flex flex-column align-items-center">
                    <h1 class="textsupport fs-5 lh-base mb-4" style="max-width: 500px;">
                        Báº¡n cÃ³ thá»ƒ Ä‘Ã³ng gÃ³p báº±ng cÃ¡ch chia sáº» nhá»¯ng hÃ¬nh áº£nh, nhá»¯ng tráº£i nghiá»‡m vá»
                        Ä‘á»™ng
                        váº­t mÃ  báº¡n Ä‘Ã£ tá»«ng gáº·p thÃ´ng qua pháº§n diá»…n Ä‘Ã n trao Ä‘á»•i cá»§a chÃºng tÃ´i.
                    </h1>
                    <a href="<?= $base ?>/Posts" class="button">
                        <span class="content">Community!</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <div class="static-button" id="startIntro" style="margin-right: -100px">
        <img src="<?= $base ?>/images/idle.gif" alt="Start Intro"
             style="max-width: 100%; max-height: 250px; height: auto; width: auto;">
        <div class="click-me"
             style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;">
            Báº¡n cáº§n trá»£ giÃºp ?
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        // Your existing JavaScript code

        // Trigger Intro.js when the image is clicked
        document.getElementById('startIntro').onclick = function () {
            introJs().setOptions({
                steps: [
                    {
                        element: document.querySelector('#someElement0'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left;">
                            <div style="flex: 1; padding: 10px;height: auto;min-width: 500px;margin-left: -200px" >
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    Xin chÃ o mÃ¬nh lÃ  Lily trá»£ lÃ½ cá»§a NEKOPARA.
                                </p>
                            </div>
                            <div style="flex: 1;">
                            <img src="<?= $base ?>/images/trailer1.gif" alt="Description of Image" style="height: 500px; width: 500px; object-fit: cover;margin-left: -180px" >
                            </div>
                        </div>
                    `
                    },
                    {
                        element: document.querySelector('#someElement2'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left;">
                            <div style="flex: 1; padding: 10px;height: auto;min-width: 500px;margin-left: -200px" >
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   HÃ´m nay mÃ¬nh sáº½ dáº«n báº¡n Ä‘i tham quan má»™t vÃ²ng trang web cá»§a tá»¥i mÃ¬nh nhÃ©!
                                </p>
                            </div>
                            <div style="flex: 1;">
                            <img src="<?= $base ?>/images/trailer2.png" alt="Description of Image" style="height: 500px; width: 500px; object-fit: cover;margin-left: -180px" >
                            </div>
                        </div>
                    `
                    },
                    {
                        element: document.querySelector('.logo1'),
                        intro: `
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   Logo cá»§a bá»n mÃ¬nh nÃ¨.
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the logo
                    },
                    {
                        element: document.querySelector('.test1'),
                        intro: `
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   ThÃ´ng tin vá» NEKOPARA.
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.test2'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   ThÃ´ng tin vá» NEKOPARA.
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   ÄÃ¢y lÃ  cÃ¡c nhÃ³m Ä‘á»™ng váº­t.
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   Khi nháº¥n vÃ o sáº½ hiá»‡n ra cÃ¡c loÃ i Ä‘á»™ng váº­t thuá»™c nhÃ³m Ä‘Ã³.
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.button'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    Báº¡n cÃ³ thá»ƒ xem tin tá»©c á»Ÿ Ä‘Ã¢y.
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.card'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   Ta hÃ£y xem thá»­ bÃªn trong nhÃ³m Ä‘á»™ng váº­t thÃ¬ cÃ³ gÃ¬ nhÃ©.
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
            }).start();
        };
    </script>

</section>
<?php
include '../footer.php';
?>
</body>
</html>
