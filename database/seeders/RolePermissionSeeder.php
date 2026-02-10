<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        | Papel admin é protegido por REGRA, não por schema
        */

        $admin = Role::updateOrCreate(
            ['name' => 'admin'],
            [
                'label' => 'Administrador',
            ]
        );

        $gestor = Role::updateOrCreate(
            ['name' => 'gestor'],
            [
                'label' => 'Gestor',
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | PERMISSÕES (organizadas por módulo)
        |--------------------------------------------------------------------------
        */

        $permissions = [
            // 👤 USUÁRIOS
            [
                'name'      => 'users.view',
                'label'     => 'Visualizar usuários',
                'module'    => 'Usuários',
                'is_system' => true,
            ],
            [
                'name'      => 'users.create',
                'label'     => 'Criar usuários',
                'module'    => 'Usuários',
                'is_system' => true,
            ],
            [
                'name'      => 'users.edit',
                'label'     => 'Editar usuários',
                'module'    => 'Usuários',
                'is_system' => true,
            ],
            [
                'name'      => 'users.delete',
                'label'     => 'Excluir usuários',
                'module'    => 'Usuários',
                'is_system' => true,
            ],
            [
                'name'      => 'users.restore',
                'label'     => 'Restaurar usuários',
                'module'    => 'Usuários',
                'is_system' => true,
            ],
            [
                'name'      => 'users.reset_password',
                'label'     => 'Resetar senha de usuários',
                'module'    => 'Usuários',
                'is_system' => true,
            ],

            // 🔐 SESSÕES / SEGURANÇA
            [
                'name'      => 'sessions.view',
                'label'     => 'Visualizar sessões',
                'module'    => 'Segurança',
                'is_system' => true,
            ],
            [
                'name'      => 'sessions.terminate',
                'label'     => 'Encerrar sessões',
                'module'    => 'Segurança',
                'is_system' => true,
            ],

            // PERMISSOES 
            [
                'name'      => 'roles.manage',
                'label'     => 'Visualizar papéis e permissões',
                'module'    => 'Permissões',
                'is_system' => true,
            ],

            // CHAT FAQS
            [
                'name'      => 'chat-faqs.manage',
                'label'     => 'Gerenciar FAQs do chat',
                'module'    => 'Chat',
                'is_system' => true,
            ],
        ];

        foreach ($permissions as $data) {
            Permission::updateOrCreate(
                ['name' => $data['name']],
                [
                    'label'     => $data['label'],
                    'module'    => $data['module'],
                    'is_system' => $data['is_system'],
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VÍNCULOS
        |--------------------------------------------------------------------------
        */

        // 👑 ADMIN → TODAS AS PERMISSÕES
        $admin->permissions()->sync(
            Permission::pluck('id')
        );

        // 👔 GESTOR → PERMISSÕES CONTROLADAS
        $gestor->permissions()->sync(
            Permission::whereIn('name', [
                'users.view',
                'users.create',
                'users.edit',
            ])->pluck('id')
        );
    }
}
