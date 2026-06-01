<?php
// filepath: e:\laragon\www\animal_php\controller\ClassAnimalController.php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/ClassAnimal.php';
require_once __DIR__ . '/../model/Notification.php';

class ClassAnimalController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Create a new class animal
    public function createClassAnimal($data) {
        $sql = "INSERT INTO classanimals (background_video, info, name) 
                VALUES (:background_video, :info, :name)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'classanimal',
                'action' => 'Đã tạo',
                'title' => 'Lớp động vật mới',
                'message' => 'Vừa thêm lớp "' . ($data['name'] ?? 'Không tên') . '"',
                'link' => '/admin/classanimals',
                'target_type' => 'classanimal',
                'target_id' => $this->db->lastInsertId(),
                'meta' => ['name' => $data['name'] ?? null],
            ]);
        }

        return $result;
    }

    // Read all class animals
    public function getAllClassAnimals() {
        $sql = "SELECT * FROM classanimals";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Read a single class animal by ID
    public function getClassAnimalById($id) {
        $sql = "SELECT * FROM classanimals WHERE id_class = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch animals related to a specific class animal
    public function getAnimalsByClassAnimalId($id) {
        $sql = "SELECT * FROM animals WHERE classanimals_id = :id"; // Updated column name
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Update an existing class animal
    public function updateClassAnimal($id, $data) {
        $current = $this->getClassAnimalById($id);
        $sql = "UPDATE classanimals SET background_video = :background_video, info = :info, name = :name WHERE id_class = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'classanimal',
                'action' => 'Đã cập nhật',
                'title' => 'Lớp động vật đã cập nhật',
                'message' => 'Vừa cập nhật lớp "' . ($data['name'] ?? ($current['name'] ?? 'Không tên')) . '"',
                'link' => '/admin/classanimals',
                'target_type' => 'classanimal',
                'target_id' => $id,
                'meta' => ['before' => $current, 'after' => $data],
            ]);
        }

        return $result;
    }

    // Delete a class animal
    public function deleteClassAnimal($id) {
        $current = $this->getClassAnimalById($id);
        $sql = "DELETE FROM classanimals WHERE id_class = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['id' => $id]);

        if ($result) {
            Notification::record([
                'type' => 'classanimal',
                'action' => 'Đã xoá',
                'title' => 'Lớp động vật đã xoá',
                'message' => 'Vừa xoá lớp "' . ($current['name'] ?? ('#' . $id)) . '"',
                'link' => '/admin/classanimals',
                'target_type' => 'classanimal',
                'target_id' => $id,
                'meta' => ['deleted' => $current],
            ]);
        }

        return $result;
    }
}
?>