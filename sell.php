<?php
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
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
    $seller_id = $_SESSION['user_id'];

    if ($name == "" || $description == "" || $category == "" || $item_condition == "" || $price <= 0 || $stock < 1) {
        $message = "Please fill all fields correctly.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, image_url, category, item_condition, stock, seller_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdsssii", $name, $description, $price, $image_url, $category, $item_condition, $stock, $seller_id);

        if ($stmt->execute()) {
            $message = "Item listed successfully.";
        } else {
            $message = "Error: " . $stmt->error;
        }
    }
}
?>

<h2>List Item for Sale</h2>

<?php if ($message != ""): ?>
    <div class="alert alert-info"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required></textarea>
    </div>

    <div class="mb-3">
        <label class="form-label">Price</label>
        <input type="number" step="0.01" name="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Image URL</label>
        <input type="text" name="image_url" class="form-control" placeholder="images/iphone12.jpg">
    </div>

    <div class="mb-3">
        <label class="form-label">Category</label>
        <select name="category" class="form-control" required>
            <option value="">Select Category</option>
            <option value="Electronics">Electronics</option>
            <option value="Books">Books</option>
            <option value="Clothing">Clothing</option>
            <option value="CDs">CDs</option>
            <option value="Vinyl Records">Vinyl Records</option>
            <option value="Collectibles">Collectibles</option>
            <option value="Other">Other</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Condition</label>
        <select name="item_condition" class="form-control" required>
            <option value="">Select Condition</option>
            <option value="Like New">Like New</option>
            <option value="Good">Good</option>
            <option value="Used">Used</option>
            <option value="Fair">Fair</option>
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" class="form-control" value="1" min="1" required>
    </div>

    <button type="submit" class="btn btn-primary">List Item</button>
</form>

<?php include 'includes/footer.php'; ?>