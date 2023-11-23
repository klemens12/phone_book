<?php

namespace App\Controllers;

use App\Helpers\ConfigHelper;
use App\Helpers\SessionHelper;
use App\Helpers\DataValidationHelper;
use App\Models\UserModel;
use App\Helpers\AuthHelper;

class Controller {
    
    protected $_config;
    public $data;
    
    /**
     * 
     * @param string $defaultLanguage
     * @param string $viewsPath
     * @param string $langPath
     */
    public function __construct(
        protected string $defaultLanguage = 'en',
        protected string $viewsPath = 'Views',
        protected string $langPath = 'lang',
    ) 
    {
        
        SessionHelper::start();
        ConfigHelper::load(__DIR__ . '/../../config/config.php');
        $config = ConfigHelper::all();
       
        $this->_config = $config['global'];
        $this->defaultLanguage = $this->_config['default_language'] ?? $this->defaultLanguage;
        $this->viewsPath = $this->_config['views_path'] ?? $this->viewsPath;
        $this->langPath = $this->_config['lang_path'] ?? $this->langPath;
       
        $this->data['translates'] = $this->loadLanguage($this->defaultLanguage);
        $this->data['currentLang'] = $this->defaultLanguage;
        $this->data['messages'] = SessionHelper::getArrayFromSession('messages');
        $this->data['is_auth'] = AuthHelper::isAuth();
        $this->data['csrf'] = SessionHelper::generateCSRFToken();
    }

    /**
     * Load translates array.
     * @return array
     */
    public function getTranslates()
    {
        return $this->data['translates'];
    }
    
    /**
     * Asset data to template and include him.
     * @param string $view
     */
    protected function render($view) 
    {
        extract($this->data, EXTR_SKIP);

        include __DIR__ . '/../' . $this->viewsPath . '/' . $view . '.php';
    }

    /**
     * Load a language file and return its content as an array.
     * @param string $langPrefix
     * @return array Associative array containing language translation data.
     */
    protected function loadLanguage($langPrefix) 
    {
        $langFile = __DIR__ . '/../' . $this->langPath . '/' . $langPrefix . '/' . $langPrefix . '.php';
        return include $langFile;
    }
    
    /**
     * Run setting up current language
     * @param string $lang
     */
    public function setLanguage($lang) 
    {
        $this->defaultLanguage = $lang;
        $this->data['translates'] = $this->loadLanguage($this->defaultLanguage);
    }
}
