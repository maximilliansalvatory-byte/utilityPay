<?php 
include 'includes/header.php'; 
$user_id = $_SESSION['user_id'];

$success = '';
$error = '';

if (isset($_POST['update_profile'])) {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    
    $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $phone, $address, $user_id);
    if ($stmt->execute()) {
        $_SESSION['name'] = $name;
        $success = "✅ Profile updated successfully!";
    }
}

if (isset($_POST['change_password'])) {
    $old = $_POST['old_password'];
    $new = $_POST['new_password'];
    $confirm = $_POST['confirm_password'];

    $res = $conn->query("SELECT password FROM users WHERE id = $user_id");
    $user = $res->fetch_assoc();

    if (password_verify($old, $user['password'])) {
        if ($new === $confirm && strlen($new) >= 6) {
            $hash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $hash, $user_id);
            if ($stmt->execute()) {
                $success = "✅ Password changed successfully!";
            }
        } else {
            $error = "❌ New passwords do not match or too short!";
        }
    } else {
        $error = "❌ Current password is wrong!";
    }
}

$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<div class="container py-5">
    <h2 class="mb-4">My Profile</h2>

    <?php if($success): ?><div class="alert alert-success"><?= $success ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>

    <div class="card">
        <div class="card-body p-5">

            <!-- Update Profile -->
            <form method="POST" class="mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <label>Full Name</label>
                        <input type="text" name="name" class="form-control mb-3" value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label>Email</label>
                        <input type="email" class="form-control mb-3" value="<?= htmlspecialchars($user['email']) ?>" readonly>
                    </div>
                </div>

                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control mb-3" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" required>

                <label>Address</label>
                <textarea name="address" class="form-control mb-4"><?= htmlspecialchars($user['address'] ?? '') ?></textarea>

                <button type="submit" name="update_profile" class="btn btn-success w-100">Update Profile</button>
            </form>

            <hr>

            <!-- Change Password -->
            <h5>Change Password</h5>
            <form method="POST">
                <label>Current Password</label>
                <input type="password" name="old_password" class="form-control mb-3" required>

                <label>New Password</label>
                <input type="password" name="new_password" class="form-control mb-3" required minlength="6">

                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" class="form-control mb-4" required minlength="6">

                <button type="submit" name="change_password" class="btn btn-danger w-100">Change Password</button>
            </form>

        </div>
    </div>
</div>


<?php include 'includes/footer.php'; ?>