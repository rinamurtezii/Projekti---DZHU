<?php 
class User{
    private $conn;
    private $table="Users";

    public function __construct($db){
        $this->conn=$db;
    }

    public function emailExists($email){
        $stmt=$this->conn->prepare(
            "SELECT 1 FROM $this->table WHERE email = ?"
        );
        $stmt->execute([$email]);
        return $stmt->rowCount()>0;
    }

    public function register($name, $email, $password, $role = 'user'){
        if($this->emailExists($email)){
            return false;
        }

        $hashed= password_hash($password, PASSWORD_DEFAULT);

        $stmt=$this->conn->prepare(
            "INSERT INTO $this->table(name,email,password,role) VALUES (?, ? , ?, ?)"
        );
        return $stmt->execute([$name,$email,$hashed,$role]);
    }
    public function login($email, $password){
        $stmt=$this->conn->prepare(
            "SELECT * FROM $this->table WHERE email=?"
        );
        $stmt->execute([$email]);
        $user=$stmt->fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($password, $user['password'])){
          return [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
                'created_at' => $user['created_at']
            ];
        }
        return false;
    }
}
?>