<?php

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\Product;

new class extends Component
{
    public array $products = [];

    // 🔹 filtros locais
    public ?string $price_min = null;
    public ?string $price_max = null;

    public function mount(): void
    {
        $this->loadProducts();
    }

    /* =====================================================
        ATUALIZA QUANDO DIGITA VALOR MÍNIMO
        - Só aplica quando termina em ,00
    ====================================================== */
    public function updatedPriceMin($value): void
    {
        if (! str_ends_with($value ?? '', ',00')) {
            return;
        }

        $this->emitFilters();
    }

    /* =====================================================
        ATUALIZA QUANDO DIGITA VALOR MÁXIMO
        - MESMA REGRA DO MÍNIMO
    ====================================================== */
    public function updatedPriceMax($value): void
    {
        if (! str_ends_with($value ?? '', ',00')) {
            return;
        }

        $this->emitFilters();
    }

    /* =====================================================
        RECEBE FILTROS EXTERNOS (continua funcionando)
    ====================================================== */
    #[On('products.filters.updated')]
    public function applyFilters(array $filters): void
    {
        $this->loadProducts($filters);

        $this->dispatch('update-map', products: $this->products);
    }

    /* =====================================================
        EMITE FILTROS LOCAIS (price_min / price_max)
    ====================================================== */
    protected function emitFilters(): void
    {
        $this->applyFilters([
            'price_min' => $this->price_min,
            'price_max' => $this->price_max,
        ]);
    }

    /* =====================================================
        QUERY PRINCIPAL
    ====================================================== */
    protected function loadProducts(array $filters = []): void
    {
        $query = Product::query()
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->with('address');

        // busca
        if (!empty($filters['search'])) {
            $query->where(fn ($q) =>
                $q->where('cod', 'like', "%{$filters['search']}%")
                  ->orWhere('ref', 'like', "%{$filters['search']}%")
            );
        }

        // status
        if (!empty($filters['status'])) {
            if ($filters['status'] === 'deleted') {
                $query->withTrashed();
            } else {
                $query->where('status', $filters['status']);
            }
        }

        // categoria
        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        // grupo
        if (!empty($filters['group_id'])) {
            $query->where('group_id', $filters['group_id']);
        }

        // bairro
        if (!empty($filters['neighborhood'])) {
            $query->whereHas('address', fn ($a) =>
                $a->where('neighborhood', $filters['neighborhood'])
            );
        }

        // cidade
        if (!empty($filters['city'])) {
            $query->whereHas('address', fn ($a) =>
                $a->where('city', $filters['city'])
            );
        }

        // valor mínimo
        if (
            array_key_exists('price_min', $filters) &&
            $filters['price_min'] !== null &&
            $filters['price_min'] !== ''
        ) {
            $query->where(
                'amount',
                '>=',
                $this->normalizeMoney($filters['price_min'])
            );
        }

        // valor máximo
        if (
            array_key_exists('price_max', $filters) &&
            $filters['price_max'] !== null &&
            $filters['price_max'] !== ''
        ) {
            $query->where(
                'amount',
                '<=',
                $this->normalizeMoney($filters['price_max'])
            );
        }

        $this->products = $query
            ->get(['id', 'cod', 'ref', 'lat', 'long', 'amount', 'status'])
            ->map(fn ($p) => [
                'id'     => $p->id,
                'cod'    => $p->cod,
                'ref'    => $p->ref,
                'lat'    => (float) $p->lat,
                'lng'    => (float) $p->long,
                'amount' => $p->amount,
                'status' => $p->status,
                'image'  => $p->coverImage
                    ? asset('storage/' . $p->coverImage->path)
                    : null,
            ])
            ->toArray();
    }

    /* =====================================================
        NORMALIZA MOEDA BR (CORREÇÃO DEFINITIVA)
        - NÃO depende de ponto ou vírgula
        - Sempre trata como centavos
    ====================================================== */
    protected function normalizeMoney(?string $value): ?float
    {
        if ($value === null || $value === '') {
            return null;
        }

        // remove tudo que não for número
        $numeric = preg_replace('/\D/', '', $value);

        if ($numeric === '') {
            return null;
        }

        // converte centavos → reais
        return ((float) $numeric) / 100;
    }
};
