<?php
// Controller: app/Http/Controllers/JournalController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        // Load accounts hierarchy
        $accounts = DB::table('accounts')->orderBy('code')->get();
        $tree = [];
        foreach ($accounts as $acct) {
            $tree[$acct->parent_id ?? 0][] = $acct;
        }

        // Load transactions
        $txns = DB::table('gl_lines as l')
            ->join('gl_headers as h', 'l.header_id', '=', 'h.id')
            ->select(
                'l.account_id',
                'h.doc_date',
                'h.doc_no',
                'l.memo as name',
                'l.debit',
                'l.credit'
            )
            ->orderBy('h.doc_date')
            ->get()
            ->groupBy('account_id');

        return view('journal.index', compact('tree', 'txns'));
    }
}