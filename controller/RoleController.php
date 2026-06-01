<?php
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../model/Role.php';
require_once __DIR__ . '/../model/Notification.php';

class RoleController extends BaseController {

    // Create a new role
    public function createRole($data) {
        $sql = "INSERT INTO role (description, name) VALUES (:description, :name)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'role',
                'action' => 'Đã tạo',
                'title' => 'Vai trò mới',
                'message' => 'Vừa thêm vai trò "' . ($data['name'] ?? 'Không tên') . '"',
                'link' => '/admin/users',
                'target_type' => 'role',
                'target_id' => $this->db->lastInsertId(),
                'meta' => ['name' => $data['name'] ?? null],
            ]);
        }

        return $result;
    }

    // Read all roles
    public function getAllRoles() {
        $sql = "SELECT * FROM role";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single role by ID
    public function getRoleById($id) {
        $sql = "SELECT * FROM role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update an existing role
    public function updateRole($id, $data) {
        $current = $this->getRoleById($id);
        $sql = "UPDATE role SET description = :description, name = :name WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $data['id'] = $id;
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'role',
                'action' => 'Đã cập nhật',
                'title' => 'Vai trò đã cập nhật',
                'message' => 'Vừa cập nhật vai trò "' . ($data['name'] ?? ($current['name'] ?? 'Không tên')) . '"',
                'link' => '/admin/users',
                'target_type' => 'role',
                'target_id' => $id,
                'meta' => ['before' => $current, 'after' => $data],
            ]);
        }

        return $result;
    }

    // Delete a role
    public function deleteRole($id) {
        $current = $this->getRoleById($id);
        $sql = "DELETE FROM role WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute(['id' => $id]);

        if ($result) {
            Notification::record([
                'type' => 'role',
                'action' => 'Đã xoá',
                'title' => 'Vai trò đã xoá',
                'message' => 'Vừa xoá vai trò "' . ($current['name'] ?? ('#' . $id)) . '"',
                'link' => '/admin/users',
                'target_type' => 'role',
                'target_id' => $id,
                'meta' => ['deleted' => $current],
            ]);
        }

        return $result;
    }
}
?>