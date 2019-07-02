<?php
$response = array();

$link = new PDO('mysql:host=localhost;dbname=backend', 'root', '');
$ajax = isset($_POST['ajax']) ? $_POST['ajax'] : "";

if ($ajax == "save") {

  $name = isset($_POST['name']) ? $_POST['name'] : "";
  $surname = isset($_POST['surname']) ? $_POST['surname'] : "";
  $age = isset($_POST['age']) ? $_POST['age'] : "";

  $name = htmlspecialchars(trim($name));
  $surname = htmlspecialchars(trim($surname));
  $age = htmlspecialchars(trim($age));

  if (!$name || !$surname || !$age) {
    $response['error'] = "Все поля должны быть заполнены!";
  } else if (!preg_match("/^[ А-я]{3,32}+$/iu", $name)) {
    $response['error'] = "Имя может содержать только русские буквы и иметь длину от 3 до 32 символов!";
  } else if (!preg_match("/^[ А-я]{3,32}+$/iu", $surname)) {
    $response['error'] = "Фамилия может содержать только русские буквы и иметь длину от 3 до 32 символов!";
  } else if (!is_numeric($age) && $age < 0 || $age > 101) {
    $response['error'] = "Возраст может быть от 1 до 100!";
  } else {
    $query = $link->prepare(" INSERT INTO users SET name=:name, surname=:surname, age=:age ");
    $params = ['name' => $name, 'surname' => $surname, 'age' => $age];
    $query->execute($params);
  }

} else if ($ajax == "unload") {



}

header('Content-Type: application/json');
echo json_encode($response);
?>
