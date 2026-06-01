<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Notification.php';

class ListAnimalController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Add an image to the listanimals table
    public function addImage($data) {
        $sql = "INSERT INTO listanimals (animalimage, animals_id) VALUES (:animalimage, :animals_id)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'animal',
                'action' => 'Đã thêm ảnh',
                'title' => 'Ảnh động vật mới',
                'message' => 'Vừa thêm ảnh phụ cho động vật #' . ($data['animals_id'] ?? ''),
                'link' => '/admin/animals',
                'target_type' => 'listanimal',
                'target_id' => $this->db->lastInsertId(),
                'meta' => $data,
            ]);
        }

        return $result;
    }

    // Fetch images related to a specific animal
    public function getAnimalImagesById($id) {
        $sql = "SELECT animalimage FROM listanimals WHERE animals_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>