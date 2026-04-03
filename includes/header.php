<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Second-Hand Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/secondhand_marketplace/css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/secondhand_marketplace/index.php">Marketplace</a>
        <div>
            <a class="btn btn-outline-light me-2" href="/secondhand_marketplace/products.php">Products</a>
            <a class="btn btn-outline-light me-2" href="/secondhand_marketplace/cart.php">Cart</a>

         <?php if (isset($_SESSION['user_id'])): ?>
    <a class="btn btn-outline-light me-2" href="/secondhand_marketplace/sell.php">Sell Item</a>
    <a class="btn btn-outline-light me-2" href="/secondhand_marketplace/my_listings.php">My Listings</a>
    <a class="btn btn-outline-light me-2" href="/secondhand_marketplace/order_history.php">Orders</a>

    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
        <a class="btn btn-warning me-2" href="/secondhand_marketplace/admin/dashboard.php">Admin</a>
    <?php endif; ?>

    <a class="btn btn-danger" href="/secondhand_marketplace/logout.php">Logout</a>
<?php else: ?>
                <a class="btn btn-success me-2" href="/secondhand_marketplace/login.php">Login</a>
                <a class="btn btn-primary" href="/secondhand_marketplace/register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
    <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
    <a class="btn btn-warning me-2" href="/secondhand_marketplace/admin/dashboard.php">Admin</a>
<?php endif; ?>
</nav>
<div class="container mt-4">