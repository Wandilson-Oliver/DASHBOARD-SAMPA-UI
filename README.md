# SAMPA UI

Design system leve e consistente para aplicações Laravel, construído com **Blade Components**, **Livewire 4** e **Tailwind CSS 4**.

O objetivo é fornecer **componentes simples, reutilizáveis e previsíveis**, com um **padrão visual único**, sem abstrações desnecessárias ou dependências externas.

---

## ✨ Princípios

- **Utility-first** (Tailwind como fonte visual)
- **Semântica clara** (cores e variantes com significado)
- **Configuração centralizada**
- **Blade simples** (sem lógica complexa)
- **Total compatibilidade com Livewire**

---

## 🎨 Sistema de Cores

A identidade visual é baseada em **cores semânticas**, mapeadas para a paleta interna do Tailwind.

### Cores semânticas

```php
primary   → blue
secondary → slate
neutral   → slate
success   → teal
warning   → amber
error     → red
info      → sky



<x-button variant="secondary">
    Cancelar
</x-button>

<x-button variant="danger">
    Excluir
</x-button>

<x-button variant="ghost" size="sm">
    Ver detalhes
</x-button>


<x-input
    name="email"
    type="email"
    wire:model.live="email"
/>
<x-input size="sm" />
<x-input size="lg" />
