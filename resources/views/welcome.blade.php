<!DOCTYPE html>
<html>
<head>
    <title>Laravel Skills Test v1.0</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
</head>
<body>
<div class="container">
    <h2>Laravel Skills Test v1.0</h2>
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>

    <form id="productsForm">
        {{ csrf_field() }}
        <div class="form-group">
            <label>Product Name:</label>
            <input type="text" name="product_name" class="form-control" placeholder="Product Name" required>
        </div>


        <div class="form-group">
            <label>Quantity In Stock:</label>
            <input type="text" name="quantity_stock" class="form-control" placeholder="Quantity In Stock" required>
        </div>


        <div class="form-group">
            <strong>Price Per Item:</strong>
            <input type="text" name="price_item" class="form-control" placeholder="Price Per Item" required>
        </div>


        <div class="form-group">
            <button class="btn btn-success btn-submit">Submit</button>
        </div>
    </form>

    <table style="width:100%">
        <tr>
            <th>Product name</th>
            <th>Quantity in stock</th>
            <th>Price per item</th>
            <th>Datetime submitted</th>
            <th>Total value number</th>
        </tr>
        <tbody id="productsTable">

        </tbody>
    </table>
</div>


<script type="text/javascript">


    $(document).ready(function () {
        $("#productsForm").submit(function (e) {
            e.preventDefault()

            let _token = $("input[name='_token']").val(),
                product_name = $("input[name='product_name']").val(),
                quantity_stock = $("input[name='quantity_stock']").val(),
                price_item = $("input[name='price_item']").val()


            $.ajax({
                url: "/products",
                type: 'POST',
                data: {
                    _token: _token,
                    product_name: product_name,
                    quantity_stock: quantity_stock,
                    price_item: price_item
                },
                success: function (data, x) {
                    if (!$.isEmptyObject(data.error)) {
                        return printErrorMsg(data.error);
                    }
                    drawResults(data)
                },
                error: function (r) {
                    printErrorMsg(r.responseText)
                }
            })
        })


        function printErrorMsg(msg) {
            let d = $('.print-error-msg')
            d.find('ul').html('')
            d.show()
            if (typeof msg === 'string') {
                msg = [msg]
            }
            $.each(msg, function (k, v) {
                d.find('ul').append('<li>' + v + '</li>')
            })
        }

        function drawResults(products) {
            let d = $('#productsTable')
            d.html('')
            $.each(products, function (k, v) {
                d.append('<tr>')
                col(d, v['product_name'])
                col(d, v['quantity_stock'])
                col(d, v['price_item'])
                col(d, formatDate(v['submitted_at']))
                col(d, v['quantity_stock'] * v['price_item'])
                d.append('</tr>')
            })
        }

        function col(d, txt) {
            if (txt === 'null' || txt === 'undefined') {
                txt = ''
            }
            d.append('<td>' + txt + '</td>')
        }

        function formatDate(d) {
            var date = new Date(d)
            var hours = date.getHours();
            var minutes = date.getMinutes();
            var ampm = hours >= 12 ? 'pm' : 'am';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            minutes = minutes < 10 ? '0' + minutes : minutes;
            var strTime = hours + ':' + minutes + ' ' + ampm;
            return date.getMonth() + 1 + "/" + date.getDate() + "/" + date.getFullYear() + "  " + strTime;
        };
    });


</script>


</body>
</html>