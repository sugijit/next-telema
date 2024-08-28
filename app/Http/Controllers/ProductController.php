<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $prod_type = $request->query('p');
        $products = $this->getProductsByType($prod_type);
        return view('product.index', compact('products', 'prod_type'));
    }


    public function getProductsByType($type)
{
        // This is where you'd fetch products based on the type
        // For now, let's return dummy data
        if ($type == 'au') {
            return ['AU Product 1', 'AU Product 2', 'AU Product 3'];
        } else {
            return ['Softbank Product 1', 'Softbank Product 2', 'Softbank Product 3'];
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
