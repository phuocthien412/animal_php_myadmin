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
                <!-- Luminous Frosted Glass Card -->
                <div class="glass-upload-card text-center p-4 p-md-5" style="background: rgba(255, 255, 255, 0.15); backdrop-filter: blur(24px); -webkit-backdrop-filter: blur(24px); border: 1px solid rgba(255, 255, 255, 0.4); border-radius: 30px; box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3), inset 0 0 0 1px rgba(255,255,255,0.2), 0 0 40px rgba(16, 185, 129, 0.2); position: relative; overflow: hidden; animation: fadeInUp 0.8s ease-out;">
                    
                    <!-- Decorative glow behind text -->
                    <div style="position: absolute; top: -50px; left: 50%; transform: translateX(-50%); width: 200px; height: 100px; background: rgba(52, 211, 153, 0.4); filter: blur(60px); z-index: 0;"></div>

                    <h2 class="fw-bold mb-3 text-white" style="position: relative; z-index: 1; font-family: 'Outfit', sans-serif; font-size: 2.8rem; letter-spacing: -0.025em; text-shadow: 0 4px 15px rgba(0,0,0,0.6);"><i class="fa-solid fa-camera-retro text-emerald-300 me-3" style="filter: drop-shadow(0 0 10px rgba(52,211,153,0.8));"></i><?= __('find_animal_title') ?></h2>
                    <p class="text-white mb-5" style="position: relative; z-index: 1; font-size: 1.15rem; font-weight: 300; text-shadow: 0 2px 4px rgba(0,0,0,0.8); letter-spacing: 0.5px;"><?= __('find_animal_subtitle') ?></p>
                    
                    <div class="upload-area mb-4" id="upload-area" style="position: relative; z-index: 1; background: rgba(0, 0, 0, 0.2); border: 2px dashed rgba(255, 255, 255, 0.6); border-radius: 20px; padding: 50px 20px; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); cursor: pointer; box-shadow: inset 0 0 20px rgba(0,0,0,0.2);" onmouseover="this.style.borderColor='rgba(52, 211, 153, 1)'; this.style.background='rgba(52, 211, 153, 0.1)'; this.style.transform='translateY(-5px) scale(1.02)'; this.style.boxShadow='0 15px 30px rgba(0,0,0,0.2), inset 0 0 20px rgba(52,211,153,0.2)';" onmouseout="this.style.borderColor='rgba(255, 255, 255, 0.6)'; this.style.background='rgba(0, 0, 0, 0.2)'; this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='inset 0 0 20px rgba(0,0,0,0.2)';">
                        
                        <div class="upload-icon-container" style="transition: transform 0.3s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            <i class="fas fa-cloud-upload-alt fa-4x mb-4 text-emerald-400" style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.4));"></i>
                        </div>
                        <h5 class="mb-2 text-white fw-bold" style="text-shadow: 0 2px 4px rgba(0,0,0,0.8); font-size: 1.3rem; letter-spacing: 0.5px;"><?= __('find_animal_drag_drop') ?></h5>
                        <p class="text-gray-300 small mb-4" style="text-shadow: 0 1px 2px rgba(0,0,0,0.8);"><?= __('find_animal_or') ?></p>
                        
                        <button type="button" class="btn rounded-pill px-5 py-2.5 fw-bold" style="background: linear-gradient(135deg, rgba(255,255,255,0.9), rgba(255,255,255,0.7)); color: #064e3b; border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px rgba(255,255,255,0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.3)';">
                            <i class="fa-solid fa-folder-open me-2"></i><?= __('find_animal_choose_file') ?>
                        </button>
                        <input type="file" id="image-upload" class="d-none" accept="image/*">
                    </div>

                    <div id="image-container" class="preview-container mb-4" style="position: relative; z-index: 1; display: none; border-radius: 20px; overflow: hidden; border: 3px solid rgba(52, 211, 153, 0.6); box-shadow: 0 10px 30px rgba(0,0,0,0.5), 0 0 30px rgba(52,211,153,0.3); padding: 5px; background: rgba(0,0,0,0.4);">
                        <!-- The JS will insert the <img> tag here. The wrapper padding makes it look like a glowing frame. -->
                    </div>
                    
                    <button type="button" onclick="resetUpload()" id="reset-btn" class="btn btn-sm rounded-pill mb-4 shadow-sm fw-bold px-4 py-2" style="position: relative; z-index: 1; display: none; background: rgba(255,255,255,0.2); color: #fff; border: 1px solid rgba(255,255,255,0.4); backdrop-filter: blur(4px); transition: all 0.3s;" onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.borderColor='white';" onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.borderColor='rgba(255,255,255,0.4)';">
                        <i class="fas fa-undo me-1"></i> <?= __('find_animal_choose_other') ?>
                    </button>
                    
                    <div id="label-container" class="d-none"></div>
                    
                    <button type="button" onclick="predict()" id="search-btn" class="btn btn-lg rounded-pill w-100 fw-bold shadow-lg py-3 mt-2" style="position: relative; z-index: 1; display: block; background: linear-gradient(135deg, #10b981, #059669); color: white; border: 1px solid rgba(255,255,255,0.3); font-size: 1.2rem; letter-spacing: 1px; text-transform: uppercase; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); box-shadow: 0 10px 25px rgba(16, 185, 129, 0.4);" onmouseover="this.style.transform='translateY(-4px) scale(1.02)'; this.style.boxShadow='0 15px 35px rgba(16, 185, 129, 0.6)';" onmouseout="this.style.transform='translateY(0) scale(1)'; this.style.boxShadow='0 10px 25px rgba(16, 185, 129, 0.4)';">
                        <i class="fas fa-magic me-2 text-emerald-100"></i> <?= __('find_animal_detect_search') ?>
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
                image.className = "img-fluid shadow-lg";
                image.style.maxHeight = "350px";
                image.style.objectFit = "cover";
                image.style.width = "100%";
                image.style.borderRadius = "14px";
                image.style.display = "block";
                image.style.animation = "fadeIn 0.5s ease-out";
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
             style="color: white; text-shadow: 0 4px 12px rgba(0,0,0,0.8);  font-size: 30px;">
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
                        <div style="display: flex; align-items: center; text-align: left; width: 800px; max-width: 90vw;">
                            <div class="home-intro-copy" style="padding: 10px; height: auto;" >
                                <p class="intro-mobile-text" style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black; margin: 0; line-height: 1.4;" >
                                    <?= __('find_animal_tour_step1') ?>
                                </p>
                            </div>
                            <div class="home-intro-visual">
                                <img src="<?= $base ?>/images/trailer1.gif" alt="Preview" style="max-width: 100%; width: min(500px, 100%); height: auto; object-fit: cover; margin-left: 0;" >
                            </div>
                        </div>
                    `
                    },
                    {
                        element: document.querySelector('#upload-area'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left; width: 600px; max-width: 90vw;">
                            <div class="home-intro-visual" style="flex-shrink: 0; margin-right: 20px;">
                                <img src="<?= $base ?>/images/idle.gif" alt="Assistant" style="width: 120px; height: auto; object-fit: contain;">
                            </div>
                            <div class="home-intro-copy" style="padding: 10px; flex-grow: 1;">
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black; font-size: 26px; margin: 0; line-height: 1.4;" >
                                     <?= __('find_animal_tour_step2') ?>
                                </p>
                            </div>
                        </div>
                        `,
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('#upload-area'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left; width: 600px; max-width: 90vw;">
                            <div class="home-intro-visual" style="flex-shrink: 0; margin-right: 20px;">
                                <img src="<?= $base ?>/images/idle.gif" alt="Assistant" style="width: 120px; height: auto; object-fit: contain;">
                            </div>
                            <div class="home-intro-copy" style="padding: 10px; flex-grow: 1;">
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black; font-size: 26px; margin: 0; line-height: 1.4;" >
                                     <?= __('find_animal_tour_step3') ?>
                                </p>
                            </div>
                        </div>
                        `,
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('#search-btn'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left; width: 600px; max-width: 90vw;">
                            <div class="home-intro-visual" style="flex-shrink: 0; margin-right: 20px;">
                                <img src="<?= $base ?>/images/idle.gif" alt="Assistant" style="width: 120px; height: auto; object-fit: contain;">
                            </div>
                            <div class="home-intro-copy" style="padding: 10px; flex-grow: 1;">
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black; font-size: 26px; margin: 0; line-height: 1.4;" >
                                     <?= __('find_animal_tour_step4') ?>
                                </p>
                            </div>
                        </div>
                        `,
                        position: 'bottom'
                    },
                    {
                        element: document.querySelector('#search-btn'),
                        intro: `
                        <div style="display: flex; align-items: center; text-align: left; width: 600px; max-width: 90vw;">
                            <div class="home-intro-visual" style="flex-shrink: 0; margin-right: 20px;">
                                <img src="<?= $base ?>/images/idle.gif" alt="Assistant" style="width: 120px; height: auto; object-fit: contain;">
                            </div>
                            <div class="home-intro-copy" style="padding: 10px; flex-grow: 1;">
                                <p style="color: white; text-shadow: 1px 1px 0 black, -1px -1px 0 black, -1px 1px 0 black, 1px -1px 0 black; font-size: 26px; margin: 0; line-height: 1.4;" >
                                     <?= __('find_animal_tour_step5') ?>
                                </p>
                            </div>
                        </div>
                        `,
                        position: 'bottom'
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