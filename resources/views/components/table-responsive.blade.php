<div class="table-responsive">
    <table {{ $attributes->merge(['class' => 'data-table']) }}>
        {{ $slot }}
    </table>
</div>