<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\CompanyRequest;
use App\Http\Resources\CompanyCollection;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        return Inertia::render('Companies/Index');
    }

    /**
     * Server-side datatable API
     */
    public function apiIndex(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        $companies = Company::query()
            ->when($request->get('search'), function($q, $search) {
                $q->where('name', 'like', "%{$search}%")
                ->onWhere('email', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        
        return new CompanyCollection($companies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CompanyRequest $request)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyRequest $request)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        $data = $request->validated();

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company = Company::create($data);

        return new CompanyResource($company);
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        return new CompanyResource($company);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyRequest $request, Company $company)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        $sta = $request->validated();

        if ($request->hasFile('logo')) {
            if ($company->logo && \Storage::disk('public')->exists($company->logo)) {
                \Storage::disk('public')->delete($company->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $company->update($data);

        return new CompanyResource($company);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        if ($company->logo && \Storage::disk('public')->exists($company->logo)) {
            \Storage::disk('public')->delete($company->logo);
        }
        $company->delete();

        return response()->noContent();
    }
}
