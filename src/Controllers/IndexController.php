<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DbModel;
use App\Models\PhoneBookModel;
use App\Helpers\SessionHelper;
use App\Helpers\DataValidationHelper;
use App\Helpers\AuthHelper;

class IndexController extends Controller{

    /**
     * Show phone book or login page.
     * 
     */
    public function run() 
    {
        
        $userModel = new UserModel();
        
        if($this->data['is_auth'] === true){
            $phoneBookModel = new PhoneBookModel();
            $user = AuthHelper::getCurrentUser();
            $userBook = $userModel->getPhoneBook();
            
            $this->data['userBook'] = $userBook;
            $this->data['noItems'] = $this->data['translates']['no_items'];
        }
        SessionHelper::removeArrayFromSession('messages');

        parent::render('index');
    }
    
    /**
     * Handle login form action. Making redirect.
     * 
     * @throws \Exception
     */
    public function loginAction()
    {
        
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $csrf = filter_input(INPUT_POST, 'csrf');
        
        try{
            if ($csrf !== SessionHelper::getItemFromSession('csrf_token')) {
                throw new \Exception($this->data['translates']['csrf_notvalid']);
            }
            if(!DataValidationHelper::email($email)){
                throw new \Exception($this->data['translates']['email_notvalid']);
            }
            if(empty($password)){
                throw new \Exception($this->data['translates']['passw_required']);
            }
            
            $userModel = new UserModel();
            $user = $userModel->getUserByEmail($email);
            $res = AuthHelper::auth($password, $user);
            
            if($res === true){
                SessionHelper::refreshCSRFToken();
                
                AuthHelper::redirect('/');
            } else{
                SessionHelper::addArrayToSession('messages',[
                    $this->data['translates']['auth_incorrect_user_data']
                ]);
                
                AuthHelper::redirect('/');
            }

        } catch (\Exception $ex) {
            SessionHelper::addArrayToSession('messages',[
                $ex->getMessage()
            ]);
            
            AuthHelper::redirect('/');
        }
    }
    
    /**
     * Logout action.
     */
    public function logoutAction()
    {
        
        AuthHelper::logout();
        AuthHelper::redirect('/');
    }
    
    /**
     * Render registration page.
     */
    public function registration()
    {
        
        SessionHelper::removeArrayFromSession('messages');
        $prev_form = SessionHelper::getArrayFromSession('prev_form');
        
        $this->data['prev_login'] = '';
        $this->data['prev_email'] = '';
        
        if(!empty($prev_form)){
            $this->data['prev_login'] = strip_tags($prev_form['login']);
            $this->data['prev_email'] = strip_tags($prev_form['email']);
        }
       
        parent::render('register');
    }
    
    /**
     * Handle form action for user registration form. After registration, make a redirect.
     * 
     * @throws \Exception
     */
    public function registerAction()
    {
        
        $login = filter_input(INPUT_POST, 'login');
        $email = filter_input(INPUT_POST, 'email');
        $password = filter_input(INPUT_POST, 'password');
        $csrf = filter_input(INPUT_POST, 'csrf');
        
        try{
            if ($csrf !== SessionHelper::getItemFromSession('csrf_token')) {
                throw new \Exception($this->data['translates']['csrf_notvalid']);
            }
            if(!DataValidationHelper::login($login)){
                throw new \Exception($this->data['translates']['login_notvalid']);
            }
            if(!DataValidationHelper::email($email)){
                throw new \Exception($this->data['translates']['email_notvalid']);
            }
            if(!DataValidationHelper::password($password)){
                throw new \Exception($this->data['translates']['passw_notvalid']);
            }
            if(empty($password)){
                throw new \Exception($this->data['translates']['passw_required']);
            }
            
            $userModel = new UserModel();
            
            //if we haven't unical indexes for users.login and users.email on db than check it on soft layer
            $userExist = $userModel->verifyUserExistence($login, $email);
            
            if($userExist === true){
                throw new \Exception($this->data['translates']['user_exist']); 
            }
            
            $hashed_password = AuthHelper::hashPassw($password);
            $res_create = $userModel->create($login, $email, $hashed_password);

            if($res_create['res'] === true){
                SessionHelper::removeArrayFromSession('prev_form');
                $user = $userModel->getUserByEmail($email);
                $res_auth = AuthHelper::auth($password, $user);
                SessionHelper::refreshCSRFToken();
                
                AuthHelper::redirect('/');
            } else{
                SessionHelper::addArrayToSession('prev_form',[
                    'login' => strip_tags($login),
                    'email'=> strip_tags($email)
                ]);
                SessionHelper::addArrayToSession('messages',[
                    $this->data['translates']['register_db_error']
                ]);
                
                AuthHelper::redirect('/registration');
            }
        } catch (\Exception $ex) {
            SessionHelper::addArrayToSession('prev_form',[
                'login' => strip_tags($login),
                'email'=> strip_tags($email)
            ]);
            SessionHelper::addArrayToSession('messages',[
                $ex->getMessage()
            ]);
            
            AuthHelper::redirect('/registration');
        }
    }
}
