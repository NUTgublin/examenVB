<?php

namespace Tests\Feature;

use App\Livewire\Company as CompanyComponent;
use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_companies_are_loaded_on_mount(): void
    {
        $companies = Company::factory()->count(3)->create();

        $component = Livewire::test(CompanyComponent::class);

        $this->assertNotEmpty($component->get('companies'));
        $this->assertCount(3, $component->get('companies'));
    }

    public function test_company_can_be_created(): void
    {
        Livewire::test(CompanyComponent::class)
            ->set('company.name', 'Test Bedrijf')
            ->set('company.address', 'Teststraat 1')
            ->set('company.email', 'test@example.com')
            ->set('company.postal_code', '1234AB')
            ->set('company.phone_number', '0612345678')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('companies', [
            'name' => 'Test Bedrijf',
            'address' => 'Teststraat 1',
            'email' => 'test@example.com',
            'postal_code' => '1234AB',
            'phone_number' => '0612345678',
        ]);
    }

    public function test_company_can_be_deleted(): void
    {
        $company = Company::factory()->create();

        Livewire::test(CompanyComponent::class)
            ->call('delete', $company->id)
            ->assertHasNoErrors();

        $this->assertDatabaseMissing('companies', [
            'id' => $company->id,
        ]);
    }

    public function test_company_can_be_updated(): void
    {
        $company = Company::factory()->create();

        Livewire::test(CompanyComponent::class)
            ->set('company.name', 'Nieuwe naam')
            ->set('company.address', 'Nieuwe straat')
            ->set('company.email', 'nieuw@example.com')
            ->set('company.postal_code', '5678CD')
            ->set('company.phone_number', '0687654321')
            ->call('update', $company->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'name' => 'Nieuwe naam',
            'address' => 'Nieuwe straat',
            'email' => 'nieuw@example.com',
            'postal_code' => '5678CD',
            'phone_number' => '0687654321',
        ]);
    }
}
