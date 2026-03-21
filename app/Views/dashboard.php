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

    .product-image img {
        max-width: 100%;
        height: auto;
        display: block;
        margin: 0 auto;
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
</style>
<div class="container">
    <div class="row text-center mb-5">
        <h1>Dashboard</h1>
    </div>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12">

            <div class="card shadow main-card">
                <div class="card-body">

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
                                    <p class="product-description"><?= (!empty($prod['product_description']) ? $prod['product_description'] : '') ?></p>
                                    <p class="product-qty"> Qty: <strong><?= (!empty($prod['product_quantity']) ? $prod['product_quantity'] : '') ?></strong></p>
                                    <p class="product-price">₹ <?= (!empty($prod['product_price']) ? $prod['product_price'] : '') ?></p>                                    
                                    <button class="add-to-cart">+ Cart</button>
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
        var loginBtn = $("#loginBtn");

        $('#loginForm').on('submit', function(e) {
            e.preventDefault();

            let formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: formData,
                beforeSend: function() {
                    loginBtn.prop('disabled', true).html('Logging in...');
                },
                success: function(response) {
                    loginBtn.prop('disabled', false).html('Login');

                    if (response.status) {
                        window.location.href = response.redirect;
                    } else {
                        $('#loginError').text(response.message);
                    }

                },
                error: function(xhr) {
                    loginBtn.prop('disabled', false).html('Login');

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let firstError = Object.values(errors)[0][0];
                        $('#loginError').text(firstError);
                    } else {
                        $('#loginError').text("Something went wrong.");
                    }

                }
            });

        });
    });
</script>
<?= $this->endSection() ?>