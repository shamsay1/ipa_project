<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AuthApiService;
class ApiController extends Controller
{
    protected $api;

    public function __construct(AuthApiService $api)
    {
        $this->api = $api;
    }

    public function index()
    {
        $users = $this->api->all();
        return view('api', compact('users'));
    }

    public function show($id)
    {
        $user = $this->api->find($id);

        if (!$user) {
            abort(404);
        }

        return view('show', compact('user'));
    }
}
