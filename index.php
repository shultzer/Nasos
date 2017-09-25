<?php
    session_start();
    require 'vendor/autoload.php';
    require 'vendor/phpexcel/phpexcel/Classes/PHPExcel.php';
    require 'data.php';

    ini_set( 'display_errors', 0 );

    if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {

        if ( isset( $_POST[ 'send' ] ) ) {

            if ( $_POST[ 'capcha' ] != $_SESSION[ 'capcha' ] ) {
                echo '<p class="danger"><mark>Не правильно введен код с картинки!</mark></p>';
            }
            else {
                $nasos         = $_POST[ 'nasos' ];
                $username      = $_POST[ 'username' ];
                $usertelephone = $_POST[ 'usertelephone' ];
                $usermail      = $_POST[ 'useremail' ];
                $usercomment   = $_POST[ 'usercomment' ];
                $message       = "$username\r\n$usertelephone\r\n$usermail\r\n$usercomment\r\n$nasos";

                if ( mail( 'work@novpolimer.ru', 'Заявка по насосам', "$message" ) ) {
                    echo '<p class="danger"><mark>Ваша заявка отправлена. Менеджер свяжется с вами в ближайшее время</mark></p>';
                }
                else {
                    echo '<p class="danger"><mark>Ваша заявка не отправлена.</mark></p>';
                };
            }
        }

        if ( isset( $_POST[ 'calculate' ] ) ) {


            if ( !is_numeric( $_POST[ 'value' ] ) ) {
                $errors[ 'value' ] = 'Введите число в графе "Откачиваемый объем"';
            }
            else {
                unset( $errors[ 'value' ] );
            };
            if ( !is_numeric( $_POST[ 'level' ] ) ) {
                $errors[ 'level' ] = 'Введите число в графе "Уровень необходимого вакуума в емкости"';
            }
            else {
                unset( $errors[ 'level' ] );
            };
            if ( !is_numeric( $_POST[ 'bar' ] ) ) {
                $errors[ 'bar' ] = 'Введите число в графе "Начальное давление"';
            }
            else {
                unset( $errors[ 'bar' ] );
            };
            if ( !is_numeric( $_POST[ 'time' ] ) ) {
                $errors[ 'time' ] = 'Введите число в графе "Время откачки"';
            }
            else {
                unset( $errors[ 'time' ] );
            };


            $formvalue = $_POST[ 'value' ];
            $formlevel = $_POST[ 'level' ];
            $formbar   = $_POST[ 'bar' ];
            $formtime  = $_POST[ 'time' ];
            $speed     = $formvalue / $formtime * log( $formbar / $formlevel ) * 3;
            $filepath  = 'Nasos.xlsx'; //файл для парсинга

            $ar = []; // инициализируем массив

            $inputFileType = PHPExcel_IOFactory::identify( $filepath );  // узнаем тип файла
            $objReader     = PHPExcel_IOFactory::createReader( $inputFileType ); // создаем объект для чтения файла
            $objPHPExcel   = $objReader->load( $filepath ); // загружаем данные файла в объект
            $ar            = $objPHPExcel->getActiveSheet()
                                         ->toArray(); // выгружаем данные из объекта в массив

            unset( $ar[ 0 ] );//удаляем шапку таблицы
            unset( $ar[ 1 ] );//удаляем шапку таблицы
            unset( $ar[ 2 ] );//удаляем шапку таблицы

            isset( $_POST[ 'speed' ] ) ? $speed = $_POST[ 'speed' ] : '';
            isset( $_POST[ 'level' ] ) ? $level = $_POST[ 'level' ] : '';

            foreach ( $ar as $ar_colls ) {
                if ( $ar_colls[ 2 ] != '' ) {
                    if ( $ar_colls[ 2 ] >= $speed and $ar_colls[ 3 ] <= $formlevel ) {

                        $factory    = $ar_colls[ 0 ];
                        $model      = $ar_colls[ 1 ];
                        $speed_file = $ar_colls[ 2 ];
                        $level_file = $ar_colls[ 3 ];

                        $variant    = compact( factory, model, speed_file, level_file );
                        $variants[] = $variant;
                    }
                }
            }

        }
    }

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Конфигуратор выбора насоса</title>

    <!-- Bootstrap -->
    <link href="vendor/booststrap/bootstrap.css" rel="stylesheet">
</head>
<body style="padding: 50px">
<h3>Конфигуратор выбора насоса</h3>
<p style="width: 50%">Предлагаем воспользоваться конфигуратором для подбора
    наиболее подходящего
    вакуумного насоса в зависимости от ваших условий. Введите в форму ввода,
    расположенную ниже, следуюшие данные: откачиваемый объем (м<sup>3</sup>)
    , уровень необходимого вакуума в емкости (мбар, диапазон от 1&middot;10
    <sup>-13</sup> до 300), начальное давление (мбар, диапазон от 1013 м и
    ниже),
    время откачки (часы). Нажмите кнопку "Рассчитать", и программа предложит вам
    наиболее подходящую для ваших условий модель вакуумного насоса. Вы можете
    сразу
    отправить предварительный запрос нашему инженеру-менеджеру, задав любые
    возникшие
    вопросы, или продолжить выбор оборудования на нашем сайте.</p>
<p>Введите следующие данные, нажмите кнопку "Рассчитать".</p>

<form class="form-inline" action="" method="post">
    <div class="form-group">
        <div class="form-group <?php if ( isset( $errors[ 'value' ] ) ) {
            echo 'has-error';
        }; ?>">
            <input style="margin: 5px" class="form-control" type="text"
                   name="value" id="value" required>
            <label for="value"> Откачиваемый объем (м<sup>3</sup>)</label>
        </div>
        <br>

        <div class="form-group <?php if ( isset( $errors[ 'level' ] ) ) {
            echo 'has-error';
        }; ?>">
            <input style="margin: 5px" class="form-control " type="text"
                   name="level" id="level" required>
            <label for="level">Уровень необходимого вакуума в емкости
                (Мбар)</label>
        </div>
        <br>

        <div class="form-group <?php if ( isset( $errors[ 'bar' ] ) ) {
            echo 'has-error';
        }; ?>">
            <input style="margin: 5px" class="form-control" type="text"
                   name="bar" id="bar" required>
            <label for="bar">Начальное давление (мбар)</label>
        </div>
        <br>

        <div class="form-group <?php if ( isset( $errors[ 'time' ] ) ) {
            echo 'has-error';
        }; ?>">
            <input style="margin: 5px" class="form-control" type="text"
                   name="time" id="time" required>
            <label for="time">Время откачки (часы)</label>
        </div>

        <br>
        <input class="btn btn-success" name="calculate" type="submit"
               value="Рассчитать">
        <input class="btn btn-danger" type="reset" value="Очистить">

    </div>
</form>
<?php if ( isset( $errors ) ) {
    foreach ( $errors as $error ):?>
        <p class="danger">
            <mark>
                <?= $error ?>
            </mark>
        </p>
    <?php endforeach;
} ?>

<h4>Предлагаемые насосы:</h4>
<table class="table table-striped" style="width: 600px">
    <tr>
        <th>Производитель</th>
        <th>Модель</th>
    </tr>
    <?php if ( !isset( $errors ) ) {
        if ( isset( $variants ) ):foreach ( $variants as $variant ): ?>
            <?= '<tr>' ?>
            <?= '<td>' . $variant[ 'factory' ] . '</td>' ?>
            <?= '<td>' . $variant[ 'model' ] . '</td>' ?>
            <?= '</tr>' ?>
        <?php endforeach;
        endif;
    } ?>
</table>
<h2>Отправить запрос менеджеру</h2>

<form class="form-inline" action="" method="post">
    <div class="form-group">
        <label for="value"> Рекомендуемый насос</label> <br>
        <input style="margin: 5px" class="form-control" type="text" name="nasos"
               id="value" required>
    </div>
    <br>
    <div class="form-group">
        <label for="level">Ваше имя*</label> <br>
        <input style="margin: 5px" class="form-control " type="text"
               name="username" id="level" required>
    </div>
    <br>
    <div class="form-group">
        <label for="bar">Ваш телефон</label> <br>
        <input style="margin: 5px" class="form-control" type="tel"
               name="usertelephone" id="bar">
    </div>
    <br>
    <div class="form-group">
        <label for="time">Ваш E-mail*</label> <br>
        <input style="margin: 5px" class="form-control" type="email"
               name="useremail" id="time" required>
    </div>
    <br>

    <div class="form-group">
        <label for="time">Ваш комментарий</label> <br>
        <textarea style="margin: 5px" class="form-control" type="text"
                  name="usercomment" id="time"></textarea>
    </div>
    <br>
    <label for="bar">Введите код с картинки:</label>
    <br>
    <div class="form-group">
        <img style="border: 1px solid gray; background: url('bg_capcha.png');"
             src="captcha.php" width="120" height="40">
        <br>
        <input class="form-control" type="text" name="capcha" required>
        <br>
    </div>
    <br>
    <input class="form-control btn btn-success" name="send" type="submit"
           value="Отправить">

</form>
</body>
</html>
