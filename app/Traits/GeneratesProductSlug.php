<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesProductSlug
{
    /**
     * Gera um slug semântico:
     * {categoria}[-no-{bairro}]-{cod}
     *
     * Exemplos:
     * - casa-no-centro-a9f2
     * - apartamento-b7k3
     */
    public function gerarSlug(): string
    {
        $partes = [
            $this->category?->name,
            $this->bairro?->name
                ? "no {$this->bairro->name}"
                : null,
            $this->cod,
        ];

        return Str::slug(
            collect($partes)->filter()->join(' ')
        );
    }

    /**
     * Gera o slug apenas se ainda não existir
     * (evita quebrar URLs)
     */
    public function gerarSlugSeVazio(): void
    {
        if (empty($this->slug)) {
            $this->slug = $this->gerarSlug();
        }
    }
}
