<?php
include 'Admin.php';

class ProfileController {
    private $db;
    private $admin;

    public function __construct($db) {
        $this->db = $db;
        $this->admin = new Admin($db);
    }

    public function updateProfile($user_id, $data, $files) {
        $data['user_id'] = $user_id;

        // فاليديشن للصورة
        if (isset($files['profilePicture']) && $files['profilePicture']['error'] == 0) {
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 2 * 1024 * 1024; // 2 ميجابايت

            if (!in_array($files['profilePicture']['type'], $allowed_types)) {
                return "invalid_image_type"; // صورة غير مدعومة
            }

            if ($files['profilePicture']['size'] > $max_size) {
                return "image_size_exceeded"; // حجم الصورة كبير جداً
            }

            // رفع الصورة بعد التحقق
            $target_dir = "../public/assets/img/gallery/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_name = time() . '_' . basename($files["profilePicture"]["name"]);
            $target_file = $target_dir . $file_name;

            if (move_uploaded_file($files["profilePicture"]["tmp_name"], $target_file)) {
                $data['profile_picture'] = $file_name;
            } else {
                return "upload_failed"; // فشل في رفع الصورة
            }
        }

        // فاليديشن لكلمة المرور
        if (!empty($data['password'])) {
            if (!$this->validatePassword($data['password'])) {
                return "weak_password"; // إذا كانت كلمة المرور ضعيفة
            }
            // تشفير كلمة المرور قبل تخزينها في قاعدة البيانات
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }

        // تحديث البيانات في قاعدة البيانات
        return $this->admin->updateUserProfile($data) ? true : "update_failed";
    }

    // دالة للتحقق من قوة كلمة المرور
    private function validatePassword($password) {
        return strlen($password) >= 12 &&
               preg_match('/[A-Z]/', $password) &&   // على الأقل حرف كبير
               preg_match('/[a-z]/', $password) &&   // على الأقل حرف صغير
               preg_match('/[0-9]/', $password) &&   // على الأقل رقم
               preg_match('/[\W_]/', $password);     // على الأقل حرف خاص
    }
}
?>
