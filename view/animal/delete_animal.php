<?php
require_once __DIR__ . '/../../config/env.php';


if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $animalController = new AnimalController();
    $animalController->deleteAnimal($id);
}

header("Location: ../../index.php");
exit();
?>