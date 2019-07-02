<?php
$response = array();

require  'google/vendor/autoload.php';
use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\ServiceRequestFactory;
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . __DIR__ . '/google/key.json');

$link = new PDO('mysql:host=;dbname=', '', '');
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
  } else if (!is_numeric($age) || $age < 0 || $age > 100) {
    $response['error'] = "Возраст может быть от 1 до 100!";
  } else {
    $query = $link->prepare(" INSERT INTO user SET name=:name, surname=:surname, age=:age ");
    $params = ['name' => $name, 'surname' => $surname, 'age' => $age];
    $query->execute($params);
  }

} else if ($ajax == "unload") {

  $client = new Google_Client;
  	$client->useApplicationDefaultCredentials();
    $client->setApplicationName("Something to do with my representatives");
  	$client->setScopes(['https://www.googleapis.com/auth/drive','https://spreadsheets.google.com/feeds']);
     if ($client->isAccessTokenExpired()) {
  		$client->refreshTokenWithAssertion();
  	}
  	$accessToken = $client->fetchAccessTokenWithAssertion()["access_token"];
  	ServiceRequestFactory::setInstance(
  		new DefaultServiceRequest($accessToken)
  	);
  	$spreadsheet = (new Google\Spreadsheet\SpreadsheetService)
  		->getSpreadsheetFeed()
  		->getByTitle('Test');
  	$worksheets = $spreadsheet->getWorksheetFeed()->getEntries();
  	$worksheet = $worksheets[0];
  	$listFeed = $worksheet->getListFeed();
    $query_news = $link->prepare("SELECT * FROM user WHERE age > '18' ");
    $query_news->execute();

    $result = $query_news->fetchAll();

    foreach ($result as $result_query) {
  		$listFeed->insert([
        'id' => $result_query['id'],
  			'name' => $result_query['name'],
        'surname' => $result_query['surname'],
        'age' => $result_query['age'],
  		]);
  	}
}

header('Content-Type: application/json');
echo json_encode($response);
?>
