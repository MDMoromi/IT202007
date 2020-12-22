<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();
if(isset($_POST["checkout"])){
	$stmt = $db->prepare("SELECT ifnull(max(order_id),0) INTO Orders(order_id)");
	$r = $stmt->execute([":order_id"=>$_POST["order_id"]]);
    $stmt = $db->prepare("INSERT INTO Orders(processor,price,quantity,user_id,product_id) VALUES(:processor,:total,:quantity,:user_id,:product_id");
    $r = $stmt->execute([":processor,:total,:quantity,:user_id,:product_id"=>$_POST["order_id"]]);
    if($r){
        flash("Order has been placed", "success");
    }
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
                <?php echo $total;?>
            </div>
        <?php else:?>
        <div class="list-group-item">
            No items in cart
        </div>
        <?php endif;?>
    </div>
	<div>
		<form method="POST">
			<label for="processor">Payment Processor:</label>
			<select id="processor" required name="processor">
				<option value="paypal">Paypal</option>
				<option value="visa">Visa</option>
				<option value="mastercard">Mastercard</option>
				<option value="amex">American Express</option>
			</select>
			<input type="text" name="street" required placeholder="Street Address"/>
			<input type="text" name="city" required placeholder="City"/>
			<input type="text" name="state" required placeholder="State"/>
			<input type="text" name="zipcode" required placeholder="zipcode" pattern="[0-9]*"/>
			<input type="submit" class="btn btn-success" name="checkout" value="Checkout"/>
		</form>
	<div>
</div>
<?php require(__DIR__ . "/partials/flash.php");