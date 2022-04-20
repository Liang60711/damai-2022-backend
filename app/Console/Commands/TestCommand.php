<?php

namespace App\Console\Commands;

use App\Http\Controllers\QuizController;
use App\Repositories\CurrencyRepository;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request = new Request([
            'from'   => 'TWD',
            'to'     => 'JPY',
            // 'amount' => '100'
        ]);
        $test    = app(QuizController::class);
        dd($test->getExchangeRate($request));
    }
}
