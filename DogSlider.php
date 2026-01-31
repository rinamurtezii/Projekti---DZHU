<?php
require_once 'DataBase.php';

class DogSlider {
    private $conn;
    private $table = 'dogs_slider';

    public function __construct() {
        $db = new DataBase();
        $this->conn = $db->startConnection();
    }

    public function getAllDogs() {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function renderSlider() {
    $dogs = $this->getAllDogs();
    $html = '';
    foreach($dogs as $dog) {
        $html .= "
        <article class='dogsNew-card'>
            <img src='".htmlspecialchars($dog['image'])."' alt='".htmlspecialchars($dog['name'])."'>
            <span class='dogsNew-pill'>".htmlspecialchars($dog['name'])." • ".htmlspecialchars($dog['age'])." years</span>
        </article>";
    }
    return $html;
}

}
?>
