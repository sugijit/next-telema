<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return view('companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('companies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'manager_name' => 'required|string|max:255',
            'manager_phone' => 'nullable|string',
            'manager_mail' => 'nullable|string|email',
            'address' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);

        Company::create($request->all());
        return redirect()->route('companies.index')->with('success', '企業を追加しました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return view('companies.show', compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'manager_name' => 'required|string|max:255',
            'manager_phone' => 'nullable|string',
            'manager_mail' => 'nullable|string|email',
            'address' => 'nullable|string',
            'plan' => 'nullable|string',
        ]);

        $company->update($request->all());
        return redirect()->route('companies.index')->with('success', '企業情報を更新しました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('companies.index')->with('success', '企業を削除しました。');
    }
}
