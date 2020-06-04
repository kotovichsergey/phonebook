<?php

namespace App\Controllers;

use App\Models\Users;
use App\Models\Phones;
use Respect\Validation\Validator as v;

class UserController extends Controller {

    public function index($request, $response) {
        $user = Users::with('phones')->get();
        if ($user->count()) {            
            return $this->response->withJson($user);
        }
        return $this->response->withJson(['msg' => 'Не удалось получить данные телефонной книги.']);
    }
    public function createUser($request, $response) {
        $validation = $this->validator->validate($request, [
            'login' => v::noWhitespace()->notEmpty(),
            'password' => v::noWhitespace()->notEmpty(),
            'email' => v::noWhitespace()->notEmpty()->email()
        ]);
        $errors = $validation->failed();
        if ($errors) {
            foreach ($errors as $field => $error)
                    $err[$field] = $error[0];
            return $this->response->withJson($err);
        }
        $userdata = [
            'login' => $request->getParam('login'),
            'password' => $request->getParam('password'),
            'email' => $request->getParam('email')
        ];
        
        if(!Users::where('email', $userdata['email'])->exists()) {
            $user = Users::create($userdata);
            $user = Users::find($user->id);
            return $this->response->withJson($user);
        } else 
            return $this->response->withJson(['msg' => 'Пользователь с таким email уже существует']);                            
    }
    public function deleteUser($request, $response) {
        $id = $request->getAttribute('id');
        if (Users::where('id', $id)->exists()) 
            $user = Users::destroy($id);
        else
            return $this->response->withJson(['msg' => 'Нет такого пользователя']);        
        if ($user) {
            return $this->response->withJson(['msg' => 'Удалён пользователь с ID = '.$id]);
        }
        return $this->response->withJson(['msg' => 'Ошибка. Попробуйте позже.']);
    }
    public function userUpdate($request, $response) {
        $id = $request->getAttribute('id'); 
        $fields = $request->getParams();
        $fieldvalidate = [];
        $userdata = [];
        if(Users::where('id', $id)->exists()) {
            if(array_key_exists('login', $fields)) {
                $fieldvalidate['login'] = v::noWhitespace()->notEmpty(); 
                $userdata['login'] = $fields['login'];
            }
            if(array_key_exists('password', $fields)) {
                $fieldvalidate['password'] = v::noWhitespace()->notEmpty();
                $userdata['password'] = $fields['password'];  
            }
            if(array_key_exists('email', $fields)) {
                if(!Users::where('email', $fields['email'])->exists()) { 
                    $fieldvalidate['email'] = v::noWhitespace()->notEmpty()->email();
                    $userdata['email'] = $fields['email']; 
                } else 
                    return $this->response->withJson(['msg' => 'Email '.$fields['email'].' уже используется.']);
            }
            if(array_key_exists('name', $fields)) {
                $fieldvalidate['name'] = v::noWhitespace()->notEmpty();
                $userdata['name'] = $fields['name'];  
            }
            if(array_key_exists('surname', $fields)) {
                $fieldvalidate['surname'] = v::noWhitespace()->notEmpty();
                $userdata['surname'] = $fields['surname'];  
            }
            if($fieldvalidate)
                $validation = $this->validator->validate($request, $fieldvalidate);
            else
                 return $this->response->withJson(['msg' => 'Укажите параметры для изменения']);
            $errors = $validation->failed();
            if ($errors) {
                foreach ($errors as $field => $error)
                        $err[$field] = $error[0];
                return $this->response->withJson($err);
            }
            $user = Users::find($id)->update($userdata);
            if($user) {
                $user = Users::with('phones')->find($id);
                return $this->response->withJson($user);
            }
            return $this->response->withJson(['msg' => 'Произошла ошибка. Попробуйте позже']);
        } else 
            return $this->response->withJson(['msg' => 'Нет такого пользователя']);
    }
    public function userPhones($request, $response) {
        $id = $request->getAttribute('id');   
        if(Users::where('id', $id)->exists())     
            $user = Users::with('phones')->find($id);
        if($user)
            return $this->response->withJson($user);
        else 
            return $this->response->withJson(['msg' => 'Нет такого пользователя']);
    }
}