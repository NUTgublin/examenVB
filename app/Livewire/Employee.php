<?php

namespace App\Livewire;

use App\Models\Employee as EmployeeModel;
use Livewire\Component;

class Employee extends Component
{
    public $employees = [];

    public $employee = [
        'name' => '',
        'address' => '',
        'email' => '',
        'postal_code' => '',
        'phone_number' => '',
    ];

    protected function rules(): array
    {
        return [
            'employee.name' => 'required|string|max:255',
            'employee.email' => 'required|email|max:255',
            'employee.phone_number' => 'required|string|max:30',
            'employee.company_id' => 'required|exists:companies,id',
        ];
    }

    public function mount(): void
    {
        $this->refreshEmployees();
    }

    public function refreshEmployees(): void
    {
        $this->employees = EmployeeModel::all();
    }

    public function save(): void
    {
        $this->validate();

        EmployeeModel::create($this->employee);

        $this->reset('employee');
        $this->refreshEmployees();
    }

    public function update($employeeId): void
    {
        $this->validate();

        $employee = EmployeeModel::findOrFail($employeeId);

        $employee->update($this->employee);

        $this->reset('employee');
        $this->refreshEmployees();
    }

    public function delete($employeeId): void
    {
        $employee = EmployeeModel::findOrFail($employeeId);

        $employee->delete();

        $this->refreshEmployees();
    }

    public function render()
    {
        return view('livewire.employee');
    }
}
