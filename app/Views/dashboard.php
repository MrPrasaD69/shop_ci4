<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<style>
    body {
        background: #ffffff;
    }

    .main-card {
        border: none;
        border-radius: 15px;
    }

    .main-card .card-body {
        padding: 2rem;
    }

    .form-control {
        border-radius: 10px;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;                 /* space between cards */
        width: 100%;
        box-sizing: border-box;
    }

    .product-card {
        flex: 0 0 calc((100% - 50px) / 6); /* 10 items per row (9 gaps × 10px = 90px) */
        box-sizing: border-box;
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
        background: #fff;
        border-radius: 6px;
    }

    .product-image{
        border:2px solid grey;
        border-radius: 2%;
    }
    .product-image img {
        max-width: 100%;
        width: auto;
        height: 100px;
        display: block;
        margin: 0 auto;
        border-radius: 2%;
    }

    .product-title {
        font-size: 16px;
        margin: 10px 0 5px;
    }

    .product-description {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
    }

    .product-qty {
        font-size: 12px;
        margin-bottom: 5px;
    }

    .add-to-cart {
        background-color: #28a745;
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .add-to-cart:hover {
        background-color: #218838;
    }

    /* Tablet: 5 per row */
    @media (max-width: 1200px) {
        .product-card {
            flex: 0 0 calc((100% - 40px) / 5); /* 4 gaps × 10px */
        }
    }

    /* Mobile: 2 per row */
    @media (max-width: 768px) {
        .product-card {
            flex: 0 0 calc((100% - 10px) / 2); /* 1 gap × 10px */
        }
    }

    /* Small mobile: 1 per row */
    @media (max-width: 480px) {
        .product-card {
            flex: 0 0 100%;
        }
    }

    /* Card Styling */
    .cart-wrapper {
        position: relative;
        display: inline-block;
    }

    .cart-icon {
        position: relative;
        font-size: 20px;
        color: #333;
        text-decoration: none;
    }

    .cart-icon:hover {
        color: #28a745;
    }

    .cart-count-empty {
        position: absolute;
        top: -8px;
        right: -10px;
        background: red;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        padding: 3px 7px;
        border-radius: 50%;
        line-height: 1;
        min-width: 18px;
        text-align: center;
    }

    .cart-count-fill {
        position: absolute;
        top: -8px;
        right: -10px;
        background: green;
        color: #fff;
        font-size: 12px;
        font-weight: bold;
        padding: 3px 7px;
        border-radius: 50%;
        line-height: 1;
        min-width: 18px;
        text-align: center;
    }
    /* Card Styling */
</style>
<div class="container">
    <div class="row text-center mb-5">
        <h1>Dashboard</h1>
    </div>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12">

            <div class="card shadow main-card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-lg-10"></div>
                        <div class="col-lg-2 text-end">
                            <div class="cart-wrapper">
                                <a href="<?= base_url('/myCart') ?>" class="cart-icon">Your Items : 
                                    <i class="bi bi-cart3"></i>
                                    <span class="<?= (!empty($cart_detail) ? 'cart-count-fill' : 'cart-count-empty') ?>"><?= (!empty($cart_detail) ? $cart_detail : '0') ?></span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="product-grid">
                        <?php
                        if(!empty($product_data)){
                            foreach($product_data as $prod){
                            ?>
                            <div class="product-card">
                                <div class="product-image">                            
                                    <img src="<?= (!empty($prod['product_image']) ? $prod['product_image'] : '') ?>" alt="Product Name">
                                </div>
                                <div class="product-details">
                                    <h2 class="product-title"><?= (!empty($prod['product_name']) ? $prod['product_name'] : '') ?></h2>
                                    <p class="product-description" title="<?= $prod['product_description'] ?>">
                                        <?php $word_count = 50; ?>
                                        <?= mb_strlen($prod['product_description']) > $word_count 
                                        ? mb_substr($prod['product_description'], 0, $word_count) . '...' 
                                        : $prod['product_description']; ?>
                                    </p>
                                    <p class="product-qty"> Qty: <strong><?= (!empty($prod['product_quantity']) ? $prod['product_quantity'] : '') ?></strong></p>
                                    <p class="product-price">₹ <?= (!empty($prod['product_price']) ? $prod['product_price'] : '') ?></p>                                    
                                    <button class="add-to-cart addToCartBtn" data-val="<?= (!empty($prod['id']) ? $prod['id'] : '') ?>">+ Cart</button>
                                </div>
                            </div>
                            <?php
                            }
                        }
                        ?>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

        $(document).on('click',".addToCartBtn",function(e){
            e.preventDefault();
            var addToCartBtn = $(this);
            var product_id = $(this).data('val');

            $.ajax({
                url: "<?= base_url('/addToCart') ?>",
                type: "POST",
                data: {
                    product_id:product_id
                },
                beforeSend: function(){
                    addToCartBtn.prop('disabled', true).html('Adding...');
                },
                success: function(response){
                    addToCartBtn.prop('disabled', false).html('+ Cart');

                    if(response.status){
                        Swal.fire({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            showConfirmButton: false,
                            timer:1000
                        }).then(()=>{
                            window.location.reload();
                        });
                    }
                    else{
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message
                        });
                    }

                },
                error: function(xhr){
                    addToCartBtn.prop('disabled', false).html('+ Cart');

                    if(xhr.responseJSON && xhr.responseJSON.errors){
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        $('#errorDiv').text(firstError);
                    }
                    else{
                        $('#errorDiv').text("Something went wrong.");
                    }

                }
            });
        });
    });
</script>
<?= $this->endSection() ?>