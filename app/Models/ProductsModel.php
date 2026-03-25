<?php

namespace App\Models;

use CodeIgniter\Model;

class ProductsModel extends Model
{
    protected $table            = 'tbl_products';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['product_name','product_price','product_description','product_quantity','product_image','created_at','status'];
}
