<div class="d-flex justify-content-center pt-2">

    {{-- BUTTON EDIT --}}
    <a href="{{ $editRoute }}"
        class="action-btn btn-warning mx-2 shadow-sm"
        title="Edit"
        aria-label="Edit Data">

        <i class="fas fa-edit fa-sm text-white"></i>

        @if ($showText ?? false)
            <span class="ml-1 text-white">
                Edit
            </span>
        @endif
    </a>

    {{-- BUTTON DELETE --}}
    <form action="{{ $deleteRoute }}"
        method="POST"
        class="d-inline"
        onsubmit="return confirmDelete()">

        @csrf
        @method('DELETE')

        <button type="submit"
            class="action-btn btn-danger border-0 mx-2 shadow-sm"
            title="Hapus"
            aria-label="Hapus Data">

            <i class="fas fa-trash fa-sm text-white"></i>

            @if ($showText ?? false)
                <span class="ml-1 text-white">
                    Hapus
                </span>
            @endif
        </button>
    </form>

</div>