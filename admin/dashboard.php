<?php
session_start();
include '../includes/db.php';

if (!isset($_SESSION['admin_id'])) {
    die("Access denied.");
}
?>

<h1>Admin Dashboard</h1>
<p>Welcome, <?php echo $_SESSION['admin_name']; ?>!</p>

<ul>
    <li><a href="product.php">Manage Products</a></li>
    <li><a href="orders.php">Manage Orders</a></li>
    <li><a href="logout.php">Logout</a></li>
</ul>