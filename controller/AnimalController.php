<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Animal.php';
require_once __DIR__ . '/../model/Notification.php';

class AnimalController extends BaseController {

    // Create a new animal
    public function createAnimal($data) {
        $sql = "INSERT INTO animals (name, gioi_thieu_text, ngoai_hinh_text, noi_sinh_song_text, avatar, noi_sinh_song_image, imgqr3d, classanimals_id) 
                VALUES (:name, :gioi_thieu_text, :ngoai_hinh_text, :noi_sinh_song_text, :avatar, :noi_sinh_song_image, :imgqr3d, :classanimals_id)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
        $animalId = $this->db->lastInsertId(); // Return the ID of the newly created animal

        Notification::record([
            'type' => 'animal',
            'action' => 'Đã tạo',
            'title' => 'Động vật mới',
            'message' => 'Vừa thêm loài "' . ($data['name'] ?? 'Không tên') . '"',
            'link' => '/admin/animals',
            'target_type' => 'animal',
            'target_id' => $animalId,
            'meta' => ['name' => $data['name'] ?? null],
        ]);

        return $animalId;
    }

    // Read all animals
    public function getAllAnimals() {
        $sql = "SELECT * FROM animals";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single animal by ID
    public function getAnimalById($id) {
        $sql = "SELECT * FROM animals WHERE id_animal = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Fetch class animal information by ID
    public function getClassAnimalInfoById($id) {
        $sql = "SELECT * FROM classanimals WHERE id_class = :id"; // Updated table name
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAnimalImagesById($id) {
        $sql = "SELECT id, animalimage FROM listanimals WHERE animals_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAnimalImage($imageId) {
        $current = $this->db->prepare("SELECT * FROM listanimals WHERE id = :id");
        $current->execute(['id' => $imageId]);
        $image = $current->fetch(PDO::FETCH_ASSOC);
        $sql = "DELETE FROM listanimals WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['id' => $imageId]);

        if ($result) {
            Notification::record([
                'type' => 'animal',
                'action' => 'Đã xoá',
                'title' => 'Ảnh động vật đã xoá',
                'message' => 'Vừa xoá một ảnh phụ của động vật #' . ($image['animals_id'] ?? $imageId),
                'link' => '/admin/animals',
                'target_type' => 'listanimal',
                'target_id' => $imageId,
                'meta' => ['deleted' => $image],
            ]);
        }

        return $result;
    }

    public function addAnimalImage($animalId, $imageName) {
        $sql = "INSERT INTO listanimals (animals_id, animalimage) VALUES (:animalId, :imageName)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['animalId' => $animalId, 'imageName' => $imageName]);

        if ($result) {
            Notification::record([
                'type' => 'animal',
                'action' => 'Đã thêm ảnh',
                'title' => 'Ảnh động vật mới',
                'message' => 'Vừa thêm ảnh phụ cho động vật #' . $animalId,
                'link' => '/admin/animals',
                'target_type' => 'listanimal',
                'target_id' => $animalId,
                'meta' => ['animal_id' => $animalId, 'image' => $imageName],
            ]);
        }

        return $result;
    }
public function deleteAnimal($id) {
    try {
        $current = $this->getAnimalById($id);
        // Begin a transaction
        $this->db->beginTransaction();

        // Delete associated images from the listanimals table
        $sqlDeleteImages = "DELETE FROM listanimals WHERE animals_id = :id";
        $stmtDeleteImages = $this->db->prepare($sqlDeleteImages);
        $stmtDeleteImages->execute(['id' => $id]);

        // Delete the animal from the animals table
        $sqlDeleteAnimal = "DELETE FROM animals WHERE id_animal = :id";
        $stmtDeleteAnimal = $this->db->prepare($sqlDeleteAnimal);
        $stmtDeleteAnimal->execute(['id' => $id]);

        // Commit the transaction
        $this->db->commit();

        Notification::record([
            'type' => 'animal',
            'action' => 'Đã xoá',
            'title' => 'Động vật đã xoá',
            'message' => 'Vừa xoá loài "' . ($current['name'] ?? ('#' . $id)) . '"',
            'link' => '/admin/animals',
            'target_type' => 'animal',
            'target_id' => $id,
            'meta' => ['deleted' => $current],
        ]);

        return true;
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $this->db->rollBack();
        throw $e;
    }
}
public function updateAnimal($id, $data) {
    try {
    $current = $this->getAnimalById($id);
        $sql = "UPDATE animals 
                SET name = :name, 
                    gioi_thieu_text = :gioi_thieu_text, 
                    ngoai_hinh_text = :ngoai_hinh_text, 
                    noi_sinh_song_text = :noi_sinh_song_text, 
                    classanimals_id = :classanimals_id,
                    avatar = :avatar,
                    noi_sinh_song_image = :noi_sinh_song_image,
                    imgqr3d = :imgqr3d
                WHERE id_animal = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'name' => $data['name'],
            'gioi_thieu_text' => $data['gioi_thieu_text'],
            'ngoai_hinh_text' => $data['ngoai_hinh_text'],
            'noi_sinh_song_text' => $data['noi_sinh_song_text'],
            'classanimals_id' => $data['classanimals_id'],
            'avatar' => $data['avatar'],
            'noi_sinh_song_image' => $data['noi_sinh_song_image'],
            'imgqr3d' => $data['imgqr3d'],
            'id' => $id
        ]);
        $affected = $stmt->rowCount(); // Return the number of affected rows

        if ($affected > 0) {
            Notification::record([
                'type' => 'animal',
                'action' => 'Đã cập nhật',
                'title' => 'Động vật đã cập nhật',
                'message' => 'Vừa cập nhật loài "' . ($data['name'] ?? ($current['name'] ?? 'Không tên')) . '"',
                'link' => '/admin/animals',
                'target_type' => 'animal',
                'target_id' => $id,
                'meta' => ['before' => $current, 'after' => $data],
            ]);
        }

        return $affected; // Return the number of affected rows
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return false;
    }
}
}
?>