<div class="card custom-card fade-in">
    <div class="card-body">

        {{-- ===== DESKTOP ===== --}}
        <div class="d-none d-md-block">
            <div class="table-responsive">
                {{ $slot }}
            </div>
        </div>

        {{-- ===== MOBILE (optional wrapper) ===== --}}
        @isset($mobile)
            <div class="d-md-none">
                {{ $mobile }}
            </div>
        @endisset

        {{-- ===== PAGINATION ===== --}}
        @isset($pagination)
            <div class="d-flex justify-content-center mt-4">
                <nav aria-label="Page navigation">
                    {{ $pagination }}
                </nav>
            </div>

            {{-- <div class="d-flex justify-content-center mt-5">
                <nav aria-label="Page navigation">
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                </nav>
            </div> --}}
        @endisset

    </div>
</div>
