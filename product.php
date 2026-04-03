<?php
include 'includes/db.php';
include 'includes/header.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

#$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
#$stmt->bind_param("i", $id);
#$stmt->execute();
#$result = $stmt->get_result();
#$product = $result->fetch_assoc();

$stmt = $conn->prepare("
    SELECT products.*, users.name AS seller_name
    FROM products
    LEFT JOIN users ON products.seller_id = users.id
    WHERE products.id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
if (!$product) {
    echo "<p>Product not found.</p>";
    include 'includes/footer.php';
    exit();
}
?>

<h2><?php echo $product['name']; ?></h2>
<img src="<?php echo $product['image_url']; ?>" class="img-fluid mb-3" style="max-width: 300px;">
<p><strong>Description:</strong> <?php echo $product['description']; ?></p>
<p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
<p><strong>Category:</strong> <?php echo $product['category']; ?></p>
<p><strong>Condition:</strong> <?php echo $product['item_condition']; ?></p>
<p><strong>Seller:</strong> <?php echo $product['seller_name'] ? $product['seller_name'] : 'Unknown'; ?></p>

<form method="POST" action="cart.php">
    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
    <input type="number" name="quantity" value="1" min="1" class="form-control w-25 mb-3">
    <button type="submit" name="add_to_cart" class="btn btn-success">Add to Cart</button>
</form>

<?php include 'includes/footer.php'; ?>