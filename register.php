<?php
require 'includes/db.php';
$error = '';
$success = '';

if (isset($_POST['register'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $password);

    if ($stmt->execute()) {
        $success = "Registration successful! You can now login.";
    } else {
        $error = "Email already exists!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - UtilityPay</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="bg-light">
<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body p-5">
                    <h3 class="text-center mb-4 text-success">Create Account</h3>
                    
                    <?php if($success) echo "<div class='alert alert-success'>$success</div>"; ?>
                    <?php if($error) echo "<div class='alert alert-danger'>$error</div>"; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="phone" class="form-control" placeholder="Phone Number (e.g. 0712345678)" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-success w-100">Register</button>
                    </form>
                    <p class="text-center mt-3">Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>