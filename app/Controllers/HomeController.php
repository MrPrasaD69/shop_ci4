<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\OrderDetailModel;
use App\Models\OrderModel;
use App\Models\ProductsModel;

class HomeController extends BaseController
{
    public function dashboard(){
        $products_model = new ProductsModel();
        $order_model = new OrderModel();
        $user_id = $this->session->get('user_id');

        $data['product_data'] = $products_model
        ->where('status','1')
        ->findAll();

        $data['cart_detail'] = $order_model
        ->join('tbl_order_detail','tbl_order.id=tbl_order_detail.fk_order_id AND tbl_order_detail.status="1"')
        ->where('tbl_order.order_status','P')
        ->where('tbl_order.status','1')
        ->countAllResults();

        return view('dashboard',$data);
    }

    public function addToCart(){
        $user_id = $this->session->get('user_id');
        $product_id = $this->request->getPost('product_id');
        $product_model = new ProductsModel();
        $order_model = new OrderModel();
        $order_detail_model = new OrderDetailModel();

        //Check Product Existence + Quantity
        $product_data = $product_model
        ->where('id',$product_id)
        ->where('status','1')
        ->first();
        if(empty($product_data)){
            return response()->setJSON([
                'status'    =>false,
                'message'   =>'Product not Found!'
            ]);
        }

        if($product_data['product_quantity'] < 1){
            return response()->setJSON([
                'status'    =>false,
                'message'   =>'Product out of Stock!'
            ]);
        }

        //Check User's Cart Detail
        $check_cart = $order_model
        ->where('fk_user_id',$user_id)
        ->where('order_status','P')
        ->where('status','1')
        ->first();

        $timeStamp = date('Y-m-d H:i:s');
        //When New Cart
        if(empty($check_cart)){

            //Store Order
            $order_model->save([
                'fk_user_id'    =>$user_id,
                'created_at'    =>$timeStamp,
                'updated_at'    =>$timeStamp
            ]);

            $order_id = $order_model->getInsertID();

            //Store Order Detail
            $order_detail_model->save([
                'fk_order_id'   =>$order_id,
                'fk_product_id' =>$product_id,
                'price'         =>$product_data['product_price'],
                'quantity'      =>1,
                'created_at'    =>$timeStamp,
                'updated_at'    =>$timeStamp                
            ]);

            //Update Product Quantity
            $updated_quantity = $product_data['product_quantity'] - 1;
            $product_model->update($product_data,[
                'product_quantity'  =>$updated_quantity,
            ]);

            return response()->setJSON([
                'status'    => true,
                'message'   =>'Product Added Successfully!'
            ]);
        }
        else{
        //Existing Cart

            $order_id = $check_cart['id'];

            //Store Order Detail
            $order_detail_model->save([
                'fk_order_id'   =>$order_id,
                'fk_product_id' =>$product_id,
                'price'         =>$product_data['product_price'],
                'quantity'      =>1,
                'created_at'    =>$timeStamp,
                'updated_at'    =>$timeStamp                
            ]);

            //Update Product Quantity
            $updated_quantity = $product_data['product_quantity'] - 1;
            $product_model->update($product_data,[
                'product_quantity'  =>$updated_quantity,
            ]);

            return response()->setJSON([
                'status'    => true,
                'message'   =>'Product Added Successfully!'
            ]);
        }
    }

    public function myCart(){
        $user_id = $this->session->get('user_id');
        $order_model = new OrderModel();
        $data = [];

        //Get User's Cart Detail
        $data['cart_data'] = $order_model
        ->select('tbl_order.id as order_id,tbl_order_detail.fk_product_id, tbl_products.product_name,tbl_products.product_price, tbl_products.product_image,
        tbl_products.product_description, COUNT(tbl_order_detail.fk_product_id) as product_qty, SUM(tbl_products.product_price) as product_price_sum')
        ->join('tbl_order_detail','tbl_order.id=tbl_order_detail.fk_order_id AND tbl_order_detail.status="1"')
        ->join('tbl_products','tbl_order_detail.fk_product_id=tbl_products.id AND tbl_products.status="1"')
        ->where('tbl_order.fk_user_id',$user_id)
        ->where('tbl_order.order_status','P')
        ->where('tbl_order.status','1')
        ->groupBy('tbl_order_detail.fk_product_id')
        ->orderBy('tbl_order_detail.created_at','DESC')
        ->findAll();

        //Get Cart Items Total #
        $data['cart_item_total'] = (!empty($data['cart_data']) ? array_sum(array_column($data['cart_data'],'product_qty')) : '0');
        // echo "<pre>";print_r($data);exit;

        return view('myCart',$data);
    }

    public function removeFromCart(){
        $user_id = $this->session->get('user_id');
        $product_id = $this->request->getPost('product_id');
        $order_model = new OrderModel();
        $order_detail_model = new OrderDetailModel();
        $product_model = new ProductsModel();

        //Fetch User's Cart Data
        $order_data = $order_model
        ->select('tbl_order.id as order_id, tbl_order_detail.id as order_detail_id, tbl_order_detail.fk_product_id')
        ->join('tbl_order_detail','tbl_order.id=tbl_order_detail.fk_order_id AND tbl_order_detail.status="1"','LEFT')
        ->where('tbl_order.order_status','P')
        ->where('tbl_order.fk_user_id',$user_id)
        ->where('tbl_order.status','1')
        ->first();
        if(empty($order_data)){
            return response()->setJSON([
                'status'    => false,
                'message'   => 'Order Details Not Found!'
            ]);
        }

        //Delete product from order detail
        if(!empty($order_data['order_detail_id'])){
            $order_detail_model->delete($order_data['order_detail_id']);
        }

        //Check if order still has products
        $remaining_products = $order_detail_model
            ->where('fk_order_id', $order_data['order_id'])
            ->where('status', '1')
            ->countAllResults();

        //Delete order if empty
        if($remaining_products == 0){
            $order_model->delete($order_data['order_id']);
        }

        //Update Product Quantity
        $product_data = $product_model
        ->where('id',$product_id)
        ->where('status','1')
        ->first();
        if(!empty($product_data)){            
            $updated_qty = $product_data['product_quantity'] + 1;
            $product_model->update($product_id,['product_quantity'=>$updated_qty]);
        }

        return response()->setJSON([
            'status'    => true,
            'message'   => 'Product Removed from Cart!'
        ]);
    }
}
