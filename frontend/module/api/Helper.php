<?php

namespace frontend\module\api;

class Helper
{
    public static function getResponse($data=null, $errors=null, $status = 200){
        \Yii::$app->response->statusCode = $status;
        return [
            'data' => $data,
            'errors' => $errors
        ];
    }


        public static function getOutputNotExistKeysOrNull($array, $keys){
        $output = 'Отсутсвует параметры: ';
        foreach ($keys as $key){
            if (!isset($array[$key])){
                 $output .= $key . ', ';
            }
        }
        if (strlen($output) === strlen('Отсутсвует параметры: '))
            return null;
        return $output;
    }
}