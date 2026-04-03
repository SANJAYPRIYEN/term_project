<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['add_to_cart'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = (int)$_POST['quantity'];

    $stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);
    $stmt->execute();
}

if (isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
}

$stmt = $conn->prepare("
    SELECT cart.id AS cart_id, products.name, products.price, cart.quantity
    FROM cart
    JOIN products ON cart.product_id = products.id
    WHERE cart.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
?>

<h2>Your Cart</h2>
<table class="table table-bordered">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): 
        $subtotal = $row['price'] * $row['quantity'];
        $total += $subtotal;
    ?>
    <tr>
        <td><?php echo $row['name']; ?></td>
        <td>$<?php echo $row['price']; ?></td>
        <td><?php echo $row['quantity']; ?></td>
        <td>$<?php echo $subtotal; ?></td>
        <td><a href="cart.php?remove=<?php echo $row['cart_id']; ?>" class="btn btn-danger btn-sm">Remove</a></td>
    </tr>
    <?php endwhile; ?>
</table>

<h4>Total: $<?php echo $total; ?></h4>
<a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>

<?php include 'includes/footer.php'; ?>