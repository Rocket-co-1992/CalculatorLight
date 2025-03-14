<?php
namespace WebPanel;

class Auth {
    private $db;
    private $session;

    public function __construct() {
        $this->db = \Core\Database::getInstance()->getConnection();
        $this->session = new \Core\Session();
    }

    public function authenticate($username, $password) {
        $sql = "SELECT * FROM admin_users WHERE username = :username AND active = 1";
        $user = $this->db->query($sql, ['username' => $username])->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $this->session->set('admin_user', [
                'id' => $user['id'],
                'username' => $user['username'],
                'role' => $user['role']
            ]);
            $this->logAccess($user['id'], 'login');
            return true;
        }
        
        return false;
    }

    private function logAccess($userId, $action) {
        $sql = "INSERT INTO admin_access_log (user_id, action, ip_address, created_at)
                VALUES (:user_id, :action, :ip, NOW())";
                
        $this->db->query($sql, [
            'user_id' => $userId,
            'action' => $action,
            'ip' => $_SERVER['REMOTE_ADDR']
        ]);
    }
}
