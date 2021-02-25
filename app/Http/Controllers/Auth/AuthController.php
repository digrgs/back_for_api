<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\LoginPostRequest;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{


    public function login(LoginPostRequest $request)
    {

        $input = $request->all();
        $route = 'login';
        $url = $this->api . $route;
        $response = Http::post($url, [
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        if ($response->clientError()) {
            //  dd($response['errors']);
            if ($response['status'] == 422) {
                return redirect()->to('/login')->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash($response['message'])->error()->important();
                return redirect()->to('/login');
            }
        }

        $body = json_decode($response->body());
        $data = $body->data;
        $token = $data->token;
        $user = $data->user;
        $route = 'profiles/my_profile';
        $url = $this->api . $route;
        $response = Http::withToken($token)->get($url, []);

        if ($response->clientError()) {
            //  dd($response['errors']);
            if ($response['status'] == 422) {
                return redirect()->to('/login')->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash($response['message'])->error();
                return redirect()->to('/login');
            }
        }

        $body = json_decode($response->body(), true);
        $data = $body['data'];
        $user = $data;

        $request->session()->put('user_token', $token);
        $request->session()->put('user_auth', $user);
        flash('Bem-vindo ' . $user['name'])->success();
        flash($user['name'] . ' você acessou como ' . $user['type_name'])->success();
        return redirect()->to('/dashboard');
    }

    public function dashboard(Request $request)
    {

        $user = $request->session()->get('user_auth');
        $token = $request->session()->get('user_token');
        $route = 'profiles/my_profile';
        $url = $this->api . $route;
        $response = Http::withToken($token)->get($url, []);

        if ($response->clientError()) {
            if ($response['status'] == 422) {
                return redirect()->to('/admin/settings')->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash($response['message'])->error();
                return redirect()->to('/admin/settings');
            }
        }

        $body = json_decode($response->body());
        $data = $body->data;
        $user = $data;

        $route = 'enterprises';
        $url = $this->api . $route;
        $response = Http::withToken($token)->get($url, []);

        if ($response->clientError()) {
            if ($response['status'] == 422) {
                return redirect()->to('/admin/settings')->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash($response['message'])->error();
                return redirect()->to('/admin/settings');
            }
        }

        $body = json_decode($response->body());
        $data = $body->data;
        $enterprises = $data;

        return view('dash', ['user' => $user, 'enterprises' => $enterprises]);
    }

    public function settings(Request $request)
    {

        $user = $request->session()->get('user_auth');
        $token = $request->session()->get('user_token');
        $route = 'profiles/my_profile';
        $url = $this->api . $route;
        $response = Http::withToken($token)->get($url, []);

        if ($response->clientError()) {
            if ($response['status'] == 422) {
                return redirect()->to('/admin/settings')->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash($response['message'])->error();
                return redirect()->to('/admin/settings');
            }
        }

        $body = json_decode($response->body());
        $data = $body->data;
        $user = $data;
        return view('admin.settings', ['user' => $user]);
    }

    public function passwordReset(Request $request)
    {
        $user = $request->session()->get('user_auth');
        $token = $request->session()->get('user_token');
        $route = 'profiles/my_profile';
        $url = $this->api . $route;
        $response = Http::withToken($token)->get($url, []);

        if ($response->clientError()) {
            if ($response['status'] == 422) {
                return redirect()->to('/admin/settings')->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash($response['message'])->error();
                return redirect()->to('/admin/settings');
            }
        }

        $body = json_decode($response->body());
        $data = $body->data;
        $user = $data;
        return view('vendor.adminlte.auth.passwords.reset', ['user' => $user, 'token' => $token]);
    }

    public function logout(Request $request)
    {
        $token = $request->session()->get('user_token');

        $route = 'logout';
        $url = $this->api . $route;
        $response = Http::withToken($token)->get($url, []);

        if ($response->clientError()) {

            if ($response['status'] == 422) {
                return redirect()->back()->withErrors($response['errors']);
            }
            if ($response['status'] != 422) {
                flash('Logout falhou ')->error();
                return redirect()->back();
            }
        }


        $request->session()->flush();
        flash('Sessão encerrada por logout')->success();
        return redirect()->to('/login');
    }
}
