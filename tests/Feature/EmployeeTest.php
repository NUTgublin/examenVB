<?php

namespace Tests\Feature;

use App\Livewire\Employee as EmployeeComponent;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_employees_are_loaded_on_mount(): void
    {
        Employee::factory()->count(3)->create();

        $component = Livewire::test(EmployeeComponent::class);

        $this->assertNotEmpty($component->get('employees'));
        $this->assertCount(3, $component->get('employees'));
    }

    public function test_employee_can_be_created(): void
    {
        $company = Company::factory()->create();

        Livewire::test(EmployeeComponent::class)
            ->set('employee.name', 'Test Employee')
            ->set('employee.email', 'test@example.com')
            ->set('employee.phone_number', '0612345678')
            ->set('employee.company_id', $company->id)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employees', [
            'name' => 'Test Employee',
            'email' => 'test@example.com',
            'phone_number' => '0612345678',
            'company_id' => $company->id,
        ]);
    }

    public function test_employee_can_be_updated(): void
    {
        $employee = Employee::factory()->create();
        $company = Company::factory()->create();

        Livewire::test(EmployeeComponent::class)
            ->set('employee.name', 'Nieuwe naam')
            ->set('employee.email', 'nieuw@example.com')
            ->set('employee.phone_number', '0687654321')
            ->set('employee.company_id', $company->id)
            ->call('update', $employee->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('employees', [
            'id' => $employee->id,
            'name' => 'Nieuwe naam',
            'email' => 'nieuw@example.com',
            'phone_number' => '0687654321',
            'company_id' => $company->id,
        ]);
    }

    public function test_employee_can_be_deleted(): void
    {
        $employee = Employee::factory()->create();

        Livewire::test(EmployeeComponent::class)
            ->call('delete', $employee->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('employees', [
            'id' => $employee->id,
        ]);
    }

    public function test_employee_requires_existing_company(): void
    {
        Livewire::test(EmployeeComponent::class)
            ->set('employee.name', 'Test Employee')
            ->set('employee.email', 'test@example.com')
            ->set('employee.phone_number', '0612345678')
            ->set('employee.company_id', 999)
            ->call('save')
            ->assertHasErrors(['employee.company_id' => 'exists']);
    }
}
