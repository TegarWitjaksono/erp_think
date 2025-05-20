<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class FinishedProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Get all finished products without joining
        $data = DB::table('finished_products')->get();

        // Map shortened status values to full names for display
        foreach ($data as $item) {
            if ($item->stock_status === 'avail') {
                $item->stock_status_display = 'available';
            } else if ($item->stock_status === 'resv') {
                $item->stock_status_display = 'reserved';
            } else {
                $item->stock_status_display = $item->stock_status;
            }
        }

        return response(view('finished_products.index', compact('data')));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'roast_batch_id' => 'required|integer',
            'weight_final' => 'required|numeric|min:0',
            'hpp' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stock_status' => 'required|in:ready,sold,reserved', // Update validation rule
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Remove the status mapping since we're using direct values now
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('finished_products')->insert([
                'roast_batch_id' => $request->roast_batch_id,
                'weight_final' => $request->weight_final,
                'hpp' => $request->hpp,
                'harga_jual' => $request->harga_jual,
                'stock_status' => $request->stock_status, // Use direct value
            ]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return redirect()->route('finished_products.index')
                ->with('success', 'Finished product created successfully');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return redirect()->back()
                ->with('error', 'Error creating finished product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        // Find the record using DB facade
        $product = DB::table('finished_products')->where('id', $id)->first();

        if (!$product) {
            abort(404);
        }

        // Get all roast batches for the dropdown
        $roastBatches = DB::table('roast_batches')->get();

        return view('finished_products.edit', compact('product', 'roastBatches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'roast_batch_id' => 'required|integer',
            'weight_final' => 'required|numeric|min:0',
            'hpp' => 'required|numeric|min:0',
            'harga_jual' => 'required|numeric|min:0',
            'stock_status' => 'required|in:ready,sold,reserved', // Update validation rule
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Remove the status mapping since we're using direct values now
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('finished_products')->where('id', $id)->update([
                'roast_batch_id' => $request->roast_batch_id,
                'weight_final' => $request->weight_final,
                'hpp' => $request->hpp,
                'harga_jual' => $request->harga_jual,
                'stock_status' => $request->stock_status, // Use direct value
            ]);
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return redirect()->route('finished_products.index')
                ->with('success', 'Finished product updated successfully');
        } catch (\Exception $e) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            return redirect()->back()
                ->with('error', 'Error updating finished product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Check if this product is used in sales
        $usedInSales = DB::table('sales')->where('finished_product_id', $id)->exists();

        if ($usedInSales) {
            return redirect()->route('finished_products.index')
                ->with('error', 'Cannot delete this product as it is used in sales records');
        }

        // Delete using DB facade
        DB::table('finished_products')->where('id', $id)->delete();

        return redirect()->route('finished_products.index')
            ->with('success', 'Finished product deleted successfully');
    }
}
