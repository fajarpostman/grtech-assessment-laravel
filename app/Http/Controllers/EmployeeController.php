<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Http\Resources\EmployeeCollection;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use App\Notifications\NewEmployeeAdded;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        return Inertia::render('Employees/Index');
    }

    /**
     * Server-side datatable API
     */

    public function apiIndex(Request $request)
    {
        $perPage = $request->get('perPage', 10);

        $employees = Employee::with('company')
            ->when($request->get('search'), function ($q, $search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhereHas('company', fn($cq) => $cq->where('name', 'like', "%{$search}%"));
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        return new EmployeeCollection($employees);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        $data = $request->validated();
        $employee = Employee::create($data);

        if ($employee->company && $employee->company->email) {
            $employee->company->notify(new NewEmployeeAdded($employee));
        }

        return response()->json([
            'message' => 'Employee created successfully',
            'data' => $employee->load('company')
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        return new EmployeeResource($employee->load('company'));
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
    public function update(EmployeeRequest $request, Employee $employee)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        $employee->update($request->validated());
        return new EmployeeResource($employee->load('company'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        // Don't forget to pray before you start coding. Enjoy and happy coding.
        $employee->delete();
        return response()->noContent();

    }
}
