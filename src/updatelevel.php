<?php

parse_str($argv[1], $params);

if($params['pass'] == "-" || $_GET['pass'] == "-") {
function ConnectdDB() {
	$servername = "localhost";
	$username = "botir_bot1";
	$password = "-";
	$dbname = "botir_football";

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}
	return $conn;
}

$date_T = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "HH");
$getTime = $date_T->format(time());

$conn = ConnectdDB();

$sql = "SELECT id, days FROM upgrade
		WHERE days!='0' AND date='" . $getTime . "'";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {
	$row["days"] -= 1;
	if($row["days"] == 0) {
		$sql = "UPDATE upgrade SET days='" . $row["days"] . "', level='0'
				WHERE id=" . $row["id"];
		$conn->query($sql);
		$sql = "UPDATE user SET level='0'
				WHERE id=" . $row["id"];
		$conn->query($sql);
	} else {
		$sql = "UPDATE upgrade SET days='" . $row["days"] . "'
				WHERE id=" . $row["id"];
		$conn->query($sql);
	}
}
$result->free();
} else
	echo "Forbidden!";

?>