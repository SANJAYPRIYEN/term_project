<?php
include 'includes/db.php';
include 'includes/header.php';

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';

$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if ($search !== "") {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $searchTerm = "%" . $search . "%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

if ($category !== "") {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<h2>All Products</h2>

<form method="GET" class="row g-3 mb-4">
    <div class="col-md-5">
        <input type="text" name="search" class="form-control" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>">
    </div>

    <div class="col-md-4">
        <select name="category" class="form-control">
            <option value="">All Categories</option>
            <option value="Electronics" <?php if ($category == 'Electronics') echo 'selected'; ?>>Electronics</option>
            <option value="Books" <?php if ($category == 'Books') echo 'selected'; ?>>Books</option>
            <option value="Clothing" <?php if ($category == 'Clothing') echo 'selected'; ?>>Clothing</option>
            <option value="CDs" <?php if ($category == 'CDs') echo 'selected'; ?>>CDs</option>
            <option value="Vinyl Records" <?php if ($category == 'Vinyl Records') echo 'selected'; ?>>Vinyl Records</option>
            <option value="Collectibles" <?php if ($category == 'Collectibles') echo 'selected'; ?>>Collectibles</option>
            <option value="Other" <?php if ($category == 'Other') echo 'selected'; ?>>Other</option>
        </select>
    </div>

    <div class="col-md-3">
        <button type="submit" class="btn btn-primary w-100">Search / Filter</button>
    </div>

    <div class="col-md-3">
        <a href="products.php" class="btn btn-secondary w-100">Reset</a>
    </div>
</form>

<div class="row">
<?php if ($result->num_rows > 0): ?>
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="<?php echo $row['image_url']; ?>" class="card-img-top" alt="Product Image" style="height: 250px; object-fit: cover;">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?php echo htmlspecialchars($row['name']); ?></h5>
                    <p class="card-text">$<?php echo number_format($row['price'], 2); ?></p>
                    <p class="card-text"><?php echo htmlspecialchars($row['category']); ?></p>
                    <a href="product.php?id=<?php echo $row['id']; ?>" class="btn btn-primary mt-auto">View Details</a>
                </div>
            </div>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p>No products found.</p>
<?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>