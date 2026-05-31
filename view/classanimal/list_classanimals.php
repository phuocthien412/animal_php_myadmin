<?php
require_once __DIR__ . '/../../config/env.php';
?>
<!DOCTYPE html>
<html>
<head>
  <title th:text="${title} ?: 'ClassAnimal List'"> ClassAnimals List </title>
  <link href='https://fonts.googleapis.com/css?family=Kanit' rel='stylesheet'>
  <link rel="stylesheet" href="<?= $base ?>/css/mystyle.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php
include '../header.php';
?>
<section layout:fragment="content" style="padding: 0;">
  <section id="explore" class="explore py-5">
    <div class="container">
      <div class="row g-4 align-items-center">
        <div class="col-lg-3 col-md-12 mb-4 mb-lg-0 text-center text-lg-start">
            <h1 class="textexplore fw-bold">Khám phá các nhóm động vật cùng NEKOPARA</h1>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="flip-container">
                <a href="<?= $base ?>/classanimal/detail/1" class="card">
                    <div class="front" style="background-image: url('<?= $base ?>/images/Explore/bosat.png');"></div>
                    <div class="back" style="background-image: url('<?= $base ?>/images/Explore/bosat.png');">
                        <h1 class="textexplore">Động vật bò sát</h1>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="flip-container">
                <a href="<?= $base ?>/classanimal/detail/2" class="card">
                    <div class="front" style="background-image: url('<?= $base ?>/images/Explore/ca.png');"></div>
                    <div class="back" style="background-image: url('<?= $base ?>/images/Explore/ca.png');">
                        <h1 class="textexplore">Cá</h1>
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
                        <h1 class="textexplore">Động vật có vú</h1>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="flip-container">
                <a href="<?= $base ?>/classanimal/detail/5" class="card">
                    <div class="front" style="background-image: url('<?= $base ?>/images/Explore/khongxuongsong.png');"></div>
                    <div class="back" style="background-image: url('<?= $base ?>/images/Explore/khongxuongsong.png');">
                        <h1 class="textexplore">Động vật không xương sống</h1>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="flip-container">
                <a href="<?= $base ?>/classanimal/detail/6" class="card">
                    <div class="front" style="background-image: url('<?= $base ?>/images/Explore/luongcu.png');"></div>
                    <div class="back" style="background-image: url('<?= $base ?>/images/Explore/luongcu.png');">
                        <h1 class="textexplore">Động vật lưỡng cư</h1>
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
</section>
<?php
include '../footer.php';
?>
</body>
</html>


