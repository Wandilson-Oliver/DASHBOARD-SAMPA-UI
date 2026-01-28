<?php

use Livewire\Component;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;

new #[Layout('layouts::app')] class extends Component
{
    /* =========================
        CRIAÇÃO
    ========================= */
    public string $newRoleLabel = '';

    /* =========================
        EDIÇÃO DO PAPEL
    ========================= */
    public ?int $selectedRoleId = null;
    public string $roleLabel = '';

    public array $selectedPermissions = [];
    public array $roleUsers = [];

    /* =========================
        AUTH
    ========================= */
    public function mount(): void
    {
        abort_unless(auth()->user()->hasPermission('roles.manage'), 403);
    }

    /* =========================
        DATA
    ========================= */
    public function getRolesProperty()
    {
        return Role::withCount('users')->orderBy('name')->get();
    }

    public function getPermissionsByModuleProperty()
    {
        return Permission::orderBy('module')
            ->orderBy('name')
            ->get()
            ->groupBy('module');
    }

    public function getSelectedRoleProperty(): ?Role
    {
        return $this->selectedRoleId
            ? Role::with(['users', 'permissions'])->find($this->selectedRoleId)
            : null;
    }

    /* =========================
        EVENTS
    ========================= */

    #[On('roles.users.updated')]
    public function refreshSelectedRole(): void
    {
        if ($this->selectedRoleId) {
            $this->selectRole($this->selectedRoleId);
        }
    }

    /* =========================
        ACTIONS
    ========================= */

    public function createRole(): void
    {
        $this->validate([
            'newRoleLabel' => 'required|min:3',
        ]);

        Role::create([
            'label' => $this->newRoleLabel,
            'name'  => Str::slug($this->newRoleLabel, '_'),
        ]);

        $this->reset('newRoleLabel');

        $this->dispatch('toast', type: 'success', title: 'Papel criado');
    }

    public function selectRole(int $roleId): void
    {
        $role = Role::with(['users', 'permissions'])->findOrFail($roleId);

        $this->selectedRoleId = $role->id;
        $this->roleLabel = $role->label;

        $this->selectedPermissions = $role->permissions
            ->pluck('id')
            ->toArray();

        $this->roleUsers = $role->users
            ->map(fn ($u) => [
                'id'       => $u->id,
                'name'     => $u->name,
                'email'    => $u->email,
                'is_admin' => $u->hasRole('admin'),
            ])
            ->toArray();
    }

    public function updateRole(): void
    {
        abort_unless($this->selectedRoleId, 404);

        $role = Role::findOrFail($this->selectedRoleId);
        abort_if($role->name === 'admin', 403);

        $this->validate([
            'roleLabel' => 'required|min:3',
        ]);

        $role->update([
            'label' => $this->roleLabel,
            'name'  => Str::slug($this->roleLabel, '_'),
        ]);

        $this->dispatch('toast', type: 'success', title: 'Papel atualizado');

        // 🔄 garante refresh visual
        $this->selectRole($role->id);
    }

    public function saveRolePermissions(): void
    {
        abort_unless($this->selectedRoleId, 404);

        $role = Role::findOrFail($this->selectedRoleId);
        abort_if($role->name === 'admin', 403);

        $role->permissions()->sync($this->selectedPermissions);

        $this->dispatch('toast', type: 'success', title: 'Permissões salvas');

        // 🔄 recarrega papel e permissões
        $this->selectRole($role->id);
    }
};
?>


<x-content title="Papéis & Permissões">

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- PAPÉIS --}}
    <x-card>
        <h3 class="font-semibold mb-4">Papéis</h3>

        <form wire:submit.prevent="createRole" class="space-y-2 mb-6">
            <x-input label="Novo papel" wire:model.defer="newRoleLabel"/>
            <x-button size="sm" type="submit">Criar papel</x-button>
        </form>

        @foreach($this->roles as $role)
            <button
                wire:key="role-{{ $role->id }}"
                wire:click="selectRole({{ $role->id }})"
                class="w-full px-3 py-2 rounded-lg text-left
                {{ $selectedRoleId === $role->id ? 'bg-slate-200' : 'hover:bg-slate-100' }}"
            >
                <div class="flex justify-between">
                    <span>{{ $role->label }}</span>
                    <span class="text-xs opacity-70">{{ $role->users_count }}</span>
                </div>
            </button>
        @endforeach
    </x-card>

    {{-- CONFIGURAÇÃO --}}
    <x-card class="lg:col-span-2">
        @if($this->selectedRole)

            @php $isAdmin = $this->selectedRole->name === 'admin'; @endphp

            <x-input
                label="Nome do papel"
                wire:model.defer="roleLabel"
                :disabled="$isAdmin"
            />

            @unless($isAdmin)
                <x-button size="sm" wire:click="updateRole">
                    Salvar papel
                </x-button>
            @endunless

            <hr class="my-6">

            {{-- USUÁRIOS --}}
            <h4 class="font-semibold text-sm mb-3">Usuários</h4>

            <ul class="space-y-3 mb-6">
                @foreach($roleUsers as $user)
                    <li class="flex justify-between items-center">
                        <div>
                            <div class="font-medium">{{ $user['name'] }}</div>
                            <div class="text-xs text-slate-500">{{ $user['email'] }}</div>
                        </div>

                        @if($user['is_admin'])
                            <x-button size="sm" variant="secondary" disabled>
                                Administrador
                            </x-button>
                        @else
                            <x-button
                                size="sm"
                                variant="warning"
                                :outline="true"
                                @click="$dispatch('users.change-role.open', { id: {{ $user['id'] }} })"
                            >
                                Alterar papel
                            </x-button>
                        @endif
                    </li>
                @endforeach
            </ul>

            <hr class="my-6">

            {{-- PERMISSÕES --}}
            @if($isAdmin)
                <p class="text-sm text-slate-500">
                    Administrador possui todas as permissões.
                </p>
            @else
                <form wire:submit.prevent="saveRolePermissions" class="space-y-6">
                    @foreach($this->permissionsByModule as $module => $permissions)
                        <div wire:key="module-{{ $module }}">
                            <h4 class="text-sm font-semibold mb-2">{{ $module }}</h4>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                @foreach($permissions as $perm)
                                    <label class="flex items-center gap-2 text-sm"
                                           wire:key="perm-{{ $perm->id }}">
                                        <input
                                            type="checkbox"
                                            wire:model.defer="selectedPermissions"
                                            value="{{ $perm->id }}"
                                        >
                                        {{ $perm->label ?? $perm->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    <x-button size="sm" type="submit">
                        Salvar permissões
                    </x-button>
                </form>
            @endif

        @else
            <p class="text-sm text-slate-500">
                Selecione um papel.
            </p>
        @endif
    </x-card>

</div>

{{-- MODAL DESACOPLADO (NOME CORRIGIDO) --}}

</x-content>
