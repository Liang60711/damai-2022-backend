<?php

namespace App\Services;

use App\Constants\CurrencyConstant;
use App\Repositories\CurrencyRepository;
use PDO;
use Carbon\Carbon;

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
        // 取匯率資料
        $exchangeRate = $this->currencyRepo->getRateTable();
        $dataTime = Carbon::parse($exchangeRate->first()['UTC'])->addHours(8)->toDateTimeString();

        // 判斷
        if ($from == 'USD' && $to == 'USD'){
            $result = round(1, 6);
        }
        elseif ($from == 'USD'){
            $result = $this->currencyRepo->getExchangeRate($to)['Exrate'];
        }
        elseif ($to == 'USD'){
            $result = round(1 / $this->currencyRepo->getExchangeRate($from)['Exrate'], 6);
        }
        else{
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
            // 若匯率表沒有此貨幣
            if (empty($fromData) || empty($toData)){
                return;
            }
            $result = round($toData['Exrate'] / $fromData['Exrate'], 6);
        }
        
        return collect([
            'exchange_rate' => $result,
            'updated_at' => $dataTime
        ]);
    }
}
