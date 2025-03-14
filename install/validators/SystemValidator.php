<?php
namespace Install\Validators;

class SystemValidator {
    private $errors = [];

    public function validate($config) {
        $this->validateAppSettings($config);
        $this->validateAdminAccount($config);
        $this->validatePaths();
        
        return empty($this->errors);
    }

    private function validateAppSettings($config) {
        if (empty($config['app_name'])) {
            $this->errors[] = 'Application name is required';
        }

        if (!filter_var($config['app_url'], FILTER_VALIDATE_URL)) {
            $this->errors[] = 'Invalid application URL';
        }
    }

    private function validateAdminAccount($config) {
        if (!filter_var($config['admin_email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = 'Invalid admin email address';
        }

        if (strlen($config['admin_password']) < 8) {
            $this->errors[] = 'Admin password must be at least 8 characters';
        }
    }

    public function getErrors() {
        return $this->errors;
    }
}
