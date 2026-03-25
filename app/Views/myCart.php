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

    .remove-cart {
        background-color: #b81f1f;
        color: #fff;
        border: none;
        padding: 8px 14px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.2s ease;
    }

    .remove-cart:hover {
        background-color: #2c2c2c;
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
        <h1>Your Cart <i class="bi bi-cart3"></i><?= $cart_item_total ?>
        </h1>
    </div>
    <div class="row justify-content-center align-items-center">
        <div class="col-md-12">

            <div class="card shadow main-card">
                <div class="card-body">

                    <div class="product-grid">
                        <?php
                        if(!empty($cart_data)){
                            foreach($cart_data as $prod){
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
                                    <p class="product-qty"> Qty: <strong><?= (!empty($prod['product_qty']) ? $prod['product_qty'] : '') ?> x [ <?= $prod['product_price'] ? $prod['product_price'] : '' ?> ]</strong></p>
                                    <p class="product-price">₹ <?= (!empty($prod['product_price_sum']) ? $prod['product_price_sum'] : '') ?></p>                                    
                                    <button class="remove-cart removeCartBtn" data-val="<?= (!empty($prod['fk_product_id']) ? $prod['fk_product_id'] : '') ?>">-</button>
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
    $(document).on("click",".removeCartBtn",function(){
        var product_id = $(this).data('val');
        var removeBtn = $(this);
        if(product_id){
            Swal.fire({
                title: "Do you want to remove?",
                showCancelButton: true,
                confirmButtonText: "Yes",
            }).then((result) =>{
                if(result.isConfirmed){
                    $.ajax({
                        url:'<?= base_url('/removeFromCart') ?>',
                        type:'POST',
                        data:{
                            product_id:product_id
                        },
                        beforeSend:function(){
                            removeBtn.prop('disabled',true);
                        },
                        success:function(resp){
                            removeBtn.prop('disabled',false);
                            if(resp.status){
                                Swal.fire({
                                    icon: "success",
                                    title: "Success",
                                    text: resp.message,
                                    timer:1000
                                }).then(()=>{
                                    window.location.reload();
                                })
                            }
                            else{
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: resp.message
                                });
                            }
                        },
                        error:function(err){
                            removeBtn.prop('disabled',false);
                            console.log(err);
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something Went Wrong!"
                            });
                        }
                    });
                }
            });
        }
    });
</script>
<?= $this->endSection() ?>