<?php

// Controller: app/Http/Controllers/LogMesinController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogMesinController extends Controller
{
    public function index()
    {
        $logs = DB::table('log_mesin')
            ->orderBy('id')
            ->paginate(20);

        return view('log_mesin.index', compact('logs'));
    }

    public function import()
    {
        return view('log_mesin.import');
    }

    public function storeImport(Request $request)
    {
        $validated = $request->validate([
            'file'     => 'required|file|mimes:csv,txt,json',
            'mesin_id' => 'required|exists:master_mesins,id',
            'batch_id' => 'nullable|exists:batch_productions,id',
        ]);

        $path      = $request->file('file')->getRealPath();
        $extension = $request->file('file')->getClientOriginalExtension();
        $rows      = [];

        if ($extension === 'json') {
            $rows = json_decode(file_get_contents($path), true);
        } else {
            $rows = array_map('str_getcsv', file($path));
        }

        DB::transaction(function () use ($rows, $validated) {
            foreach ($rows as $record) {
                DB::table('log_mesin')->insert([
                    'mesin_id'       => $validated['mesin_id'],
                    'batch_id'       => $validated['batch_id'] ?? null,
                    'waktu_mesin'    => $record['waktu_mesin'] ?? now(),
                    'time_roast'     => $record['time_roast'] ?? 0,
                    'phase'          => $record['phase'] ?? null,
                    'interest_point' => $record['interest_point'] ?? null,
                    'bt'             => $record['bt'] ?? null,
                    'dbt'            => $record['dbt'] ?? null,
                    'et'             => $record['et'] ?? null,
                    'det'            => $record['det'] ?? null,
                    'burner_power'   => $record['burner_power'] ?? null,
                    'gas_pressure'   => $record['gas_pressure'] ?? null,
                    'airflow_power'  => $record['airflow_power'] ?? null,
                    'air_pressure'   => $record['air_pressure'] ?? null,
                    'drum_power'     => $record['drum_power'] ?? null,
                    'rpm'            => $record['rpm'] ?? null,
                    'event'          => $record['event'] ?? null,
                    'event_value'    => $record['event_value'] ?? null,
                    'source'         => $record['source'] ?? 'import',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ]);

                if (($record['event'] ?? '') === 'drop' && !empty($validated['batch_id'])) {
                    DB::table('batch_productions')
                        ->where('id', $validated['batch_id'])
                        ->update(['status' => 'closing']);
                }
            }
        });

        return redirect()->route('log-mesins.index')
                         ->with('success', 'Logs berhasil diimpor');
    }

    public function show($batchId)
    {
        $logs = DB::table('log_mesin')
            ->where('batch_id', $batchId)
            ->orderBy('time_roast')
            ->get();

        $times = $logs->pluck('time_roast');
        $bt    = $logs->pluck('bt');
        $et    = $logs->pluck('et');

        return view('log_mesin.show', compact('logs', 'times', 'bt', 'et', 'batchId'));
    }

    public function destroy($id)
    {
        DB::table('log_mesin')->where('id', $id)->delete();
        return back()->with('success', 'Log dihapus');
    }
}