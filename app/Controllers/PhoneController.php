<?php

namespace App\Controllers;

use App\Models\Users;
use App\Models\Phones;
use Respect\Validation\Validator as v;

class PhoneController extends Controller {
    public function addUserPhone($request, $response) {
        $id = $request->getAttribute('id'); 
        if(Users::where('id', $id)->exists()) {
            $validation = $this->validator->validate($request, [
                'phone' => v::noWhitespace()->notEmpty()->numeric()->length(12, 12)
            ]);
            $errors = $validation->failed();
            if ($errors) {
                foreach ($errors as $field => $error)
                        $err[$field] = $error[0];
                return $this->response->withJson($err);
            }
            $phonedata = [
                'phone' => $request->getParam('phone'),
                'user_id' => $id
            ];       
            $user = Phones::create($phonedata);
            return $this->response->withJson($user);
        } else 
            return $this->response->withJson(['msg' => 'Нет такого пользователя']);
    }
    public function phoneUpdate($request, $response) {
        $user_id = $request->getAttribute('id');
        $phone_id = $request->getAttribute('phone_id');
        if(Users::where('id', $user_id)->exists()) {
            if (Phones::where('user_id', $user_id)->where('id', $phone_id)->exists()) {
                $validation = $this->validator->validate($request, [
                    'phone' => v::noWhitespace()->notEmpty()->numeric()->length(12, 12)
                ]);
                $errors = $validation->failed();
                if ($errors) {
                    foreach ($errors as $field => $error)
                            $err[$field] = $error[0];
                    return $this->response->withJson($err);
                }
                $phonedata = [
                    'phone' => $request->getParam('phone'),
                    'user_id' => $user_id,
                ];       
                $userPhone = Phones::find($phone_id)->update($phonedata);
                if ($userPhone)
                    return $this->response->withJson(Phones::find($phone_id));  
                return $this->response->withJson(['msg' => 'Произошла ошибка. Попробуйте позже']);                  
            } else
                return $this->response->withJson(['msg' => 'У пользователя с ID = '.$user_id.' Телефон с ID = '.$phone_id.' не найден']);
        } else 
            return $this->response->withJson(['msg' => 'Нет такого пользователя']);
    }
    public function deletePhone($request, $response) {
        $id = $request->getAttribute('id');
        if (Phones::where('id', $id)->exists()) 
            $phone = Phones::destroy($id);
        else
            return $this->response->withJson(['msg' => 'Нет такого телефона']);        
        if ($user) {
            return $this->response->withJson(['msg' => 'Удалён телефон с ID = '.$id]);
        }
        return $this->response->withJson(['msg' => 'Ошибка. Попробуйте позже.']);
    }
}