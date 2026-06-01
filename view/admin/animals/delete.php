<?php
require_once '../../../controller/AnimalController.php';
require_once '../../../config/env.php'; // Load $base từ .env

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $animalId         = intval($_GET['id']);
    $animalController = new AnimalController();

    try {
        $animalController->deleteAnimal($animalId);
        header("Location: " . $base . "/admin/animals?success=" . urlencode(__('msg_delete_animal_success')));
    } catch (Exception $e) {
        header("Location: " . $base . "/admin/animals?error=" . urlencode($e->getMessage()));
    }
    exit();
}

header("Location: " . $base . "/admin/animals");
exit();
?>