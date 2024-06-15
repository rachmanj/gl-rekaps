<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\DailyJournal;
use App\Models\Transit;
use Illuminate\Http\Request;

class DailyJournalController extends Controller
{
    public function index()
    {
        return view('daily_journal.index');
    }

    public function getJournals()
    {
        $exclude_doctype = [
            'Goods Receipt',
            'InventoryTransfer',
            'AP DP',
            'Goods Receipt PO',
            'AR Invoice',
            'Production Order',
        ];

        // group by account and sum debit and credit
        $journals = Transit::whereNotIn('doc_type', $exclude_doctype)
            // ->selectRaw('account, sum(debit) as debit, sum(credit) as credit')
            // ->groupBy('account')
            ->get();

        // add account_id to journals
        foreach ($journals as $journal) {
            $account = Account::where('account_number', $journal->account)
                ->first();

            $journal->account_id = $account->id;
        }

        return $journals;
    }

    public function insertJournals()
    {
        $journals = $this->getJournals();
        $batch = DailyJournal::max('batch') + 1;

        foreach ($journals as $journal) {
            DailyJournal::create([
                'create_date' => $journal->create_date,
                'posting_date' => $journal->posting_date,
                'tx_num' => $journal->tx_num,
                'doc_num' => $journal->doc_num,
                'doc_type' => $journal->doc_type,
                'project_code' => $journal->project_code,
                'account_id' => $journal->account_id,
                'debit' => $journal->debit,
                'credit' => $journal->credit,
                'remarks' => $journal->remarks,
                'batch' =>  $batch,
            ]);
        }

        return 'Data inserted successfully';
    }

    public function dailyRekaps()
    {
        $daily_rekaps = DailyJournal::selectRaw('account_number, sum(debit) as debit, sum(credit) as credit')
            ->groupBy('account_number')
            ->get();

        return $daily_rekaps;
    }

    public function getDailyMutasi($create_date)
    {
        $result = DailyJournal::selectRaw('account_id, project_code, sum(debit) as debit, sum(credit) as credit')
            ->where('create_date', $create_date)
            ->groupBy('account_id', 'project_code')
            ->get();

        // add total variance = debit - credit and account_number to result
        // foreach ($result as $item) {
        //     $item->variance = $item->debit - $item->credit;
        //     $item->account_number = Account::where('id', $item->account_id)
        //         ->first()
        //         ->account_number;
        // }

        return $result;
    }

    public function getDailyProjects($create_date)
    {
        $projects = DailyJournal::select('project_code')
            ->whereNotNull('project_code')
            ->where('create_date', $create_date)
            ->groupBy('project_code')
            ->get()
            ->pluck('project_code')
            ->toArray();

        return $projects;
    }

    public function getDailyAccounts($create_date)
    {
        $accounts = DailyJournal::select('account_id')
            ->whereNotNull('project_code')
            ->where('create_date', $create_date)
            ->groupBy('account_id')
            ->get()
            ->pluck('account_id')
            ->toArray();

        // add account_number to accounts
        // foreach ($accounts as $account) {
        //     $account_number = Account::where('id', $account->account_id)
        //         ->first()
        //         ->account_number;

        //     $account->account_number = $account_number ? $account_number : 'null';
        // }

        return $accounts;
    }
}
