<?php

parse_str($argv[1], $params);

if($params['pass'] == "-" || $_GET['pass'] == "-") {
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

function DelChartRecords($conn, $ch) {
	$sql = "DELETE FROM " . $ch;
	$conn->query($sql);
}

$chart["iran"] = "http://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1-%D8%AE%D9%84%DB%8C%D8%AC-%D9%81%D8%A7%D8%B1%D8%B3-97-96";
$chart["england"] = "http://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A7%D9%86%DA%AF%D9%84%DB%8C%D8%B3-2017-2018-%D9%84%DB%8C%DA%AF-%D8%A8%D8%B1%D8%AA%D8%B1";
$chart["germany"] = "http://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A2%D9%84%D9%85%D8%A7%D9%86-2017-2018-%D8%A8%D9%88%D9%86%D8%AF%D8%B3%D9%84%DB%8C%DA%AF%D8%A7";
$chart["spain"] = "http://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A7%D8%B3%D9%BE%D8%A7%D9%86%DB%8C%D8%A7-2017-2018-%D9%84%D8%A7%D9%84%DB%8C%DA%AF%D8%A7";
$chart["italy"] = "http://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D8%A7%DB%8C%D8%AA%D8%A7%D9%84%DB%8C%D8%A7-2017-2018-%D8%B3%D8%B1%DB%8C-%D8%A2";
$chart["france"] = "http://www.varzesh3.com/table/%D8%AC%D8%AF%D9%88%D9%84-%D9%81%D8%B1%D8%A7%D9%86%D8%B3%D9%87-2017-2018-%D9%84%D9%88%D8%B4%D8%A7%D9%85%D9%BE%DB%8C%D9%88%D9%86%D8%A7";

$conn = ConnectdDB();

foreach($chart as $x => $x_value) {
	$chart_str = "chart_" . substr($x, 0, 2);
	DelChartRecords($conn, $chart_str);

	$html = str_get_html(getPage($x_value));
	$table = $html->find('table', 0);
	$row_count = count($table->find('tr'));

	for($x = 2; $x < $row_count; $x++) {
		$row = $table->find('tr', $x);
		$rate = strip_tags($row->find('td', 0));
		if(strip_tags($row->find('td', 1)->find('a', 0)))
			$team = strip_tags($row->find('td', 1)->find('a', 0));
		else
			$team = trim(strip_tags($row->find('td', 1)));
		$game = strip_tags($row->find('td', 2));
		$point = strip_tags($row->find('td', 9));

		$sql = "INSERT INTO " . $chart_str . " (rate, team, game, point)
					   VALUES ('" . $rate . "', '" . $team . "', '" . $game . "', '" . $point . "')";
		$conn->query($sql);
	}
}

$conn->close();
} else
	echo "Forbidden!";

?>