<?php include VIEW_LAYOUT_DIR. '/header.php'; ?>

<div class="container pt-5" style="">

    <div class="row">
        <div class="col-12 col-sm-6 align-items-center justify-content-center">

            <form action="/url" method="POST" id="stockProcessingForm" class="mx-auto card p-4">

                <div class="row">
                    <div class="col-12">
                        <h3 class="text-center">Stock Lookup Form</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="product" class="form-label">Product</label>
                        <select name="product" id="product" class="form-control form-select">
                            <?php foreach ($products as $product) : ?>
                                <option value="<?php echo $product->getId(); ?>"><?php echo $product->getName(); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" step="1" min="1" id="quantity" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-12">
                        <button class="btn btn-secondary float-start my-3" id="checkStock" type="button">Check Stock</button>
                        <button class="btn btn-primary float-end my-3" id="processOrder" type="button">Process Order</button>

                    </div>
                </div>

            </form>

            <div class="row mt-5">
                <div class="col-12">
                    <div id="responsePanel">

                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <div id="notificationPanel">
                        <?php if(isset($_SESSION['notifications'])): ?>
                            <?php foreach($_SESSION['notifications'] as $notification) : ?>
                                <?php $p = $im->getProductById($notification['product_id']); ?>
                                <div class="alert alert-primary"><?php echo sprintf("Low stock: Only %s units of %s remaining", $p->getStockLevel(), $p?->getName())  ?></div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-12 col-sm-6">
            <div class="card p-4">

                <h3 class="text-center">Orders List</h3>

                <?php include(VIEW_DIR. '/components/orders-table.php'); ?>
            </div>
        </div>

    </div>


</div>

<script>

    window.addEventListener("DOMContentLoaded", () => {

        const checkStockButton = document.querySelector("#checkStock");
        const processOrderButton = document.querySelector("#processOrder");

        checkStockButton.addEventListener("click", checkStock)
        processOrderButton.addEventListener("click", processOrder)

    })

    const getFormData = () => {
        const productId = document.querySelector("#product").value;
        const quantity = document.querySelector("#quantity").value;

        if(productId == null) return;//showError
        if(quantity == null) return;//showError

        return {product_id: productId, quantity: quantity};

    }

    const checkStock = () => {

        clearNotifications();
        clearResponses();

        const payload = getFormData();

        console.log({payload: payload})

        return fetch(`/check-stock`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify(payload)
        }).then(res => {
            console.log(res)
            if(res?.status === 200) {
                return res.json().then(res => addSuccessResponse(res))
            } else {
                return res.json().then(res => addErrorResponse(res))
            }

        })
    }



    const processOrder = () => {

        clearNotifications();
        clearResponses();

        const payload = getFormData();

        console.log({payload: payload})

        return fetch(`/process-order`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json'},
            body: JSON.stringify(payload)
        }).then(res => {
            console.log(res)
            if(res?.status === 200) {
                return res.json().then(res => {
                    addSuccessResponse(res);
                    setTimeout(()=> window.location.reload(), 800)
                })
            } else {
                return res.json().then(res => addErrorResponse(res))
            }

        })
    }

    const addNotification = (res) => {

        const notificationPanel = document.querySelector("#notificationPanel");

        const notification = document.createElement("div");
        notification.classList.add("alert", "alert-primary");
        notification.innerText = `${res.message}: ${res.detail ?? ''}`

        notificationPanel.appendChild(notification);
    }

    const addSuccessResponse = (res) => {

        const responsePanel = document.querySelector("#responsePanel");

        const response = document.createElement("div");
        response.classList.add("alert", "alert-success");
        response.innerText = `${res.message}: ${res.detail ?? ''}`

        responsePanel.appendChild(response);
    }

    const addErrorResponse = (res) => {

        const responsePanel = document.querySelector("#responsePanel");

        const response = document.createElement("div");
        response.classList.add("alert", "alert-danger");
        response.innerText = `${res.message}: ${res.detail ?? ''}`

        responsePanel.appendChild(response);
    }

    const clearNotifications = () => {
        const notificationPanel = document.querySelector("#notificationPanel");
        notificationPanel.innerHTML = "";
    }

    const clearResponses = () => {
        const responsePanel = document.querySelector("#responsePanel");
        responsePanel.innerHTML = "";
    }

</script>
























