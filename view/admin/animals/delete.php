<?php

$animalController = new AnimalController();
$animalController->authorize('ADMIN', '/Login');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $animalId = intval($_GET['id']);
    try {
        $animalController->deleteAnimal($animalId);
        $animalController->redirect('/admin/animals', 'msg_delete_animal_success', 'success');
    } catch (Exception $e) {
        $animalController->redirect('/admin/animals', $e->getMessage(), 'error');
    }
} else {
    $animalController->redirect('/admin/animals', '', 'error');
}
?>