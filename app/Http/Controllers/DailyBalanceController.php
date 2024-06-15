<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\DailyBalance;
use Illuminate\Http\Request;

class DailyBalanceController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;

        $response = $this->getDailyBalance($date);

        if ($response === false) {
            return 'No data to insert';
        }

        return 'Data inserted successfully';
    }

    public function getDailyBalance($date)
    {
        $previous_date = date('Y-m-d', strtotime($date . ' -1 day'));

        $daily_journals = app(DailyJournalController::class)->getDailyMutasi($date);

        if (count($daily_journals) == 0) {
            return false;
        }

        foreach ($daily_journals as $journal) {
            // field needed: date, project_code, account_id, balance, mutasi = previous_balance + debit - credit
            $previous_balance = DailyBalance::where('date', $previous_date)
                ->where('project_code', $journal->project_code)
                ->where('account_id', $journal->account_id)
                ->first()
                ->balance ?? 0;

            $current_balance = $previous_balance + $journal->debit - $journal->credit;

            $mutasi = $journal->debit - $journal->credit;

            DailyBalance::create([
                'date' => $date,
                'project_code' => $journal->project_code,
                'account_id' => $journal->account_id,
                'balance' => $current_balance,
                'mutasi' => $mutasi,
            ]);
        }

        // return record count
        return count($daily_journals);
    }


    // not use
    public function getDailyBalances_old()
    {
        $date = '2024-05-31';
        $previous_date = date('Y-m-d', strtotime($date . ' -1 day'));


        $projects = ['000H', '001H', '017C', '021C', '022C', '023C'];
        $accounts = Account::all();

        $daily_journals = app(DailyJournalController::class)->getDailyMutasi($date);

        $data_daily_balances = [];
        foreach ($projects as $project) {
            $daily_balances = [];
            foreach ($accounts as $account) {
                $previous_balance = DailyBalance::where('date', $previous_date)
                    ->where('project_code', $project)
                    ->where('account_id', $account->id)
                    ->first()
                    ->balance ?? 0;

                $daily_balance = [
                    'project_code' => $project,
                    'account_id' => $account->id,
                    'previous_balance' => $previous_balance,
                ];

                // foreach ($daily_journals as $daily_journal) {
                //     if ($daily_journal->project_code == $project && $daily_journal->account_id == $account->id) {
                //         $daily_balance['debit'] += $daily_journal->debit;
                //         $daily_balance['credit'] += $daily_journal->credit;
                //     }
                // }

                $daily_balances[] = $daily_balance;
            }

            $data_daily_balances = array_merge($data_daily_balances, $daily_balances);
        }

        return $data_daily_balances;
    }
}
