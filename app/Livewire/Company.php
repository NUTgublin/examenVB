<?php

namespace App\Livewire;

use App\Models\Company as CompanyModel;
use Livewire\Component;

class Company extends Component
{
    public $companies = [];

    public $company = [
        'name' => '',
        'address' => '',
        'email' => '',
        'postal_code' => '',
        'phone_number' => '',
    ];

    protected function rules(): array
    {
        return [
            'company.name' => 'required|string|max:255',
            'company.address' => 'required|string|max:255',
            'company.email' => 'required|email|max:255',
            'company.postal_code' => 'required|string|max:20',
            'company.phone_number' => 'required|string|max:30',
        ];
    }

    public function mount(): void
    {
        $this->refreshCompany();
    }

    public function refreshCompany(): void
    {
        $this->companies = CompanyModel::all();
    }

    public function save(): void
    {
        $this->validate();

        CompanyModel::create($this->company);

        $this->reset('company');
        $this->refreshCompany();
    }

    public function update($companyId): void
    {
        $this->validate();

        $company = CompanyModel::findOrFail($companyId);

        $company->update($this->company);

        $this->reset('company');
        $this->refreshCompany();
    }

    public function delete($companyId): void
    {
        $company = CompanyModel::findOrFail($companyId);

        $company->delete();

        $this->refreshCompany();
    }

    public function render()
    {
        return view('livewire.company');
    }
}
