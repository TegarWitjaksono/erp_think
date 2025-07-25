@extends('dashboard')

@section('konten')
<div class="content-wrapper">
    {{-- Header --}}
    <div class="content-header bg-light border-bottom shadow-sm mb-3">
        <div class="container-fluid">
            <div class="row mb-2 align-items-center">
                <div class="col-sm-6 d-flex">
                    <div class="mr-3 p-3 rounded-circle" style="background-color: rgba(121,82,59,0.15);">
                        <i class="fas fa-book fa-2x" style="color:#79523B"></i>
                    </div>
                    <div>
                        <h1 class="m-0 font-weight-bold" style="color:#4A2C1A">General Ledger</h1>
                        <div style="height:3px;width:60px;background:linear-gradient(to right,#79523B,#D2B48C);margin-top:5px;border-radius:3px"></div>
                        <p class="text-muted mt-2 mb-0">Overview of all accounts and transactions</p>
                    </div>
                </div>
                <div class="col-sm-6">
                    <nav aria-label="breadcrumb" class="float-sm-right mt-3">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item"><a href="/home" style="color:#79523B"><i class="fas fa-home"></i> Home</a></li>
                            <li class="breadcrumb-item active font-weight-bold">General Ledger</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content --}}
    <section class="content">
        <div class="container-fluid">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <i class="fas fa-table"></i> Data General Ledger
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>Account</th>
                                <th>Date</th>
                                <th>Doc Number</th>
                                <th>Name</th>
                                <th class="text-right">Debit</th>
                                <th class="text-right">Credit</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Recursive helper --}}
                            @php
                            function renderRows($acct, $tree, $txns, $level = 0) {
                                // Account header
                                $indent = str_repeat('&nbsp;&nbsp;&nbsp;', $level);
                                echo '<tr data-toggle="collapse" data-target="#acct-'.$acct->id.'" class="bg-light clickable">';
                                echo '<td><i class="fas fa-chevron-right mr-2"></i>'.$indent.$acct->code.' - '.$acct->name.'</td>';
                                echo '<td colspan="6"></td></tr>';

                                // Transactions collapse
                                echo '<tr class="collapse" id="acct-'.$acct->id.'"><td colspan="7" class="p-0">';
                                echo '<table class="table table-sm mb-0"><tbody>';

                                $balance = 0;
                                if (isset($txns[$acct->id])) {
                                    $rows = $txns[$acct->id];
                                    foreach ($rows as $t) {
                                        $balance += $t->debit - $t->credit;
                                        echo '<tr>';
                                        echo '<td width="5%"></td>';
                                        echo '<td width="12%">'.$t->doc_date.'</td>';
                                        echo '<td width="15%">'.$t->doc_no.'</td>';
                                        echo '<td width="25%">'.$t->name.'</td>';
                                        echo '<td class="text-right" width="13%">'.number_format($t->debit,2).'</td>';
                                        echo '<td class="text-right" width="13%">'.number_format($t->credit,2).'</td>';
                                        echo '<td class="text-right" width="17%">'.number_format($balance,2).'</td>';
                                        echo '</tr>';
                                    }
                                    // Totals
                                    $totD = collect($rows)->sum('debit');
                                    $totC = collect($rows)->sum('credit');
                                    echo '<tr class="font-weight-bold bg-white">';
                                    echo '<td colspan="4">Total '.$acct->code.'</td>';
                                    echo '<td class="text-right">'.number_format($totD,2).'</td>';
                                    echo '<td class="text-right">'.number_format($totC,2).'</td>';
                                    echo '<td class="text-right">'.number_format($balance,2).'</td>';
                                    echo '</tr>';
                                } else {
                                    echo '<tr><td colspan="7" class="text-center text-muted py-2">No transactions</td></tr>';
                                }

                                echo '</tbody></table></td></tr>';

                                // Recurse children
                                if (isset($tree[$acct->id])) {
                                    foreach ($tree[$acct->id] as $child) {
                                        renderRows($child, $tree, $txns, $level+1);
                                    }
                                }
                            }
                            @endphp

                            {{-- Start recursion --}}
                            @foreach($tree[0] as $root)
                                @php renderRows($root, $tree, $txns); @endphp
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script>
    // Toggle chevron icon on collapse
    $(document).on('click', '.clickable', function() {
        var icon = $(this).find('i.fas');
        icon.toggleClass('fa-chevron-right fa-chevron-down');
    });
</script>
@endpush
@endsection
