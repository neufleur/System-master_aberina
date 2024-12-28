<?php
namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

class AdminController extends Controller

{
    public function dashboard()
    {
        return view('admin.dashboard');  //講師
    }

    public function users()
    {
         return view('admin.users'); //生徒
    } // 他の管理者専用アクション
    }