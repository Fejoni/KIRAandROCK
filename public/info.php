<?php
/*
// Показывать всю информацию, по умолчанию INFO_ALL
phpinfo();

exec('audiowaveform -i test.mp3 -o test.json');

	// Указываем то что нужно записать в файл
	$text = "Ваш индивидуальный текст";
	 
	// Открываем файл в нужном нам режиме. Нам же, нужно его создать и что то записать.
	$fp = fopen("file.txt", "w");//поэтому используем режим 'w'
	 
	// записываем данные в открытый файл
	fwrite($fp, $text);
	 
	//не забываем закрыть файл, это ВАЖНО
	fclose($fp);
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

$code = 0;
$data = [];
$res = exec('/usr/local/bin/audiowaveform -i test.mp3 -o test.json > result_stdout.txt', $data, $code);
if ($res) {
    echo $res;
} else {
    echo '<pre>';
    echo "Вывод: " . PHP_EOL;
    print_r($data);
    echo PHP_EOL . "Код: " . $code;
    echo '</pre>';
}
exit();

?>
