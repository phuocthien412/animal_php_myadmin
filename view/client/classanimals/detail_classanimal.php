<?php
// filepath: e:\laragon\www\animal_php\view\classanimal\detail_classanimal.php
require_once __DIR__ . '/../../../config/env.php';

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
    <link rel="stylesheet" href="<?= $base ?>/css/client/home.css">
    <link rel="stylesheet" href="<?= $base ?>/css/client/classanimals.css">
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
            localStorage.removeItem('introCompleted');
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