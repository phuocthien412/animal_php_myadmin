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
        <div class="PostList" style="margin-top: 40px; margin-bottom: 30px; display: flex; justify-content: center; width: 100%; position: relative; z-index: 10;">
            <div class="popup">
                <!-- Trigger/Open The Modal -->
                <a id="mbtn" style="cursor: pointer; display: inline-flex; align-items: center; justify-content: center; gap: 10px; background: linear-gradient(135deg, #10b981, #059669); color: white; padding: 14px 40px; border-radius: 999px; font-weight: 700; font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; text-decoration: none; box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4); border: 1px solid rgba(255,255,255,0.1); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);" onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 12px 30px rgba(16, 185, 129, 0.6)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 4px 20px rgba(16, 185, 129, 0.4)';">
                    <i class="fa-solid fa-wand-magic-sparkles text-emerald-100"></i>
                    <span><?= __('btn_check_result') ?></span>
                </a>
                <!-- The Modal -->
                <div id="modalDialog" class="modal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(5, 11, 8, 0.8); backdrop-filter: blur(8px);">
                    <div class="modal-content" style="background: rgba(15, 34, 26, 0.85); margin: 10% auto; padding: 0; border: 1px solid rgba(16, 185, 129, 0.2); width: 90%; max-width: 500px; border-radius: 20px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); overflow: hidden; animation: fadeInTop 0.3s ease-out;">
                        <div class="modal-header" style="background: rgba(16, 185, 129, 0.1); padding: 20px 24px; border-bottom: 1px solid rgba(16, 185, 129, 0.1); display: flex; justify-content: space-between; align-items: center;">
                            <h3 class="modal-title" style="color: #34d399; margin: 0; font-size: 1.25rem; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-microchip"></i> <?= __('search_results') ?>
                            </h3>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="background: transparent; border: none; color: #94a3b8; font-size: 1.5rem; cursor: pointer; padding: 0; transition: color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='#94a3b8'">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="padding: 24px;">
                            <div id="predictionResults" style="color: #e2e8f0;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="list" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 40px; padding: 20px 40px 80px 40px; max-width: 1200px; margin: 0 auto;">
            <?php if (empty($animals)) { ?>
                <div style="text-align: center; width: 100%; margin-top: 40px;">
                    <h1 class="textclassanimalName" style="margin-bottom: 30px;"><?= __('animal_not_found_msg') ?></h1>
                    <a href="<?= $base ?>/Posts" class="button">
                        <span class="content"><?= __('btn_community') ?></span>
                    </a>
                </div>
            <?php } else { ?>
                <?php foreach ($animals as $animal) { ?>
                    <a href="<?= $base ?>/animal/detail/<?php echo htmlspecialchars($animal['id_animal']); ?>"
                       style="text-decoration: none; display: flex; flex-direction: column; align-items: center; transition: transform 0.3s;" onmouseover="this.style.transform='translateY(-10px)'" onmouseout="this.style.transform='translateY(0)'">
                        <div style="width: 250px; height: 250px; overflow: hidden; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.5); border: 2px solid rgba(255,255,255,0.1);">
                            <img src="<?= $base ?>/images/Animal/Avatar/<?php echo htmlspecialchars($animal['avatar']); ?>" alt="Avatar" class="itemAvatar"
                                 style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"/>
                        </div>
                        <h1 class="textclassanimalInfo" style="text-align:center; margin-top: 15px; font-size: 1.25rem; text-shadow: 1px 1px 4px rgba(0,0,0,0.8);"><?php echo htmlspecialchars($animal['name']); ?></h1>
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
                let resultsHtml = '<div style="display: flex; flex-direction: column; gap: 16px;">';
                predictions.forEach((prediction, index) => {
                    const score = parseFloat(prediction.score); 
                    const isTop = index === 0;
                    const barColor = isTop ? 'linear-gradient(90deg, #10b981, #34d399)' : 'rgba(16, 185, 129, 0.4)';
                    const textColor = isTop ? '#34d399' : '#94a3b8';
                    const fw = isTop ? '700' : '500';

                    resultsHtml += `
                        <div style="display: flex; flex-direction: column; gap: 8px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; font-size: 14px; color: ${textColor}; font-weight: ${fw};">
                                <span>${isTop ? '<i class="fa-solid fa-crown text-warning mr-1"></i> ' : ''}${prediction.animal}</span>
                                <span>${score.toFixed(1)}%</span>
                            </div>
                            <div style="width: 100%; height: 8px; background: rgba(0,0,0,0.3); border-radius: 999px; overflow: hidden; border: 1px solid rgba(255,255,255,0.05);">
                                <div style="height: 100%; width: ${score}%; background: ${barColor}; border-radius: 999px; transition: width 1s ease-in-out;"></div>
                            </div>
                        </div>
                    `;
                });
                resultsHtml += '</div>';
                predictionResultsElement.html(resultsHtml);
            } else {
                predictionResultsElement.html('<div style="text-align: center; color: #94a3b8; padding: 20px;"><i class="fa-solid fa-ghost text-4xl mb-3"></i><br><?= __('no_predictions') ?></div>');
            }
        }
    </script>
</section>
<?php
include '../footer.php';
?>
</body>

</html>
