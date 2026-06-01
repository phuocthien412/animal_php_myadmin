<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Post.php';
require_once __DIR__ . '/../model/Notification.php';

class PostController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Create a new post
    public function createPost($data) {
        // Check if user_id exists
        $sql = "SELECT COUNT(*) FROM user WHERE id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $data['user_id']]);
        if ($stmt->fetchColumn() == 0) {
            throw new Exception("User ID does not exist.");
        }

        $sql = "INSERT INTO posts (date, image, title, user_id) VALUES (:date, :image, :title, :user_id)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'post',
                'action' => 'Đã tạo',
                'title' => 'Bài viết mới',
                'message' => 'Vừa thêm bài viết "' . ($data['title'] ?? 'Không tiêu đề') . '"',
                'link' => '/admin/posts',
                'target_type' => 'post',
                'target_id' => $this->db->lastInsertId(),
                'meta' => ['title' => $data['title'] ?? null],
            ]);
        }

        return $result;
    }

    // Read all posts
    public function getAllPosts($limit = null) {
        $sql = "SELECT * FROM posts ORDER BY date DESC";
        if ($limit) {
            $sql .= " LIMIT " . (int)$limit;
        }
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single post by ID
    public function getPostById($id_post) {
        $sql = "SELECT * FROM posts WHERE id_post = :id_post";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_post' => $id_post]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update an existing post
    public function updatePost($id_post, $data) {
        $current = $this->getPostById($id_post);
        $sql = "UPDATE posts SET date = :date, image = :image, title = :title, user_id = :user_id WHERE id_post = :id_post";
        $stmt = $this->db->prepare($sql);
        $data['id_post'] = $id_post;
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'post',
                'action' => 'Đã cập nhật',
                'title' => 'Bài viết đã cập nhật',
                'message' => 'Vừa cập nhật bài viết "' . ($data['title'] ?? ($current['title'] ?? 'Không tiêu đề')) . '"',
                'link' => '/admin/posts',
                'target_type' => 'post',
                'target_id' => $id_post,
                'meta' => ['before' => $current, 'after' => $data],
            ]);
        }

        return $result;
    }

    // Delete a post
    public function deletePost($id_post) {
        $current = $this->getPostById($id_post);
        $sql = "DELETE FROM posts WHERE id_post = :id_post";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
    
        if (!$stmt->execute()) {
            throw new Exception("Error deleting post: " . implode(", ", $stmt->errorInfo()));
        }

        Notification::record([
            'type' => 'post',
            'action' => 'Đã xoá',
            'title' => 'Bài viết đã xoá',
            'message' => 'Vừa xoá bài viết "' . ($current['title'] ?? ('#' . $id_post)) . '"',
            'link' => '/admin/posts',
            'target_type' => 'post',
            'target_id' => $id_post,
            'meta' => ['deleted' => $current],
        ]);
    }
    public function getPostsByUserId($user_id) {
        $sql = "SELECT * FROM posts WHERE user_id = :user_id ORDER BY date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    private function ensurePostLikesTable(): void {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS post_likes (
                post_id INT,
                user_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (post_id, user_id)
            )");
        } catch (PDOException $e) {
            // Ignore if fails
        }
    }

    public function toggleLike($post_id, $user_id) {
        $this->ensurePostLikesTable();
        $stmt = $this->db->prepare("SELECT 1 FROM post_likes WHERE post_id = :pid AND user_id = :uid");
        $stmt->execute(['pid' => $post_id, 'uid' => $user_id]);
        if ($stmt->fetch()) {
            $del = $this->db->prepare("DELETE FROM post_likes WHERE post_id = :pid AND user_id = :uid");
            $del->execute(['pid' => $post_id, 'uid' => $user_id]);
            return false;
        } else {
            $ins = $this->db->prepare("INSERT INTO post_likes (post_id, user_id) VALUES (:pid, :uid)");
            $ins->execute(['pid' => $post_id, 'uid' => $user_id]);
            return true;
        }
    }

    public function getLikeCount($post_id) {
        $this->ensurePostLikesTable();
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM post_likes WHERE post_id = :pid");
        $stmt->execute(['pid' => $post_id]);
        return (int)$stmt->fetchColumn();
    }

    public function isLikedByUser($post_id, $user_id) {
        if (!$user_id) return false;
        $this->ensurePostLikesTable();
        $stmt = $this->db->prepare("SELECT 1 FROM post_likes WHERE post_id = :pid AND user_id = :uid");
        $stmt->execute(['pid' => $post_id, 'uid' => $user_id]);
        return (bool)$stmt->fetch();
    }
}
?>