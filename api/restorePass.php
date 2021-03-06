<?php

require_once('startsession.php');
require_once('appvars.php');
require_once('connectvars.php');
require_once('functions.php');

$request_body = file_get_contents('php://input');
$data = json_decode($request_body, true);
$id = isset($data['id']) ? $data['id'] : "";
$login = isset($data['login']) ? $data['login'] : "";
$changePass = isset($data['changePass']) ? $data['changePass'] : "";
$restorePassNum = isset($data['restorePassNum']) ? $data['restorePassNum'] : "";
$loginOrEmail = isset($data['loginOrEmail']) ? $data['loginOrEmail'] : "";

if ($id !== "" && $restorePassNum !== "" && $changePass !== "") {
    $query = "UPDATE sn_user SET password = sha('$changePass') WHERE user_id  = $id";
    $result = sqlAction($query);

    echo '{"needWalidate": false, "login": "' . $login . '", "step": 4, "message": {"type": "success", "textAlert": "Пароль успешно изменен."}}';
    exit;
}

if ($loginOrEmail !== "") {
    $query = "SELECT * FROM sn_user WHERE login = '$loginOrEmail' OR email= '$loginOrEmail'";
    $result = sqlAction($query);

    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_array($result);
        $sn_user_id = $row['user_id'];
        $email = $row['email'];
        $restorePassNum = (string)mt_rand();

        $query = "UPDATE sn_user SET restorePassNum = $restorePassNum WHERE user_id = $sn_user_id";
        sqlAction($query);

        $subject = "Восатновление пароля Simple Notes";
        $message = "
                <html>
                    <head>
                     <title>Восатновление пароля Simple Notes</title>
                    </head>
                    <body>
                      Для восстановления пароля,<br />
                      перейдите пожалуйста по ссылке
                      <a href=\"http://" . ADDRESS . "/#!/restorePass/$sn_user_id/$restorePassNum\">
                        " . ADDRESS . "/#!/restorePass/$sn_user_id/$restorePassNum
                      </a>
                    </body>
                </html>
                ";
        $resultMail = snMail($email, $subject, $message);
        if ($resultMail == false) {
            echo '{"needWalidate": false, "loginOrEmail": "' . $loginOrEmail . '", "step": 1,
            "message": {"type": "warning", "textAlert": "При отправке электронной почты, для восстановления пароля, что-то пошло не так."}}';
        } else {
            echo '{"needWalidate": false, "step": 2, "message": {"type": "success", "textAlert": "На ваш E-mail выслана ссылка, перейдя по которой вы можете продолжить востановление пароля."}}';
        }
    } else {
        echo '{"needWalidate": false, "step": 1,
        "message": {"type": "warning", "textAlert": "Введенный вами логин или E-mail не закреплен ни за одним опльзователем."}}';
    }
    exit;
}

if ($id !== "" && $restorePassNum !== "") {
    $query = "SELECT * FROM sn_user WHERE user_id = '$id'";
    $result = sqlAction($query);

    if (mysqli_num_rows($result) != 0) {
        $row = mysqli_fetch_array($result);
        $login = $row['login'];

        echo '{"needWalidate": false, "login": "' . $login . '", "step": 3, "message": {"type": "success", "textAlert": "Введите, пожалуйста, новый пароль."}}';
    } else {
        echo '{"needWalidate": false, "login": "", "step": 1, "message": {"type": "error", "textAlert": "При восстановлении пароля произошла ошибка, попробуйте еще раз."}}';
    }
    exit;
}
?>
