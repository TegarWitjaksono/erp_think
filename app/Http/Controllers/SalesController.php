<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Just get sales data without joining since we don't have product name column
        $data = DB::table('sales')->get();
            
        // Get all finished products for the dropdown
        $products = DB::table('finished_products')->get();
        
        return view('sales.index', compact('data', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'finished_product_id' => 'required|integer|exists:finished_products,id',
            'qty_sold' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Insert directly using DB facade without timestamps
        DB::table('sales')->insert([
            'finished_product_id' => $request->finished_product_id,
            'qty_sold' => $request->qty_sold,
            'total_price' => $request->total_price,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Sale record created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id)
    {
        $id = base64_decode($id);
        // Find the record using DB facade
        $sale = DB::table('sales')->where('id', $id)->first();
        
        if (!$sale) {
            abort(404);
        }
        
        // Get all finished products for the dropdown
        $products = DB::table('finished_products')->get();
        
        return view('sales.edit', compact('sale', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'finished_product_id' => 'required|integer|exists:finished_products,id',
            'qty_sold' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Update using DB facade without timestamp
        DB::table('sales')->where('id', $id)->update([
            'finished_product_id' => $request->finished_product_id,
            'qty_sold' => $request->qty_sold,
            'total_price' => $request->total_price,
            'sale_date' => $request->sale_date,
        ]);

        return redirect()->route('sales.index')
            ->with('success', 'Sale record updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // Delete using DB facade
        DB::table('sales')->where('id', $id)->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale record deleted successfully');
    }

    /**
     * Get the price of a product by its ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProductPrice($id)
    {
        $product = DB::table('finished_products')->select('harga_jual')->find($id);
        return response()->json($product);
    }
}