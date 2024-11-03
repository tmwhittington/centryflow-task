<table class="table table-striped">
    <tr>
        <th>ID</th>
        <th>Product ID</th>
        <th>Quantity</th>
        <th>Time</th>
    </tr>
    <?php foreach($orders as $order) : ?>
        <tr>
            <th><?php echo $order->getId() ?></th>
            <th><?php echo $im->getProductById($order->getProductId())?->getName() ?></th>
            <th><?php echo $order->getQuantity() ?></th>
            <th><?php echo date("Y-m-d H:i:s", $order->getTimestamp()) ?></th>
        </tr>

    <?php endforeach; ?>
</table>
