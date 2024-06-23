<?php

namespace App\Http\Controllers;

use App\Models\DailyJournal;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        // $test = app(DailyJournalController::class)->getJournals();
        // $test = app(DailyJournalController::class)->getDailyMutasi('2024-05-31');
        // $test = app(DailyJournalController::class)->getDailyAccounts();
        $test = app(DailyBalanceController::class)->getDailyBalances('2024-05-31');

        return $test;
    }
}
