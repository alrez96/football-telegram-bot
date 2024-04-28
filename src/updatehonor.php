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
	$conn->set_charset("utf8mb4");
	return $conn;
}

function RunCommand($url) {
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_ENCODING, "" );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

function RemovePoint($conn, $ver) {
	if($ver == 1) {
		$str = "point";
		$inv = "invite";
	} elseif($ver == 2) {
		$str = "point_month";
		$inv = "invite_month";
	} else {
		$str = "point_week";
		$inv = "invite_week";
	}
	$sql = "UPDATE user SET " . $str . "='0', " . $inv . "='0'";
	$conn->query($sql);
}

function RemoveFore($conn) {
	$sql = "SELECT id, level, chance, forecast_1, forecast_2, forecast_3, forecast_4,
					  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
					  forecast_10, forecast_11, forecast_12, forecast_13,
					  forecast_14, forecast_15 FROM user";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$num = 0;
		$noempty = 0;
		$str = "";
		for($x = 1; $x <= 15; $x++) {
			if(substr_count($row["forecast_" . $x], " ") == 2) {
				$row["forecast_" . $x] = "";
				$num++;
				$sql = "UPDATE user SET forecast_" . $x . "='" . $str . "'
						WHERE id=" . $row["id"];
				$conn->query($sql);
			} elseif($row["forecast_" . $x] != "")
				$noempty++;
		}
		$row["chance"] += $num;
		$row["chance"] += $noempty;

		if($row["level"] == 3 && $row["chance"] > 15) {
			$row["chance"] = 15;
			$row["chance"] -= $noempty;
		} elseif($row["level"] == 2 && $row["chance"] > 12) {
			$row["chance"] = 12;
			$row["chance"] -= $noempty;
		} elseif($row["level"] == 1 && $row["chance"] > 9) {
			$row["chance"] = 9;
			$row["chance"] -= $noempty;
		} elseif($row["level"] == 0 && $row["chance"] > 7) {
			$row["chance"] = 7;
			$row["chance"] -= $noempty;
		} else
			$row["chance"] -= $noempty;

		$sql = "UPDATE user SET chance='" . $row["chance"] . "'
			    WHERE id=" . $row["id"];
		$conn->query($sql);
	}
	$result->free();
}

$date = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "d");
$getDay = $date->format(time());

$date = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "e");
$getDayWeek = $date->format(time());

$date = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "M");
$getMonth = $date->format(time());


$conn = ConnectdDB();

if($getMonth == 1 || $getMonth == 4 || $getMonth == 7 || $getMonth == 10 && $getDay == 1) {
	$sql = "SELECT id, point, honor FROM user
	        ORDER BY point DESC
			LIMIT 15";
	$result = $conn->query($sql);
	$num = 1;
	$keep = 0;
	$rep = 0;
	while($row = $result->fetch_assoc()) {
		if($row["point"] == 0)
			break;
		if($keep == $row["point"]) {
			$num--;
			$rep++;
		} else {
			$num += $rep;
			$rep = 0;
		}
		if($num > 3)
			break;
		if($num == 1)
			$str = "\xf0\x9f\xa5\x87";
		elseif($num == 2)
			$str = "\xf0\x9f\xa5\x88";
		else
			$str = "\xf0\x9f\xa5\x89";
		$row["honor"] .= $str;
		$sql = "UPDATE user SET honor='" . $row["honor"] . "'
							WHERE id='" . $row["id"] . "'";
		$conn->query($sql);
		$num++;
		$keep = $row["point"];
	}
	$sql = "SELECT id, invite, honor FROM user
	        ORDER BY invite DESC
			LIMIT 15";
	$result = $conn->query($sql);
	$keep = 0;
	$set = false;
	while($row = $result->fetch_assoc()) {
		if($row["invite"] == 0)
			break;
		if($row["invite"] == $keep) {
			$row["honor"] .= "\xf0\x9f\x8e\xbd";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
		} else {
			if($set)
				break;
			$row["honor"] .= "\xf0\x9f\x8e\xbd";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
			$keep = $row["invite"];
			$set = true;
		}
	}
	RemovePoint($conn, 1);
	$result->free();
}
if($getDay == 1) {
	$sql = "SELECT id, point_month, honor FROM user
	        ORDER BY point_month DESC
			LIMIT 15";
	$result = $conn->query($sql);
	$keep = 0;
	$set = false;
	while($row = $result->fetch_assoc()) {
		if($row["point_month"] == 0)
			break;
		if($row["point_month"] == $keep) {
			$row["honor"] .= "\xf0\x9f\x8e\x96";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
		} else {
			if($set)
				break;
			$row["honor"] .= "\xf0\x9f\x8e\x96";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
			$keep = $row["point_month"];
			$set = true;
		}
	}
	$sql = "SELECT id, invite_month, honor FROM user
	        ORDER BY invite_month DESC
			LIMIT 15";
	$result = $conn->query($sql);
	$keep = 0;
	$set = false;
	while($row = $result->fetch_assoc()) {
		if($row["invite_month"] == 0)
			break;
		if($row["invite_month"] == $keep) {
			$row["honor"] .= "\xf0\x9f\x8e\x97";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
		} else {
			if($set)
				break;
			$row["honor"] .= "\xf0\x9f\x8e\x97";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
			$keep = $row["invite_month"];
			$set = true;
		}
	}
	RemovePoint($conn, 2);
	$result->free();
}
if($getDayWeek == 7 || $getDayWeek == 0) {
	$sql = "SELECT id, point_week, honor FROM user
	        ORDER BY point_week DESC
			LIMIT 15";
	$result = $conn->query($sql);
	$keep = 0;
	$set = false;
	while($row = $result->fetch_assoc()) {
		if($row["point_week"] == 0)
			break;
		if($row["point_week"] == $keep) {
			$row["honor"] .= "\xf0\x9f\x8f\x85";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
		} else {
			if($set)
				break;
			$row["honor"] .= "\xf0\x9f\x8f\x85";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
			$keep = $row["point_week"];
			$set = true;
		}
	}
	$sql = "SELECT id, invite_week, honor FROM user
	        ORDER BY invite_week DESC
			LIMIT 15";
	$result = $conn->query($sql);
	$keep = 0;
	$set = false;
	while($row = $result->fetch_assoc()) {
		if($row["invite_week"] == 0)
			break;
		if($row["invite_week"] == $keep) {
			$row["honor"] .= "\xf0\x9f\x8f\xb5";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
		} else {
			if($set)
				break;
			$row["honor"] .= "\xf0\x9f\x8f\xb5";
			$sql = "UPDATE user SET honor='" . $row["honor"] . "'
									WHERE id='" . $row["id"] . "'";
			$conn->query($sql);
			$keep = $row["invite_week"];
			$set = true;
		}
	}
	RunCommand("https://7bot.ir/bots/football/footballbot.php?pass=2c6QskSgXkdbc4ke&comm=getfullpoint");
	RemoveFore($conn);
	RemovePoint($conn, 3);
	$result->free();
}

$conn->close();
} else
	echo "Forbidden!";

?>