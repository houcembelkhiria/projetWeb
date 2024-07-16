<?php
class User {
    private $id;
    private $username;
    private $password;
    private $role;

    public function __construct($id = null, $username = null, $password = null) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
    }

    // Getter for id
    public function getId() {
        return $this->id;
    }

    // Setter for id
    public function setId($id) {
        $this->id = $id;
    }

    // Getter for username
    public function getUsername() {
        return $this->username;
    }

    // Setter for username
    public function setUsername($username) {
        $this->username = $username;
    }

    // Getter for password
    public function getPassword() {
        return $this->password;
    }

    // Setter for password
    public function setPassword($password) {
        $this->password = $password;
    }


    // Method to verify password using SHA-256 hash
    public function verifyPassword($inputPassword) {
        // Hash the input password using SHA-256
        $hashedInputPassword = hash('sha256', $inputPassword);

        // Compare hashed input password with stored hashed password
        return hash_equals($hashedInputPassword, $this->password);
    
}

    // Getter for role
    public function getRole() {
        return $this->role;
    }


}
?>
