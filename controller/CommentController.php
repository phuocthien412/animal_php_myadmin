<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../model/Comment.php';
require_once __DIR__ . '/../model/Notification.php';

class CommentController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Create a new comment
    public function createComment($data) {
        $sql = "INSERT INTO comments (chat_data, date_time, post_id, user_id) VALUES (:chat_data, :date_time, :post_id, :user_id)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'comment',
                'action' => 'Đã tạo',
                'title' => 'Bình luận mới',
                'message' => 'Vừa có bình luận mới',
                'link' => '/admin/comments',
                'target_type' => 'comment',
                'target_id' => $this->db->lastInsertId(),
                'meta' => $data,
            ]);
        }

        return $result;
    }

    // Read all comments
    public function getAllComments() {
        $sql = "SELECT * FROM comments";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Read a single comment by ID
    public function getCommentById($id_cmt) {
        $sql = "SELECT * FROM comments WHERE id_cmt = :id_cmt";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id_cmt' => $id_cmt]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Update an existing comment
    public function updateComment($id_cmt, $data) {
        $current = $this->getCommentById($id_cmt);
        $sql = "UPDATE comments SET chat_data = :chat_data, date_time = :date_time, post_id = :post_id, user_id = :user_id WHERE id_cmt = :id_cmt";
        $stmt = $this->db->prepare($sql);
        $data['id_cmt'] = $id_cmt;
        $result = $stmt->execute($data);

        if ($result) {
            Notification::record([
                'type' => 'comment',
                'action' => 'Đã cập nhật',
                'title' => 'Bình luận đã cập nhật',
                'message' => 'Vừa cập nhật bình luận #' . $id_cmt,
                'link' => '/admin/comments',
                'target_type' => 'comment',
                'target_id' => $id_cmt,
                'meta' => ['before' => $current, 'after' => $data],
            ]);
        }

        return $result;
    }

    // Delete a comment
    public function deleteComment($id_cmt) {
        $current = $this->getCommentById($id_cmt);
        $sql = "DELETE FROM comments WHERE id_cmt = :id_cmt";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_cmt', $id_cmt, PDO::PARAM_INT);
    
        if (!$stmt->execute()) {
            throw new Exception("Error deleting comment: " . implode(", ", $stmt->errorInfo()));
        }

        Notification::record([
            'type' => 'comment',
            'action' => 'Đã xoá',
            'title' => 'Bình luận đã xoá',
            'message' => 'Vừa xoá bình luận #' . $id_cmt,
            'link' => '/admin/comments',
            'target_type' => 'comment',
            'target_id' => $id_cmt,
            'meta' => ['deleted' => $current],
        ]);
    }
    public function getCommentsByPostId($post_id) {
        $sql = "SELECT * FROM comments WHERE post_id = :post_id ORDER BY date_time ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['post_id' => $post_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function deleteCommentsByUserId($user_id) {
        $sql = "DELETE FROM comments WHERE user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $user_id]);
        $deleted = $stmt->rowCount();

        if ($deleted > 0) {
            Notification::record([
                'type' => 'comment',
                'action' => 'Đã xoá',
                'title' => 'Xoá bình luận theo người dùng',
                'message' => 'Vừa xoá ' . $deleted . ' bình luận của người dùng #' . $user_id,
                'link' => '/admin/comments',
                'target_type' => 'comment',
                'target_id' => $user_id,
                'meta' => ['deleted_count' => $deleted, 'user_id' => $user_id],
            ]);
        }

        return $deleted;
    }

    // Ensure hidden/orig columns exist (non-destructive check + add)
    private function ensureHiddenColumns(): void {
        try {
            $res = $this->db->query("SHOW COLUMNS FROM comments LIKE 'hidden'")->fetch();
            if (!$res) {
                $this->db->exec("ALTER TABLE comments ADD COLUMN hidden TINYINT(1) DEFAULT 0, ADD COLUMN orig_chat_data TEXT NULL");
            }
        } catch (PDOException $e) {
            // ignore if ALTER fails (permissions), methods will fallback
        }
    }

    public function hideComment($id_cmt) {
        // Prefer soft-hide using new columns; fallback to replacing text
        $this->ensureHiddenColumns();
        $c = $this->getCommentById($id_cmt);
        if (!$c) return false;
        try {
            $sql = "UPDATE comments SET orig_chat_data = chat_data, chat_data = :placeholder, hidden = 1 WHERE id_cmt = :id_cmt";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['placeholder' => '[Đã ẩn bởi quản trị]', 'id_cmt' => $id_cmt]);
            Notification::record([
                'type' => 'comment',
                'action' => 'Đã ẩn',
                'title' => 'Bình luận đã ẩn',
                'message' => 'Vừa ẩn bình luận #' . $id_cmt,
                'link' => '/admin/comments',
                'target_type' => 'comment',
                'target_id' => $id_cmt,
                'meta' => ['before' => $c],
            ]);
            return true;
        } catch (PDOException $e) {
            // fallback: replace content
            $result = $this->updateComment($id_cmt, ['chat_data' => '[Đã ẩn bởi quản trị]', 'date_time' => $c['date_time'], 'post_id' => $c['post_id'], 'user_id' => $c['user_id']]);
            return $result;
        }
    }

    public function unhideComment($id_cmt) {
        // Restore original content if available
        try {
            $res = $this->db->query("SHOW COLUMNS FROM comments LIKE 'orig_chat_data'")->fetch();
            if ($res) {
                $c = $this->getCommentById($id_cmt);
                if (!$c) return false;
                $orig = $c['orig_chat_data'] ?? null;
                if ($orig !== null) {
                    $sql = "UPDATE comments SET chat_data = :orig, orig_chat_data = NULL, hidden = 0 WHERE id_cmt = :id_cmt";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute(['orig' => $orig, 'id_cmt' => $id_cmt]);
                    Notification::record([
                        'type' => 'comment',
                        'action' => 'Đã hiện',
                        'title' => 'Bình luận đã hiện',
                        'message' => 'Vừa hiện lại bình luận #' . $id_cmt,
                        'link' => '/admin/comments',
                        'target_type' => 'comment',
                        'target_id' => $id_cmt,
                        'meta' => ['restored' => $c],
                    ]);
                    return true;
                }
            }
        } catch (PDOException $e) {
            // ignore
        }
        return false;
    }
    public function addComment($post_id, $user_id, $chat_data) {
        $sql = "INSERT INTO comments (post_id, user_id, chat_data, date_time) VALUES (:post_id, :user_id, :chat_data, NOW())";
        $stmt = $this->db->prepare($sql);
    
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':chat_data', $chat_data, PDO::PARAM_STR);
    
        if (!$stmt->execute()) {
            throw new Exception("Error adding comment: " . implode(", ", $stmt->errorInfo()));
        }

        Notification::record([
            'type' => 'comment',
            'action' => 'Đã tạo',
            'title' => 'Bình luận mới',
            'message' => 'Vừa có bình luận mới cho bài viết #' . $post_id,
            'link' => '/admin/comments',
            'target_type' => 'comment',
            'target_id' => $this->db->lastInsertId(),
            'meta' => ['post_id' => $post_id, 'user_id' => $user_id],
        ]);
    }

    private function ensureCommentLikesTable(): void {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS comment_likes (
                comment_id INT,
                user_id INT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (comment_id, user_id)
            )");
        } catch (PDOException $e) {
            // Ignore if fails
        }
    }

    public function toggleLike($comment_id, $user_id) {
        $this->ensureCommentLikesTable();
        $stmt = $this->db->prepare("SELECT 1 FROM comment_likes WHERE comment_id = :cid AND user_id = :uid");
        $stmt->execute(['cid' => $comment_id, 'uid' => $user_id]);
        if ($stmt->fetch()) {
            $del = $this->db->prepare("DELETE FROM comment_likes WHERE comment_id = :cid AND user_id = :uid");
            $del->execute(['cid' => $comment_id, 'uid' => $user_id]);
            return false;
        } else {
            $ins = $this->db->prepare("INSERT INTO comment_likes (comment_id, user_id) VALUES (:cid, :uid)");
            $ins->execute(['cid' => $comment_id, 'uid' => $user_id]);
            return true;
        }
    }

    public function getLikeCount($comment_id) {
        $this->ensureCommentLikesTable();
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM comment_likes WHERE comment_id = :cid");
        $stmt->execute(['cid' => $comment_id]);
        return (int)$stmt->fetchColumn();
    }

    public function isLikedByUser($comment_id, $user_id) {
        if (!$user_id) return false;
        $this->ensureCommentLikesTable();
        $stmt = $this->db->prepare("SELECT 1 FROM comment_likes WHERE comment_id = :cid AND user_id = :uid");
        $stmt->execute(['cid' => $comment_id, 'uid' => $user_id]);
        return (bool)$stmt->fetch();
    }
}
?>