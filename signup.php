<?php

include 'db.php';     
include 'header.php'; 

$username = '';
$email    = '';
$errors   = [];
$success  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    if ($username === '') {
        $errors[] = "Username is required.";
    }
    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email is not valid.";
    }
    if ($password === '') {
        $errors[] = "Password is required.";
    }
    
    

    if (empty($errors)) {
        try {
            $sql = "INSERT INTO Users (username, email, password_hash)
                    VALUES (:username, :email, :password_hash)";
            $stmt = $pdo->prepare($sql);
            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt->execute([
                ':username'      => $username,
                ':email'         => $email,
                ':password_hash' => $hash
            ]);

            $success  = "Account created successfully!";
            $username = '';
            $email    = '';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $errors[] = "That username or email is already in use.";
            } else {
                $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
            }
        }
    }
}
?>
<div class="signup-container">
    <div class="card">
        <div class="card-header">
            <h2>Create Account</h2>
        </div>

        <?php if (!empty($errors)): ?>
            <div style="color: red; margin-bottom: 10px;">
                <?php foreach ($errors as $err): ?>
                    <p><?php echo htmlspecialchars($err); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div style="color: green; margin-bottom: 10px;">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="post" action="signup.php">
            <label>Username</label><br>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" required><br><br>

            <label>Email</label><br>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br><br>

            <label>Password</label><br>
            <input type="password" name="password" required><br><br>

            <button type="submit" class="btn">Sign Up</button>
        </form>
    </div>
</div>
<style>

.signup-container {
    display: flex;
    justify-content: center;
    margin-top: 50px;
}

.card {
    width: 100%;
    max-width: 450px;
    padding: 25px;
    border-radius: 12px;
    background: #fdfdfd;
    box-shadow: 0 4px 14px rgba(0,0,0,0.08);
}

.card-header h2 {
    font-size: 22px;
    margin: 0 0 14px 0;
    text-align: center;
}


form label {
    font-weight: 600;
    margin-bottom: 6px;
    display: inline-block;
}

form input {
    width: 100%;
    padding: 10px 12px;
    border-radius: 8px;
    border: 1px solid #cfcfcf;
    margin-bottom: 16px;
    font-size: 15px;
    transition: border-color 0.2s;
}

form input:focus {
    outline: none;
    border-color: #007bff;
}


.btn {
    width: 100%;
    background-color: #3c6e71;
    color: white;
    font-size: 15px;
    font-weight: bold;
    padding: 12px 0;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
}

.btn:hover {
    background-color: #3c6e71;
}

.error-box, .success-box {
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 14px;
    font-size: 14px;
}

.error-box {
    background: #ffe2e2;
    color: #a10000;
}

.success-box {
    background: #e3ffe6;
    color: #006400;
}
</style>


</div></body></html>
