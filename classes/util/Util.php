<?php

class Util {

    static function encrypt($string, $cost = 10) {
        $opts = array(
            'cost' => $cost
        );
        return password_hash($string, PASSWORD_DEFAULT, $opts);
    }

    static function sendEmail ($destination, $subject, $msg) {
        require_once 'classes/vendor/autoload.php';
        $client = new Google_Client();
        $client->setApplicationName(Constants::APPNAME);
        $client->setClientId(Constants::CLIENTID);
        $client->setClientSecret(Constants::CLIENTSECRET);
        $client->setAccessType('offline');
        $client->setAccessToken(file_get_contents(Constants::TOKEN));
        if ($client->getAccessToken()) {
            $service = new Google_Service_Gmail($client);
            try {
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->CharSet = "UTF-8";
                $mail->From = Constants::CORREO;
                $mail->FromName = Constants::ALIAS;
                $mail->AddAddress($destination);
                $mail->AddReplyTo(Constants::CORREO, Constants::ALIAS);
                $mail->Subject = $subject;
                $mail->Body = $msg;
                $mail->IsHTML(true);
                $mail->preSend();
                $mime = $mail->getSentMIMEMessage();
                $mime = rtrim(strtr(base64_encode($mime), '+/', '-_'), '=');
                $msg = new Google_Service_Gmail_Message();
                $msg->setRaw($mime);
                $service->users_messages->send('me', $msg);
                return true;
            } catch (Exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    static function renderHtmlSelect(array $array, $name, $val = null) {
        $html = '<select name="' . $name . '">';
        foreach ($array as $key => $value) {
            $selected = '';
            if ($val == $key) {
                $selected = 'selected="selected"';
            }
            $html .= '<option ' . $selected . 'value=' . $key . '>' . $value . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    /*static function renderSelectCivilState($name, $val = null){
        $array = array(
            "" => "",
            1 => 'soltero',
            2 => 'casado',
            3 => 'divorciado',
            4 => 'viudo',
            5 => 'de hecho',
            6 => 'otro'
        );
        return self::renderHtmlSelect($array, $name, $val);
    }*/

    static function renderTemplate($template, array $data = array()) {
        if (!file_exists($template)) {
            return '';
        }
        $content = file_get_contents($template);
        return self::renderText($content, $data);
    }

    static function renderText($text, array $data = array()) {
        foreach ($data as $index => $value) {
            $text = str_replace('{{' . $index . '}}', $value, $text);
        }
        //quitar los {{...}} restantes
        $text = preg_replace('/{{[^\s]+}}/', '', $text);
        return $text;
    }

    static function varDump($value){
        return '<pre>' . var_export($value, true) . '</pre>';   
    }

    static function verifyPass($decryptedKey, $encryptedKey) {
        return password_verify($decryptedKey, $encryptedKey);
    }
    
}
