<?php

use Livewire\Component;
use App\Services\ViaCepService;

new class extends Component
{
    /**
     * Endereço vindo do componente pai (edição)
     */
    public array $address = [];

    public string $zipcode = '';
    public string $street = '';
    public string $neighborhood = '';
    public string $city = '';
    public string $state = '';
    public string $country = 'BR';

    /* =========================
        MOUNT (EDIÇÃO)
    ========================= */

    public function mount(): void
    {
        if (!empty($this->address)) {
            $this->zipcode      = $this->address['zipcode'] ?? '';
            $this->street       = $this->address['street'] ?? '';
            $this->neighborhood = $this->address['neighborhood'] ?? '';
            $this->city         = $this->address['city'] ?? '';
            $this->state        = $this->address['state'] ?? '';
            $this->country      = $this->address['country'] ?? 'BR';
        }
    }

    /* =========================
        VALIDATION
    ========================= */

    protected function rules()
    {
        return [
            'zipcode' => 'nullable|digits:8',
            'street' => 'required_with:zipcode',
            'city' => 'required_with:zipcode',
            'state' => 'required_with:zipcode',
        ];
    }

    /**
     * Limpa erro ao digitar
     */
    public function updatingZipcode(): void
    {
        $this->resetErrorBag('zipcode');
    }

    /**
     * Consulta CEP quando tiver 8 dígitos
     */
    public function updatedZipcode(): void
    {
        $zip = preg_replace('/\D/', '', $this->zipcode);

        if (strlen($zip) !== 8) {
            return;
        }

        try {
            $this->resetErrorBag('zipcode');

            $data = ViaCepService::lookup($zip);

            $this->fill([
                'zipcode'      => $zip,
                'street'       => $data['street']       ?? '',
                'neighborhood' => $data['neighborhood'] ?? '',
                'city'         => $data['city']         ?? '',
                'state'        => $data['state']        ?? '',
            ]);

            $this->emitResolved();

        } catch (\Exception $e) {
            $this->addError('zipcode', 'CEP não encontrado');
            $this->reset(['street', 'neighborhood', 'city', 'state']);
        }
    }

    /* =========================
        EMIT
    ========================= */

    private function emitResolved(): void
    {
        $this->dispatch('address:resolved', [
            'zipcode' => $this->zipcode,
            'street' => $this->street,
            'neighborhood' => $this->neighborhood,
            'city' => $this->city,
            'state' => $this->state,
            'country' => $this->country,
        ]);
    }
};
?>


<div class="grid grid-cols-2 gap-4">
    <x-input label="CEP" wire:model.live.300ms="zipcode" size="lg" variant="secondary" />
    <x-input label="Rua" wire:model="street" size="lg" variant="secondary" />
    <x-input label="Bairro" wire:model="neighborhood" size="lg" variant="secondary" />
    <x-input label="Cidade" wire:model="city" size="lg" variant="secondary" />
    <x-input label="Estado" wire:model="state" size="lg" variant="secondary" />
</div>
