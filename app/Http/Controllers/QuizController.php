<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExchangeResource;
use App\Services\ExchangeService;
use Illuminate\Http\Request;


/**
 *
 */
class QuizController extends Controller
{
    private $exchangeService;

    public function __construct(
        ExchangeService $exchangeService
    ) {
        $this->exchangeService = $exchangeService;
    }

    public function getExchangeRate(Request $request)
    {
        $response = collect([]);
        // TODO: 實作取得匯率
        // API回傳結果
        $input = $request->validate([
            'from' => 'required|string|max:10',
            'to' => 'required|string|max:10'
        ]);

        $data = $this->exchangeService->getExchangeRate($input['from'], $input['to']);
        return new ExchangeResource($response);
    }
}
