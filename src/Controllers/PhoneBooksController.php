<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DbModel;
use App\Models\PhoneBookModel;
use App\Helpers\SessionHelper;
use App\Helpers\DataValidationHelper;
use App\Helpers\AuthHelper;
use App\Helpers\FilesHelper;

class PhoneBooksController extends Controller{

    /**
     * Handle form action for adding a new record to the current phone book.
     * 
     * @return string json response for ajax
     * @throws \Exception
     */
    public function createAction()
    {
        $first_name = strip_tags(filter_input(INPUT_POST, 'first_name'));
        $last_name = strip_tags(filter_input(INPUT_POST, 'last_name'));
        $phone = strip_tags(filter_input(INPUT_POST, 'phone'));
        $email = strip_tags(filter_input(INPUT_POST, 'email'));
        $csrf = strip_tags(filter_input(INPUT_POST, 'csrf'));
        $picture = $_FILES['user_picture'];
        
        try{ 
            $data['res_create'] = false;
            $data['success'] = false;
            
            if ($csrf !== SessionHelper::getItemFromSession('csrf_token')) {
                throw new \Exception($this->data['translates']['csrf_notvalid']);
            }
            if(empty($first_name)){
                throw new \Exception($this->data['translates']['first_name_required']);
            }
            if(empty($last_name)){
                throw new \Exception($this->data['translates']['last_name_required']);
            }
            if(!DataValidationHelper::textInput($first_name) || !DataValidationHelper::textInput($last_name)) {
                throw new \Exception($this->data['translates']['text_input_invalid']);
            }
            if(!empty($email) && !DataValidationHelper::email($email)){
                throw new \Exception($this->data['translates']['email_notvalid']);
            }
            if(empty($phone)){
                throw new \Exception($this->data['translates']['phone_required']);
            }
            if(!DataValidationHelper::uaPhone($phone)){
                throw new \Exception($this->data['translates']['phone_invalid']);
            }
            
            $phone_book_model = new PhoneBookModel();
            $res = $phone_book_model->create($first_name, $last_name, $phone, $email);
            
            $data['res_create'] = $res;
            if($data['res_create'] === false){
                throw new \Exception($this->data['translates']['phone_unical_invalid']);
            }
            
            if (isset($data['res_create']['res']) && $data['res_create']['res'] === true) {
                $data['success'] = true;
                $data['stored_data'] = [];
                
                $data['stored_data']['first_name'] = $first_name;
                $data['stored_data']['last_name'] = $last_name;
                $data['stored_data']['phone'] = $phone;
                $data['stored_data']['email'] = $email;
               
                
                $file_html_name = "user_picture";
                
                if ($_FILES[$file_html_name]["error"] == UPLOAD_ERR_OK) {
                    $picture = $_FILES[$file_html_name];
                    $tmp_name = $picture["tmp_name"];
                    $name = $picture["name"];
                    $part_url = "img/upload/" . FilesHelper::generateUniqueFileName($name);
                    $destination = $_SERVER["DOCUMENT_ROOT"] . $part_url;
                    $type = $picture['type'];
                    $size = $picture['size'];

                    
                    if (!empty($tmp_name) && !empty($name) && !empty($type)) {
                        if (!DataValidationHelper::image($type)) {
                            $data['res_upload'] = false;
                            throw new \Exception($this->data['translates']['user_picture_invalid']);
                        }
                        if (!FilesHelper::fileSize($size, $this->_config['max_book_item_img_size'])) {
                            $data['res_upload'] = false;
                            throw new \Exception($this->data['translates']['user_picture_invalid']);
                        }
                        $res_upload = FilesHelper::upload($tmp_name, $destination);
                        $data['res_upload'] = $res_upload;
                        
                        if (!$data['res_upload']) {
                            throw new \Exception($this->data['translates']['filesystem_error']);
                        }
                        
                        $inserted_id = $data['res_create']['insert_id'];
                        $res_update_img = $phone_book_model->updateById('user_picture', $part_url, $inserted_id);

                        $data['res_update_img'] = $res_update_img;
                        if($data['res_update_img'] === true){
                            $data['stored_data']['picture'] = $part_url;
                        }
                    }
                }
            }
        } catch (\Exception $ex) {
            $data['success'] = !empty($data['res_create']['res']) ? true : false;
            $data['res_upload'] = !empty($data['res_upload']) ? true : false;
            $data['errors'] = $ex->getMessage();  
        }
        header("Content-Type: application/json");
        echo json_encode($data);
        
        exit;
    }
    
    /**
     * Delete phone book record using Ajax.
     * 
     * @return string json response for ajax
     * @throws \Exception
     */
    public function deleteAction()
    {
        $csrf = strip_tags(filter_input(INPUT_POST, 'csrf'));
        $item_id = filter_input(INPUT_POST, 'item_id');
        
        try{ 
            $data['res_delete'] = false;
            $data['success'] = false;
            
            if ($csrf !== SessionHelper::getItemFromSession('csrf_token')) {
                throw new \Exception($this->data['translates']['csrf_notvalid']);
            }
            if(empty($item_id)) {
                throw new \Exception($this->data['translates']['item_delete_empty_id']);
            }
            $phone_book_model = new PhoneBookModel();
            $res = $phone_book_model->delete($item_id);
            
            $data['res_delete'] = $res;
            
            if (isset($data['res_delete']['res']) && $data['res_delete']['res'] === true) {
                $data['success'] = true;
            }
        } catch (\Exception $ex) {
            $data['success'] = !empty($data['res_delete']['res']) ? true : false;
          
            $data['errors'] = $ex->getMessage();  
        }
        
        header("Content-Type: application/json");
        echo json_encode($data);
        
        exit;
        
    }
}
