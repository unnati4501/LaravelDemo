<!DOCTYPE html>
<html>
<head>
    <title>Products Listing (Laravel + DataTable)</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
</head>
<body class="p-4">

<div class="container">
    <h2>Product List (with MVC + DataTable)</h2>
    <div class="d-flex justify-content-between mb-3">
        <div>
            <label>Filter by Category:</label>
            <select id="categoryFilter" class="form-select w-auto d-inline-block">
                <option value="">All</option>
                <option value="Electronics">Electronics</option>
                <option value="Clothing">Clothing</option>
                <option value="Furniture">Furniture</option>
            </select>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-success">+ Add Product</a>
    </div>

    <table class="table table-bordered" id="productTable">
        <thead>
        <tr>
            <th>#</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price (₹)</th>
            <th>Quantity</th>
            <th>Actions</th>
        </tr>
        </thead>
    </table>
</div>

<script type="text/javascript">
$(function () {
    var table = $('#productTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('products.list') }}",
            data: function (d) {
                d.category = $('#categoryFilter').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false },
            { data: 'name', name: 'name' },
            { data: 'category', name: 'category' },
            { data: 'price', name: 'price' },
            { data: 'quantity', name: 'quantity' },
            { data: 'actions', name: 'actions', orderable:false, searchable:false }
        ]
    });

    $('#categoryFilter').change(function() {
        table.draw();
    });

    // Delete Action
    $(document).on('click', '.deleteBtn', function(){
        if(confirm("Are you sure you want to delete this product?")) {
            var id = $(this).data('id');
            $.ajax({
                url: '/products/' + id,
                type: 'DELETE',
                data: { _token: '{{ csrf_token() }}' },
                success: function(res) {
                    if(res.success){
                        table.ajax.reload();
                        alert("Product deleted successfully!");
                    }
                }
            });
        }
    });
});
</script>
</body>
</html>
