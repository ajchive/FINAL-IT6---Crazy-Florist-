<?php
include 'includes/db.php';

$success = "";
$error = "";

if (isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($fullname == "" || $email == "" || $password == "") {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $fullname, $email, $password);

        if ($stmt->execute()) {
            $success = "Registration successful! You may now login.";
        } else {
            $error = "Something went wrong. Try again.";
        }
    }
}

include 'includes/header.php';
?>

<section style="padding: 50px 0;">
    <div class="container" style="max-width:500px;">
        <div class="card" style="padding:30px;">
            <div class="center" style="margin-bottom:25px;">
                <h2 class="section-title" style="font-size:2rem;">Create an Account</h2>
                <p class="muted">Join Crazy Florist and start ordering beautiful blooms.</p>
            </div>

            <?php if($success): ?>
                <p style="color:green; font-weight:600; margin-bottom:15px;"><?php echo $success; ?></p>
            <?php endif; ?>

            <?php if($error): ?>
                <p style="color:red; font-weight:600; margin-bottom:15px;"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST">
                <label>Full Name</label><br>
                <input type="text" name="fullname" required style="width:100%; padding:12px; margin:8px 0 18px; border:1px solid #ddd; border-radius:8px;">

                <label>Email Address</label><br>
                <input type="email" name="email" required style="width:100%; padding:12px; margin:8px 0 18px; border:1px solid #ddd; border-radius:8px;">

                <label>Password</label><br>
                <input type="password" name="password" required style="width:100%; padding:12px; margin:8px 0 18px; border:1px solid #ddd; border-radius:8px;">

                <button type="submit" name="register" class="btn" style="width:100%;">Register</button>
            </form>

            <p class="center small" style="margin-top:20px;">
                Already have an account? <a href="login.php" style="color:var(--primary); font-weight:600;">Login here</a>
            </p>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>