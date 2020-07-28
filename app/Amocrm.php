<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Amocrm extends Model
{
    protected $table = 'amocrm';

    public static function refreshAMOCRMToken(){
        $amo = Amocrm::all()->first();
        $link = 'https://worldsamo.amocrm.ru/oauth2/access_token'; //Формируем URL для запроса
        $data = [
            'client_id' => $amo->client_id,
            'client_secret' => $amo->cleint_secret,
            'grant_type' => 'refresh_token',
            'refresh_token' => $amo->refresh_token,
            'redirect_uri' => 'https://app.worldsamo.com',
        ];

        /**
         * Нам необходимо инициировать запрос к серверу.
         * Воспользуемся библиотекой cURL (поставляется в составе PHP).
         * Вы также можете использовать и кроссплатформенную программу cURL, если вы не программируете на PHP.
         */
        $curl = curl_init(); //Сохраняем дескриптор сеанса cURL
        /** Устанавливаем необходимые опции для сеанса cURL  */
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-oAuth-client/1.0');
        curl_setopt($curl,CURLOPT_URL, $link);
        curl_setopt($curl,CURLOPT_HTTPHEADER,['Content-Type:application/json']);
        curl_setopt($curl,CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($curl,CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl,CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl,CURLOPT_SSL_VERIFYHOST, 2);
        $out = curl_exec($curl); //Инициируем запрос к API и сохраняем ответ в переменную
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        /** Теперь мы можем обработать ответ, полученный от сервера. Это пример. Вы можете обработать данные своим способом. */
        $code = (int)$code;
        $errors = [
            400 => 'Bad request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not found',
            500 => 'Internal server error',
            502 => 'Bad gateway',
            503 => 'Service unavailable',
        ];

        try
        {
            /** Если код ответа не успешный - возвращаем сообщение об ошибке  */
            if ($code < 200 || $code > 204) {
                // dd($errors[$code]);
            }
        }
        catch(\Exception $e)
        {
            // die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
        }

        /**
         * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
         * нам придётся перевести ответ в формат, понятный PHP
         */
        $response = json_decode($out, true);
        if(array_key_exists('access_token',$response)){
            $access_token = $response['access_token']; //Access токен
            $refresh_token = $response['refresh_token']; //Refresh токен
            $token_type = $response['token_type']; //Тип токена
            $expires_in = $response['expires_in']; //Через сколько действие токена истекает
            $amo->access_token = $access_token;
            $amo->refresh_token = $refresh_token;
            $amo->save();
        }

        return true;
    }
}
