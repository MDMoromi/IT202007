<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}
?>

<?php
//$query = "SELECT * FROM Products WHERE quantity > 0 ORDER BY CREATED DESC LIMIT 10";
$db = getDB();
	$stmt = $db->prepare("SELECT * FROM Products WHERE quantity > 0 ORDER BY CREATED DESC LIMIT 10");
    $r = $stmt->execute();
    if ($r) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    else {
        flash("There was a problem fetching the results");
    }
?>

<div class="results">
    <div class="list-group">
        <?php foreach ($results as $r): ?>
            <div class="list-group-item">
                <div class="product-div">
                    <div>Name:</div>
                    <div><?php safer_echo($r["name"]); ?></div>
                </div>
                <div class="product-div">
                    <div>Quantity:</div>
                    <div><?php safer_echo($r["quantity"]); ?></div>
                </div>
                <div class="product-div">
                    <div>Price:</div>
                    <div><?php safer_echo($r["price"]); ?></div>
                </div>
                <div class="product-div">
                    <div>Description:</div>
                    <div><?php safer_echo($r["description"]); ?></div>
                </div>
                <div class="add-view-div">
                    <a type="button" href="view_products.php?id=<?php safer_echo($r['id']); ?>">View</a>
					<form method="post">
						<input type="hidden" name="product_id" value="<?php echo $r['id'];?>"/>
						<input type="submit" value="Add to Cart"/>
					</form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>