    @foreach ($distribusi as $item)
        @if ($item->dokumentasi && count($item->dokumentasi) > 0)
            <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1" role="dialog"
                aria-labelledby="imageModalLabel{{ $item->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered dark-modal" role="document">
                    <div class="modal-content bg-dark text-light">
                        <div class="modal-header border-secondary">
                            <h5 class="modal-title" id="imageModalLabel{{ $item->id }}">
                                <i class="fas fa-images mr-2"></i>Dokumentasi Distribusi
                                <small class="text-light ml-2">
                                    ({{ optional($item->pelaksanaan)->Penyembelihan
                                        ? \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y')
                                        : 'Tanggal tidak tersedia' }})
                                </small>
                            </h5>
                            <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body bg-dark">
                            <!-- Image Carousel -->
                            <div id="carousel{{ $item->id }}" class="carousel slide dark-carousel"
                                data-ride="carousel">
                                <!-- Indicators -->
                                @if (count($item->dokumentasi) > 1)
                                    <ol class="carousel-indicators dark-indicators">
                                        @foreach ($item->dokumentasi as $index => $foto)
                                            <li data-target="#carousel{{ $item->id }}"
                                                data-slide-to="{{ $index }}"
                                                class="{{ $index == 0 ? 'active' : '' }}"></li>
                                        @endforeach
                                    </ol>
                                @endif

                                <!-- Slides -->
                                <div class="carousel-inner">
                                    @foreach ($item->dokumentasi as $index => $foto)
                                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                            <div class="image-container text-center bg-black">
                                                <img src="{{ asset('storage/' . $foto->file_path) }}"
                                                    class="img-fluid modal-image-dark"
                                                    alt="Dokumentasi {{ $index + 1 }}">
                                            </div>
                                            <div class="carousel-caption d-none d-md-block">
                                                <p class="bg-black d-inline-block px-3 py-2 rounded">Gambar
                                                    {{ $index + 1 }} dari {{ count($item->dokumentasi) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Controls -->
                                @if (count($item->dokumentasi) > 1)
                                    <a class="carousel-control-prev dark-control" href="#carousel{{ $item->id }}"
                                        role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon dark-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next dark-control" href="#carousel{{ $item->id }}"
                                        role="button" data-slide="next">
                                        <span class="carousel-control-next-icon dark-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                @endif
                            </div>

                        </div>
                        <div class="modal-footer border-secondary">
                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <span class="badge badge-dark border border-light">
                                        <i class="fas fa-image mr-1"></i> {{ count($item->dokumentasi) }} Gambar
                                    </span>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-outline-light" data-dismiss="modal">
                                        <i class="fas fa-times mr-1"></i> Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach
