<?php
require_once __DIR__ . '/../../../config/env.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
    <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" integrity="sha512-YWzhKL2whUzgiheMoBFwW8CKV4qpHQAEuvilg9FAn5VJUDwKZZxkJNuGM4XkWuk94WCrrwslk8yWNGmY1EduTA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/introjs.min.css">
    <link rel="stylesheet" href="<?= $base ?>/css/client/home.css">
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
            <h1 class="textclassanimalName"><?= __('animal_title') ?></h1>
            <h1 class="textclassanimalInfo"><?= __('animal_desc') ?></h1>
        </div>
    </div>
    <div class="container mt-5 mb-5" style="position: relative; z-index: 2;">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="glass-upload-card text-center p-4 p-md-5" style="background: rgba(15, 34, 26, 0.7); backdrop-filter: blur(16px); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);">
                    <h2 class="fw-bold mb-3 text-white" style="font-family: 'Outfit', sans-serif; font-size: 2.5rem; letter-spacing: -0.025em; text-shadow: 0 4px 12px rgba(0,0,0,0.5);"><i class="fa-solid fa-camera-retro text-emerald-400 mr-2"></i><?= __('find_animal_title') ?></h2>
                    <p class="text-slate-300 mb-5" style="font-size: 1.1rem;"><?= __('find_animal_subtitle') ?></p>
                    
                    <div class="upload-area mb-4" id="upload-area" style="background: rgba(5, 11, 8, 0.4); border: 2px dashed rgba(16, 185, 129, 0.4); border-radius: 16px; padding: 40px 20px; transition: all 0.3s ease; cursor: pointer;" onmouseover="this.style.borderColor='rgba(16, 185, 129, 0.8)'; this.style.background='rgba(16, 185, 129, 0.05)';" onmouseout="this.style.borderColor='rgba(16, 185, 129, 0.4)'; this.style.background='rgba(5, 11, 8, 0.4)';">
                        <i class="fas fa-cloud-upload-alt fa-4x mb-4 text-emerald-500"></i>
                        <h5 class="mb-2 text-white fw-bold"><?= __('find_animal_drag_drop') ?></h5>
                        <p class="text-slate-400 small mb-4"><?= __('find_animal_or') ?></p>
                        <button type="button" class="btn rounded-pill px-5 py-2.5 fw-bold" style="background: rgba(16, 185, 129, 0.1); color: #34d399; border: 1px solid rgba(16, 185, 129, 0.3); transition: all 0.2s;" onmouseover="this.style.background='rgba(16, 185, 129, 0.2)'" onmouseout="this.style.background='rgba(16, 185, 129, 0.1)'">
                            <?= __('find_animal_choose_file') ?>
                        </button>
                        <input type="file" id="image-upload" class="d-none" accept="image/*">
                    </div>

                    <div id="image-container" class="preview-container mb-4" style="display: none; border-radius: 16px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                    </div>
                    <button type="button" onclick="resetUpload()" id="reset-btn" class="btn btn-sm rounded-pill mb-4 shadow-sm fw-bold px-4 py-2" style="display: none; background: rgba(255,255,255,0.1); color: #fff; border: 1px solid rgba(255,255,255,0.2);">
                        <i class="fas fa-undo me-1"></i> <?= __('find_animal_choose_other') ?>
                    </button>
                    
                    <div id="label-container" class="d-none"></div>
                    
                    <button type="button" onclick="predict()" id="search-btn" class="btn btn-lg rounded-pill w-100 fw-bold shadow-lg py-3" style="display: none; background: linear-gradient(135deg, #10b981, #059669); color: white; border: none; letter-spacing: 0.5px; transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="fas fa-magic me-2"></i> <?= __('find_animal_detect_search') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@tensorflow/tfjs@latest/dist/tf.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@teachablemachine/image@latest/dist/teachablemachine-image.min.js"></script>
    <script type="text/javascript">
        const URL = "<?= $base ?>/AnimalPredict/";

        let model, imageContainer, labelContainer, maxPredictions;

        async function start() {
            const modelURL = URL + "model.json";
            const metadataURL = URL + "metadata.json";

            model = await tmImage.load(modelURL, metadataURL);
            maxPredictions = model.getTotalClasses();

            const imageUpload = document.getElementById("image-upload");
            imageUpload.addEventListener("change", onImageUpload);

            const uploadArea = document.getElementById("upload-area");
            uploadArea.addEventListener("click", () => imageUpload.click());
            uploadArea.addEventListener("dragover", (e) => {
                e.preventDefault();
                uploadArea.classList.add("dragover");
            });
            uploadArea.addEventListener("dragleave", () => uploadArea.classList.remove("dragover"));
            uploadArea.addEventListener("drop", (e) => {
                e.preventDefault();
                uploadArea.classList.remove("dragover");
                if (e.dataTransfer.files.length > 0) {
                    const file = e.dataTransfer.files[0];
                    if(file.type.startsWith("image/")) {
                        imageUpload.files = e.dataTransfer.files;
                        triggerImageUpload(file);
                    }
                }
            });

            imageContainer = document.getElementById("image-container");
            labelContainer = document.getElementById("label-container");
        }

        function onImageUpload(event) {
            if (event.target.files.length > 0) {
                triggerImageUpload(event.target.files[0]);
            }
        }

        function triggerImageUpload(file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const image = document.createElement("img");
                image.src = e.target.result;
                image.className = "img-fluid rounded shadow-sm";
                image.style.maxHeight = "250px";
                image.style.objectFit = "contain";
                image.style.width = "100%";
                imageContainer.innerHTML = "";
                imageContainer.appendChild(image);
                
                imageContainer.style.display = "block";
                document.getElementById("search-btn").style.display = "inline-block";
                document.getElementById("reset-btn").style.display = "inline-block";
                document.getElementById("upload-area").style.display = "none";
            };
            reader.readAsDataURL(file);
        }

        function resetUpload() {
            imageContainer.style.display = "none";
            imageContainer.innerHTML = "";
            document.getElementById("search-btn").style.display = "none";
            document.getElementById("reset-btn").style.display = "none";
            document.getElementById("upload-area").style.display = "block";
            document.getElementById("image-upload").value = "";
        }

        async function predict() {
            const image = document.querySelector("#image-container img");
            if (!image) {
                alert("<?= __('find_animal_alert_upload') ?>");
                return;
            }

            const predictions = await model.predict(image);
            const topPredictions = getTopPredictions(predictions, 5);

            let searchTerm = '';
            if (topPredictions[0].probability >= 0.8) {
                searchTerm = topPredictions[0].className;
            } else {
                searchTerm = "Unknown";
            }

            $('#searchTerm').val(searchTerm);
            $('#searchForm').unbind('submit').submit();

            // Save top predictions to local storage with keys "animal" and "score"
            const formattedPredictions = topPredictions.map(pred => ({
                animal: pred.className,
                score: (pred.probability * 100).toFixed(2) // Convert to percentage
            }));

            localStorage.setItem('animalPredictions', JSON.stringify(formattedPredictions));
        }

        function getTopPredictions(predictions, topN) {
            // Sort predictions by probability in descending order
            predictions.sort((a, b) => b.probability - a.probability);
            return predictions.slice(0, topN); // Get top N predictions
        }

        start();
    </script>
    <script src="~/js/script.js"></script>
    <div class="static-button" id="startIntro">
        <img src="<?= $base ?>/images/idle.gif" alt="Start Intro"
             style="max-width: 100%; max-height: 250px; height: auto; width: auto;">
        <div class="click-me"
             style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;">
            <?= __('need_help') ?>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intro.js/7.2.0/intro.min.js"></script>
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
                        <div class="container">
                            <div class="row align-items-center text-left">
                                <div class="col-md-6 p-3">
                                    <p class="text-white fw-bold fs-4" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);">
                                        <?= __('find_animal_tour_step1') ?>
                                    </p>
                                </div>
                                <div class="col-md-6 text-center">
                                    <img src="<?= $base ?>/images/trailer1.gif" alt="Description of Image" class="img-fluid rounded" style="max-height: 400px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    `
                    },
                    {
                        element: document.querySelector('#upload-area'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                     <?= __('find_animal_tour_step2') ?>
                                 </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('#upload-area'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                     <?= __('find_animal_tour_step3') ?>
                                 </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('#search-btn'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                     <?= __('find_animal_tour_step4') ?>
                                 </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },
                    {
                        element: document.querySelector('#search-btn'),
                        intro: `
                                 <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black;  font-size: 30px;" >
                                     <?= __('find_animal_tour_step5') ?>
                                 </p>
                `,
                        position: 'bottom' // Position tooltip directly below the text
                    },

                ],
                tooltipPosition: 'bottom', // Default position for tooltips
                positionPrecedence: ['bottom', 'top', 'left', 'right'] // Order of positioning
            }).start();
        };
    </script>
</section>
</section>
<?php
include '../footer.php';
?>
</body>
</html>