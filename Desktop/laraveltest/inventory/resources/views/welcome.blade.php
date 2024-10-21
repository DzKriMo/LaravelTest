<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Product Inventory Form</h2>

    <form id="productForm" class="mb-4">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity in Stock</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Price per Item</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <h3>Product Inventory List</h3>
    <table class="table table-bordered table-striped" id="productTable">
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Quantity in Stock</th>
                <th>Price per Item</th>
                <th>Datetime Submitted</th>
                <th>Total Value</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
           
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Total:</strong></td>
                <td id="totalSum"></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Load initial product data
        loadProducts();

        // Handle form submission
        $('#productForm').on('submit', function(event) {
            event.preventDefault();

            $.ajax({
                url: '/products',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    loadProducts();
                    $('#productForm')[0].reset();
                }
            });
        });

        function loadProducts() {
            $.get('/products', function(products) {
                let tableBody = $('#productTable tbody');
                tableBody.empty();
                let totalSum = 0;

                products.forEach(product => {
                    let totalValue = product.quantity * product.price;
                    totalSum += totalValue;

                    tableBody.append(`
                        <tr>
                            <td>${product.name}</td>
                            <td>${product.quantity}</td>
                            <td>${product.price.toFixed(2)}</td>
                            <td>${new Date(product.created_at).toLocaleString()}</td>
                            <td>${totalValue.toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn" data-id="${product.id}">Edit</button>
                            </td>
                        </tr>
                    `);
                });

                $('#totalSum').text(totalSum.toFixed(2));
            });
        }
    });
</script>
</body>
</html>
