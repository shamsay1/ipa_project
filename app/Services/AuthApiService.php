<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;
class AuthApiService
{
    public $geturl = 'https://jsonplaceholder.typicode.com/users';
    protected $posturl = 'https://jsonplaceholder.typicode.com/';
    public function all()
    {
        $response = Http::get($this->geturl);

        return $response->successful() ? $response->json() : [];
    }

    

    public function postapi($endpoint,array $postData){
        $response = Http::post(
            $this->posturl.$endpoint, //posts
            $postData
        );

        return $response->successful() ? $response->json() : [];

    }

    public function find($id)
    {
        $users = $this->all();

        foreach ($users as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }

        return null;
    }
}
