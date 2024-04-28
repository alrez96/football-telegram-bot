<?php

include_once('simple_html_dom.php');

function getPage($url) {
	$ch = curl_init($url);
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

function ConvertPer2En($str) {
    $persian = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $str);

    return $convertedPersianNums;
}

$date_K = new IntlDateFormatter("fa@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "EEE d MMM yyy");

$date_game = IntlCalendar::createInstance(
    'Asia/Tehran',
    '@calendar=persian'
);

$conn = ConnectdDB();

$sql = "DELETE FROM game
		WHERE league='1'";
$conn->query($sql);

$iran = "http://www.varzesh3.com/leagues/%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1-%D8%A7%DB%8C%D8%B1%D8%A7%D9%86/96-97";
//$england = "http://www.varzesh3.com/leagues/%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1-%D8%A7%D9%86%DA%AF%D9%84%DB%8C%D8%B3/2016-2017";
//$germany = "http://www.varzesh3.com/leagues/%D8%A8%D9%88%D9%86%D8%AF%D8%B3%D9%84%DB%8C%DA%AF%D8%A7/2016-2017";
//$spain = "http://www.varzesh3.com/leagues/%D9%84%D8%A7%D9%84%DB%8C%DA%AF%D8%A7/2016-2017";
//$italy = "http://www.varzesh3.com/leagues/%D8%B3%D8%B1%DB%8C-%D8%A2/2016-2017";

$html = str_get_html(getPage($iran));

$table_count = count($html->find('table'));
if($table_count == 3) {
	$table = $html->find('table', 0);
	$goal = $html->find('table', 1);
	$game = $html->find('table', 2);
} else {
	$table = $html->find('table', 0);
	$game = $html->find('table', 1);
}

$row_count = count($game->find('tr'));
$id = 1001;
for($x = 0; $x < $row_count; $x++) {
	if($game->find('tr', $x)->class == "match-date") {
		$date = str_replace(" ", "", strip_tags($game->find('tr', $x)));
		$y = substr($date, 0, 4) - 1;
		$m = substr($date, 5, 2) - 1;
		$d = substr($date, 8, 2) - 1;
		$y += 1;
		$d += 1;
		$date_game->set($y, $m, $d);
		$dateSave =  ConvertPer2En($date_K->format($date_game));
	} else {
		$row = $game->find('tr', $x);
		$td_host = trim(strip_tags($row->find('td', 0)));
		$td_time = str_replace(" ", "", strip_tags($row->find('td', 2)));
		$td_guest = trim(strip_tags($row->find('td', 4)));

		$sql = "INSERT INTO game (id, league, date, date_num, time, t_guest, g_guest, g_host, t_host, status, status_des)
					   VALUES ('" . $id . "', '1', '" . $dateSave . "', '" . $date . "',
					           '" . $td_time . "', '" . $td_guest . "', '0', '0',
							   '" . $td_host . "', '0', '?')";
		$conn->query($sql);
		$id++;
	}
}

?>