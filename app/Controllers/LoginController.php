<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsersModel;

class LoginController extends BaseController
{
    public function index(){
        return view('login');
    }

    public function attemptLogin(){
        $email = $this->request->getPost('email') ?? null;
        $password = $this->request->getPost('password') ?? null;
        $users_model = new UsersModel();

        if(empty($email) || empty($password)){
            return response()->setJSON([
                'status'    =>false,
                'message'   =>'Credentials Missing!'
            ]);
        }

        $check_user = $users_model
        ->where('email',$email)
        ->where('status','1')
        ->first();
        if(empty($check_user)){
            return response()->setJSON([
                'status'    =>false,
                'message'   =>'User not Found!'
            ]);
        }

        if(md5($password) != $check_user['password']){
            return response()->setJSON([
                'status'    =>false,
                'message'   =>'Invalid Email or Password!'
            ]);
        }

        //Set Session
        $this->session->set([
            'user_id'   => $check_user['id'],
            'username'  => $check_user['first_name']." ".$check_user['last_name'],
            'email'     => $check_user['email']
        ]);

        return response()->setJSON([
            'status'    =>true,
            'message'   =>'Login Success',
            'redirect'  =>base_url('/dashboard')
        ]);        
    }

    public function logout(){
        $this->session->destroy();

        return redirect()->to('/login');
    }
}
