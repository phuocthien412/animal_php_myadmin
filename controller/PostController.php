<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Post.php';

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
        return $stmt->execute($data);
    }

    // Read all posts
    public function getAllPosts() {
        $sql = "SELECT * FROM posts ORDER BY date DESC";
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
        $sql = "UPDATE posts SET date = :date, image = :image, title = :title, user_id = :user_id WHERE id_post = :id_post";
        $stmt = $this->db->prepare($sql);
        $data['id_post'] = $id_post;
        return $stmt->execute($data);
    }

    // Delete a post
    public function deletePost($id_post) {
        $sql = "DELETE FROM posts WHERE id_post = :id_post";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_post', $id_post, PDO::PARAM_INT);
    
        if (!$stmt->execute()) {
            throw new Exception("Error deleting post: " . implode(", ", $stmt->errorInfo()));
        }
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