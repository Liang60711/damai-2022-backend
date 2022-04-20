<?php

namespace App\Repositories;

use App\Constants\CurrencyConstant;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;

/**
 *
 */
class CurrencyRepository
{
    /**
     * 取得幣別列表 中英轉換
     * @return string[]
     */
    public function getCurrencyList()
    {
        return CurrencyConstant::CURRENCY;
    }

    /**
     * 取得美元兌換該幣別匯率
     *
     * @param String $currency 幣別 ex: TWD, JPY
     *
     * @return array|mixed [
     *                      "Exrate": 匯率
     *                      "UTC": 更新時間
     *                      ]
     * @throws GuzzleException
     */
    public function getExchangeRate(string $currency)
    {
        $content = file_get_contents('https://tw.rter.info/capi.php');
        $rateData = json_decode($content, true);
        $key      = "USD" . $currency;
        return $rateData[$key] ?? [];

    }

    /**
     * 取得所有匯率表
     * 
     * @return collection
     */
    public function getRateTable()
    {
        $content = file_get_contents('https://tw.rter.info/capi.php');
        $rateData = json_decode($content, true);
        
        return collect($rateData);

    }
}
