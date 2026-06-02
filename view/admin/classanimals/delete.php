<?php
require_once __DIR__ . '/../../../config/env.php';

$classAnimalController = new ClassAnimalController();
$classAnimalController->authorize('ADMIN', '/Login');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $classId = intval($_GET['id']);
    try {
        // Check if there are any animals in this class
        $animals = $classAnimalController->getAnimalsByClassAnimalId($classId);
        if (count($animals) > 0) {
            $classAnimalController->redirect('/admin/classanimals', __('msg_classanimal_has_animals') ?? 'Cannot delete because it contains animals.', 'error');
            exit();
        }

        $classAnimalController->deleteClassAnimal($classId);
        $classAnimalController->redirect('/admin/classanimals', __('msg_delete_classanimal_success') ?? 'Successfully deleted.', 'success');
    } catch (Exception $e) {
        $classAnimalController->redirect('/admin/classanimals', $e->getMessage(), 'error');
    }
} else {
    $classAnimalController->redirect('/admin/classanimals', '', 'error');
}
?>
