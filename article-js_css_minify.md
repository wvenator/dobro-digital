# Как минифицировать JS и CSS с помощью PHP
В редких случаях приходится минифицировать JS и CSS подручными средствами. Сейчас мы расмотрим один из таких вариантов, на примере Toptal API. А работать с API мы будем с помощью cURL PHP
## Минифицирование JS
Для этого, в папке со скриптами, создадим php-файл и попишем в нем следующий код
```php
$url = 'https://www.toptal.com/developers/javascript-minifier/api/raw';
$dir = getcwd();
$files = preg_grep('~\.(js)$~', scandir($dir));
foreach ($files as $file) {
    $ch = curl_init();
    $fileinput = file_get_contents($file);
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
        CURLOPT_POSTFIELDS => http_build_query([ "input" => $fileinput ])
    ]);
    $minified = curl_exec($ch);
    $filename = preg_replace('|([^<]*).js|', '\1.min.js', $file);
    $filename = 'minify/'.$filename; // Конечная директория
    echo $filename.'<br>'; // Отображение имен обработанных файлов
    file_put_contents($filename, $minified);
    curl_close($ch);
}
```
Этот скрипт обработает все файлы с форматом **.js**, сохранит ее в дочерней папке **minify** и добавит к каждому файлу приписку **.min** в конце. Запускать можно как самостоятельно, перейдя по URL на него, либо по CRON, либо по различным триггерам, любым удобным для вас способом.
## Минифицирование CSS
Для этого, в папке со стилям, создадим php-файл и попишем в нем следующий код
```php
$url = 'https://www.toptal.com/developers/cssminifier/api/raw';
$dir = getcwd();
$files = preg_grep('~\.(css)$~', scandir($dir));
$phptemplatefile = 'minify/template.php';
foreach ($files as $file) {
    $ch = curl_init();
    $fileinput = file_get_contents($file);
    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ["Content-Type: application/x-www-form-urlencoded"],
        CURLOPT_POSTFIELDS => http_build_query([ "input" => $fileinput ])
    ]);
    $minified = curl_exec($ch);
    $filename = preg_replace('|([^<]*).css|', '\1.min.css', $file);
    $filename = 'minify/'.$filename; // Конечная директория
    echo $filename.'<br>'; // Отображение имен обработанных файлов
    file_put_contents($filename, $minified);
    $phpfilename = preg_replace('|([^<]*).css|', '\1.php', $filename); // Конвертирование в inline-стили
    echo $phpfilename.'<br>'; // Отображение имен inline-стилей
    copy($phptemplatefile, $phpfilename);
    curl_close($ch);
}
```
Этот скрипт работает сходим образом, как и обработчик JS, но также дополнительно записывает inline-стили, для встраивания на страницу без подключения CSS-файлов.
Например, следующим образом:
```php
include( 'assets/css/minify/front-page.min.php' );
```
## Итог
В результате мы можем подключать на страницы минифицированные JS и CSS, а также inline-стили. Также это будет полезно, когда вы меняете стили или скрипты сразу на сайте, тестируете их в изначальном виде на выделенной для этого странице, а уже после тестов минифицируете.
