<?php
namespace Porabote\Components\Recaptcha;

class Recaptcha
{
    private $token_v2;
    private $token_v3;
    private $privateKeyV2;
    private $privateKeyV3;

    public static function check($tokenV3 = null, $tokenV2 = null)
    {
        try {

            $recaptcha = new Recaptcha();
            $recaptcha->privateKeyV2 = getenv("RECAPTCHA_PRIVATE_KEY_V2");
            $recaptcha->privateKeyV3 = getenv("RECAPTCHA_PRIVATE_KEY_V3");

            $recaptcha->token_v2 = isset($tokenV2) ? $tokenV2 : null;
            $recaptcha->token_v3 = $tokenV3;

            if (!$recaptcha->token_v2 && !$recaptcha->token_v3) {
                throw new \Exception("Recaptcha tokens not set");
            }

            return $recaptcha->checkCaptcha();
        } catch (\Exception $exception) {
            return ['error' => $exception->getMessage()];
        }
    }

    // Предварительный обработчик для фронта, перед появлением второй капчи
    public function checkCaptcha3($token)
    {
        if (!$token) {
            return ['error' => 'Token is empty!!'];
        }

        $result = $this->checkCaptchaCurl($token, $this->privateKeyV3);

        // проверяем количество очков от 0 до 1. Чем ближе к 1, тем больше вероятности, что это человек
        if ($result['score'] < 0.5) {
            return ['error' => 'fall_captcha_v3'];
        }

        // возвращаем успех, если проверки пройдены
        return ['success' => true, 'text' => 'Captcha 3 Valid'];
    }

    function checkCaptcha()
    {
        // если не передано ни одного токена - возвращаем ошибку
        if (!$this->token_v3 && !$this->token_v2) {
            return ['error' => 'fall_captcha!!'];
        } // если дело дошло до капчи второй версии
        else if ($this->token_v2) {
            // проверяем информацию по второй версии, если google ответил, что провека успешная - возвращаем успех
            $result = $this->checkCaptchaCurl($this->token_v2, $this->privateKeyV2);

            if (!$result['success']) {
                // если проверка провалилась - тоже ошибка
                return ['error' => 'fall_captcha_v2'];
            }
        } // если токен второй версии еще не получен, но есть 3, значит проверяем невидимую капчу
        else {
            $result = $this->checkCaptchaCurl($this->token_v3, $this->privateKeyV3);

            // проверяем количество очков от 0 до 1. Чем ближе к 1, тем больше вероятности, что это человек
            if ($result['score'] < 0.5) {
                return ['error' => 'fall_captcha_v3'];
            }
        }
        // возвращаем успех, если проверки пройдены
        $text = 'successfully';
        return ['success' => true, 'text' => $text];
    }

    /**
     * Метод для отправки запроса в google через CURL
     * @param $response
     * @param $secret
     * @return mixed
     */
    function checkCaptchaCurl($response, $secret)
    {
        $url_data = 'https://www.google.com/recaptcha/api/siteverify' . '?secret=' . $secret . '&response=' . $response . '&remoteip=' . $_SERVER['REMOTE_ADDR'];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_data);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $captcha_res = curl_exec($curl);
        curl_close($curl);

        $captcha_res = json_decode($captcha_res, true);
        return $captcha_res;
    }

}