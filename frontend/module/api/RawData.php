<?php

namespace frontend\module\api;

use IEXBase\TronAPI\Tron;
use Yii;
use yii\base\ErrorException;
use yii\web\BadRequestHttpException;

class RawData
{
    public $owner_address;
    public $amount;
    public $to_address;


    public function __construct($raw)
    {
        try {
            $raw = $raw['contract'][0]['parameter']['value'];
            $this->to_addressowner_address = $raw['owner_address'];
            $this->to_address = $raw['to_address'];
            $this->amount = $raw['amount'];
        } catch (ErrorException $e) {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        }
    }

    public static function createFromSignedTransaction(array $signedTransaction)
    {
        try {
            return new RawData($signedTransaction['raw_data']);
        } catch (ErrorException $e) {
            throw new BadRequestHttpException('In param not exist key - \'raw_data\'');
        }
    }

    public function HasRightOwnerAddress(): bool
    {
        $url_api = Yii::$app->params['apiTronGrid'];
        $tron = new Tron();



        return $this->to_address === Yii::$app->params['adminWalletAddress'] or
            $tron->hexString2Address($this->to_address) === Yii::$app->params['adminWalletAddress'];
    }
}