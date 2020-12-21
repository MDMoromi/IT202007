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
                    <div>
                        <div>Name:</div>
                        <div><?php safer_echo($r["name"]); ?></div>
                    </div>
                    <div>
                        <div>Quantity:</div>
                        <div><?php safer_echo($r["quantity"]); ?></div>
                    </div>
                    <div>
                        <div>Price:</div>
                        <div><?php safer_echo($r["price"]); ?></div>
                    </div>
                    <div>
                        <div>Description:</div>
                        <div><?php safer_echo($r["description"]); ?></div>
                    </div>
                    <div>
                        <a type="button" href="add_to_cart.php?id=<?php safer_echo($r['id']); ?>">Add to Cart</a>
                        <a type="button" href="view_products.php?id=<?php safer_echo($r['id']); ?>">View</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No results</p>
    <?php endif; ?>
</div>