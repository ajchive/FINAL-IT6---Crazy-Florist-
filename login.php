<?php
include 'includes/db.php';
include 'includes/header.php';

$error = "";

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if ($password === $user['password']) { 
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Incorrect password.";
        }
    } else {
        $error = "No account found.";
    }
}

?>

<section style="padding: 50px 0;">
    <div class="container" style="max-width:500px;">
        <div class="card" style="padding:30px;">
            <div class="center" style="margin-bottom:25px;">
                <h2 class="section-title" style="font-size:2rem;">Welcome Back</h2>
                <p class="muted">Login to continue shopping with Crazy Florist.</p>
            </div>

            <?php if($error): ?>
                <p style="color:red; font-weight:600; margin-bottom:15px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Email Address</label><br>
                <input type="email" name="email" required style="width:100%; padding:12px; margin:8px 0 18px; border:1px solid #ddd; border-radius:8px;">

                <label>Password</label><br>
                <input type="password" name="password" required style="width:100%; padding:12px; margin:8px 0 18px; border:1px solid #ddd; border-radius:8px;">

                <button type="submit" name="login" class="btn" style="width:100%;">Login</button>
            </form>

            <p class="center small" style="margin-top:20px;">
                Don’t have an account yet? <a href="register.php" style="color:var(--primary); font-weight:600;">Register here</a>
            </p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>