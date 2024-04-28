<?php

parse_str($argv[1], $params);

if($params['pass'] == "-" || $_GET['pass'] == "-") {
include_once('simple_html_dom.php');

function getPage() {
	$ch = curl_init("http://www.varzesh3.com/livescore/feed");
	curl_setopt($ch, CURLOPT_ENCODING, "" );
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
	$content = curl_exec($ch);
	curl_close($ch);
	return $content;
}

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

function UpdateGamePoint($conn, $league, $gameID, $data) {
	$str = $gameID . "(" . $league . ")";
	if($data[2] == 2) {
		$sql = "SELECT id, point, point_week, point_month,
			(CASE
				WHEN forecast_1 LIKE '%" . $str . "%' THEN  forecast_1
				WHEN forecast_2 LIKE '%" . $str . "%' THEN  forecast_2
				WHEN forecast_3 LIKE '%" . $str . "%' THEN  forecast_3
				WHEN forecast_4 LIKE '%" . $str . "%' THEN  forecast_4
				WHEN forecast_5 LIKE '%" . $str . "%' THEN  forecast_5
				WHEN forecast_6 LIKE '%" . $str . "%' THEN  forecast_6
				WHEN forecast_7 LIKE '%" . $str . "%' THEN  forecast_7
				WHEN forecast_8 LIKE '%" . $str . "%' THEN  forecast_8
				WHEN forecast_9 LIKE '%" . $str . "%' THEN  forecast_9
				WHEN forecast_10 LIKE '%" . $str . "%' THEN  forecast_10
				WHEN forecast_11 LIKE '%" . $str . "%' THEN  forecast_11
				WHEN forecast_12 LIKE '%" . $str . "%' THEN  forecast_12
				WHEN forecast_13 LIKE '%" . $str . "%' THEN  forecast_13
				WHEN forecast_14 LIKE '%" . $str . "%' THEN  forecast_14
				WHEN forecast_15 LIKE '%" . $str . "%' THEN  forecast_15
			END) AS forecast,

			(CASE
				WHEN forecast_1 LIKE '%" . $str . "%' THEN  '1'
				WHEN forecast_2 LIKE '%" . $str . "%' THEN  '2'
				WHEN forecast_3 LIKE '%" . $str . "%' THEN  '3'
				WHEN forecast_4 LIKE '%" . $str . "%' THEN  '4'
				WHEN forecast_5 LIKE '%" . $str . "%' THEN  '5'
				WHEN forecast_6 LIKE '%" . $str . "%' THEN  '6'
				WHEN forecast_7 LIKE '%" . $str . "%' THEN  '7'
				WHEN forecast_8 LIKE '%" . $str . "%' THEN  '8'
				WHEN forecast_9 LIKE '%" . $str . "%' THEN  '9'
				WHEN forecast_10 LIKE '%" . $str . "%' THEN  '10'
				WHEN forecast_11 LIKE '%" . $str . "%' THEN  '11'
				WHEN forecast_12 LIKE '%" . $str . "%' THEN  '12'
				WHEN forecast_13 LIKE '%" . $str . "%' THEN  '13'
				WHEN forecast_14 LIKE '%" . $str . "%' THEN  '14'
				WHEN forecast_15 LIKE '%" . $str . "%' THEN  '15'
			END) AS num FROM user
			WHERE     forecast_1 LIKE '%" . $str . "%' OR forecast_2 LIKE '%" . $str . "%'
				   OR forecast_3 LIKE '%" . $str . "%' OR forecast_4 LIKE '%" . $str . "%'
				   OR forecast_5 LIKE '%" . $str . "%' OR forecast_6 LIKE '%" . $str . "%'
		           OR forecast_7 LIKE '%" . $str . "%' OR forecast_8 LIKE '%" . $str . "%'
		           OR forecast_9 LIKE '%" . $str . "%' OR forecast_10 LIKE '%" . $str . "%'
	               OR forecast_11 LIKE '%" . $str . "%' OR forecast_12 LIKE '%" . $str . "%'
		           OR forecast_13 LIKE '%" . $str . "%' OR forecast_14 LIKE '%" . $str . "%'
		           OR forecast_15 LIKE '%" . $str . "%'";
		$result = $conn->query($sql);
		if($result->num_rows != 0) {
			while($row = $result->fetch_assoc()) {
				$pos = strpos($row["forecast"], ")") + 2;
				$goal_g = substr($row["forecast"], $pos, (strpos($row["forecast"], "-")) - $pos);
				$goal_h = substr($row["forecast"], strpos($row["forecast"], "-") + 1);
				$point = 2;
				if($goal_h == $data[0] && $goal_g == $data[1]) {
					$point += 13;
				} elseif(($goal_h > $goal_g && $data[0] > $data[1])
					  || ($goal_h < $goal_g && $data[0] < $data[1])
					  || ($goal_h == $goal_g && $data[0] == $data[1])) {
					if($goal_h == $data[0] || $goal_g == $data[1])
						$point += 6;
					elseif(($goal_h - $goal_g) == ($data[0] - $data[1]))
						$point += 6;
					else
						$point += 4;
				} elseif($goal_h == $data[0] || $goal_g == $data[1]) {
					$point += 2;
				}
				$row["point"] += $point;
				$row["point_week"] += $point;
				$row["point_month"] += $point;
				$forecast_str = $row["forecast"] . " " . $point;

				$sql_1 = "UPDATE user SET point='" . $row["point"] . "', point_week='" . $row["point_week"] . "',
							point_month='" . $row["point_month"] . "', forecast_" . $row["num"] . "='" . $forecast_str . "'
							WHERE id=" . $row["id"];
				$conn->query($sql_1);
			}
		}
		$result->free();
	}
	$sql = "UPDATE game SET g_host='" . $data[0] . "', g_guest='" . $data[1] . "',
							status='" . $data[2] . "', status_des='" . $data[3] . "'
							WHERE id='" . $gameID . "' AND league='" . $league . "'";
	$conn->query($sql);
}

function UpdateGameUnk($conn, $league, $gameID, $data) {
	$sql = "UPDATE game SET g_host='" . $data[0] . "', g_guest='" . $data[1] . "',
							status='" . $data[2] . "', status_des='" . $data[3] . "',
							date_num='" . $data[4] . "', time='" . $data[5] . "',
							date='" . $data[6] . "'
							WHERE id='" . $gameID . "' AND league='" . $league . "'";
	$conn->query($sql);
}

function UpdateGameTime($conn, $league, $gameID, $data) {
	$sql = "UPDATE game SET g_host='" . $data[0] . "', g_guest='" . $data[1] . "',
							status='" . $data[2] . "', status_des='" . $data[3] . "',
							time='" . $data[4] . "'
							WHERE id='" . $gameID . "' AND league='" . $league . "'";
	$conn->query($sql);
}

function ConvertPer2En($str) {
    $persian = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $str);

    return $convertedPersianNums;
}

$date_D = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::GREGORIAN, "yyy/MM/dd");
$getDate = $date_D->format(time());

$date_T = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "HH:mm");
$getTime = $date_T->format(time());

$date_K = new IntlDateFormatter("fa@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "EEE d MMM yyy");

$date_game = IntlCalendar::createInstance(
    'Asia/Tehran',
    '@calendar=persian'
);

$conn = ConnectdDB();

$sql = "SELECT id, league, t_host, t_guest, g_host, g_guest, status, status_des, date_num, time FROM game
	    WHERE status!='2' AND status!='3'";
$result = $conn->query($sql);

$html = str_get_html(getPage());
$match_row_count = count($html->find('div.match-row'));

while($row = $result->fetch_assoc()) {
	$find = false;
	for($x = 0; $x < $match_row_count; $x++) {
		$match_row = $html->find('div.match-row', $x);
		$temp_1 = $match_row->find('div.start-time');
		$start_time = strip_tags($temp_1[0]);

		$temp_1 = $match_row->find('div.start-date');
		$start_date = strip_tags($temp_1[0]);

		$temp_1 = $match_row->find('div.match-status');
		$temp_2 = $temp_1[0]->find('span');
		$match_status = str_replace(" ", "", strip_tags($temp_2[0]));

		$temp_team = $match_row->find('div.team-names');
		$temp_score = $temp_team[0]->find('div.scores-container');

		$temp_1 = $temp_team[0]->find('div[class*="teamname right"]');
		$teamname_right = strip_tags($temp_1[0]);

		if($match_status != "نتیجهنهایی(پنالتی)") {
			$temp_1 = $temp_score[0]->find('div[class*="score right"]');
			$score_right = strip_tags($temp_1[0]);

			$temp_1 = $temp_score[0]->find('div[class*="score left"]');
			$score_left = strip_tags($temp_1[0]);
		} else {
			$temp_1 = $temp_score[0]->find('div[class*="score right"]');
			$temp_2 = strip_tags($temp_1[0]);
			$score_right = substr($temp_2, 0, strpos($temp_2, "("));
			$score_left = $score_right;

			$p_right = substr($temp_2, strpos($temp_2, "(") + 1, -1);
			$temp_1 = $temp_score[0]->find('div[class*="score left"]');
			$temp_2 = strip_tags($temp_1[0]);
			$p_left = substr($temp_2, strpos($temp_2, "(") + 1, -1);
		}

		$temp_1 = $temp_team[0]->find('div[class*="teamname left"]');
		$teamname_left = strip_tags($temp_1[0]);

		if($row["t_host"] == $teamname_right || $row["t_guest"] == $teamname_left) {
			$find = true;
			$data[0] = $score_right;
			$data[1] = $score_left;
			if($match_status == "" && $row["status"] != 4) {
				$y = substr($row["date_num"], 0, 4) - 1;
				$m = substr($row["date_num"], 5, 2) - 1;
				$d = substr($row["date_num"], 8, 2) - 1;
				$y += 1;
				$d += 1;
				$date_game->set($y, $m, $d);
				$gameDate = $date_D->format($date_game);
				$date1 = date_create($getDate . " " . $getTime);
				$date2 = date_create($gameDate . " " . $start_time);
				$diff = date_diff($date1, $date2);
				if($diff->format("%a") == "0") {
					if($diff->format("%h") == "0") {
						if($date1 > $date2)
							$data[3] = "هنوز شروع نشده";
						else {
							if($diff->format("%i") == "0")
								$data[3] = "هنوز شروع نشده";
							else
								$data[3] = $diff->format("%i") . " دقیقه مانده";
						}
					} else {
						if($diff->format("%i") == "0")
							$data[3] = $diff->format("%h") . " ساعت مانده";
						else
							$data[3] = $diff->format("%h") . " ساعت و " . $diff->format("%i") . " دقیقه مانده";
					}
				} else {
					if($diff->format("%h") == "0")
						$data[3] = $diff->format("%a") . " روز مانده";
					else
						$data[3] = $diff->format("%a") . " روز و " . $diff->format("%h") . " ساعت مانده";
				}
				$data[2] = 0;
			} elseif($match_status == "نتیجهنهایی") {
				$data[2] = 2;
				$data[3] = "تمام شده";
			}  elseif($match_status == "نتیجهنهایی(پنالتی)") {
				$data[2] = 2;
				$data[3] = "تمام شده (پنالتی " . $p_right . " - " . $p_left . "(";
			} elseif($match_status == "پایاننیمهاول") {
				$data[2] = 1;
				$data[3] = "پایان نیمه اول";
			} elseif($match_status == "لغوشده") {
				$data[2] = 3;
				$data[3] = "لغو شده";
			} elseif($match_status == "نامعلوم") {
				$data[2] = 4;
				$data[3] = "نامعلوم";
			} elseif($match_status == "بهتعویقافتاد") {
				$data[2] = 4;
				$data[3] = "به تعویق افتاد";
			} elseif($row["status"] == 4) {
				$data[2] = 0;
				$data[3] = "نامعلوم";
				$data[4] = $start_date;
				$data[5] = $start_time;
				$y = substr($start_date, 0, 4) - 1;
				$m = substr($start_date, 5, 2) - 1;
				$d = substr($start_date, 8, 2) - 1;
				$y += 1;
				$d += 1;
				$date_game->set($y, $m, $d);
				$data[6] = ConvertPer2En($gameDate = $date_K->format($date_game));
				UpdateGameUnk($conn, $row["league"], $row["id"], $data);
			} else {
				$data[2] = 1;
				if($match_status == "-1")
					$data[3] = "دقیقه " . "0";
				elseif($row["status_des"] == "پایان نیمه اول")
					$data[3] = "دقیقه " . "45";
				else
					$data[3] = "دقیقه " . $match_status;
			}
			if($data[3] == $row["status_des"] && $row["time"] == $start_time && $row["date_num"] == $start_date
			  && $row["g_host"] == $score_right && $row["g_guest"] = $score_left)
				break;
			if($row["status"] != 4 && $row["time"] == $start_time && $row["date_num"] == $start_date)
				UpdateGamePoint($conn, $row["league"], $row["id"], $data);
			elseif($row["status"] != 4 && $row["date_num"] == $start_date) {
				$data[4] = $start_time;
				UpdateGameTime($conn, $row["league"], $row["id"], $data);
			} elseif($row["status"] != 4) {
				$data[4] = $start_date;
				$data[5] = $start_time;
				$y = substr($start_date, 0, 4) - 1;
				$m = substr($start_date, 5, 2) - 1;
				$d = substr($start_date, 8, 2) - 1;
				$y += 1;
				$d += 1;
				$date_game->set($y, $m, $d);
				$data[6] = ConvertPer2En($gameDate = $date_K->format($date_game));
				UpdateGameUnk($conn, $row["league"], $row["id"], $data);
			}
			break;
		}
	}
	if($find == false) {
		$y = substr($row["date_num"], 0, 4) - 1;
		$m = substr($row["date_num"], 5, 2) - 1;
		$d = substr($row["date_num"], 8, 2) - 1;
		$y += 1;
		$d += 1;
		$date_game->set($y, $m, $d);
		$gameDate = $date_D->format($date_game);
		$date1 = date_create($getDate . " " . $getTime);
		$date2 = date_create($gameDate . " " . $row["time"]);
		$diff = date_diff($date1, $date2);
		$data[0] = "?";
		$data[1] = "?";
		$data[2] = 0;
		if($diff->format("%a") == "0") {
			if($diff->format("%i") == "0")
				$data[3] = $diff->format("%h") . " ساعت مانده";
			else
				$data[3] = $diff->format("%h") . " ساعت و " . $diff->format("%i") . " دقیقه مانده";
		}
		else {
			if($diff->format("%h") == "0")
				$data[3] = $diff->format("%a") . " روز مانده";
			else
				$data[3] = $diff->format("%a") . " روز و " . $diff->format("%h") . " ساعت مانده";
		}
		UpdateGamePoint($conn, $row["league"], $row["id"], $data);
	}
}
$result->free();
$conn->close();
} else
	echo "Forbidden!";

?>