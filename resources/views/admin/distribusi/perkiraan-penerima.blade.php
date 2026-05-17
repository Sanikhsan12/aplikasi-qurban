<!DOCTYPE html>
<html lang="id">

@include('components.admin.head')

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        @include('components.admin.sidebar')
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                @include('components.admin.topbar')
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <!-- Page Header -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="h3 mb-0 text-gray-800">
                                Data Pendistribusian daging kurban
                            </h1>
                            {{-- <p class="text-muted mb-0">Kelola status dan dokumentasi pendistribusian daging kurban</p> --}}
                        </div>
                    </div>

                    <!-- Messages -->
                    @include('components.admin.message')

                    <!-- Data Table -->
                    <div class="card custom-card fade-in">
                        <div class="card-body">

                            <!-- Desktop Table View -->
                            <div class="d-none d-md-block">
                                <div class="card mb-3">
                                    <div
                                        class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Rekap Perkiraan Distribusi Daging</h5>
                                        <div class="d-flex align-items-center">
                                            <label for="kg_per_orang" class="mb-0 me-2 mr-2 text-white">Asumsi</label>
                                            {{-- <div class="input-group input-group-sm" style="width: 10rem;"> --}}
                                            <input type="number" name="kg_per_orang" id="kg_per_orang"
                                                class="form-control form-control-sm" value="{{ $kgPerOrang }}"
                                                min="0.1" step="0.1" required>
                                            <span class="input-group-text">kg / orang</span>
                                            {{-- </div> --}}
                                            <button id="resetBtn" class="btn btn-secondary btn-sm ms-2">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th width="50">No</th>
                                                    <th>Jenis Hewan</th>
                                                    <th>Perkiraan Daging</th>
                                                    <th>Perkiraan Total Penerima</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody">
                                                @forelse($dataTabel as $index => $item)
                                                    <tr data-total-daging="{{ $item->total_daging }}">
                                                        <td class="font-weight-bold">{{ $index + 1 }}</td>
                                                        <td>
                                                            <span class="text-primary">
                                                                {{ $item->jenis_hewan ?? 'Tidak Diketahui' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="total-daging">
                                                                {{ number_format($item->total_daging, 2) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="penerima">
                                                                {{ number_format(floor($item->total_daging / $kgPerOrang)) }}
                                                                orang
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-5">
                                                            <div class="empty-state">
                                                                <div class="empty-state-icon">
                                                                    <i class="fas fa-calendar-times"></i>
                                                                </div>
                                                                <h4 class="text-gray-700 mb-3">Belum Ada Data</h4>
                                                                <p class="text-gray-500 mb-4">Tidak ada data distribusi
                                                                    yang tersedia.</p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                            <tfoot id="tableFooter" style="display: none;">
                                                <tr class="table-info">
                                                    <td colspan="3" class="text-end"><strong>Total Penerima:</strong>
                                                    </td>
                                                    <td><strong id="totalPenerima">0 orang</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                             {{-- @forelse($pelaksanaan as $index => $item) --}}
                            <p>* Perkiraan jumlah penerima daging kurban untuk waktu penyembelihan: {{ \Carbon\Carbon::parse($pelaksanaan->penyembelihan)->format('d M Y') }}
                            {{-- @endforelse --}}

                            <!-- Mobile Card View -->
                            <div class="d-md-none">
                                <div class="card-header bg-primary text-white">
                                    <h5 class="mb-0">Rekap Perkiraan Distribusi Daging</h5>
                                    <small class="asumsi-text">Asumsi {{ $kgPerOrang }} kg / orang</small>
                                </div>

                                <div class="d-flex align-items-center p-3">
                                    <label for="kg_per_orang_mobile" class="mb-0 me-2">Asumsi</label>
                                    <input type="number" name="kg_per_orang" id="kg_per_orang_mobile"
                                        class="form-control form-control-sm kg-per-orang-input"
                                        value="{{ $kgPerOrang }}" min="0.1" step="0.1" required>
                                    <span class="input-group-text ms-2">kg / orang</span>
                                    <button class="btn btn-secondary btn-sm ms-2 reset-kg-btn" data-target="mobile">
                                        <i class="fas fa-undo"></i>
                                    </button>
                                </div>

                                @forelse($dataTabel as $index => $item)
                                    <div class="mobile-card-view card mb-3 shadow-sm fade-in"
                                        data-total-daging="{{ $item->total_daging }}">
                                        <div class="card-body">
                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">No</div>
                                                    <div class="mobile-value font-weight-bold">{{ $loop->iteration }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Jenis Hewan</div>
                                                    <div class="mobile-value">
                                                        <span>{{ $item->jenis_hewan ?? 'Tidak Diketahui' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mobile-row">
                                                <div>
                                                    <div class="mobile-label">Perkiraan Daging</div>
                                                    <div class="mobile-value text-primary total-daging-mobile">
                                                        {{ number_format($item->total_daging, 2) }}
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="mobile-label">Perkiraan Penerima</div>
                                                    <div class="mobile-value donor-name penerima-mobile">
                                                        {{ number_format($item->perkiraan_penerima) }} orang
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="empty-state">
                                        <div class="empty-state-icon">
                                            <i class="fas fa-calendar-times"></i>
                                        </div>
                                        <h4 class="text-gray-700 mb-3">Belum Ada Jadwal</h4>
                                        <p class="text-gray-500 mb-4">Tidak ada data penyembelihan yang tersedia.</p>
                                        <a href="{{ route('admin.penyembelihan.create') }}" class="btn btn-custom">
                                            <i class="fas fa-plus-circle mr-2"></i>Tambah Data
                                        </a>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Pagination -->
                            @if ($dataTabel instanceof \Illuminate\Pagination\LengthAwarePaginator && $dataTabel->hasPages())
                                <!-- Default Bootstrap Pagination -->
                                <div class="d-flex justify-content-center mt-5">
                                    <nav aria-label="Page navigation">
                                        {{ $dataTabel->onEachSide(1)->links('pagination::bootstrap-5') }}
                                    </nav>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Sistem Manajemen Kurban {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
        </div>
        <!-- End of Content Wrapper -->
    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Image Modals -->
    {{-- @foreach ($penyembelihan as $item)
        @if ($item->dokumentasi_penyembelihan)
            <div class="modal fade" id="imageModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title">
                                <i class="fas fa-image mr-2"></i>Dokumentasi Penyembelihan
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ asset('storage/' . $item->dokumentasi_penyembelihan) }}"
                                alt="Dokumentasi Penyembelihan" class="img-fluid rounded">
                            <div class="mt-3 text-left">
                                <p><strong>Donatur:</strong> {{ $item->order->user->name ?? '-' }}</p>
                                <p><strong>Tanggal:</strong>
                                    {{ \Carbon\Carbon::parse($item->pelaksanaan->Penyembelihan)->format('d M Y') ?? '-' }}
                                </p>
                                <p><strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $item->status)) ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Tutup
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach --}}

    <!-- Logout Modal -->
    @include('components.admin.logout')

    <!-- Bootstrap & jQuery Scripts -->
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('js/admin/penyembelihan/index.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Function to format number
            function formatNumber(number, decimals = 0) {
                return parseFloat(number).toLocaleString('id-ID', {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                });
            }

            // Function to update desktop table
            function updateDesktopCalculations(kgPerOrang) {
                kgPerOrang = parseFloat(kgPerOrang) || 1;
                let totalPenerima = 0;

                // Update each row in desktop table
                document.querySelectorAll('tbody tr[data-total-daging]').forEach(row => {
                    const totalDaging = parseFloat(row.getAttribute('data-total-daging'));
                    const penerima = Math.floor(totalDaging / kgPerOrang);

                    row.querySelector('.penerima').textContent =
                        formatNumber(penerima) + ' orang';

                    totalPenerima += penerima;
                });

                // Update footer
                const totalPenerimaElem = document.getElementById('totalPenerima');
                if (totalPenerimaElem) {
                    totalPenerimaElem.textContent = formatNumber(totalPenerima) + ' orang';
                }

                // Show/hide footer
                const tableFooter = document.getElementById('tableFooter');
                if (tableFooter) {
                    tableFooter.style.display = totalPenerima > 0 ? '' : 'none';
                }

                // Update header text
                const desktopHeaderText = document.querySelector('.d-none.d-md-block .card-header small');
                if (desktopHeaderText) {
                    desktopHeaderText.textContent = `Asumsi ${kgPerOrang} kg / orang`;
                }
            }

            // Function to update mobile cards
            function updateMobileCalculations(kgPerOrang) {
                kgPerOrang = parseFloat(kgPerOrang) || 1;
                let totalPenerima = 0;

                // Update each mobile card
                document.querySelectorAll('.mobile-card-view[data-total-daging]').forEach(card => {
                    const totalDaging = parseFloat(card.getAttribute('data-total-daging'));
                    const penerima = Math.floor(totalDaging / kgPerOrang);

                    // Find and update penerima element
                    const penerimaElem = card.querySelector('.penerima-mobile');
                    if (penerimaElem) {
                        penerimaElem.textContent = formatNumber(penerima) + ' orang';
                    }

                    totalPenerima += penerima;
                });

                // Update mobile header text
                const mobileHeaderText = document.querySelector('.d-md-none .asumsi-text');
                if (mobileHeaderText) {
                    mobileHeaderText.textContent = `Asumsi ${kgPerOrang} kg / orang`;
                }

                // Optional: Add total penerima for mobile
                const mobileTotalElem = document.querySelector('.mobile-total-penerima');
                if (!mobileTotalElem && totalPenerima > 0) {
                    // Create total display for mobile
                    const totalDisplay = document.createElement('div');
                    totalDisplay.className = 'alert alert-info mt-3 mobile-total-penerima';
                    totalDisplay.innerHTML =
                    `<strong>Total Penerima: ${formatNumber(totalPenerima)} orang</strong>`;

                    // Insert after mobile cards
                    const mobileView = document.querySelector('.d-md-none');
                    const lastCard = mobileView.querySelector('.mobile-card-view:last-child');
                    if (lastCard) {
                        lastCard.parentNode.insertBefore(totalDisplay, lastCard.nextSibling);
                    }
                } else if (mobileTotalElem) {
                    mobileTotalElem.innerHTML =
                        `<strong>Total Penerima: ${formatNumber(totalPenerima)} orang</strong>`;
                }
            }

            // Function to update all views
            function updateAllCalculations(kgPerOrang) {
                updateDesktopCalculations(kgPerOrang);
                updateMobileCalculations(kgPerOrang);
            }

            // Event listeners for all kg inputs
            document.querySelectorAll('.kg-per-orang-input').forEach(input => {
                input.addEventListener('input', function() {
                    updateAllCalculations(this.value);
                });

                input.addEventListener('change', function() {
                    updateAllCalculations(this.value);
                });
            });

            // Event listeners for reset buttons
            document.querySelectorAll('.reset-kg-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    let input;

                    if (target === 'mobile') {
                        input = document.getElementById('kg_per_orang_mobile');
                    } else if (target === 'desktop') {
                        input = document.getElementById('kg_per_orang_desktop');
                    } else {
                        // Reset all
                        document.querySelectorAll('.kg-per-orang-input').forEach(inp => {
                            inp.value = 1;
                        });
                        updateAllCalculations(1);
                        return;
                    }

                    if (input) {
                        input.value = 1;
                        updateAllCalculations(1);
                    }
                });
            });

            // Add increment/decrement buttons dynamically
            document.querySelectorAll('.kg-per-orang-input').forEach(input => {
                const wrapper = document.createElement('div');
                wrapper.className = 'input-group';

                // Wrap the input
                input.parentNode.insertBefore(wrapper, input);
                wrapper.appendChild(input);

                // Add buttons
                const decrementBtn = document.createElement('button');
                decrementBtn.className = 'btn btn-outline-secondary btn-sm';
                decrementBtn.innerHTML = '<i class="fas fa-minus"></i>';
                decrementBtn.type = 'button';

                const incrementBtn = document.createElement('button');
                incrementBtn.className = 'btn btn-outline-secondary btn-sm';
                incrementBtn.innerHTML = '<i class="fas fa-plus"></i>';
                incrementBtn.type = 'button';

                decrementBtn.addEventListener('click', function() {
                    let value = parseFloat(input.value) || 1;
                    if (value > 0.1) {
                        input.value = (value - 0.1).toFixed(1);
                        updateAllCalculations(input.value);
                    }
                });

                incrementBtn.addEventListener('click', function() {
                    let value = parseFloat(input.value) || 1;
                    input.value = (value + 0.1).toFixed(1);
                    updateAllCalculations(input.value);
                });

                // Create button container
                const btnContainer = document.createElement('div');
                btnContainer.className = 'input-group-append';
                btnContainer.appendChild(decrementBtn);
                btnContainer.appendChild(incrementBtn);

                wrapper.appendChild(btnContainer);
            });

            // Initialize calculations
            const initialKg = document.querySelector('.kg-per-orang-input')?.value || 1;
            updateAllCalculations(initialKg);
        });
    </script>
</body>

</html>
