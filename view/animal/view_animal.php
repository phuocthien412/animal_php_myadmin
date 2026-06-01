<?php
// filepath: e:\laragon\www\animal_php\view\animal\view_animal.php
require_once __DIR__ . '/../../config/env.php';

if (!isset($_GET['id'])) {
    echo "Invalid animal ID.";
    exit();
}

$animalController = new AnimalController();
$animal = $animalController->getAnimalById($_GET['id']);
$animalImages = $animalController->getAnimalImagesById($_GET['id']);

if (!$animal) {
    echo "Animal not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">
    <style>

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
    <section class="AnimalDetail" style="padding-top: 80px !important; padding-bottom: 80px !important;">
        <h1 class="textAnimalName" style="margin-top: 0; margin-bottom: 50px;">
        <?php echo htmlspecialchars($animal['name']); ?>
        </h1>
        <div class="row">
            <div class="col-md-6">
                <div class="listimage">
                    <figure>
                        <img src="<?= $base ?>/images/Animal/Avatar/<?php echo htmlspecialchars($animal['avatar']); ?>" class="itemListimg" style="">
                    </figure>

                    <div class="slide">
    <?php foreach ($animalImages as $image) { ?>
        <img src="<?= $base ?>/images/Animal/ListImage/<?php echo htmlspecialchars($image['animalimage']); ?>" alt=""/>
    <?php } ?>
</div>
                </div>
            </div>
            <div class="col-md-6 gioithieu">
                <h1 class="texttitle" style="text-align:left;">
                    <?= __('animal_intro') ?>
                </h1>
                <p class="textdata">
                <?php echo htmlspecialchars($animal['gioi_thieu_text']); ?>
                </p>
            </div>
        </div>
        <div class="row" style="margin-top:80px">
            <h1 class="texttitle" style="text-align:left;margin-left:0;margin-top:50px;">
                <?= __('animal_class') ?>
            </h1>
            </h1>
            <h1 class="texttitle" style="text-align:left;margin-left:150px;margin-top:10px;">
            <?php
                // Assuming you have a method to get class animal info by ID
                $classAnimalInfo = $animalController->getClassAnimalInfoById($animal['classanimals_id']);
                echo htmlspecialchars($classAnimalInfo['name']);
                ?>

            </h1>
            <p class="textdata" style="width:80%;margin-left:150px;">
            <?php
                // Assuming you have a method to get class animal info by ID
                $classAnimalInfo = $animalController->getClassAnimalInfoById($animal['classanimals_id']);
                echo htmlspecialchars($classAnimalInfo['info']);
                ?>
            </p>
        </div>
        <div class="row" style="margin-top:80px">
            <div class="col-md-6">
                <div class="listimage">
                    <figure>
                        <img src="<?= $base ?>/images/Animal/NoiSinhSong/<?php echo htmlspecialchars($animal['noi_sinh_song_image']); ?>" alt="" class="itemListimg" style="margin-top:50px">
                    </figure>
                </div>
            </div>
            <div class="col-md-6 noisinhsong">
                <h1 class="texttitle" style="text-align:left;margin-top:50px;">
                    <?= __('animal_habitat') ?>
                </h1>
                <p class="textdata" style="text-align:left;margin-top:10px;">
                <?php echo htmlspecialchars($animal['noi_sinh_song_text']); ?>
                </p>
            </div>
        </div>
        <div class="row" style="margin-top:80px">
            <div class="col-md-6 ngoaihinh">
                <h1 class="texttitle" style="text-align:left;margin-left:150px;margin-top:50px;">
                    <?= __('animal_appearance') ?>
                </h1>
                <p class="textdata" style="text-align:left;margin-left:150px;margin-top:10px;" >
                <?php echo htmlspecialchars($animal['ngoai_hinh_text']); ?>
                </p>>
            </div>
            <div class="col-md-6 animal3d">
                <div class="listimage">
                    <figure class="scan3d">
                        <img src="<?= $base ?>/images/Animal/3DQR/<?php echo htmlspecialchars($animal['imgqr3d']); ?>" alt="" class="itemListimg" style="margin-top:50px; max-width: 100%; height: auto;">
                    </figure>
                    <h1 class="texttitle" style="text-align:center;margin-top:20px;">
                        <?= __('animal_ar_scan') ?>
                    </h1>
                    <div class="popup">
                        <!-- Trigger/Open The Modal -->
                        <a id="mbtn" class="button" style="margin-top:-10px">
                            <span class="content"><?= __('animal_guide') ?></span>
                        </a>
                        <!-- The Modal -->
                        <div id="modalDialog" class="modal" >
                            <div class="modal-content animate-top" style="width:30%">
                                <div class="modal-header">
                                    <b class="modal-title"><?= __('animal_3d_guide_title') ?></b>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body" >
                                    <p style="text-align:justify">
                                        <span><?= __('step_1') ?></span> <?= __('guide_step_1') ?>
                                    </p>
                                    <img src="<?= $base ?>/images/QRScan.png" alt="" class="itemListimg" style="max-width: 100%; height: auto;">
                                    <p style="text-align:justify">
                                        <span><?= __('step_2') ?></span> <?= __('guide_step_2') ?>
                                    </p>
                                    <p style="text-align:justify">
                                        <span><?= __('step_3') ?></span> <?= __('guide_step_3') ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            const figureImg = document.querySelector('figure img');
            const Imgs = document.querySelectorAll('.slide img');

            Imgs.forEach(function (img) {
                // console.log(img);
                img.addEventListener('click', function (e) {
                    // console.log(e.target.src);
                    // console.log(figureImg.src);
                    figureImg.src = e.target.src;
                })
            })
        </script>

    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        /*
         * Modal popup
         */
        // Get the modal
        var modal = $('#modalDialog');

        // Get the button that opens the modal
        var btn = $("#mbtn");

        // Get the element that closes the modal
        var span = $(".close");

        $(document).ready(function () {
            // When the user clicks the button, open the modal
            btn.on('click', function () {
                modal.show();
            });

            // When the user clicks on (x), close the modal
            span.on('click', function () {
                modal.hide();
            });
        });

        // When the user clicks anywhere outside of the modal, close it
        $('body').bind('click', function (e) {
            if ($(e.target).hasClass("modal")) {
                modal.hide();
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script type="text/javascript">
        if (localStorage.getItem('introAnimal') === 'true') {
            introJs().setOptions({
                steps: [
                    {
                        element: document.querySelector('.textAnimalName'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_name') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.gioithieu'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_intro') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.listimage'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_images') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.slide'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_more_images') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.noisinhsong'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_habitat') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.ngoaihinh'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_appearance') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.animal3d'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_ar') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.button'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_app_guide') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.scan3d'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_scan') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    // Add more steps as needed...
                ],
                tooltipPosition: 'bottom', // Default position for tooltips
                positionPrecedence: ['bottom', 'top', 'left', 'right']
            }).start();
            localStorage.removeItem('introAnimal');
        };
    </script>
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
            introJs().setOptions({
                steps: [
                    {
                        element: document.querySelector('.textAnimalName'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_name') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.gioithieu'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_intro') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.listimage'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_images') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.slide'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_more_images') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.noisinhsong'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_habitat') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.ngoaihinh'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_appearance') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.animal3d'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_ar') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.button'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_app_guide') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.scan3d'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_animal_scan') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    
                    // Add more steps as needed...
                ],
                tooltipPosition: 'bottom', // Default position for tooltips
                positionPrecedence: ['bottom', 'top', 'left', 'right'] // Order of positioning
            }).start();
        };
    </script>
</section>
</body>
<?php
include '../footer.php';
?>
</html>