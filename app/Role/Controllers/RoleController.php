<?php

namespace App\Role\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    private int $limit = 10;
    private int $page = 1;
    private string $search = '';

    public function create() {

    }
}
