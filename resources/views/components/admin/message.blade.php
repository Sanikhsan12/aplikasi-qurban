<!-- Messages -->
@if ($errors->any())
    <div class="alert alert-danger alert-custom fade-in">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-lg mr-3"></i>
            <div>
                <strong>Perhatian!</strong> Terdapat kesalahan dalam data:
                <ul class="mb-0 mt-2 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-custom fade-in">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-lg mr-3"></i>
            <div>
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-custom fade-in">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-circle fa-lg mr-3"></i>
            <div>
                <strong>Error!</strong> {{ session('error') }}
            </div>
            <button type="button" class="close ml-auto" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
@endif
