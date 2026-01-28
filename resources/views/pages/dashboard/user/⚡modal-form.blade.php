<?php

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public bool $open = false;
    public bool $isEdit = false;

    public array $form = [
        'id' => null,
        'name' => '',
        'email' => '',
        'status' => '1',
        'phone' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    /* =========================
        EVENTS (LIVEWIRE 4)
    ========================= */

    #[On('users.create')]
    public function create(): void
    {
        $this->resetForm();
        $this->open = true;
    }

#[On('users.edit')]
public function edit(int $id): void
{
    $user = User::findOrFail($id);

    $this->resetForm();

    $this->form = [
        'id' => $user->id,
        'name' => $user->name,
        'email' => $user->email,
        'status' => $user->status ? '1' : '0',
        'password' => '',
        'password_confirmation' => '',
        'phone' => $user->phone,
    ];

    $this->isEdit = true;
    $this->open = true;
}


    /* =========================
        VALIDATION
    ========================= */

    protected function rules(): array
    {
        return [
            'form.name' => 'required|string|max:255',
            'form.email' => 'required|email|unique:users,email,' . ($this->form['id'] ?? 'NULL'),
            'form.status' => 'required|in:0,1',
            'form.password' => [
                $this->isEdit ? 'nullable' : 'required',
                'min:6',
                'same:form.password_confirmation',
            ],
        ];
    }

    /* =========================
        ACTIONS
    ========================= */

    public function generatePassword(): void
    {
        $password = str()->random(10);

        $this->form['password'] = $password;
        $this->form['password_confirmation'] = $password;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->form['name'],
            'email' => $this->form['email'],
            'status' => (bool) $this->form['status'],
            'phone' => $this->form['phone'],
        ];

        if ($this->form['password']) {
            $data['password'] = bcrypt($this->form['password']);
        }

        $user = User::updateOrCreate(
            ['id' => $this->form['id']],
            $data
        );

        // 🔥 atualiza tabela automaticamente
        $this->dispatch('users.saved', payload: [
            'id' => $user->id,
        ]);

        $this->dispatch(
            'toast',
            type: 'success',
            title: $this->isEdit ? 'Usuário atualizado!' : 'Novo usuário!',
            message: 'As informações foram salvas com sucesso.'
        );

        $this->open = false;
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['form', 'isEdit']);

        $this->form['status'] = '1';

        $this->resetValidation();
        $this->resetErrorBag();
    }
};
?>

<x-modal
    model="open"
    title="{{ $isEdit ? 'Editar Usuário' : 'Novo Usuário' }}"
    subtitle="Preencha os dados do usuário"
    size="4xl"
>
    <form
        wire:submit.prevent="save"
        wire:loading.class="opacity-50 pointer-events-none"
        class="space-y-6"
    >
        {{-- DADOS --}}
        <div>
            <h3 class="font-semibold mb-3">Dados</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input
                    label="Nome"
                    wire:model.defer="form.name"

                    variant="secondary"
                    size="lg"
                    class="bg-slate-50"
                />

                <x-input
                    label="Email"
                    wire:model.defer="form.email"

                    variant="secondary"
                    size="lg"
                    class="bg-slate-50"
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                <x-phone
                    label="Whatsapp"
                    wire:model.defer="form.phone"

                    variant="secondary"
                    size="lg"
                    class="bg-slate-50"
                />
            </div>
        </div>

        {{-- PERMISSÕES --}}
        <div>
            <h3 class="font-semibold mb-3">Permissões</h3>
            <div class="flex w-3/12">
                <x-select
                    label="Status"
                    variant="secondary"
                    size="lg"
                    class="bg-slate-50"
                    :options="[
                        ['id' => '1', 'label' => 'Ativo'],
                        ['id' => '0', 'label' => 'Inativo'],
                    ]"
                    wire:model.defer="form.status"
                />
            </div>
        </div>

        {{-- SEGURANÇA --}}
        <div>
            <h3 class="font-semibold mb-3">Segurança</h3>

            <div
                class="
                    grid grid-cols-1 lg:grid-cols-3 gap-4
                    border-dashed border border-slate-300
                    p-4 rounded-xl
                "
            >
                <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <x-password
                        label="Senha"
                        wire:model.defer="form.password"
                        strength
                        variant="secondary"
                        size="lg"
                        class="bg-slate-50"
                    />

                    <x-password
                        label="Confirmar Senha"
                        wire:model.defer="form.password_confirmation"
                        variant="secondary"
                        size="lg"
                        class="bg-slate-50"
                    />
                </div>

                <div class="flex items-center justify-center">
                    <x-button
                        type="button"
                        variant="warning"
                        size="sm"
                        :outline="true"
                        wire:click="generatePassword"
                    >
                        <i class="bi bi-shield-lock"></i>
                        Gerar senha
                    </x-button>
                </div>
            </div>
        </div>

        {{-- ACTIONS --}}
        <div class="flex justify-end gap-2 pt-4">
            <x-button
                type="button"
                class="btn-sm btn-secondary"
                wire:click="$set('open', false)"
            >
                Cancelar
            </x-button>

            <x-button
                type="submit"
                class="btn-sm btn-primary"
            >
                <span wire:loading.remove>Salvar</span>
                <span wire:loading>Salvando...</span>
            </x-button>
        </div>
    </form>
</x-modal>
