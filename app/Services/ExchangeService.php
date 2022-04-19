<?php

namespace App\Services;

use App\Constants\CurrencyConstant;
use App\Repositories\CurrencyRepository;
use PDO;

/**
 *
 */
class ExchangeService
{
    private $currencyRepo;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepo = $currencyRepository;
    }

    public function getExchangeRate(string $from, string $to)
    {
        // 取換算匯率資料
        $content  = file_get_contents('https://tw.rter.info/capi.php');
        $currency = json_decode($content, true);
        
        // 換算
        $exchangeRate = collect($currency);
        $fromData = $exchangeRate
            ->map(function($value, $key) use($from){
                if (str_contains($key, $from)) {
                    return $value;
                }
            })
            ->filter()
            ->first();
        $toData = $exchangeRate
            ->map(function($value, $key) use($to){
                if (str_contains($key, $to)) {
                    return $value;
                }
            })
            ->filter()
            ->first();
        
        if (empty($fromData) || empty($toData)){
            return false;
        }
        
        $result = $fromData['Exrate'] / $toData['Exrate'];
        return [
            'exchange_rate' => $result,
            'updated_at' => $fromData['UTC']
        ];
    }
}
