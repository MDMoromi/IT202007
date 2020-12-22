<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();
if(isset($_POST["checkout"])){
    //$stmt = $db->prepare("DELETE FROM Cart where id = :id");
    //$r = $stmt->execute([":id"=>$_POST["cartId"]]);
    //if($r){
    //    flash("Deleted item from cart", "success");
    //}
}

$stmt = $db->prepare("SELECT c.id, p.name, c.price, c.quantity, (c.price * c.quantity) as sub from Cart c JOIN Products p on c.product_id = p.id where c.user_id = :id");
$stmt->execute([":id"=>get_user_id()]);
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
?>

<div class="container-fluid">
    <h3>Checkout</h3>
        <div class="list-group">
        <?php if($results && count($results) > 0):?>
            <?php foreach($results as $r):?>
            <div class="list-group-item">
                <form method="POST">
                <div class="row">
					<div class="col">
                       Product
                    </div>
                    <div class="col">
                        <?php echo $r["name"];?>
                    </div>
					<div class="col">
                       Price
                    </div>
                    <div class="col">
                        <?php echo $r["price"];?>
                    </div>
					<div class="col">
                       Quantity
                    </div>
                    <div class="col">
						<?php echo $r["quantity"];?>
                    </div>
					<div class="col">
                       Subtotal for Product
                    </div>
                    <div class="col">
                        <?php echo $r["sub"];?>
                    </div>
					<?php $total += (float)$r["sub"];?>
					<br/>
                </div>
            </div>
            <?php endforeach;?>
			<div class="col">
               Overall Subtotal
            </div>
            <div class="col">
                <?php echo $r["total"];?>
            </div>
        <?php else:?>
        <div class="list-group-item">
            No items in cart
        </div>
        <?php endif;?>
        </div>
    </div>
<?php require(__DIR__ . "/partials/flash.php");