@props([
    'paginator',
    'label' => 'bản ghi',
])

@if(isset($paginator))
<div {{ $attributes->merge(['class' => 'd-flex flex-wrap align-items-center justify-content-between gap-2 mt-3']) }}>
    <div class="text-muted small">
        @if($paginator->total() > 0)
        Đang hiển thị {{ $label }} từ <strong>{{ $paginator->firstItem() }}</strong> đến <strong>{{ $paginator->lastItem() }}</strong> của <strong>{{ $paginator->total() }}</strong> {{ $label }}.
        @else
        Đang hiển thị <strong>0</strong> {{ $label }}.
        @endif
    </div>
    @if($paginator->hasPages())
    <div>
        {{ $paginator->withQueryString()->links() }}
    </div>
    @endif
</div>
@endif
