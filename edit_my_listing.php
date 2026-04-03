<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    die("Listing not found or access denied.");
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = (float)$_POST['price'];
    $image_url = trim($_POST['image_url']);
    $category = trim($_POST['category']);
    $item_condition = trim($_POST['item_condition']);
    $stock = (int)$_POST['stock'];

    $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, image_url=?, category=?, item_condition=?, stock=? WHERE id=? AND seller_id=?");
    $stmt->bind_param("ssdsssiii", $name, $description, $price, $image_url, $category, $item_condition, $stock, $id, $user_id);

    if ($stmt->execute()) {
        header("Location: my_listings.php");
        exit();
    } else {
        $message = "Error updating listing.";
    }
}
?>

<h2>Edit My Listing</h2>

<?php if ($message != ""): ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" value="<?php echo $product['name']; ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required><?php echo $product['description']; ?></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?php echo $product['price']; ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Image URL</label>
        <input type="text" name="image_url" class="form-control" value="<?php echo $product['image_url']; ?>">
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category" class="form-control" required>
            <option value="Electronics" <?php if ($product['category'] == 'Electronics') echo 'selected'; ?>>Electronics</option>
            <option value="Books" <?php if ($product['category'] == 'Books') echo 'selected'; ?>>Books</option>
            <option value="Clothing" <?php if ($product['category'] == 'Clothing') echo 'selected'; ?>>Clothing</option>
            <option value="CDs" <?php if ($product['category'] == 'CDs') echo 'selected'; ?>>CDs</option>
            <option value="Vinyl Records" <?php if ($product['category'] == 'Vinyl Records') echo 'selected'; ?>>Vinyl Records</option>
            <option value="Collectibles" <?php if ($product['category'] == 'Collectibles') echo 'selected'; ?>>Collectibles</option>
            <option value="Other" <?php if ($product['category'] == 'Other') echo 'selected'; ?>>Other</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Condition</label>
        <select name="item_condition" class="form-control" required>
            <option value="Like New" <?php if ($product['item_condition'] == 'Like New') echo 'selected'; ?>>Like New</option>
            <option value="Good" <?php if ($product['item_condition'] == 'Good') echo 'selected'; ?>>Good</option>
            <option value="Used" <?php if ($product['item_condition'] == 'Used') echo 'selected'; ?>>Used</option>
            <option value="Fair" <?php if ($product['item_condition'] == 'Fair') echo 'selected'; ?>>Fair</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="<?php echo $product['stock']; ?>" min="1" required>
    </div>

    <button type="submit" class="btn btn-primary">Update Listing</button>
    <a href="my_listings.php" class="btn btn-secondary">Back</a>
</form>

<?php include 'includes/footer.php'; ?>