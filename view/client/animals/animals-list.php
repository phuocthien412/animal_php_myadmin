<?php
// filepath: e:\laragon\www\animal_php\view\animal\animals-list.php
require_once __DIR__ . '/../../../config/env.php';

$animalController = new AnimalController();
$searchQuery = isset($_GET['searchQuery']) ? $_GET['searchQuery'] : '';
$animals = $animalController->getAllAnimals();

function normalizeString($str) {
    $str = mb_strtolower($str, 'UTF-8');
    $str = preg_replace('/[áàảãạâấầẩẫậăắằẳẵặ]/u', 'a', $str);
    $str = preg_replace('/[éèẻẽẹêếềểễệ]/u', 'e', $str);
    $str = preg_replace('/[íìỉĩị]/u', 'i', $str);
    $str = preg_replace('/[óòỏõọôốồổỗộơớờởỡợ]/u', 'o', $str);
    $str = preg_replace('/[úùủũụưứừửữự]/u', 'u', $str);
    $str = preg_replace('/[ýỳỷỹỵ]/u', 'y', $str);
    $str = preg_replace('/đ/u', 'd', $str);
    return $str;
}

if ($searchQuery !== '') {
    if ($searchQuery === 'Unknown') {
        $animals = [];
    } else {
        $normalizedSearchQuery = normalizeString($searchQuery);
        $exactMatches = array_filter($animals, function($animal) use ($searchQuery) {
            return mb_strtolower($animal['name'], 'UTF-8') === mb_strtolower($searchQuery, 'UTF-8');
        });
        if (!empty($exactMatches)) {
            $animals = $exactMatches;
        } else {
            $animals = array_filter($animals, function($animal) use ($normalizedSearchQuery) {
                return mb_stripos(normalizeString($animal['name']), $normalizedSearchQuery) !== false;
            });
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
    <link rel="stylesheet" href="<?= $base ?>/lib/bootstrap/dist/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css?v=<?= time() ?>"/>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>
    <link rel="stylesheet" href="<?= $base ?>/css/client/animals.css">
</head>
<body>
<?php
include '../header.php';
?>
<section layout:fragment="content" style="padding: 0;">
    <section class="ClassAnimal">
        <div class="hero-container">
            <img src="<?= $base ?>/images/ClassAnimal/Background/Background.jpg" alt="Background" class="classbg"/>
            <div class="hero-overlay">
                <h1 class="textclassanimalName"><?= __('animal_title') ?></h1>
                <h1 class="textclassanimalInfo"><?= __('animal_desc') ?></h1>
            </div>
        </div>
        <div class="PostList" style="margin-top: 50px; display: flex; justify-content: center; width: 100%;">
            <div class="popup">
                <!-- Trigger/Open The Modal -->
                <a id="mbtn" class="button">
                    <span class="content"><?= __('btn_check_result') ?></span>
                </a>
                <!-- The Modal -->
                <div id="modalDialog" class="modal">
                    <div class="modal-content animate-top"
                         style="background-image: url('<?= $base ?>/images/Explore/bg.png');object-fit: cover;">
                        <div class="modal-header">
                            <b class="modal-title" style="color:white;"><?= __('search_results') ?></b>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p id="predictionResults" style="text-align:justify;color:white;"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="list" style="margin-top:-100px; display: flex; flex-wrap: wrap;">
            <?php if (empty($animals)) { ?>
                <h1 class="textclassanimalName" style="margin-top:100px;"><?= __('animal_not_found_msg') ?></h1>
                <a href="<?= $base ?>/Posts" class="button" style="margin-top: 50px;">
                    <span class="content"><?= __('btn_community') ?></span>
                </a>
            <?php } else { ?>
                <?php foreach ($animals as $animal) { ?>
                    <a href="<?= $base ?>/view/animal/view_animal.php?id=<?php echo htmlspecialchars($animal['id_animal']); ?>"
                       style="margin-left: 160px;margin-top: 60px; text-decoration: none; border-radius:20px;">
                        <img src="<?= $base ?>/images/Animal/Avatar/<?php echo htmlspecialchars($animal['avatar']); ?>" alt="Avatar" class="itemAvatar"
                             style="border-radius:20px;"/>
                        <h1 class="textclassanimalInfo" style="text-align:center;"><?php echo htmlspecialchars($animal['name']); ?></h1>
                    </a>
                <?php } ?>
            <?php } ?>
        </div>
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
                displayPredictionsInModal();
                modal.show();
            });

            // When the user clicks on (x), close the modal
            span.on('click', function () {
                modal.hide();
            });
        });

        $('body').bind('click', function (e) {
            if ($(e.target).hasClass("modal")) {
                modal.hide();
            }
        });

        function displayPredictionsInModal() {
            const predictions = JSON.parse(localStorage.getItem('animalPredictions'));
            const predictionResultsElement = $('#predictionResults');

            if (predictions && predictions.length > 0) {
                let resultsHtml = '';
                predictions.forEach(prediction => {
                    const score = (prediction.score); // Assuming score is already a percentage
                    resultsHtml += `
                <div style="display: flex; align-items: center; margin-bottom: 10px;">
                     <div class="animal-name" style="margin-right: 10px;">${prediction.animal}:</div>
                    <div class="progress-container" style="flex-grow: 1;">
                        <div class="progress-bar" style="width: ${score}%;"></div>
                        <span class="progress-text" style="color: red">${score}%</span>
                    </div>
                </div>
            `;
                });
                predictionResultsElement.html(resultsHtml);
            } else {
                predictionResultsElement.html('<?= __('no_predictions') ?>');
            }
        }
    </script>
</section>
<?php
include '../footer.php';
?>
</body>

</html>
