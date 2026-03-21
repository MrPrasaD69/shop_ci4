<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Products;
use App\Models\ProductsModel;
use CodeIgniter\HTTP\ResponseInterface;

class HomeController extends BaseController
{
    public function dashboard(){
        $products_model = new ProductsModel();

        $data['product_data'] = $products_model
        ->where('status','1')
        ->findAll();

        return view('dashboard',$data);
    }
}
