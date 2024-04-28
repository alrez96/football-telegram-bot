<?php

if($_GET['pass'] == "-") {
// Define token's bot
define("token", "-");

// Perform api request
function makeRequest($method, $data=[]) {
    $url = "https://api.telegram.org/bot".token."/".$method;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    $result = curl_exec($ch);
	curl_close ($ch);
	return json_decode($result);
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

function ConvertNum2Emo($str) {
    $emoji = ["\x30\xe2\x83\xa3", "\x31\xe2\x83\xa3", "\x32\xe2\x83\xa3", "\x33\xe2\x83\xa3",
			  "\x34\xe2\x83\xa3", "\x35\xe2\x83\xa3", "\x36\xe2\x83\xa3", "\x37\xe2\x83\xa3",
			  "\x38\xe2\x83\xa3", "\x39\xe2\x83\xa3"];
    $num = range(0, 9);
    $enojiNum = str_replace($num, $emoji, strrev($str));

    return $enojiNum;
}

function ConvertEn2Per($str) {
    $persian = ["۰", "۱", "۲", "۳", "۴", "۵", "۶", "۷", "۸", "۹"];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($num, $persian, $str);

    return $convertedPersianNums;
}

function NewUser($conn, $data) {
	$sql = "INSERT INTO user (id, username, firstname, label, symbol,
							  favteam, honor, level, reg_date, point,
							  point_week, point_month, invite, invite_week,
							  invite_month, edit_name, edit_label, edit_symbol,
							  edit_favteam, edit_full, report, message, stop,
							  cancel, block, uplevel, get_gID, get_result, gameID, chance,
							  league, forecast_1, forecast_2, forecast_3, forecast_4,
							  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
							  forecast_10, forecast_11, forecast_12, forecast_13, forecast_14,
							  forecast_15)
			VALUES ('" . $data[0] . "', '" . $data[1] . "', '" . $data[2] . "',
					'" . $data[3] . "', '" . $data[4] . "', '" . $data[5] . "',
					'" . $data[6] . "', '" . $data[7] . "', '" . $data[8] . "',
					'" . $data[9] . "', '" . $data[10]. "', '" . $data[11]. "',
					'" . $data[12] . "', '" . $data[13] . "', '" . $data[14] . "',
					'" . $data[15] . "', '" . $data[16] . "', '" . $data[17] . "',
					'" . $data[18] . "', '" . $data[19] . "', '" . $data[20] . "',
					'" . $data[21] . "', '" . $data[22] . "', '" . $data[23] . "',
					'" . $data[24] . "', '" . $data[25] . "', '" . $data[26] . "',
					'" . $data[27] . "', '" . $data[28] . "', '" . $data[29] . "',
					'" . $data[30] . "', '" . $data[31] . "', '" . $data[32] . "',
					'" . $data[33] . "', '" . $data[34]. "', '" . $data[35]. "',
					'" . $data[36] . "', '" . $data[37] . "', '" . $data[38] . "',
					'" . $data[39] . "', '" . $data[40] . "', '" . $data[41] . "',
					'" . $data[42] . "', '" . $data[43] . "', '" . $data[44] . "',
					'" . $data[45] . "')";

	$conn->query($sql);
}

function SelectUser($conn, $user_id) {
	$sql = "SELECT id FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	if($result->num_rows == 0) {
		$result->free();
		return true;
	}
	else {
		$result->free();
		return false;
	}
}

function GetUser($conn, $user_id) {
	$sql = "SELECT firstname, label, symbol, level, honor, favteam, reg_date FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$data = array($row["firstname"], $row["label"], $row["symbol"], $row["reg_date"], $row["level"], $row["honor"], $row["favteam"]);
	$result->free();
	return $data;
}

function GetLevel($conn, $user_id) {
	$sql = "SELECT level FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["level"];
}

function GetLevelTime($conn, $user_id) {
	$sql = "SELECT days FROM upgrade
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["days"];
}

function SetBool($conn, $user_id) {
	$sql = "UPDATE user SET stop='false', report='false',
	message='false', uplevel='false', edit_label='false', edit_symbol='false',
    edit_name='false', edit_favteam='false', edit_full='false',
	get_gID='false', get_result='false', gameID=''  WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetStop($conn, $set, $user_id) {
	$sql = "UPDATE user SET stop='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetStop($conn, $user_id) {
	$sql = "SELECT stop FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["stop"];
}

function SetUplevel($conn, $set, $user_id) {
	$sql = "UPDATE user SET uplevel='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetUplevel($conn, $user_id) {
	$sql = "SELECT uplevel FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["uplevel"];
}

function SetReport($conn, $set, $user_id) {
	$sql = "UPDATE user SET report='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetReport($conn, $user_id) {
	$sql = "SELECT report FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["report"];
}

function SetMessage($conn, $set, $user_id) {
	$sql = "UPDATE user SET message='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetMessage($conn, $user_id) {
	$sql = "SELECT message FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["message"];
}

function SetLabelEdit($conn, $set, $user_id) {
	$sql = "UPDATE user SET edit_label='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetLabelEdit($conn, $user_id) {
	$sql = "SELECT edit_label FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["edit_label"];
}

function SetLabel($conn, $set, $user_id) {
	$sql = "UPDATE user SET label='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetSymbolEdit($conn, $set, $user_id) {
	$sql = "UPDATE user SET edit_symbol='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetSymbolEdit($conn, $user_id) {
	$sql = "SELECT edit_symbol FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["edit_symbol"];
}

function SetSymbol($conn, $set, $user_id) {
	$sql = "UPDATE user SET symbol='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetNameEdit($conn, $set, $user_id) {
	$sql = "UPDATE user SET edit_name='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetNameEdit($conn, $user_id) {
	$sql = "SELECT edit_name FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["edit_name"];
}

function SetName($conn, $set, $user_id) {
	$sql = "UPDATE user SET firstname='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetFavEdit($conn, $set, $user_id) {
	$sql = "UPDATE user SET edit_favteam='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetFavEdit($conn, $user_id) {
	$sql = "SELECT edit_favteam FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["edit_favteam"];
}

function SetFavTeam($conn, $set, $user_id) {
	$sql = "UPDATE user SET favteam='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetFullEdit($conn, $set, $user_id) {
	$sql = "UPDATE user SET edit_name='" . $set . "',
							edit_label='" . $set . "',
							edit_symbol='" . $set . "',
							edit_favteam='" . $set . "',
							edit_full='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetFull($conn, $set, $user_id) {
	$sql = "UPDATE user SET edit_full='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetFullEdit($conn, $user_id) {
	$sql = "SELECT edit_full FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["edit_full"];
}

function AddInvite($conn, $set, $user_id) {
	$sql = "SELECT invite, invite_week, invite_month FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$row["invite"] += $set;
	$row["invite_week"] += $set;
	$row["invite_month"] += $set;
	$sql = "UPDATE user SET invite='" . $row["invite"] . "',
							invite_week='" . $row["invite_week"] . "',
							invite_month='" . $row["invite_month"] . "' WHERE id=" . $user_id;
	$conn->query($sql);
	$result->free();
}

function GetUsername($conn, $user_id) {
	$sql = "SELECT username FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["username"];
}

function SetUsername($conn, $set, $user_id) {
	$sql = "UPDATE user SET username='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetTopSeason($conn) {
	$sql = "SELECT label, symbol, point FROM user
	        ORDER BY point DESC
			LIMIT 5";
	$result = $conn->query($sql);
	$num = 0;
	while ($row = $result->fetch_assoc()) {
        $data[$num] = $row["label"];
		$num++;
		$data[$num] = $row["symbol"];
		$num++;
		$data[$num] = $row["point"];
		$num++;
    }
	if($data[2] == 0) {
		$result->free();
		return $str = "\xf0\x9f\x94\xa2 رده‌بندی موجود نیست!";
	}
	$rep = 0;
	$rate = 1;
	$str = ConvertNum2Emo($rate) . " کاربر: " .    $data[0] . " " . $data[1] . "  امتیاز: *" .   $data[2] . "*";
	if($data[5] == 0) {
		$result->free();
		return $str;
	} elseif($data[5] == $data[2]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  امتیاز: *" .   $data[5] . "*";
		$rep++;
	} elseif($data[5] < $data[2]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  امتیاز: *" .   $data[5] . "*";
	}
	if($data[8] == 0) {
		$result->free();
		return $str;
	} elseif($data[8] == $data[5]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  امتیاز: *" .   $data[8] . "*";
		$rep++;
	} elseif($data[8] < $data[5]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  امتیاز: *" .   $data[8] . "*";
	}
	if($data[11] == 0) {
		$result->free();
		return $str;
	} elseif($data[11] == $data[8]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  امتیاز: *" .   $data[11] . "*";
		$rep++;
	} elseif($data[11] < $data[8]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  امتیاز: *" .   $data[11] . "*";
	}
	if($data[14] == 0) {
		$result->free();
		return $str;
	} elseif($data[14] == $data[11]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  امتیاز: *" .   $data[14] . "*";
	} elseif($data[14] < $data[11]) {
		$rate++;
		$rate += $rep;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  امتیاز: *" .   $data[14] . "*";
	}

	$result->free();
	return $str;
}

function GetInviteSeason($conn) {
	$sql = "SELECT label, symbol, invite FROM user
	        ORDER BY invite DESC
			LIMIT 3";
	$result = $conn->query($sql);
	$num = 0;
	while ($row = $result->fetch_assoc()) {
        $data[$num] = $row["label"];
		$num++;
		$data[$num] = $row["symbol"];
		$num++;
		$data[$num] = $row["invite"];
		$num++;
    }
	if($data[2] == 0) {
		$result->free();
		return $str = "\xf0\x9f\x94\xa2 رده‌بندی موجود نیست!";
	}
	$rep = 0;
	$rate = 1;
	$str = ConvertNum2Emo($rate) . " کاربر: " .    $data[0] . " " . $data[1] . "  با *" .   $data[2] . "* نفر";
	if($data[5] == 0) {
		$result->free();
		return $str;
	} elseif($data[5] == $data[2]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  با *" .   $data[5] . "* نفر";
		$rep++;
	} elseif($data[5] < $data[2]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  با *" .   $data[5] . "* نفر";
	}
	if($data[8] == 0) {
		$result->free();
		return $str;
	} elseif($data[8] == $data[5]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  با *" .   $data[8] . "* نفر";
	} elseif($data[8] < $data[5]) {
		$rate++;
		$rate += $rep;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  با *" .   $data[8] . "* نفر";
	}

	$result->free();
	return $str;
}

function GetTopMonth($conn) {
	$sql = "SELECT label, symbol, point_month FROM user
	        ORDER BY point_month DESC
			LIMIT 10";
	$result = $conn->query($sql);
	$num = 0;
	while ($row = $result->fetch_assoc()) {
        $data[$num] = $row["label"];
		$num++;
		$data[$num] = $row["symbol"];
		$num++;
		$data[$num] = $row["point_month"];
		$num++;
    }
	if($data[2] == 0) {
		$result->free();
		return $str = "\xf0\x9f\x94\xa2 رده‌بندی موجود نیست!";
	}
	$rep = 0;
	$rate = 1;
	$str = ConvertNum2Emo($rate) . " کاربر: " .    $data[0] . " " . $data[1] . "  امتیاز: *" .   $data[2] . "*";
	if($data[5] == 0) {
		$result->free();
		return $str;
	} elseif($data[5] == $data[2]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  امتیاز: *" .   $data[5] . "*";
		$rep++;
	} elseif($data[5] < $data[2]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  امتیاز: *" .   $data[5] . "*";
	}
	if($data[8] == 0) {
		$result->free();
		return $str;
	} elseif($data[8] == $data[5]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  امتیاز: *" .   $data[8] . "*";
		$rep++;
	} elseif($data[8] < $data[5]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  امتیاز: *" .   $data[8] . "*";
	}
	if($data[11] == 0) {
		$result->free();
		return $str;
	} elseif($data[11] == $data[8]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  امتیاز: *" .   $data[11] . "*";
		$rep++;
	} elseif($data[11] < $data[8]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  امتیاز: *" .   $data[11] . "*";
	}
	if($data[14] == 0) {
		$result->free();
		return $str;
	} elseif($data[14] == $data[11]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  امتیاز: *" .   $data[14] . "*";
		$rep++;
	} elseif($data[14] < $data[11]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  امتیاز: *" .   $data[14] . "*";
	}
	if($data[17] == 0) {
		$result->free();
		return $str;
	} elseif($data[17] == $data[14]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[15] . " " . $data[16] . "  امتیاز: *" .   $data[17] . "*";
		$rep++;
	} elseif($data[17] < $data[14]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[15] . " " . $data[16] . "  امتیاز: *" .   $data[17] . "*";
	}
	if($data[20] == 0) {
		$result->free();
		return $str;
	} elseif($data[20] == $data[17]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[18] . " " . $data[19] . "  امتیاز: *" .   $data[20] . "*";
		$rep++;
	} elseif($data[20] < $data[17]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[18] . " " . $data[19] . "  امتیاز: *" .   $data[20] . "*";
	}
	if($data[23] == 0) {
		$result->free();
		return $str;
	} elseif($data[23] == $data[20]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[21] . " " . $data[22] . "  امتیاز: *" .   $data[23] . "*";
		$rep++;
	} elseif($data[23] < $data[20]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[21] . " " . $data[22] . "  امتیاز: *" .   $data[23] . "*";
	}
	if($data[26] == 0) {
		$result->free();
		return $str;
	} elseif($data[26] == $data[23]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[24] . " " . $data[25] . "  امتیاز: *" .   $data[26] . "*";
		$rep++;
	} elseif($data[26] < $data[23]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[24] . " " . $data[25] . "  امتیاز: *" .   $data[26] . "*";
	}
	if($data[29] == 0) {
		$result->free();
		return $str;
	} elseif($data[29] == $data[26]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[27] . " " . $data[28] . "  امتیاز: *" .   $data[29] . "*";
	} elseif($data[29] < $data[26]) {
		$rate++;
		$rate += $rep;
		if($rate == 10)
			$str .= "\n\xf0\x9f\x94\x9f کاربر: " . $data[27] . " " . $data[28] . "  امتیاز: *" .   $data[29] . "*";
		else
			$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[27] . " " . $data[28] . "  امتیاز: *" .   $data[29] . "*";
	}

	$result->free();
	return $str;
}

function GetInviteMonth($conn) {
	$sql = "SELECT label, symbol, invite_month FROM user
	        ORDER BY invite_month DESC
			LIMIT 5";
	$result = $conn->query($sql);
	$num = 0;
	while ($row = $result->fetch_assoc()) {
        $data[$num] = $row["label"];
		$num++;
		$data[$num] = $row["symbol"];
		$num++;
		$data[$num] = $row["invite_month"];
		$num++;
    }
	if($data[2] == 0) {
		$result->free();
		return $str = "\xf0\x9f\x94\xa2 رده‌بندی موجود نیست!";
	}
	$rep = 0;
	$rate = 1;
	$str = ConvertNum2Emo($rate) . " کاربر: " .    $data[0] . " " . $data[1] . "  با *" .   $data[2] . "* نفر";
	if($data[5] == 0) {
		$result->free();
		return $str;
	} elseif($data[5] == $data[2]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  با *" .   $data[5] . "* نفر";
		$rep++;
	} elseif($data[5] < $data[2]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  با *" .   $data[5] . "* نفر";
	}
	if($data[8] == 0) {
		$result->free();
		return $str;
	} elseif($data[8] == $data[5]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  با *" .   $data[8] . "* نفر";
		$rep++;
	} elseif($data[8] < $data[5]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  با *" .   $data[8] . "* نفر";
	}
	if($data[11] == 0) {
		$result->free();
		return $str;
	} elseif($data[11] == $data[8]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  با *" .   $data[11] . "* نفر";
		$rep++;
	} elseif($data[11] < $data[8]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  با *" .   $data[11] . "* نفر";
	}
	if($data[14] == 0) {
		$result->free();
		return $str;
	} elseif($data[14] == $data[11]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  با *" .   $data[14] . "* نفر";
	} elseif($data[14] < $data[11]) {
		$rate++;
		$rate += $rep;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  با *" .   $data[14] . "* نفر";
	}

	$result->free();
	return $str;
}

function GetTopWeek($conn) {
	$sql = "SELECT label, symbol, point_week FROM user
	        ORDER BY point_week DESC
			LIMIT 5";
	$result = $conn->query($sql);
	$num = 0;
	while ($row = $result->fetch_assoc()) {
        $data[$num] = $row["label"];
		$num++;
		$data[$num] = $row["symbol"];
		$num++;
		$data[$num] = $row["point_week"];
		$num++;
    }
	if($data[2] == 0) {
		$result->free();
		return $str = "\xf0\x9f\x94\xa2 رده‌بندی موجود نیست!";
	}
	$rep = 0;
	$rate = 1;
	$str = ConvertNum2Emo($rate) . " کاربر: " .    $data[0] . " " . $data[1] . "  امتیاز: *" .   $data[2] . "*";
	if($data[5] == 0) {
		$result->free();
		return $str;
	} elseif($data[5] == $data[2]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  امتیاز: *" .   $data[5] . "*";
		$rep++;
	} elseif($data[5] < $data[2]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  امتیاز: *" .   $data[5] . "*";
	}
	if($data[8] == 0) {
		$result->free();
		return $str;
	} elseif($data[8] == $data[5]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  امتیاز: *" .   $data[8] . "*";
		$rep++;
	} elseif($data[8] < $data[5]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  امتیاز: *" .   $data[8] . "*";
	}
	if($data[11] == 0) {
		$result->free();
		return $str;
	} elseif($data[11] == $data[8]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  امتیاز: *" .   $data[11] . "*";
		$rep++;
	} elseif($data[11] < $data[8]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[9] . " " . $data[10] . "  امتیاز: *" .   $data[11] . "*";
	}
	if($data[14] == 0) {
		$result->free();
		return $str;
	} elseif($data[14] == $data[11]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  امتیاز: *" .   $data[14] . "*";
	} elseif($data[14] < $data[11]) {
		$rate++;
		$rate += $rep;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[12] . " " . $data[13] . "  امتیاز: *" .   $data[14] . "*";
	}

	$result->free();
	return $str;
}

function GetInviteWeek($conn) {
	$sql = "SELECT label, symbol, invite_week FROM user
	        ORDER BY invite_week DESC
			LIMIT 3";
	$result = $conn->query($sql);
	$num = 0;
	while ($row = $result->fetch_assoc()) {
        $data[$num] = $row["label"];
		$num++;
		$data[$num] = $row["symbol"];
		$num++;
		$data[$num] = $row["invite_week"];
		$num++;
    }
	if($data[2] == 0) {
		$result->free();
		return $str = "\xf0\x9f\x94\xa2 رده‌بندی موجود نیست!";
	}
	$rep = 0;
	$rate = 1;
	$str = ConvertNum2Emo($rate) . " کاربر: " .    $data[0] . " " . $data[1] . "  با *" .   $data[2] . "* نفر";
	if($data[5] == 0) {
		$result->free();
		return $str;
	} elseif($data[5] == $data[2]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  با *" .   $data[5] . "* نفر";
		$rep++;
	} elseif($data[5] < $data[2]) {
		$rate++;
		$rate += $rep;
		$rep = 0;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[3] . " " . $data[4] . "  با *" .   $data[5] . "* نفر";
	}
	if($data[8] == 0) {
		$result->free();
		return $str;
	} elseif($data[8] == $data[5]) {
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  با *" .   $data[8] . "* نفر";
	} elseif($data[8] < $data[5]) {
		$rate++;
		$rate += $rep;
		$str .= "\n" . ConvertNum2Emo($rate) . " کاربر: " . $data[6] . " " . $data[7] . "  با *" .   $data[8] . "* نفر";
	}

	$result->free();
	return $str;
}

function GetBlock($conn, $user_id) {
	$sql = "SELECT block FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	if($result->num_rows == 0) {
		$result->free();
		return 0;
	}
	else {
		$row = $result->fetch_assoc();
		$result->free();
		return $row["block"];
	}
}

function SetBlock($conn, $set, $user_id) {
	$sql = "UPDATE user SET block='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetCancel($conn, $user_id) {
	$sql = "SELECT cancel FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["cancel"];
}

function SetCancel($conn, $set, $user_id) {
	$sql = "UPDATE user SET cancel='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SelectLabel($conn, $label) {
	$sql = "SELECT label FROM user
	        WHERE label='" . $label . "'";
	$result = $conn->query($sql);
	if($result->num_rows == 0) {
		$result->free();
		return false;
	}
	else {
		$result->free();
		return true;
	}
}

function GetUserRate($conn, $set, $user_id) {
	if($set == 1)
		$select = "point";
	elseif($set == 2)
		$select = "point_month";
	elseif($set == 3)
		$select = "point_week";
	elseif($set == 4)
		$select = "invite";
	elseif($set == 5)
		$select = "invite_month";
	elseif($set == 6)
		$select = "invite_week";
	$sql = "SELECT id, label, symbol, " . $select . " FROM user
	        ORDER BY " . $select . " DESC";
	$result = $conn->query($sql);
	$data[0] = 0;
	$rep = 0;
	$keep = 0;
	while ($row = $result->fetch_assoc()) {
        if($row["id"] == $user_id || $row[$select] == 0) {
			if($rep == 0)
				$data[0]++;
			if($rep > 0 && $row[$select] != $keep) {
				$data[0]++;
				$data[0] += $rep;
			}
			if($row[$select] == $keep && $rep == 0)
				$data[0]--;
			$data[1] = $row["label"];
			$data[2] = $row["symbol"];
			$data[3] = $row[$select];
			break;
		} elseif($row[$select] == $keep) {
			$rep++;
		} else {
			$data[0]++;
			$data[0] += $rep;
			$rep = 0;
			$keep = $row[$select];
		}
    }
	$result->free();
	return $data;
}

function SetDate($conn, $set, $user_id) {
	$sql = "UPDATE user SET reg_date='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function SetLeague($conn, $set, $user_id) {
	$sql = "UPDATE user SET league='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetLeague($conn, $user_id) {
	$sql = "SELECT league FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["league"];
}

function ShowGame($conn, $league) {
	$sql = "SELECT * FROM game
	        WHERE league='" . $league . "' AND status='0'";
	$str = "";
	$result = $conn->query($sql);
	if($result->num_rows == 0) {
		$str = "\xe2\x9a\xa0 بازی برای پیش‌بینی تعریف نشده" . "1";
	} else {
		while($row = $result->fetch_assoc()) {
			$str .= "\xf0\x9f\x93\x86 " . ConvertEn2Per($row["date"]) . "  \xf0\x9f\x95\x93 "
			. $row["time"] . "\n\xf0\x9f\x94\x91 شماره بازی: *" . $row["id"]
			. "*\n\xe2\x9a\xbd\xef\xb8\x8f " . $row["t_host"] . " *" . $row["g_host"] . "* - *"
			. $row["g_guest"] . "* " . $row["t_guest"] . "\n"
			. "\xf0\x9f\x94\xb9 وضعیت: " . $row["status_des"]
			. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
		}
	}
	$result->free();
	return $str;
}

function CheckGameStatus($conn, $league, $game_id) {
	$sql = "SELECT status FROM game
	        WHERE league='" . $league . "' AND id='" . $game_id . "'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if($row["status"] > 0 && $row["status"] < 3) {
		$result->free();
		return true;
	} else {
		$result->free();
		return false;
	}
}

function CheckGameID($conn, $league, $game_id, $user_id) {
	$sql = "SELECT * FROM game
	        WHERE league='" . $league . "' AND id='" . $game_id . "'";
	$result = $conn->query($sql);
	$sql_ch = "SELECT chance, forecast_1, forecast_2, forecast_3, forecast_4,
					  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
					  forecast_10, forecast_11, forecast_12, forecast_13,
					  forecast_14, forecast_15 FROM user
			   WHERE id=" . $user_id;
	$result_ch = $conn->query($sql_ch);
	$row_ch = $result_ch->fetch_assoc();
	$str = "";
	$IsFor = false;
	for($x = 1; $x <= 15; $x++) {
		if(strpos($row_ch["forecast_" . $x], $game_id) !== false) {
			$IsFor = true;
			break;
		}
	}
	if($result->num_rows == 0) {
		$str .= "\xe2\x9a\xa0 بازی با این شماره وجود نداره"
				. "\nفقط میتونی یکی از شماره های بالا رو وارد کنی" . "0";
	} else {
		$row = $result->fetch_assoc();
		if($row["status"] == 1) {
			$str .= "\xe2\x9a\xa0 این بازی شروع شده\n"
			. "بنابراین نمیتونی پیش‌بینی کنی یا حرفتو عوض کنی" . "0";
		} elseif($row["status"] == 2) {
			$str .= "\xe2\x9a\xa0 این بازی تموم شده" . "0";
		} elseif($row["status"] == 3 || $row["status"] == 4) {
			if($IsFor) {
				if($row["status"] == 4) {
					$str .= "\xe2\x9a\xa0 وضعیت برگزاری این بازی مشخص نیست\n"
					. "میتونی حذفش کنی" . "4";
				} else {
					$str .= "\xe2\x9a\xa0 این بازی لغو شده\n"
					. "میتونی حذفش کنی" . "4";
				}
			} else {
				$str .= "\xe2\x9a\xa0 این بازی لغو شده\n"
				. "وقتی تاریخ برگزاری مشخص بشه مجدد قابل پیش‌بینی میشه" . "5";
			}
		} elseif($IsFor) {
			$str .= "قبلا این بازی رو پیش‌بینی کردی\nحالا میتونی اصلاح یا حذفش کنی:\n\n\xf0\x9f\x93\x86 "
			. ConvertEn2Per($row["date"]) . "  \xf0\x9f\x95\x93 "
			. $row["time"] . "\n\xf0\x9f\x94\x91 شماره بازی: *" . $row["id"]
			. "*\n\xe2\x9a\xbd\xef\xb8\x8f " . $row["t_host"] . " *" . $row["g_host"] . "* - *"
			. $row["g_guest"] . "* " . $row["t_guest"] . "\n"
			. "\xf0\x9f\x94\xb9 وضعیت: " . $row["status_des"] . "\n\n"
			. "نتیجه مورد نظرت رو به این صورت وارد کن:\nگل تیم سمت راست-گل تیم سمت چپ\n"
			. "\xe2\x9c\x85 مثال ورودی صحیح: 2-1" . "3";
		} elseif($row_ch["chance"] == 0) {
			$str .= "\xe2\x9a\xa0 از همه فرصت های پیش‌بینی این هفته استفاده کردی"
					."\n\xe2\x9c\x85 با ارتقاء سطح عضویتت از بخش\n"
					. "\xF0\x9F\x91\xA4 پروفایل من > \xe2\x9c\x8a ارتقاء سطح عضویت\n"
					. "تا 15 بازی رو در هفته پیش‌بینی کن!" . "2";
		} else {
			$str .= "قصد پیش‌بینی بازی زیر رو داری:\n\n\xf0\x9f\x93\x86 " . ConvertEn2Per($row["date"]) . "  \xf0\x9f\x95\x93 "
			. $row["time"] . "\n\xf0\x9f\x94\x91 شماره بازی: *" . $row["id"]
			. "*\n\xe2\x9a\xbd\xef\xb8\x8f " . $row["t_host"] . " *" . $row["g_host"] . "* - *"
			. $row["g_guest"] . "* " . $row["t_guest"] . "\n"
			. "\xf0\x9f\x94\xb9 وضعیت: " . $row["status_des"] . "\n\n"
			. "نتیجه مورد نظرت رو به این صورت وارد کن:\nگل تیم سمت راست-گل تیم سمت چپ\n"
			. "\xe2\x9c\x85 مثال ورودی صحیح: 2-1" . "1";
		}
	}
	$result->free();
	return $str;
}

function SetGameID($conn, $set, $user_id) {
	$sql = "UPDATE user SET get_gID='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetGameID($conn, $user_id) {
	$sql = "SELECT get_gID FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["get_gID"];
}

function SaveGameID($conn, $set, $user_id) {
	$sql = "UPDATE user SET gameID='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetSaveGameID($conn, $user_id) {
	$sql = "SELECT gameID FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["gameID"];
}

function SetGameResult($conn, $set, $user_id) {
	$sql = "UPDATE user SET get_result='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
}

function GetGameResult($conn, $user_id) {
	$sql = "SELECT get_result FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["get_result"];
}

function CheckResult($str) {
	if(preg_match("/^([1-9]?[0-9]{1})-([1-9]?[0-9]{1})$/", $str))
		return true;
	else
		return false;
}

function SetGameForecast($conn, $set, $user_id) {
	$id = substr($set, 0, 7);
	$sql = "SELECT    forecast_1, forecast_2, forecast_3, forecast_4,
					  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
					  forecast_10, forecast_11, forecast_12, forecast_13,
					  forecast_14, forecast_15 FROM user
			   WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$keep = 0;
	$ret = 0;
	for($x = 1; $x <= 15; $x++) {
		if(strpos($row["forecast_" . $x], $id) !== false) {
			$keep = $x;
			$ret = 1;
			break;
		} elseif($row["forecast_" . $x] == "") {
			$keep = $x;
			$ret = 0;
		}
	}

	$sql = "UPDATE user SET forecast_" . $keep . "='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
	$result->free();
	return $ret;
}

function SetChance($conn, $set, $user_id) {
	$sql = "SELECT chance FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$row["chance"] += $set;
	$sql = "UPDATE user SET chance='" . $row["chance"] . "' WHERE id=" . $user_id;
	$conn->query($sql);
	$result->free();
}

function GetChance($conn, $user_id) {
	$sql = "SELECT chance FROM user
	        WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	return $row["chance"];
}

function DelResult($conn, $str, $user_id) {
	$sql = "SELECT    forecast_1, forecast_2, forecast_3, forecast_4,
					  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
					  forecast_10, forecast_11, forecast_12, forecast_13,
					  forecast_14, forecast_15 FROM user
			   WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$keep = 0;
	$set = "";
	for($x = 1; $x <= 15; $x++) {
		if(strpos($row["forecast_" . $x], $str) !== false) {
			$keep = $x;
			break;
		}
	}

	$sql = "UPDATE user SET forecast_" . $keep . "='" . $set . "' WHERE id=" . $user_id;
	$conn->query($sql);
	$result->free();
}

function GetFullForecast($conn, $league, $user_id) {
	$sql = "SELECT    forecast_1, forecast_2, forecast_3, forecast_4,
					  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
					  forecast_10, forecast_11, forecast_12, forecast_13,
					  forecast_14, forecast_15 FROM user
			   WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$str = "";
	for($x = 1; $x <= 15; $x++) {
		if($row["forecast_" . $x] == "")
			$str .= "";
		else {
			$game_id = substr($row["forecast_" . $x], 0, 4);
			$league_for = substr($row["forecast_" . $x], 5, 1);
			$pos = strpos($row["forecast_" . $x], ")") + 2;
			$goal_g = substr($row["forecast_" . $x], $pos, (strpos($row["forecast_" . $x], "-")) - $pos);
			$goal_h = substr($row["forecast_" . $x], strpos($row["forecast_" . $x], "-") + 1);
			$sql_game = "SELECT * FROM game
					WHERE league='" . $league . "' AND id='" . $game_id . "'";
			$result_game = $conn->query($sql_game);
			$row_game = $result_game->fetch_assoc();
			if($league == $league_for) {
				if($row_game["status"] == 2) {
					$point = substr($goal_h, strpos($goal_h, " ") + 1);
					$temp = $goal_h;
					$goal_h = substr($temp, 0, strpos($goal_h, " "));
					if($goal_h == $row_game["g_host"]) {
						$temp = $goal_h;
						$goal_h = "*" . $temp . "*";
					}
					if($goal_g == $row_game["g_guest"]) {
						$temp = $goal_g;
						$goal_g = "*" . $temp . "*";
					}
					$icon = "\xf0\x9f\x94\xbb";
					$str .= "\xe2\x9a\xbd\xef\xb8\x8f " . $row_game["t_host"] . " *" . $row_game["g_host"] . "* - *"
						. $row_game["g_guest"] . "* " . $row_game["t_guest"] . "\n"
						. $icon . " وضعیت: " . $row_game["status_des"] . "  \xf0\x9f\x92\xb0 *" . $point . "* امتیاز\n"
						. "\xf0\x9f\xa4\x94 " . $row_game["t_host"] . " " . $goal_h . " - " . $goal_g . " " . $row_game["t_guest"]
						. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
				} else {
					if($goal_h == $row_game["g_host"]) {
						$temp = $goal_h;
						$goal_h = "*" . $temp . "*";
					}
					if($goal_g == $row_game["g_guest"]) {
						$temp = $goal_g;
						$goal_g = "*" . $temp . "*";
					}
					if($row_game["status"] == 1) {
						$icon = "\xf0\x9f\x94\x8a گزارش زنده: ";
						$str .= "\xe2\x9a\xbd\xef\xb8\x8f " . $row_game["t_host"] . " *" . $row_game["g_host"] . "* - *"
								. $row_game["g_guest"] . "* " . $row_game["t_guest"] . "\n"
								. $icon . $row_game["status_des"] . "\n"
								. "\xf0\x9f\xa4\x94 " . $row_game["t_host"] . " " . $goal_h . " - " . $goal_g . " " . $row_game["t_guest"]
								. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
					}
					else {
						$icon = "\xf0\x9f\x94\xb9 وضعیت: ";
						$str .= "\xf0\x9f\x93\x86 " . ConvertEn2Per($row_game["date"]) . "  \xf0\x9f\x95\x93 "
								. $row_game["time"] . "\n\xf0\x9f\x94\x91 شماره بازی: *" . $row_game["id"]
								. "*\n\xe2\x9a\xbd\xef\xb8\x8f " . $row_game["t_host"] . " *" . $row_game["g_host"] . "* - *"
								. $row_game["g_guest"] . "* " . $row_game["t_guest"] . "\n"
								. $icon . $row_game["status_des"] . "\n"
								. "\xf0\x9f\xa4\x94 " . $row_game["t_host"] . " " . $goal_h . " - " . $goal_g . " " . $row_game["t_guest"]
								. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
					}
				}
			}
		}
	}
	$result->free();
	if($str == "")
		$str = "\xe2\x9a\xa0 هنوز هیچ بازی تو این لیگ پیش‌بینی نکردی" . "0";
	return $str;
}

function GetPointForecast($conn) {
	$sql = "SELECT    id, point_week, forecast_1, forecast_2, forecast_3, forecast_4,
						forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
						forecast_10, forecast_11, forecast_12, forecast_13,
						forecast_14, forecast_15 FROM user
				WHERE cancel!='1' AND block!='2'
				ORDER BY point_week DESC";
	$result = $conn->query($sql);
	$num = 0;
	$rep = 0;
	$keep = 0;
	while($row = $result->fetch_assoc()) {
		$str = "";
		for($x = 1; $x <= 15; $x++) {
			if(substr_count($row["forecast_" . $x], " ") == 2) {
				$game_id = substr($row["forecast_" . $x], 0, 4);
				$pos = strpos($row["forecast_" . $x], ")") + 2;
				$goal_g = substr($row["forecast_" . $x], $pos, (strpos($row["forecast_" . $x], "-")) - $pos);
				$goal_h = substr($row["forecast_" . $x], strpos($row["forecast_" . $x], "-") + 1);
				$sql_game = "SELECT * FROM game
						WHERE id='" . $game_id . "'";
				$result_game = $conn->query($sql_game);
				$row_game = $result_game->fetch_assoc();
				$point = substr($goal_h, strpos($goal_h, " ") + 1);
				$temp = $goal_h;
				$goal_h = substr($temp, 0, strpos($goal_h, " "));
				if($goal_h == $row_game["g_host"]) {
					$temp = $goal_h;
					$goal_h = "*" . $temp . "*";
				}
				if($goal_g == $row_game["g_guest"]) {
					$temp = $goal_g;
					$goal_g = "*" . $temp . "*";
				}
				$str .= "\xe2\x9a\xbd\xef\xb8\x8f " . $row_game["t_host"] . " *" . $row_game["g_host"] . "* - *"
					. $row_game["g_guest"] . "* " . $row_game["t_guest"] . "\n"
					. "\xf0\x9f\xa4\x94 " . $row_game["t_host"] . " " . $goal_h . " - " . $goal_g . " " . $row_game["t_guest"]
					. " \n\xf0\x9f\x92\xb0 امتیاز کسب شده: *" . $point
					. "*\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
			}
		}
		if($str == "")
			$str = "\xe2\x9a\xa0 این هفته هیچ بازی رو پیش‌بینی نکردی";
		if($row["point_week"] == 0) {
			$prev = "\xf0\x9f\x92\x81 گزارش وضعیتت تو این هفته:\n"
					. "\xf0\x9f\x92\xb0 مجموع امتیاز: *" . $row["point_week"]
					. "*\n\xf0\x9f\x93\x8a رتبه: \x30\xe2\x83\xa3"
					. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
		} else {
			if($keep == $row["point_week"])
				$rep++;
			else {
				$keep = $row["point_week"];
				$num++;
				$num += $rep;
				$rep = 0;
			}
			if($num == 10) {
				$prev = "\xf0\x9f\x92\x81 گزارش وضعیتت تو این هفته:\n"
						. "\xf0\x9f\x92\xb0 مجموع امتیاز: *" . $row["point_week"]
						. "*\n\xf0\x9f\x93\x8a رتبه: \xf0\x9f\x94\x9f"
						. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
			} else {
				$prev = "\xf0\x9f\x92\x81 گزارش وضعیتت تو این هفته:\n"
						. "\xf0\x9f\x92\xb0 مجموع امتیاز: *" . $row["point_week"]
						. "*\n\xf0\x9f\x93\x8a رتبه: " . ConvertNum2Emo($num)
						. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
			}
		}
		if($row["point_week"] != 0) {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $row["id"],
								"text" => $prev . $str,
								"parse_mode" => "markdown"
				])
		);
		}
	}
	$result->free();
}

function GetIsForecast($conn, $for, $user_id) {
	$sql = "SELECT    forecast_1, forecast_2, forecast_3, forecast_4,
					  forecast_5, forecast_6, forecast_7, forecast_8, forecast_9,
					  forecast_10, forecast_11, forecast_12, forecast_13,
					  forecast_14, forecast_15 FROM user
			   WHERE id=" . $user_id;
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$result->free();
	for($x = 1; $x <= 15; $x++) {
		if($row["forecast_" . $x] == "")
			continue;
		else {
			$game_id = substr($row["forecast_" . $x], 0, 7);
			if($game_id == $for)
				return true;
			else
				continue;
		}
	}
	return false;
}

function getChart($conn, $league) {
	$str = "";
	if($league == 1)
		$ch = "chart_ir";
	elseif($league == 2)
		$ch = "chart_en";
	elseif($league == 3)
		$ch = "chart_ge";
	elseif($league == 4)
		$ch = "chart_sp";
	elseif($league == 5)
		$ch = "chart_it";
	else
		return $str = "\xE2\x9A\xA0 هنوز جدولی تعریف نشده\n\n";

	$sql = "SELECT rate, team, game, point
			FROM " .  $ch;
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$str .= "*" . $row["rate"] . "*)  " . $row["team"]
			  . "\n    \xe2\x96\xab بازیها: *" . $row["game"] . "*   \xe2\x96\xaa امتیاز: *" . $row["point"]
			  . "*\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n";
	}
	$result->free();
	return $str .= "\n";
}

function SendMessageToAll($conn, $str) {
	$sql = "SELECT id FROM user WHERE cancel!='1' AND block!='2'";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $row["id"],
								"text" => $str
				])
		);
	}
	$result->free();
}

function SendPhotoToAll($conn, $file_id, $caption) {
	$sql = "SELECT id FROM user WHERE cancel!='1' AND block!='2'";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		var_dump(makeRequest("sendPhoto", [
								"chat_id" => $row["id"],
								"photo" => $file_id,
								"caption" => $caption
				])
		);
	}
	$result->free();
}

function SendGifToAll($conn, $file_id, $caption) {
	$sql = "SELECT id FROM user WHERE cancel!='1' AND block!='2'";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		var_dump(makeRequest("sendDocument", [
								"chat_id" => $row["id"],
								"document" => $file_id,
								"caption" => $caption
				])
		);
	}
	$result->free();
}

function SendMessageToUser($conn, $id, $str) {
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $id,
							"text" => $str
			])
	);
}

function AdminBlockUser($conn, $id, $level) {
	$sql = "UPDATE user SET block='" . $level . "' WHERE id=" . $id;
	$conn->query($sql);
}

function GetBotMembers($conn) {
	$sql = "SELECT id FROM user";
	$result = $conn->query($sql);
	$str = "تعداد کل اعضا:\n" . $result->num_rows . " نفر";
	$result->free();
	return $str;
}

function AdminGetUser($conn, $user) {
	$sql = "SELECT id FROM user WHERE label='" . $user . "'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	if($result->num_rows == 0) {
		$result->free();
		return $str = "پیداش نکردم!";
	} else {
		$result->free();
		return $str = $row["id"];
	}
}

function UserProfReset($conn, $id) {
	$sql = "SELECT firstname FROM user WHERE id='" . $id . "'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$sql = "UPDATE user SET label='" . $row["firstname"] . "',
						    symbol='', favteam='' WHERE id=" . $id;
	$conn->query($sql);
	$result->free();
}

function DelForecast($conn, $league, $gameID) {
	$str = $gameID . "(" . $league . ")";
	$sql = "SELECT id, chance,
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
	$set = "";
	if($result->num_rows != 0) {
		while($row = $result->fetch_assoc()) {
			$row["chance"] += 1;
			$sql_1 = "UPDATE user SET chance='" . $row["chance"] . "', forecast_" . $row["num"] . "='" . $set . "'
						WHERE id=" . $row["id"];
			$conn->query($sql_1);
		}
	}
	$result->free();
}

function DelUpperCase($conn) {
	$sql = "SELECT id, label FROM user WHERE label LIKE '%\_%'";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$row["label"] = str_replace("_", "", $row["label"]);
		$sql_1 = "UPDATE user SET label='" . $row["label"] . "' WHERE id=" . $row["id"];
		$conn->query($sql_1);
	}
	$result->free();
}


// Fetching update
$update = json_decode(file_get_contents("php://input"));

// Define admin id
$admin_message = 420015473;
$admin_report = 420015473;

$conn = ConnectdDB();

// Make update parameters
$chat_id = $update->message->chat->id;
$user_id = $update->message->from->id;
$command = $update->message->text;

$date = new IntlDateFormatter("en_US@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "yyy/MM/dd");
$getDate = $date->format(time());
$date_K = new IntlDateFormatter("fa@calendar=persian", IntlDateFormatter::FULL,
IntlDateFormatter::FULL, 'Asia/Tehran', IntlDateFormatter::TRADITIONAL, "EEE d MMM yyy");
$getFullD = $date_K->format(time());


if($_GET['comm'] == "getfullpoint") {
	GetPointForecast($conn);
}

if($user_id == 420015473) {
	if($command == "هوی") {
		$key_remove = array(
						"remove_keyboard" => true
						);
		$json_key_remove = json_encode($key_remove);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x99\x87 سلام به گنده و سرور من"
								. "\nکدوم یکی رو انجام بدم؟\n\n"
								. "1- sendtoall message\n2- sendtouserXidX message\n"
								. "3- blockuserXidX level\n4- ifPhoto toall\n5- ifGif toall\n"
								. "6- members\n7- useridXlabelX\n8- resetuserXidX\n9- delgameXidX league\n"
								. "10- delupper",
								"reply_markup" => $json_key_remove
				])
		);
	} elseif(substr($command, 0, 9) == "sendtoall") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\xa4\x97 فرستادم برای همه فدای چشات"
				])
		);
		$str = substr($command, 9);
		SendMessageToAll($conn, $str);
	} elseif(substr($command, 0, 10) == "sendtouser") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\xa4\x97 فرستادم برای طرف فداتشم"
				])
		);
		$make = substr($command, strpos($command, "X") + 1);
		$id = substr($make, 0, strpos($make, "X"));
		$message = substr($make, strpos($make, "X") + 1);
		SendMessageToUser($conn, $id, $message);
	} elseif(substr($command, 0, 9) == "blockuser") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 زدم بلاکش کردم قشنگم"
				])
		);
		$make = substr($command, strpos($command, "X") + 1);
		$id = substr($make, 0, strpos($make, "X"));
		$level = substr($make, strpos($make, "X") + 1);
		AdminBlockUser($conn, $id, $level);
	} elseif(isset($update->message->photo)) {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 فرستادم برای همه عجقولکم\n"
								. "\xf0\x9f\x96\xbc عکس قشنگی بودا..."
				])
		);
		$file_id = $update->message->photo[0]->file_id;
		$caption = $update->message->caption;
		SendPhotoToAll($conn, $file_id, $caption);
	} elseif(isset($update->message->document)) {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 فرستادم برای همه عجقولکم\n"
								. "\xf0\x9f\x96\xbc گیفت عالی بود..."
				])
		);
		$file_id = $update->message->document->file_id;
		$caption = $update->message->caption;
		SendGifToAll($conn, $file_id, $caption);
	} elseif($command == "members") {
		$str = GetBotMembers($conn);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xe2\x98\xba تعداد اعضای ربات برای نفسم\n\n"
								. $str
				])
		);
	} elseif(substr($command, 0, 6) == "userid") {
		$make = substr($command, strpos($command, "X") + 1);
		$user = substr($make, 0, strpos($make, "X"));
		$str = AdminGetUser($conn, $user);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 آیدی کاربر اینه قربان\n"
								. $str
				])
		);
	} elseif(substr($command, 0, 9) == "resetuser") {
		$make = substr($command, strpos($command, "X") + 1);
		$id = substr($make, 0, strpos($make, "X"));
		UserProfReset($conn, $id);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 ریستش کردم نازم"
				])
		);
	} elseif(substr($command, 0, 7) == "delgame") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 بازی رو پاک کردم فداتشم"
				])
		);
		$make = substr($command, strpos($command, "X") + 1);
		$gameID = substr($make, 0, strpos($make, "X"));
		$league = substr($make, strpos($make, "X") + 1);
		DelForecast($conn, $league, $gameID);
	} elseif(substr($command, 0, 8) == "delupper") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x98\x82 اصلاحش کردم عزیزم"
				])
		);
		DelUpperCase($conn);
	} else {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "متوجه دستورت نشدم، غلط کردم به خدا \xf0\x9f\x98\x93"
				])
		);
	}
} else {

if(GetBlock($conn, $user_id) == 2) {
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9b\x94 عضویت شما به صورت کامل لغو شده است\n"
							. "دلیل این اتفاق میتونه یکی از موارد زیر باشه:\n"
							. "\xf0\x9f\x94\x9e استفاده از نام، لقب یا نماد نامناسب\n"
							. "\xf0\x9f\x9a\xaf ارسال پیام های مکرر و بی محتوا به پشتیبانی\n\n"
			])
	);
} elseif(GetBlock($conn, $user_id) == 1 && $command != "\xf0\x9f\x93\x84 متن تعهدنامه"
   && $command != "\xf0\x9f\xa4\x9d بله قبول میکنم") {
	$key_block = array(
					"keyboard" => array(
									array("\xf0\x9f\x93\x84 متن تعهدنامه")
								),
					"resize_keyboard" => true
				);
	$json_key_block = json_encode($key_block);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9b\x94 عضویتت به دلیل تخلف صورت گرفته معلق شده\n"
							. "دلیل این اتفاق میتونه یکی از موارد زیر باشه:\n"
							. "\xf0\x9f\x94\x9e استفاده از نام، لقب یا نماد نامناسب\n"
							. "\xf0\x9f\x9a\xaf ارسال پیام های مکرر و بی محتوا به پشتیبانی\n\n"
							. "\xf0\x9f\x91\xae ولی هنوز یه فرصت برای برگشتن داری!\n"
							. "روی گزینه پایین کلیک کن و تعهدنامه رو قبول کن",
							"reply_markup" => $json_key_block
			])
	);
} elseif(substr($command, 0, 6) == "/start" || $command == "صفحه اصلی \xF0\x9F\x94\x99"
       || $command == "عضویت مجدد \xF0\x9F\x94\x99" || $command == "بیخیال برگرد \xF0\x9F\x94\x99") {
	if(SelectUser($conn, $user_id) || GetCancel($conn, $user_id)) {
		$username = $update->message->from->username;
		$user_name = $update->message->from->first_name;
		if(substr($command, 0, 7) == "/start " && SelectUser($conn, $user_id)) {
			$getFriId = base64_decode(substr($command, 7));
			if(!SelectUser($conn, $getFriId)) {
				AddInvite($conn, 1, $getFriId);
			}
		}
		if(GetCancel($conn, $user_id)) {
			SetDate($conn, $getDate, $user_id);
			SetCancel($conn, false, $user_id);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "سلام " . $user_name . " عزیز!\n"
										    . "خوشحالیم که دوباره برگشتی!"
					])
			);
		} else {
			if(SelectLabel($conn, $user_name)) {
				$base = base64_encode($user_id);
				$user_label = $user_name . substr($base, 0, 4);
			}
			else
				$user_label = $user_name;
			$data = array($user_id, $username, $user_name, $user_label, "",
						  "", "", 0, $getDate, 0, 0, 0, 0, 0, 0, false,
						  false, false, false, false, false, false, false,
						  false, 0, false, false, false, "", 7, 0, "", "", "", "",
						  "", "", "", "", "", "", "", "", "", "", "");
			NewUser($conn, $data);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "سلام " . $user_name . " عزیز!\n"
								            . "به ربات پیش‌بینی فوتبال خوش اومدی..."
					])
			);
		}
	}
	SetBool($conn, $user_id);
	$key_menu = array(
					"keyboard" => array(
									array("\xF0\x9F\x94\xB0 لیگ ها"),
									array("\xF0\x9F\x8F\x86 رده‌بندی", "\xF0\x9F\x91\xA4 پروفایل من"),
									array("\xF0\x9F\x91\xAC دعوت از دوستان"),
									array("\xf0\x9f\x91\xa8\xe2\x80\x8d\xf0\x9f\x94\xa7 پشتیبانی", "\xF0\x9F\x92\xA1 راهنما"),
								),
					"resize_keyboard" => true
				);

	$json_Key_menu = json_encode($key_menu);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xF0\x9F\x92\xA1 یه مورد رو انتخاب کن:",
							"reply_markup" => $json_Key_menu
			])
	);
} elseif(GetCancel($conn, $user_id)) {
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xE2\x9A\xA0 شما عضویت خودتون رو لغو کردید\n"
							. "برای عضویت مجدد دستور /start رو بفرست\n"
							. "یا روی گزینه عضویت مجدد کلیک کن"
			])
	);
} elseif($command == "\xF0\x9F\x94\xB0 لیگ ها") {
	SetBool($conn, $user_id);
	$key_league = array(
					"keyboard" => array(
									array("\xf0\x9f\x87\xae\xf0\x9f\x87\xb7 لیگ برتر ایران \xf0\x9f\x87\xae\xf0\x9f\x87\xb7",
										  "\xf0\x9f\x8c\x90 لیگ قهرمانان اروپا \xf0\x9f\x8c\x90"),
									array("\xf0\x9f\x87\xa9\xf0\x9f\x87\xaa بوندسلیگای آلمان \xf0\x9f\x87\xa9\xf0\x9f\x87\xaa",
										  "\xf0\x9f\x87\xac\xf0\x9f\x87\xa7 لیگ برتر انگلیس \xf0\x9f\x87\xac\xf0\x9f\x87\xa7"),
									array("\xf0\x9f\x87\xae\xf0\x9f\x87\xb9 سری آ ایتالیا \xf0\x9f\x87\xae\xf0\x9f\x87\xb9",
										  "\xf0\x9f\x87\xaa\xf0\x9f\x87\xb8 لالیگای اسپانیا \xf0\x9f\x87\xaa\xf0\x9f\x87\xb8"),
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);

	$json_key_league = json_encode($key_league);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "یه لیگ رو انتخاب کن:",
							"reply_markup" => $json_key_league
			])
	);
} elseif($command == "\xf0\x9f\x87\xae\xf0\x9f\x87\xb7 لیگ برتر ایران \xf0\x9f\x87\xae\xf0\x9f\x87\xb7") {
	SetBool($conn, $user_id);
	SetLeague($conn, 1, $user_id);
	$str = ShowGame($conn, 1);
	$status = substr($str, -1);
	SetGameID($conn, true, $user_id);
	$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_into = json_encode($key_league_into);
	if($status == "1") {
		$str = substr($str, 0, -1);
		$str_out = "\xf0\x9f\x94\x84 به زودی بازی های جدید قرار می گیرند";
	} else {
		$str_out = "\xe2\x9c\x85 بازی های بالا برای پیش‌بینی فعال هستن\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id)
							. "*\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str,
							"parse_mode" => "markdown"
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str_out,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_league_into
			])
	);
} elseif($command == "\xf0\x9f\x8c\x90 لیگ قهرمانان اروپا \xf0\x9f\x8c\x90") {
	SetBool($conn, $user_id);
	SetLeague($conn, 7, $user_id);
	$str = ShowGame($conn, 7);
	$status = substr($str, -1);
	SetGameID($conn, true, $user_id);
	$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_into = json_encode($key_league_into);
	if($status == "1") {
		$str = substr($str, 0, -1);
		$str_out = "\xf0\x9f\x94\x84 به زودی بازی های جدید قرار می گیرند";
	} else {
		$str_out = "\xe2\x9c\x85 بازی های بالا برای پیش‌بینی فعال هستن\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id)
							. "*\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str,
							"parse_mode" => "markdown"
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str_out,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_league_into
			])
	);
} elseif($command == "\xf0\x9f\x87\xac\xf0\x9f\x87\xa7 لیگ برتر انگلیس \xf0\x9f\x87\xac\xf0\x9f\x87\xa7") {
	SetBool($conn, $user_id);
	SetLeague($conn, 2, $user_id);
	$str = ShowGame($conn, 2);
	$status = substr($str, -1);
	SetGameID($conn, true, $user_id);
	$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_into = json_encode($key_league_into);
	if($status == "1") {
		$str = substr($str, 0, -1);
		$str_out = "\xf0\x9f\x94\x84 به زودی بازی های جدید قرار می گیرند";
	} else {
		$str_out = "\xe2\x9c\x85 بازی های بالا برای پیش‌بینی فعال هستن\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id)
							. "*\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str,
							"parse_mode" => "markdown"
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str_out,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_league_into
			])
	);
} elseif($command == "\xf0\x9f\x87\xa9\xf0\x9f\x87\xaa بوندسلیگای آلمان \xf0\x9f\x87\xa9\xf0\x9f\x87\xaa") {
	SetBool($conn, $user_id);
	SetLeague($conn, 3, $user_id);
	$str = ShowGame($conn, 3);
	$status = substr($str, -1);
	SetGameID($conn, true, $user_id);
	$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_into = json_encode($key_league_into);
	if($status == "1") {
		$str = substr($str, 0, -1);
		$str_out = "\xf0\x9f\x94\x84 به زودی بازی های جدید قرار می گیرند";
	} else {
		$str_out = "\xe2\x9c\x85 بازی های بالا برای پیش‌بینی فعال هستن\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id)
							. "*\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str,
							"parse_mode" => "markdown"
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str_out,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_league_into
			])
	);
} elseif($command == "\xf0\x9f\x87\xaa\xf0\x9f\x87\xb8 لالیگای اسپانیا \xf0\x9f\x87\xaa\xf0\x9f\x87\xb8") {
	SetBool($conn, $user_id);
	SetLeague($conn, 4, $user_id);
	$str = ShowGame($conn, 4);
	$status = substr($str, -1);
	SetGameID($conn, true, $user_id);
	$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_into = json_encode($key_league_into);
	if($status == "1") {
		$str = substr($str, 0, -1);
		$str_out = "\xf0\x9f\x94\x84 به زودی بازی های جدید قرار می گیرند";
	} else {
		$str_out = "\xe2\x9c\x85 بازی های بالا برای پیش‌بینی فعال هستن\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id)
							. "*\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str,
							"parse_mode" => "markdown"
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str_out,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_league_into
			])
	);
} elseif($command == "\xf0\x9f\x87\xae\xf0\x9f\x87\xb9 سری آ ایتالیا \xf0\x9f\x87\xae\xf0\x9f\x87\xb9") {
	SetBool($conn, $user_id);
	SetLeague($conn, 5, $user_id);
	$str = ShowGame($conn, 5);
	$status = substr($str, -1);
	SetGameID($conn, true, $user_id);
	$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_into = json_encode($key_league_into);
	if($status == "1") {
		$str = substr($str, 0, -1);
		$str_out = "\xf0\x9f\x94\x84 به زودی بازی های جدید قرار می گیرند";
	} else {
		$str_out = "\xe2\x9c\x85 بازی های بالا برای پیش‌بینی فعال هستن\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id)
							. "*\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str,
							"parse_mode" => "markdown"
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str_out,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_league_into
			])
	);
} /*elseif($command == "\xF0\x9F\x9A\xAB بوندسلیگای آلمان \xf0\x9f\x87\xa9\xf0\x9f\x87\xaa" ||
		   $command == "\xF0\x9F\x9A\xAB سری آ ایتالیا \xf0\x9f\x87\xae\xf0\x9f\x87\xb9") {
	SetBool($conn, $user_id);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9a\xa0 این لیگ درحال حاضر غیرفعال است"
			])
	);
} */elseif($command == "\xf0\x9f\x97\x91 حذفش کن") {
	if(GetSaveGameID($conn, $user_id) == "") {
		if(GetGameID($conn, $user_id) == 0) {
			SetBool($conn, $user_id);
		}
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xe2\x9a\xa0 چیزی برای حذف انتخاب نشده"
				])
		);
	} else {
		$str = GetSaveGameID($conn, $user_id) . "(" . GetLeague($conn, $user_id)
				. ")";
		$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
		$json_key_league_into = json_encode($key_league_into);
		if(GetIsForecast($conn, $str, $user_id) == false) {
			SetGameResult($conn, false, $user_id);
			SetGameID($conn, true, $user_id);
			SaveGameID($conn, "", $user_id);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9a\xa0 این بازی رو پیش‌بینی نکردی",
									"reply_markup" => $json_key_league_into
					])
			);
		} elseif(CheckGameStatus($conn, GetLeague($conn, $user_id), GetSaveGameID($conn, $user_id))) {
			SetGameResult($conn, false, $user_id);
			SetGameID($conn, true, $user_id);
			SaveGameID($conn, "", $user_id);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9a\xa0 فرصت پیش‌بینی این بازی تموم شده",
									"reply_markup" => $json_key_league_into
					])
			);
		} else {
			DelResult($conn, $str, $user_id);
			SetChance($conn, 1, $user_id);
			SetGameResult($conn, false, $user_id);
			SetGameID($conn, true, $user_id);
			SaveGameID($conn, "", $user_id);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 پیش‌بینی قبلیت حذف شد!"
									. "\n\n\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id) . "*",
									"parse_mode" => "markdown",
									"reply_markup" => $json_key_league_into
					])
			);
		}
	}
} elseif($command == "\xf0\x9f\x97\x93 جدول لیگ") {
	if(GetLeague($conn, $user_id) == 0) {
		var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9a\xa0 یکی از لیگ ها رو انتخاب کن"
			])
		);
		if(GetGameID($conn, $user_id) == 0) {
			SetBool($conn, $user_id);
		}
	} else {
		$str = getChart($conn, GetLeague($conn, $user_id));
		//$str .= "\xf0\x9f\x94\x84 آخرین تغییرات: " . ConvertEn2Per($getFullD);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str,
								"parse_mode" => "markdown"
				])
		);
	}
} elseif($command == "\xf0\x9f\x93\x8a نتایج من") {
	if(GetLeague($conn, $user_id) == 0) {
		var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9a\xa0 یکی از لیگ ها رو انتخاب کن"
			])
		);
		if(GetGameID($conn, $user_id) == 0) {
			SetBool($conn, $user_id);
		}
	} else {
		if(GetGameID($conn, $user_id) == 1) {
			$str_o = "\nیه بازی رو برای پیش‌بینی انتخاب کن" . " و شمارش رو برام بفرست";
			$str_p = "اگر میخوای پیش‌بینی های قبلی رو" . "\nاصلاح یا حذف کنی"
					 . "\n\xf0\x9f\x94\x91 شماره بازی مورد نظرت رو برام بفرست";
		} else {
			SetBool($conn, $user_id);
			$str_o = "";
			$str_p = "";
		}
		$str = GetFullForecast($conn, GetLeague($conn, $user_id), $user_id);
		$status = substr($str, -1);
		$str_out = substr($str, 0, -1);
		if($status == "0") {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => $str_out . $str_o
					])
			);
		} else {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => $str . $str_p,
									"parse_mode" => "markdown"
					])
			);
		}
	}
} elseif(GetGameID($conn, $user_id)) {
	$str = CheckGameID($conn, GetLeague($conn, $user_id), $command, $user_id);
	$status = substr($str, -1);
	$str_out = substr($str, 0, -1);
	if($status == "1") {
		SetGameID($conn, false, $user_id);
		SetGameResult($conn, true, $user_id);
		SaveGameID($conn, $command, $user_id);
		$key_get_ID = array(
						"keyboard" => array(
										array("منصرف شدم \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true,
					);

		$json_key_get_ID = json_encode($key_get_ID);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str_out,
								"parse_mode" => "markdown",
								"reply_markup" => $json_key_get_ID
				])
		);
	} elseif($status == "0" || $status == "5") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str_out
								. "\n\n\xf0\x9f\x94\x91 یه شماره بازی دیگه بفرست"
				])
		);
	} elseif($status == "2") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str_out
								. "\n\n\xf0\x9f\x94\xb8 میتونی از بخش نتایج من، پیش‌بینی های قبلیت رو "
								. "اصلاح یا حتی حذف کنی"
				])
		);
	} else {
		SetGameID($conn, false, $user_id);
		if($status == "4")
			SetGameResult($conn, false, $user_id);
		else
			SetGameResult($conn, true, $user_id);
		SaveGameID($conn, $command, $user_id);
		$key_get_retry = array(
						"keyboard" => array(
										array("منصرف شدم \xF0\x9F\x94\x99", "\xf0\x9f\x97\x91 حذفش کن")
									),
						"resize_keyboard" => true,
					);
		$json_key_get_retry = json_encode($key_get_retry);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str_out,
								"parse_mode" => "markdown",
								"reply_markup" => $json_key_get_retry
				])
		);
	}
} elseif($command == "منصرف شدم \xF0\x9F\x94\x99") {
	SetBool($conn, $user_id);
	SetGameID($conn, true, $user_id);
	$key_league_cancel = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
	$json_key_league_cancel = json_encode($key_league_cancel);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9c\x85 حله!"
							. "\n\n\xf0\x9f\x94\x91 شماره بازی مورد نظرتو برام بفرست",
							"reply_markup" => $json_key_league_cancel
			])
	);
} elseif(GetGameResult($conn, $user_id)) {
	if(CheckGameStatus($conn, GetLeague($conn, $user_id), GetSaveGameID($conn, $user_id))) {
		$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
		$json_key_league_into = json_encode($key_league_into);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xe2\x9a\xa0 فرصت پیش‌بینی این بازی تموم شده",
								"reply_markup" => $json_key_league_into
				])
		);
		SetGameResult($conn, false, $user_id);
		SaveGameID($conn, "", $user_id);
		SetGameID($conn, true, $user_id);
	} elseif(CheckResult($command)) {
		$str = GetSaveGameID($conn, $user_id) . "(" . GetLeague($conn, $user_id)
			   . ")" . " " . $command;
		$ret = SetGameForecast($conn, $str, $user_id);
		if($ret != 1)
			SetChance($conn, -1, $user_id);
		SetGameResult($conn, false, $user_id);
		$key_league_into = array(
					"keyboard" => array(
									array("\xf0\x9f\x97\x93 جدول لیگ", "\xf0\x9f\x93\x8a نتایج من"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\xB0 لیگ ها")
								),
					"resize_keyboard" => true
				);
		$json_key_league_into = json_encode($key_league_into);
		SaveGameID($conn, "", $user_id);
		SetGameID($conn, true, $user_id);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xe2\x9c\x85 نتیجه مورد نظرت ثبت شد!"
								. "\n\n\xf0\x9f\xa4\x94 پیش‌بینی های باقی مانده: *" . GetChance($conn, $user_id) . "*",
								"parse_mode" => "markdown",
								"reply_markup" => $json_key_league_into
				])
		);
	}
	else {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xe2\x9a\xa0 نتیجه بازی رو به صورت صحیح وارد کن"
				])
		);
	}
} elseif($command == "\xF0\x9F\x8F\x86 رده‌بندی") {
	SetBool($conn, $user_id);
	$str_top = GetTopSeason($conn);
	$str_invite = GetInviteSeason($conn);
	$str_top_user = GetUserRate($conn, 1, $user_id);
	$str_invite_user = GetUserRate($conn, 4, $user_id);
	if($str_top_user[3] == 0)
		$str_t = "\x30\xe2\x83\xa3 امتیازی کسب نکردی";
	elseif($str_top_user[0] == 10) {
		$str_t = "\xf0\x9f\x94\x9f کاربر: " . $str_top_user[1]
							. " " . $str_top_user[2] . "  امتیاز: *" . $str_top_user[3] . "*";
	} else {
		$str_t = ConvertNum2Emo($str_top_user[0]) . " کاربر: " . $str_top_user[1]
							. " " . $str_top_user[2] . "  امتیاز: *" . $str_top_user[3] . "*";
	}
	if($str_invite_user[3] == 0)
		$str_i = "\x30\xe2\x83\xa3 کسی رو دعوت نکردی";
	elseif($str_invite_user[0] == 10) {
		$str_i = "\xf0\x9f\x94\x9f کاربر: " . $str_invite_user[1]
							. " " . $str_invite_user[2] . "  با *" . $str_invite_user[3] . "* نفر";
	} else {
		$str_i = ConvertNum2Emo($str_invite_user[0]) . " کاربر: " . $str_invite_user[1]
							. " " . $str_invite_user[2] . "  با *" . $str_invite_user[3] . "* نفر";
	}
	$key_rate = array(
					"keyboard" => array(
									array("\xF0\x9F\x91\xA5 معرفین برتر هفته", "\xE2\xAD\x90 برترین های هفته"),
									array("\xF0\x9F\x91\xA5 معرفین برتر ماه", "\xE2\xAD\x90 برترین های ماه"),
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);
	$json_key_rate = json_encode($key_rate);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xF0\x9F\x8C\x9F برترین های فصل:\n\n" . $str_top
							. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ"
							. "\n\xF0\x9F\x91\xA5 برترین معرفین فصل:\n\n" . $str_invite
							. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
							. "\xf0\x9f\x94\xb8 رتبه شما:\n\n" . $str_t
							. "\n" . $str_i,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_rate
			])
	);
} elseif($command == "\xE2\xAD\x90 برترین های هفته") {
	SetBool($conn, $user_id);
	$str_top = GetTopWeek($conn);
	$str_top_user = GetUserRate($conn, 3, $user_id);
	if($str_top_user[3] == 0)
		$str_t = "\x30\xe2\x83\xa3 امتیازی کسب نکردی";
	elseif($str_top_user[0] == 10) {
		$str_t = "\xf0\x9f\x94\x9f کاربر: " . $str_top_user[1]
							. " " . $str_top_user[2] . "  امتیاز: *" . $str_top_user[3] . "*";
	} else {
		$str_t = ConvertNum2Emo($str_top_user[0]) . " کاربر: " . $str_top_user[1]
							. " " . $str_top_user[2] . "  امتیاز: *" . $str_top_user[3] . "*";
	}
	$key_top_week = array(
					"keyboard" => array(
									array("\xE2\xAD\x90 برترین های ماه", "\xF0\x9F\x91\xA5 معرفین برتر هفته"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x91\xA5 معرفین برتر ماه")
								),
					"resize_keyboard" => true
				);

	$json_key_top_week = json_encode($key_top_week);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xE2\xAD\x90 برترین های این هفته:\n\n" . $str_top
							. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
							. "\xf0\x9f\x94\xb9 رتبه شما:\n\n" . $str_t,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_top_week
			])
	);
} elseif($command == "\xE2\xAD\x90 برترین های ماه") {
	SetBool($conn, $user_id);
	$str_top = GetTopMonth($conn);
	$str_top_user = GetUserRate($conn, 2, $user_id);
	if($str_top_user[3] == 0)
		$str_t = "\x30\xe2\x83\xa3 امتیازی کسب نکردی";
	elseif($str_top_user[0] == 10) {
		$str_t = "\xf0\x9f\x94\x9f کاربر: " . $str_top_user[1]
							. " " . $str_top_user[2] . "  امتیاز: *" . $str_top_user[3] . "*";
	} else {
		$str_t = ConvertNum2Emo($str_top_user[0]) . " کاربر: " . $str_top_user[1]
							. " " . $str_top_user[2] . "  امتیاز: *" . $str_top_user[3] . "*";
	}
	$key_top_month = array(
					"keyboard" => array(
									array("\xE2\xAD\x90 برترین های هفته", "\xF0\x9F\x91\xA5 معرفین برتر هفته"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x91\xA5 معرفین برتر ماه")
								),
					"resize_keyboard" => true
				);

	$json_key_top_month = json_encode($key_top_month);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xE2\xAD\x90 برترین های این ماه:\n\n" . $str_top
							. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
							. "\xf0\x9f\x94\xb9 رتبه شما:\n\n" . $str_t,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_top_month
			])
	);
} elseif($command == "\xF0\x9F\x91\xA5 معرفین برتر هفته") {
	SetBool($conn, $user_id);
	$str_invite = GetInviteWeek($conn);
	$str_invite_user = GetUserRate($conn, 6, $user_id);
	if($str_invite_user[3] == 0)
		$str_i = "\x30\xe2\x83\xa3 کسی رو دعوت نکردی";
	elseif($str_invite_user[0] == 10) {
		$str_i = "\xf0\x9f\x94\x9f کاربر: " . $str_invite_user[1]
							. " " . $str_invite_user[2] . "  با *" . $str_invite_user[3] . "* نفر";
	} else {
		$str_i = ConvertNum2Emo($str_invite_user[0]) . " کاربر: " . $str_invite_user[1]
							. " " . $str_invite_user[2] . "  با *" . $str_invite_user[3] . "* نفر";
	}
	$key_invite_week = array(
					"keyboard" => array(
									array("\xF0\x9F\x91\xA5 معرفین برتر ماه", "\xE2\xAD\x90 برترین های هفته"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xE2\xAD\x90 برترین های ماه")
								),
					"resize_keyboard" => true
				);

	$json_key_invite_week = json_encode($key_invite_week);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xF0\x9F\x91\xA5 برترین معرفین این هفته:\n\n" . $str_invite
							. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
							. "\xf0\x9f\x94\xb8 رتبه شما:\n\n" . $str_i,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_invite_week
			])
	);
} elseif($command == "\xF0\x9F\x91\xA5 معرفین برتر ماه") {
	SetBool($conn, $user_id);
	$str_invite = GetInviteMonth($conn);
	$str_invite_user = GetUserRate($conn, 5, $user_id);
	if($str_invite_user[3] == 0)
		$str_i = "\x30\xe2\x83\xa3 کسی رو دعوت نکردی";
	elseif($str_invite_user[0] == 10) {
		$str_i = "\xf0\x9f\x94\x9f کاربر: " . $str_invite_user[1]
							. " " . $str_invite_user[2] . "  با *" . $str_invite_user[3] . "* نفر";
	} else {
		$str_i = ConvertNum2Emo($str_invite_user[0]) . " کاربر: " . $str_invite_user[1]
							. " " . $str_invite_user[2] . "  با *" . $str_invite_user[3] . "* نفر";
	}
	$key_invite_month = array(
					"keyboard" => array(
									array("\xF0\x9F\x91\xA5 معرفین برتر هفته", "\xE2\xAD\x90 برترین های هفته"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xE2\xAD\x90 برترین های ماه")
								),
					"resize_keyboard" => true
				);

	$json_key_invite_month = json_encode($key_invite_month);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xF0\x9F\x91\xA5 برترین معرفین این ماه:\n\n" . $str_invite
							. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
							. "\xf0\x9f\x94\xb8 رتبه شما:\n\n" . $str_i,
							"parse_mode" => "markdown",
							"reply_markup" => $json_key_invite_month
			])
	);
} elseif($command == "\xF0\x9F\x91\xA4 پروفایل من") {
	SetBool($conn, $user_id);
	$username = $update->message->from->username;
	if(GetUsername($conn, $user_id) != $username) {
		SetUsername($conn, $username, $user_id);
	}
	$str = "\xF0\x9F\x99\x8C ویترین افتخارات: ";
	$data = GetUser($conn, $user_id);
	if($data[2] == "")
		$data[2] = "تعریف نشده";
	if($data[4] == 0)
		$data[4] = "معمولی";
	elseif($data[4] == 1)
		$data[4] = "برنزی";
	elseif($data[4] == 2)
		$data[4] = "نقره‌ای";
	elseif($data[4] == 3)
		$data[4] = "طلایی";
	if($data[4] == "معمولی")
		$time = "نامحدود";
	else
		$time = GetLevelTime($conn, $user_id) . " روز";
	if($data[5] == "")
		$data[5] = "خالی";
	else
		$str = "\xF0\x9F\x99\x8C ویترین افتخارات:\n";
	if($data[6] == "")
		$data[6] = "تعریف نشده";
	$key_profile = array(
					"keyboard" => array(
									array("\xe2\x9c\x8a ارتقاء سطح عضویت", "\xF0\x9F\x93\x9D ویرایش پروفایل"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x9A\xAB لغو عضویت")
								),
					"resize_keyboard" => true
				);

	$json_key_profile = json_encode($key_profile);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xE2\x9C\x8C نام شما: " . $data[0]
							. "\n\xF0\x9F\x91\x91 لقب شما: " . $data[1]
							. "\n\xF0\x9F\x94\xB1 نماد شما: " . $data[2]
							. "\n\xE2\x9A\xBD تیم محبوب: " . $data[6]
							. "\n\n\xF0\x9F\x93\x86 تاریخ عضویت: " . $data[3]
							. "\n\xF0\x9F\x92\xAA سطح عضویت: " . $data[4]
							. "\n\xe2\x8f\xb3 مدت اعتبار: " . $time
							. "\n\n" . $str . $data[5],
							"reply_markup" => $json_key_profile
			])
	);
} elseif($command == "\xF0\x9F\x93\x9D ویرایش پروفایل") {
	SetBool($conn, $user_id);
	$key_edit = array(
					"keyboard" => array(
									array("\xF0\x9F\x91\x91 ویرایش لقب", "\xE2\x9C\x8C ویرایش نام"),
									array("\xE2\x9A\xBD ویرایش تیم", "\xF0\x9F\x94\xB1 ویرایش نماد"),
									array("صفحه اصلی \xF0\x9F\x94\x99", "\xF0\x9F\x94\x84 ویرایش کامل")
								),
					"resize_keyboard" => true
				);

	$json_key_edit = json_encode($key_edit);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "کدومو میخوای ویرایش کنی؟",
							"reply_markup" => $json_key_edit
			])
	);
} elseif($command == "\xe2\x9c\x8a ارتقاء سطح عضویت") {
	SetBool($conn, $user_id);
	SetUplevel($conn, true, $user_id);
	$key_uplevel = array(
					"keyboard" => array(
									array("سطح \xf0\x9f\xa5\x88 نقره‌ای", "سطح \xf0\x9f\xa5\x87 طلایی"),
									array("بیخیال برگرد \xF0\x9F\x94\x99", "سطح \xf0\x9f\xa5\x89 برنزی")
								),
					"resize_keyboard" => true
				);
	$json_key_uplevel = json_encode($key_uplevel);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x92\x81 برای افزایش سطح عضویت یکی از موارد زیر را انتخاب کنید:\n\n"
									. "\xe2\x86\x99 سطح \xf0\x9f\xa5\x87 طلایی:\n"
									. "\xe2\x9a\xbd امکان پیش‌بینی 15 بازی در هفته\n"
									. "\xe2\x8f\xb3 مدت اعتبار: 90 روز\n\n"
									. "\xe2\x86\x99 سطح \xf0\x9f\xa5\x88 نقره‌ای:\n"
									. "\xe2\x9a\xbd امکان پیش‌بینی 12 بازی در هفته\n"
									. "\xe2\x8f\xb3 مدت اعتبار: 75 روز\n\n"
									. "\xe2\x86\x99 سطح \xf0\x9f\xa5\x89 برنزی:\n"
									. "\xe2\x9a\xbd امکان پیش‌بینی 9 بازی در هفته\n"
									. "\xe2\x8f\xb3 مدت اعتبار: 60 روز"
									. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
									. "\xf0\x9f\x92\xaf به زودی امتیازات ویژه دیگری برای هر سطح در نظر گرفته می‌‌شود!",
									"reply_markup" => $json_key_uplevel
			])
	);
} elseif($command == "سطح \xf0\x9f\xa5\x87 طلایی" && GetUplevel($conn, $user_id)) {
	$key_uplevel = array(
					"keyboard" => array(
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);
	$json_key_uplevel = json_encode($key_uplevel);
	$level = GetLevel($conn, $user_id);
	$link = "https://7bot.ir/pay/request.php?a=3000&u=" . base64_encode($user_id);
	if($level == 3) {
		$str = "\xE2\x9A\xA0 هم اکنون در همین سطح هستی\n"
			 . "\xe2\x9c\x85 با پرداخت مجدد مبلغ 3000 تومن میتونی سطح فعلیت رو تمدید کنی!\n\n";
	} else {
		$str = "\xf0\x9f\x92\xaf برای افزایش سطحت به طلایی کافیه وارد لینک زیر بشی\n"
			 . "\xe2\x9c\x85 با پرداخت مبلغ 3000 تومن میتونی از امکانات ویژه این سطح لذت ببری!\n\n";
	}
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => $str
									. "<a href='" . $link . "'>\xf0\x9f\x91\x88 افزایش سطح عضویت به طلایی \xf0\x9f\x91\x89</a>"
									. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
									. "\xf0\x9f\x93\xa9 درصورت بروز هرگونه مشکل در پرداخت یا تمایل به استفاده از سایر روش های انتقال وجه "
									. "میتونی به پشتیبانی پیام ارسال کنی!",
									"reply_markup" => $json_key_uplevel,
									"parse_mode" => "HTML",
									"disable_web_page_preview" => true
			])
	);
} elseif($command == "سطح \xf0\x9f\xa5\x88 نقره‌ای" && GetUplevel($conn, $user_id)) {
	$key_uplevel = array(
					"keyboard" => array(
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);
	$json_key_uplevel = json_encode($key_uplevel);
	$level = GetLevel($conn, $user_id);
	$link = "https://7bot.ir/pay/request.php?a=1900&u=" . base64_encode($user_id);
	if($level == 2) {
		$str = "\xE2\x9A\xA0 هم اکنون در همین سطح هستی\n"
			 . "\xe2\x9c\x85 با پرداخت مجدد مبلغ 1900 تومن میتونی سطح فعلیت رو تمدید کنی!\n\n";
	} elseif($level == 3) {
		$str = "\xE2\x9A\xA0 هم اکنون در سطح بالاتری هستی\n"
			 . "\xe2\x9c\x85 با اتمام مدت اعتبار سطح فعلیت میتونی این سطح رو انتخاب کنی!";
	} else {
		$str = "\xf0\x9f\x92\xaf برای افزایش سطحت به نقره‌ای کافیه وارد لینک زیر بشی\n"
			 . "\xe2\x9c\x85 با پرداخت مبلغ 1900 تومن میتونی از امکانات ویژه این سطح لذت ببری!\n\n";
	}
	if($level != 3) {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str
										. "<a href='" . $link . "'>\xf0\x9f\x91\x88 افزایش سطح عضویت به نقره‌ای \xf0\x9f\x91\x89</a>"
										. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
										. "\xf0\x9f\x93\xa9 درصورت بروز هرگونه مشکل در پرداخت یا تمایل به استفاده از سایر روش های انتقال وجه "
										. "میتونی به پشتیبانی پیام ارسال کنی!",
										"reply_markup" => $json_key_uplevel,
										"parse_mode" => "HTML",
										"disable_web_page_preview" => true
				])
		);
	} else {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str,
										"reply_markup" => $json_key_uplevel
				])
		);
	}
} elseif($command == "سطح \xf0\x9f\xa5\x89 برنزی" && GetUplevel($conn, $user_id)) {
	$key_uplevel = array(
					"keyboard" => array(
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);
	$json_key_uplevel = json_encode($key_uplevel);
	$level = GetLevel($conn, $user_id);
	$link = "https://7bot.ir/pay/request.php?a=900&u=" . base64_encode($user_id);
	if($level == 1) {
		$str = "\xE2\x9A\xA0 هم اکنون در همین سطح هستی\n"
			 . "\xe2\x9c\x85 با پرداخت مجدد مبلغ 900 تومن میتونی سطح فعلیت رو تمدید کنی!\n\n";
	} elseif($level == 2 || $level == 3) {
		$str = "\xE2\x9A\xA0 هم اکنون در سطح بالاتری هستی\n"
			 . "\xe2\x9c\x85 با اتمام مدت اعتبار سطح فعلیت میتونی این سطح رو انتخاب کنی!";
	} else {
		$str = "\xf0\x9f\x92\xaf برای افزایش سطحت به برنزی کافیه وارد لینک زیر بشی\n"
			 . "\xe2\x9c\x85 با پرداخت مبلغ 900 تومن میتونی از امکانات ویژه این سطح لذت ببری!\n\n";
	}
	if($level != 2 && $level != 3) {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str
										. "<a href='" . $link . "'>\xf0\x9f\x91\x88 افزایش سطح عضویت به برنزی \xf0\x9f\x91\x89</a>"
										. "\nــــــــــــــــــــــــــــــــــــــــــــــــــــــــ\n"
										. "\xf0\x9f\x93\xa9 درصورت بروز هرگونه مشکل در پرداخت یا تمایل به استفاده از سایر روش های انتقال وجه "
										. "میتونی به پشتیبانی پیام ارسال کنی!",
										"reply_markup" => $json_key_uplevel,
										"parse_mode" => "HTML",
										"disable_web_page_preview" => true
				])
		);
	} else {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => $str,
										"reply_markup" => $json_key_uplevel
				])
		);
	}
} elseif($command == "\xF0\x9F\x9A\xAB لغو عضویت") {
	SetBool($conn, $user_id);
	SetStop($conn, true, $user_id);
	$key_stop = array(
					"keyboard" => array(
									array("آره، لغوش کن"),
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);

	$json_key_stop = json_encode($key_stop);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9a\xa0 مطمئنی میخوای ما رو ترک کنی؟",
							"reply_markup" => $json_key_stop
			])
	);
} elseif($command == "آره، لغوش کن" && GetStop($conn, $user_id)) {
	SetStop($conn, false, $user_id);
	SetCancel($conn, true, $user_id);
	$key_del = array(
					"keyboard" => array(
									array("عضویت مجدد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);

	$json_key_del = json_encode($key_del);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x92\x94 عضویتت لغو شد!\n\n"
							. "\xf0\x9f\x98\x94 حیف شد که ما رو ترک کردی"
							. "\nولی از برگشتن دوبارت استقبال میکنیم \xf0\x9f\xa4\x97",
							"reply_markup" => $json_key_del
			])
	);
} elseif($command == "\xF0\x9F\x91\xAC دعوت از دوستان") {
	SetBool($conn, $user_id);
	$link = "https://t.me/Football_7Bot?start=" . base64_encode($user_id);
	$key_friend = array(
					"keyboard" => array(
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);

	$json_key_friend = json_encode($key_friend);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x99\x8b\xe2\x80\x8d\xe2\x99\x82\xef\xb8\x8f سلام دوست عزیز؛\n"
							. "\xe2\x9c\x85 با کلیک روی لینک زیر میتونی تو مسابقات پیش‌بینی فوتبال شرکت کنی "
							. "و با بقیه به رقابت بپردازی:\n"
							. "<a href='" . $link . "'>\xe2\x9a\xbd جذابترین رقابت پیش‌بینی فوتبال \xe2\x9a\xbd</a>"
							. "\n\xf0\x9f\x94\xb9 شرکت در تمامی مسابقات رایگانه"
							. "\n\xf0\x9f\x8f\x86 ویترین افتخارات تو آمادس تا با پیش‌بینی های دقیقت پر از مدال بشه",
							"parse_mode" => "HTML",
							"disable_web_page_preview" => true
			])
	);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x91\x86 برای دعوت از دوستات کافیه پیام بالا رو "
							. "براشون فوروارد کنی\n\xF0\x9F\x94\x97 لینک درج شده تو این پیام "
							. "مختص به توعه و هرکی از طریق این لینک ثبت نام کنه به عنوان دوسته "
							. "تو ثبت میشه\n\xF0\x9F\x92\xB0 دعوت از هر دوست و ثبت نام موفقش یه امتیاز معرفی "
							. "برات داره\n",
							"reply_markup" => $json_key_friend
			])
	);
} elseif($command == "\xf0\x9f\x91\xa8\xe2\x80\x8d\xf0\x9f\x94\xa7 پشتیبانی") {
	SetBool($conn, $user_id);
	$key_support = array(
					"keyboard" => array(
									array("\xe2\x9c\x89\xef\xb8\x8f ارسال پیام", "\xE2\x9D\x8C گزارش خطا"),
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);

	$json_key_support = json_encode($key_support);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "برای ارتباط با ما یک مورد رو انتخاب کن:",
							"reply_markup" => $json_key_support
			])
	);
} elseif($command == "\xE2\x9D\x8C گزارش خطا") {
	SetBool($conn, $user_id);
	SetReport($conn, true, $user_id);
	$key_report = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_report = json_encode($key_report);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x92\xa2 مشکل به وجود اومده رو خیلی دقیق شرح بده!"
							."\n\xf0\x9f\x96\xbc میتونی برامون عکس هم بفرستی و مشکل رو زیر "
							."عکس به صورت مختصر توضیح بدی",
							"reply_markup" => $json_key_report
			])
	);
} elseif($command == "\xe2\x9c\x89\xef\xb8\x8f ارسال پیام") {
	SetBool($conn, $user_id);
	SetMessage($conn, true, $user_id);
	$key_message = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_message = json_encode($key_message);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x93\xa9 پیامت رو برامون بنویس و ارسال کن\n"
							. "\xe2\x9c\x85 درصورت نیاز حتما جوابت رو میدیم"
							. "\n\n\xf0\x9f\x9a\xab لطفا از ارسال پیام های بی محتوا و تکراری خودداری کنید",
							"reply_markup" => $json_key_message
			])
	);
} elseif($command == "\xF0\x9F\x92\xA1 راهنما") {
	SetBool($conn, $user_id);
	$key_help = array(
					"keyboard" => array(
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true
				);

	$json_key_help = json_encode($key_help);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "من اینجام که کمکت کنم! "
							. "برای دریافت راهنمایی در مورد هر موضوع، "
							. "کافیه دستور آبی رنگی که مقابل اون سوال هست رو لمس کنی:\n\n"
							. "\xf0\x9f\x94\xb9 این ربات چی هست کلا؟ /faq1\n\n"
							. "\xf0\x9f\x94\xb8 چطوری بازی ها رو پیش‌بینی کنم؟ /faq2\n\n"
							. "\xf0\x9f\x94\xb9 نحوه امتیازدهی به چه صورتیه؟ /faq3\n\n"
							. "\xf0\x9f\x94\xb8 هر مدال معرف چیه؟ /faq4\n\n"
							. "\xf0\x9f\x94\xb9 جایزه هم میدین؟ /faq5\n\n"
							. "\xe2\x9d\x93 اگر بازم سوالی داشتی که جوابش رو اینجا پیدا نکردی"
							. " میتونی از قسمت پشتیبانی برای ما پیام ارسال کنی و جواب بگیری!",
							"reply_markup" => $json_key_help
			])
	);
} elseif($command == "/faq1") {
	SetBool($conn, $user_id);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x94\xb7 این ربات چی هست کلا؟\n\n"
							. "\xe2\x9a\xbd این ربات برای کارشناسی و پیش‌بینی بازی‌های فوتبال طراحی شده\n"
							. "\xf0\x9f\x94\xb0 شما می‌تونید با پیش‌بینی نتایج بازی‌ها امتیاز کسب کنید و رتبه‌تون رو در بین بقیه‌ی شرکت‌کننده‌ها ببینید\n"
							. "\xe2\x98\x91 شرکت در تمامی مسابقات رایگانه\n"
							. "\xf0\x9f\x9a\xab همچنین هیچگونه شرط بندی، قمار و امثال این موارد در ربات ما جایی نداره!"
			])
	);
} elseif($command == "/faq2") {
	SetBool($conn, $user_id);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x94\xb6 چطوری بازی ها رو پیش‌بینی کنم؟\n\n"
							. "\xf0\x9f\x92\xaf از بخش لیگ ها میتونی وارد لیگ موردنظرت بشی و بازی های فعال رو پیش‌بینی کنی\n"
							. "\xf0\x9f\x98\x89 بهتره که قبل از شروع پیش‌بینی پروفایلتو تکمیل کرده باشی!"
			])
	);
} elseif($command == "/faq3") {
	SetBool($conn, $user_id);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x94\xb7 نحوه امتیازدهی به چه صورتیه؟\n\n"
							. "\xe2\x9c\x85 امتیازی که می‌گیرید متناسب با میزان درستی پیش‌بینی‌تون از نتیجه‌ی هر بازیه:\n"
							. "\xf0\x9f\x98\xb3 پیش‌بینی دقیق نتیجه *15* امتیاز\n"
							. "\xf0\x9f\x98\xb2 پیش‌بینی درست برنده‌ی بازی و اختلاف گل‌ها *8* امتیاز\n"
							. "\xf0\x9f\x98\xae پیش‌بینی درست برنده‌ی بازی و گل زده‌ی یک طرف *8* امتیاز\n"
							. "\xf0\x9f\x98\x80 پیش‌بینی درست برنده‌ی بازی *6* امتیاز\n"
							. "\xf0\x9f\x98\x8c پیش‌بینی گل زده‌ی یک طرف *4* امتیاز\n"
							. "\xf0\x9f\x99\x82 شرکت در پیش‌بینی *2* امتیاز\n",
							"parse_mode" => "markdown"
			])
	);
} elseif($command == "/faq4") {
	SetBool($conn, $user_id);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x94\xb6 هر مدال معرف چیه؟\n\n"
							. "ترتیب مدال ها به این صورته:"
							. "\nاین مدال \xf0\x9f\x8f\x85  نفر اول هفته"
							. "\nاین مدال \xf0\x9f\x8e\x96  نفر اول ماه"
							. "\nاین مدال \xf0\x9f\xa5\x87  نفر اول فصل"
							. "\nاین مدال \xf0\x9f\xa5\x88  نفر دوم فصل"
							. "\nاین مدال \xf0\x9f\xa5\x89  نفر سوم فصل"
							. "\nاین جام  \xF0\x9F\x8F\x86  نفر اول تورنمنت ها"
							. "\nاین نشان \xf0\x9f\x8f\xb5  نفر اول معرفین هفته"
							. "\nاین نشان \xf0\x9f\x8e\x97  نفر اول معرفین ماه"
							. "\nاین نشان \xf0\x9f\x8e\xbd  نفر اول معرفین فصل"
							. "\n\n\xe2\x9c\x85 اهدای مدال ها روز شنبه هر هفته، روز اول هر ماه و روز اول هر فصل انجام میشه"
			])
	);
} elseif($command == "/faq5") {
	SetBool($conn, $user_id);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x94\xb7 جایزه هم میدین؟\n\n"
							. "\xe2\x9c\x85 حتما!\n"
							. "\xe2\x9a\xbd فوتبال با پیش‌بینی میچسبه \xf0\x9f\x98\x8b\n"
							. "\xf0\x9f\xa4\x94 پیش‌بینی هم با جایزه‌هاش \xf0\x9f\x8e\x81\xf0\x9f\x8e\x89\n\n"
							. "\xf0\x9f\x92\xaf در پایان هر دوره جوایزی به نفرات برتر اهدا خواهد شد!"
			])
	);
} elseif(GetReport($conn, $user_id)) {
	if(strlen($command) < 10 && isset($update->message->photo) == false) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 متن پیام کوتاهه، مجدد سعی کن"
			    ])
	    );
	} else {
		SetReport($conn, false, $user_id);
		if($username != "")
			$username = "@" . $username;
		else
			$username = "تعریف نشده";
		$key_send_report = array(
						"keyboard" => array(
										array("صفحه اصلی \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true
					);

		$json_key_send_report = json_encode($key_send_report);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xf0\x9f\x92\xaf گزارش شما دریافت شد\n"
								. "\xe2\x9c\x85 درصورت نیاز حتما جوابت رو میدیم",
								"reply_markup" => $json_key_send_report
				])
		);
		$username = $update->message->from->username;
		$user_name = $update->message->from->first_name;
		if(isset($update->message->photo)) {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $admin_report,
									"text" => "گزارش خطا از طرف: " . $user_name
									. "\nبا آیدی تلگرام: @" . $username
									. "\nو آیدی شماره: " . $user_id
									. "\nپیام همراه با تصویر"
					])
			);
			var_dump(makeRequest("sendPhoto", [
									"chat_id" => $admin_report,
									"photo" => $update->message->photo[0]->file_id,
									"caption" => $update->message->caption
					])
			);
		} else {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $admin_report,
									"text" => "گزارش خطا از طرف: " . $user_name
									. "\nبا آیدی تلگرام: @" . $username
									. "\nو آیدی شماره: " . $user_id
									. "\nمتن گزارش:\n"
									. $command
					])
			);
		}
	}
} elseif(GetMessage($conn, $user_id)) {
	if(strlen($command) < 10) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 متن پیام کوتاهه، مجدد سعی کن"
			    ])
	    );
	} else {
		SetMessage($conn, false, $user_id);
		if($username != "")
			$username = "@" . $username;
		else
			$username = "تعریف نشده";
		$key_send_message = array(
						"keyboard" => array(
										array("صفحه اصلی \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true
					);

		$json_key_send_message = json_encode($key_send_message);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xE2\x9C\x85 پیام شما دریافت شد",
								"reply_markup" => $json_key_send_message
				])
		);
		$username = $update->message->from->username;
		$user_name = $update->message->from->first_name;
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $admin_message,
								"text" => "پیام جدید از طرف: " . $user_name
								. "\nبا آیدی تلگرام: @" . $username
								. "\nو آیدی شماره: " . $user_id
								. "\nمتن پیام:\n"
								. $command
				])
		);
	}
} elseif($command == "\xF0\x9F\x94\x84 ویرایش کامل") {
	SetBool($conn, $user_id);
	SetFullEdit($conn, true, $user_id);
	$key_edit_full = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_edit_full = json_encode($key_edit_full);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "نام خودت رو وارد کن",
							"reply_markup" => $json_key_edit_full
			])
	);
} elseif($command == "\xE2\x9C\x8C ویرایش نام") {
	SetBool($conn, $user_id);
	SetNameEdit($conn, true, $user_id);
	$key_edit_name = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_edit_name = json_encode($key_edit_name);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "نام خودت رو به صورت کامل وارد کن",
							"reply_markup" => $json_key_edit_name
			])
	);
}  elseif($command == "\xF0\x9F\x91\x91 ویرایش لقب") {
	SetBool($conn, $user_id);
	SetLabelEdit($conn, true, $user_id);
	$key_edit_label = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_edit_label = json_encode($key_edit_label);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xf0\x9f\x92\xa1 لقب نام نمایشیت تو جدول رده‌بندیه\n"
							. "چه لقبی رو برای خودت میپسندی؟",
							"reply_markup" => $json_key_edit_label
			])
	);
} elseif($command == "\xF0\x9F\x94\xB1 ویرایش نماد") {
	SetBool($conn, $user_id);
	SetSymbolEdit($conn, true, $user_id);
	$key_edit_symbol = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_edit_symbol = json_encode($key_edit_symbol);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "یه ایموجی به عنوان نمادت برام بفرست\n"
							. "این نماد جلوی لقبت تو رده‌بندی قرار میگیره",
							"reply_markup" => $json_key_edit_symbol
			])
	);
} elseif($command == "\xE2\x9A\xBD ویرایش تیم") {
	SetBool($conn, $user_id);
	SetFavEdit($conn, true, $user_id);
	$key_edit_fav = array(
					"keyboard" => array(
									array("بیخیال برگرد \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_edit_fav = json_encode($key_edit_fav);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "اسم تیم محبوبت رو وارد کن",
							"reply_markup" => $json_key_edit_fav
			])
	);
} elseif(GetNameEdit($conn, $user_id)) {
	if(strlen($command) < 3) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 این نام خیلی کوتاهه"
			    ])
	    );
	} else {
		SetName($conn, $command, $user_id);
		SetNameEdit($conn, false, $user_id);
		$key_edited_name = array(
						"keyboard" => array(
										array("صفحه اصلی \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true
					);
		if(GetFullEdit($conn, $user_id)) {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 نامت تغییر کرد به: " . $command
									. "\n\nحالا لقبت رو وارد کن"
					])
			);
		} else {
			$json_key_edited_name = json_encode($key_edited_name);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 نامت تغییر کرد به: " . $command,
									"reply_markup" => $json_key_edited_name
					])
			);
		}
	}
} elseif(GetLabelEdit($conn, $user_id)) {
	if(strlen($command) < 3) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 این لقب خیلی کوتاهه، برو بالای 3 حرف"
			    ])
	    );
	} elseif(SelectLabel($conn, $command)) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 این لقب رو قبلا یکی انتخاب کرده "
							 . "یه لقب جدید پیدا کن"
			    ])
	    );
	} else {
		SetLabel($conn, $command, $user_id);
		SetLabelEdit($conn, false, $user_id);
		$key_edited_lable = array(
						"keyboard" => array(
										array("صفحه اصلی \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true
					);
		if(GetFullEdit($conn, $user_id)) {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 لقبت تغییر کرد به: " . $command
									. "\n\nحالا نمادت رو وارد کن"
					])
			);
		} else {
			$json_key_edited_lable = json_encode($key_edited_lable);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 لقبت تغییر کرد به: " . $command,
									"reply_markup" => $json_key_edited_lable
					])
			);
		}
	}
} elseif(GetSymbolEdit($conn, $user_id)) {
	if(strlen($command) > 10) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 مطمئنی ایموجی رو درست انتخاب کردی؟\n"
							 . "دقت کن که بیشتر از یکی دوتا ایموجی انتخاب نکرده باشی"
			    ])
	    );
	} else {
		SetSymbol($conn, $command, $user_id);
		SetSymbolEdit($conn, false, $user_id);
		$key_edited_symbol = array(
						"keyboard" => array(
										array("صفحه اصلی \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true
					);
		if(GetFullEdit($conn, $user_id)) {
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 نمادت تغییر کرد به: " . $command
									. "\n\nحالا تیم محبوبت رو وارد کن"
					])
			);
		} else {
			$json_key_edited_symbol = json_encode($key_edited_symbol);
			var_dump(makeRequest("sendMessage", [
									"chat_id" => $chat_id,
									"text" => "\xe2\x9c\x85 نمادت تغییر کرد به: " . $command,
									"reply_markup" => $json_key_edited_symbol
					])
			);
		}
	}
} elseif(GetFavEdit($conn, $user_id)) {
	if(strlen($command) < 3) {
		var_dump(makeRequest("sendMessage", [
							 "chat_id" => $chat_id,
							 "text" => "\xE2\x9A\xA0 اسم تیم خیلی کوتاهه!" . "\nدوباره سعی کن"
			    ])
	    );
	} else {
		SetFavTeam($conn, $command, $user_id);
		SetFavEdit($conn, false, $user_id);
		$key_edited_fav = array(
						"keyboard" => array(
										array("صفحه اصلی \xF0\x9F\x94\x99")
									),
						"resize_keyboard" => true
					);
		if(GetFullEdit($conn, $user_id)) {
			SetFull($conn, false, $user_id);
		}
		$json_key_edited_fav = json_encode($key_edited_fav);
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xe2\x9c\x85 تیم محبوبت تغییر کرد به: " . $command,
								"reply_markup" => $json_key_edited_fav
				])
		);
	}
} elseif($command == "\xf0\x9f\x93\x84 متن تعهدنامه" && GetBlock($conn, $user_id) == 1) {
	$key_recognizance = array(
					"keyboard" => array(
									array("\xf0\x9f\xa4\x9d بله قبول میکنم")
								),
					"resize_keyboard" => true,
				);

	$json_key_recognizance = json_encode($key_recognizance);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "\xe2\x9c\x8b تعهد میکنم که مجدد هیچ کدام از مواردی که به عنوان تخلف "
							. "محسوب می شوند را انجام ندهم و درصورت تکرار کلیه عواقب را میپذیرم!\n\n"
							. "\xf0\x9f\x9a\xab درصورت تکرار مجدد تخلف از طرف شما\n"
							. "عضویت به صورت کامل لغو شده و دیگر اجازه عضویت مجدد داده نمی‌‌شود",
							"reply_markup" => $json_key_recognizance
			])
	);
} elseif($command == "\xf0\x9f\xa4\x9d بله قبول میکنم" && GetBlock($conn, $user_id) == 1) {
	SetBlock($conn, 0, $user_id);
	$key_accept = array(
					"keyboard" => array(
									array("صفحه اصلی \xF0\x9F\x94\x99")
								),
					"resize_keyboard" => true,
				);

	$json_key_accept = json_encode($key_accept);
	var_dump(makeRequest("sendMessage", [
							"chat_id" => $chat_id,
							"text" => "تبریک، دوباره به جمع ما پیوستی!",
							"reply_markup" => $json_key_accept
			])
	);
} else {
	SetBool($conn, $user_id);
	if($command == "آره، لغوش کن") {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xE2\x9A\xA0 برای لغو عضویت از بخش"
								. " \xF0\x9F\x91\xA4 پروفایل من اقدام کنید"
					])
			);
	} else {
		var_dump(makeRequest("sendMessage", [
								"chat_id" => $chat_id,
								"text" => "\xE2\x9A\xA0 متوجه نشدم\n"
								. "برای دسترسی به بخش های داخلی از منوی اصلی استفاده کن"
					])
			);
	}
}
}

$conn->close();
} else
	echo "Forbidden!";

?>