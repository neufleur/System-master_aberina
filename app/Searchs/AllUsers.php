<?php
namespace App\Searchs;

use App\Models\User\Users;

class AllUsers implements DisplayUsers{

  public function resultUsers($keyword, $category, $updown, $gender, $role, $subjects){
    $users = User::all();
    return $users;
  }


}