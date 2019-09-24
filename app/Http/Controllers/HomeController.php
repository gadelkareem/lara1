<?php


namespace App\Http\Controllers;


use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class HomeController extends Controller
{


    /**
     * Display a listing of the products.
     *
     * @throws
     * @return \Illuminate\Http\Response
     */
    public function products(Request $request)
    {
        $product = [
            'product_name' => $request->get('product_name'),
            'quantity_stock' => $request->get('quantity_stock'),
            'price_item' => $request->get('price_item'),
            'submitted_at' => Carbon::now()
        ];
        $disk = Storage::disk('public');
        $file = 'products.json';
        $products = $disk->exists($file) ? $disk->get($file) : "[]";
        $products = json_decode($products);
        $products[] = $product;
        $products = collect($products)->sortBy('date')->all();

        $disk->put($file, json_encode($products));
        return response()->json($products);
    }
}