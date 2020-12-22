<?php require_once(__DIR__ . "/partials/nav.php"); ?>

<?php
if (!is_logged_in()) {
    //this will redirect to login and kill the rest of this script (prevent it from executing)
    flash("You must be logged in to access this page");
    die(header("Location: login.php"));
}

$db = getDB();
if(isset($_POST["checkout"])){
	//get new order ref
	$stmt = $db->prepare("SELECT IFNULL(MAX(order_id),0) as om FROM Orders");
	$stmt->execute();
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	$max = (int)$result["om"];
	$max++;
	
	//get product info
	$stmt->prepare("SELECT p.id, p.price, c.quantity, p.price*c.quantity as subtotal From Cart c JOIN Products p on c.product_id = p.id where c.user_id = :uid");
	$stmt->execute([":uid"=>get_user_id()]);
	$purchases = $stmt->fetchAll(FETCH_ASSOC);
	
	foreach($purchases as $p){
		//insert line item to order
		$stmt = $db->("INSERT INTO Order (order_id, product_id, quantity, price, user_id, add, pay) VALUES(:oid, :pid, :q, :p, :uid, :add, :pay)");
		$stmt->execute([
		":oid"=>$max,
		":pid"=>$p["id"],
		":q"=>$p["quantity"],
		":p"=>$p["price"],
		":uid"=>get_user_id();
		":add"=>$address,
		":pay"=>$processor]);
		
		//update quantity
		$stmt = $db->prepare("UPDATE Products set quantity = quantity - :q WHERE id = :id");
		$stmt->execute([
		":q"=>$p["quantity"],
		":id"=>$p["id"]);
	}
	
	//clear cart
	$stmt = $db->prepare("DELETE FROM Cart where user_id = :uid");
	$stmt->execute([":uid"=>get_user_id()]);
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