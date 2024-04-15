<?php

// Токен обновлять раз в год по этому адресу:
// https://oauth.yandex.ru/authorize?response_type=token&client_id=xxxxxxxxxx
$token = 'xxxxxxxxxx';

if ($_GET["id"]) {
	$params_id = $_GET["id"];
} else {
	$params_id = "";
}
if ($_GET["goal_id"]) {
	$params_metrics = 'ym:s:goal'.$_GET["goal_id"].'conversionRate';
} else {
	$params_metrics = "";
}
if ($_GET["source"]) {
	$params_source = "ym:s:UTMSource=='".$_GET["source"]."'";
} else {
	$params_source = "";
}
if ($_GET["date1"]) {
	$params_date1 = $_GET["date1"];
} else {
	$params_date1 = "";
}
if ($_GET["date2"]) {
	$params_date2 = $_GET["date2"];
} else {
	$params_date2 = "";
}

$params = array(
	'id'			=> $params_id,
	'metrics'		=> $params_metrics,
	'dimensions'	=> "ym:s:UTMSource",
	'filters'		=> $params_source,
	'date1'			=> $params_date1,
	'date2'			=> $params_date2,
	'group'			=> "day"
);

$ch = curl_init('https://api-metrika.yandex.net/stat/v1/data/bytime?' . urldecode(http_build_query($params)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $token));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, false);
$res = curl_exec($ch);
curl_close($ch);

$res = json_decode($res, true);

// Просмотр массива
// echo "<hr>";
// echo "<pre>";
// print_r($res);
// echo "</pre>";
// echo "<hr>";

if ($_GET["only_value"] && $_GET["only_value"] == "1") {

	$array_result = array();

	foreach ($res["totals"][0] as $key => $value) {
		$value = round($value);
		if ($value == 0) {
			$value = "";
		} else {
			$value = str_replace('.' ,',', $value)." %";
		}
		$array_totals = array("total" => $value);
		array_push($array_result, $array_totals);
	}

} else {

	$array_result = array(
		array(
			"time" => "Дата",
			"total" => "Конверсия"
		)
	);

	foreach ($res["totals"][0] as $key => $value) {
		$value = round($value, 1);
		$value = str_replace('.' ,',', $value);
		$array_totals = array("time" => "", "total" => $value);
		array_push($array_result, $array_totals);
	}

	foreach ($res["time_intervals"] as $key => $value) {
		$array_index = $key + 1;
		$array_result[$array_index]["time"] = $value[0];
	}

}


// Отдача в виде файла
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=main.csv");
header("Pragma: no-cache");
header("Expires: 0");
$buffer = fopen('php://output', 'w');
fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
foreach($array_result as $value) {
	fputcsv($buffer, $value, ';');
}
fclose($buffer);
exit();

// Запись в файл
// echo "<hr>";
// echo "<h1>Итоговый массив</h1>";
// echo "<pre>";
// print_r($array_result);
// echo "</pre>";
// echo "<hr>";
// $buffer = fopen(__DIR__ . '/main.csv', 'w');
// fputs($buffer, chr(0xEF) . chr(0xBB) . chr(0xBF));
// foreach($array_result as $value) {
// 	fputcsv($buffer, $value, ';');
// }
// fclose($buffer);
// echo "<h2>Запись в main.csv прошла успешно</h2>";
// echo "<hr>";
