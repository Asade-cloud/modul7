<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pageTitle = 'Employee List';

        $employees = Employee::all();

        // $employees = DB::select('
        //     SELECT *, employees.id as employee_id, positions.name as position_name
        //     FROM employees
        //     LEFT JOIN positions on employees.position_id = positions.id
        // ');

        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $pageTitle = 'Create Employee';

        $positions = Position::all();

        return view('employee.create', compact('pageTitle', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // INSERT QUERY
        $employee = new Employee;
        $employee->firstname = $request->input('firstName');
        $employee->lastname = $request->input('lastName');
        $employee->email = $request->input('email');
        $employee->age = $request->input('age');
        $employee->position_id = $request->input('position');
        $employee->save();

        // DB::table('employees')->insert([
        //     'firstname' => $request->firstName,
        //     'lastname' => $request->lastName,
        //     'email' => $request->email,
        //     'age' => $request->age,
        //     'position_id' => $request->position,
        // ]);

        return redirect()->route('employees.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pageTitle = 'Employee Detail';

         // Eloquent
         $employee = Employee::find($id);

        // $employee = collect(DB::select('
        // select *, employees.id as employee_id, positions.name as position_name
        // from employees
        // left join positions on employees.position_id = positions.id
        // where employees.id = ?', [$id]))->first();

        return view('employee.show', compact('pageTitle', 'employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $pageTitle = 'Employee Edit';

        $positions = Position::all();
        $employee = Employee::find($id);

        $positions = Position::get();

        // // dd($employee);
        // return view('employee.edit', [
        //     'pageTitle' => $pageTitle,
        //     'employee' => $employee,
        //     'positions' => $positions
        // ]);
        // // return view('employee.edit', compact('pageTitle', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

         // ELOQUENT
         $employee = Employee::find($id);
         $employee->firstname = $request->firstName;
         $employee->lastname = $request->lastName;
         $employee->email = $request->email;
         $employee->age = $request->age;
         $employee->position_id = $request->position;
         $employee->save();
        // $employee = new Employee;
        // $employee->firstname = $request->input('firstName');
        // $employee->lastname = $request->input('lastName');
        // // $employee->email = $request->input('email');
        // $employee->age = $request->input('age');
        // $employee->position_id = $request->input('position');
        // $employee->update();

        return redirect()->route('employees.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         // ELOQUENT
         Employee::find($id)->delete();

        // DB::table('employees')
        //     ->where('id', $id)
        //     ->delete();

        return redirect()->route('employees.index');
    }
}