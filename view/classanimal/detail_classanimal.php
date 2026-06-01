<?php
// filepath: e:\laragon\www\animal_php\view\classanimal\detail_classanimal.php
require_once __DIR__ . '/../../controller/ClassAnimalController.php';
require_once __DIR__ . '/../../config/env.php';

// Get the class animal ID from the URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$classAnimalController = new ClassAnimalController();
$classanimal = $classAnimalController->getClassAnimalById($id);

// Fetch animals related to this class animal
$animals = $classAnimalController->getAnimalsByClassAnimalId($id);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title th:text="${title} ?: 'ClassAnimal Detail'"> ClassAnimals List </title>
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css">
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
        
        /* Hover Effects for Animal Cards */
        .animal-card-link .itemAvatar {
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease !important;
        }
        .animal-card-link:hover .itemAvatar {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(0,0,0,0.4) !important;
        }
        .animal-card-link h1 {
            transition: all 0.4s ease !important;
        }
        .animal-card-link:hover h1 {
            background: rgba(255, 255, 255, 0.9) !important;
            color: #000 !important;
            text-shadow: none !important;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2) !important;
        }

        .classanimal-toolbar {
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .classanimal-toolbar .btn-group {
            flex-wrap: wrap;
        }

        .classanimal-toolbar .btn {
            white-space: nowrap;
        }

        @media (max-width: 767.98px) {
            .static-button {
                right: 12px;
                bottom: 12px;
            }

            .static-button img {
                width: 48px;
            }

            .click-me {
                font-size: 18px !important;
                margin-top: 2px;
            }

            .classanimal-toolbar {
                justify-content: center !important;
            }

            .classanimal-toolbar .btn-group {
                width: 100%;
                justify-content: center;
            }

            .classanimal-toolbar .btn-group .btn {
                flex: 1 1 auto;
            }

            .animal-card-link {
                max-width: 100% !important;
            }
        }
    </style>
</head>
<body>
<?php
include '../header.php';
?>
<section layout:fragment="content" style="padding: 0;">
    <section class="ClassAnimal">
        <div class="hero-container">
            <img src="<?= $base ?>/images/ClassAnimal/Background/Background.jpg" alt="Background" class="classbg" />
            <div class="hero-overlay">
                <h1 class="textclassanimalName"><?php echo htmlspecialchars($classanimal['name']); ?></h1>
                <h1 class="textclassanimalInfo"><?php echo htmlspecialchars($classanimal['info']); ?></h1>
            </div>
        </div>
        <div class="container mt-5">
            <!-- Layout Toolbar -->
            <div class="d-flex justify-content-end mb-4 classanimal-toolbar">
                <div class="btn-group shadow-sm bg-white rounded-pill p-1" role="group">
                    <button type="button" class="btn btn-light rounded-pill px-3 layout-btn active" data-layout="col-md-4" title="<?= __('layout_3') ?>">
                        <i class="fas fa-th"></i> 3
                    </button>
                    <button type="button" class="btn btn-light rounded-pill px-3 layout-btn mx-1" data-layout="col-md-6" title="<?= __('layout_2') ?>">
                        <i class="fas fa-th-large"></i> 2
                    </button>
                    <button type="button" class="btn btn-light rounded-pill px-3 layout-btn" data-layout="col-md-12" title="<?= __('layout_1') ?>">
                        <i class="fas fa-square"></i> 1
                    </button>
                </div>
            </div>

            <div class="row justify-content-center g-4" id="animal-grid">
                <?php foreach ($animals as $animal) { ?>
                    <div class="grid-item col-12 col-md-4 mb-4 d-flex justify-content-center">
                        <a href="<?= $base ?>/animal/detail/<?php echo htmlspecialchars($animal['id_animal']); ?>" class="w-100 animal-card-link" style="text-decoration: none; display: block; text-align: center; max-width: 400px;">
                            <img src="<?= $base ?>/images/Animal/Avatar/<?php echo htmlspecialchars($animal['avatar']); ?>" alt="Avatar" class="itemAvatar w-100 shadow-sm" style="height: auto; aspect-ratio: 1/1; border-radius:20px;" />
                            <h1 class="mt-3 fw-bold d-inline-block px-4 py-2" style="font-family: 'Be Vietnam Pro', sans-serif; font-size: clamp(18px, 2.5vw, 24px); color: #fff; text-shadow: 1px 2px 5px rgba(0,0,0,0.8); background: rgba(0, 0, 0, 0.4); backdrop-filter: blur(10px); border-radius: 30px; border: 1px solid rgba(255,255,255,0.2);"><?php echo htmlspecialchars($animal['name']); ?></h1>
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const layoutBtns = document.querySelectorAll('.layout-btn');
        const gridItems = document.querySelectorAll('.grid-item');

        layoutBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class from all buttons
                layoutBtns.forEach(b => {
                    b.classList.remove('active');
                    b.classList.replace('btn-primary', 'btn-light');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                this.classList.replace('btn-light', 'btn-primary');
                
                const layoutClass = this.getAttribute('data-layout');
                
                // Update grid items classes
                gridItems.forEach(item => {
                    item.classList.remove('col-md-4', 'col-md-6', 'col-md-12');
                    item.classList.add(layoutClass);
                });
            });
        });

        // Initialize active button styling
        const activeBtn = document.querySelector('.layout-btn.active');
        if (activeBtn) {
            activeBtn.classList.replace('btn-light', 'btn-primary');
        }
    });
</script>


        </div>
    </section>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
    <div class="static-button" id="startIntro">
        <img src="<?= $base ?>/images/idle.gif" alt="Start Intro"
             style="max-width: 100%; max-height: 250px; height: auto; width: auto;">
        <div class="click-me"
             style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black; font-size: 30px;">
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
                        element: document.querySelector('.textclassanimalName'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_class_name') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.textclassanimalInfo'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('tour_class_info') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.itemAvatar'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('tour_class_animals') ?>
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
    <script type="text/javascript">
        if (localStorage.getItem('introCompleted') === 'true') {
            introJs().setOptions({
                steps: [
                    {
                        element: document.querySelector('.textclassanimalName'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                    <?= __('tour_class_name') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.textclassanimalInfo'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('tour_class_info') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.itemAvatar'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('tour_class_animals') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.itemAvatar'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('tour_class_animals_detail') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('.list'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                   <?= __('tour_class_animals_detail') ?>
                                </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    // Add more steps as needed...
                ],
                tooltipPosition: 'bottom', // Default position for tooltips
                positionPrecedence: ['bottom', 'top', 'left', 'right'],
                // Order of positioning
            }).onchange(function (targetElement) {
                // Check if the current step is the last step
                if (targetElement === document.querySelector('.list')) {
                    localStorage.removeItem('introCompleted');
                    localStorage.setItem('introAnimal', 'true');
                    window.location.href = '<?= $base ?>/animal/detail/1';
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