// form-pendaftaran.js
document.addEventListener('DOMContentLoaded', function () {
    // =============================================
    // 1. ELEMENTS SELECTION
    // =============================================
    const tipePendaftaran = document.getElementById('tipe_pendaftaran');
    const transferGroup = document.getElementById('transfer-group');
    const kirimGroup = document.getElementById('kirim-group');
    const beratKirimGroup = document.getElementById('berat-kirim-group');
    const buktiGroup = document.getElementById('bukti-group');
    const bankSelectGroup = document.getElementById('bank-select-group');
    const bankInfoGroup = document.getElementById('bank-info-group');
    const bankDetailContainer = document.getElementById('bank-detail-container');
    const bankSelect = document.getElementById('bank_id');

    // Form calculation elements
    const hewanSelect = document.getElementById('ketersediaan_hewan_id');
    const jumlahInput = document.getElementById('total_hewan');
    const jenisHewanInput = document.getElementById('jenis_hewan_input');
    const beratKirimInput = document.getElementById('berat_kirim_input');

    // Hidden fields
    const beratHidden = document.getElementById('berat_hewan_hidden');
    const dagingHidden = document.getElementById('perkiraan_daging_hidden');
    const hargaHidden = document.getElementById('total_harga_hidden');
    const jenisHidden = document.getElementById('jenis_hewan_hidden');

    // Display fields
    const beratDisplay = document.getElementById('berat_total_display');
    const dagingDisplay = document.getElementById('perkiraan_daging_display');
    const hargaDisplay = document.getElementById('total_harga_display');

    // =============================================
    // 1. NAVBAR FUNCTIONALITY
    // =============================================
    const mobileToggle = document.getElementById('mobileToggle');
    const navLinks = document.getElementById('navLinks');
    const userTrigger = document.getElementById('userTrigger');
    const dropdownMenu = document.getElementById('dropdownMenu');

    // Mobile menu toggle
    if (mobileToggle) {
        mobileToggle.addEventListener('click', function () {
            navLinks.classList.toggle('open');
            this.innerHTML = navLinks.classList.contains('open')
                ? '<i class="fas fa-times"></i>'
                : '<i class="fas fa-bars"></i>';
        });
    }

    // User dropdown toggle
    if (userTrigger) {
        userTrigger.addEventListener('click', function (e) {
            e.stopPropagation();
            this.classList.toggle('open');
            dropdownMenu.classList.toggle('open');
        });
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function (e) {
        if (userTrigger && !userTrigger.contains(e.target) &&
            dropdownMenu && !dropdownMenu.contains(e.target)) {
            userTrigger.classList.remove('open');
            dropdownMenu.classList.remove('open');
        }
    });

    // Close mobile menu when clicking on a link
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function () {
            if (window.innerWidth <= 768 && navLinks) {
                navLinks.classList.remove('open');
                if (mobileToggle) {
                    mobileToggle.innerHTML = '<i class="fas fa-bars"></i>';
                }
            }
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            if (this.getAttribute('href') === '#') return;

            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);

            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 100,
                    behavior: 'smooth'
                });
            }
        });
    });

    // =============================================
    // 2. TOGGLE FORM SECTIONS
    // =============================================
    function toggleFormSections() {
        if (!tipePendaftaran) return;

        const isTransfer = tipePendaftaran.value === 'transfer';
        const isKirim = tipePendaftaran.value === 'kirim langsung';

        // Toggle visibility
        if (transferGroup) transferGroup.style.display = isTransfer ? 'block' : 'none';
        if (bankSelectGroup) bankSelectGroup.style.display = isTransfer ? 'block' : 'none';
        if (buktiGroup) buktiGroup.style.display = isTransfer ? 'block' : 'none';
        if (kirimGroup) kirimGroup.style.display = isKirim ? 'block' : 'none';
        if (beratKirimGroup) beratKirimGroup.style.display = isKirim ? 'block' : 'none';

        // Sembunyikan bank info jika bukan transfer
        if (!isTransfer && bankInfoGroup) {
            bankInfoGroup.style.display = 'none';
            if (bankDetailContainer) {
                bankDetailContainer.innerHTML = '';
            }
        }

        // Toggle required attributes
        if (hewanSelect) hewanSelect.required = isTransfer;
        if (jenisHewanInput) jenisHewanInput.required = isKirim;
        if (beratKirimInput) beratKirimInput.required = isKirim;
        if (bankSelect) bankSelect.required = isTransfer;

        const buktiInput = document.querySelector('input[name="bukti_pembayaran"]');
        if (buktiInput) buktiInput.required = isTransfer;

        // Reset jika berganti tipe
        if (isTransfer) {
            if (jenisHewanInput) jenisHewanInput.value = '';
            if (beratKirimInput) beratKirimInput.value = '';
        } else if (isKirim) {
            if (hewanSelect) hewanSelect.value = '';
            if (bankSelect) bankSelect.value = '';
            if (bankInfoGroup) bankInfoGroup.style.display = 'none';
            if (bankDetailContainer) bankDetailContainer.innerHTML = '';
        }

        // Hitung ulang
        hitungDanUpdate();
    }

    // =============================================
    // 3. BANK SELECTION FUNCTIONALITY
    // =============================================
    function updateBankDetail() {
        if (!bankSelect || !bankDetailContainer) return;

        const selectedBank = bankSelect.options[bankSelect.selectedIndex];

        if (!selectedBank.value) {
            bankInfoGroup.style.display = 'none';
            bankDetailContainer.innerHTML = '';
            return;
        }

        const namaBank = selectedBank.getAttribute('data-nama-bank');
        const noRek = selectedBank.getAttribute('data-no-rek');
        const asNama = selectedBank.getAttribute('data-as-nama');

        const bankHTML = `
            <h5 class="card-title mb-3" style="color: var(--primary);">
                <i class="fas fa-university me-2"></i>Informasi Transfer
            </h5>
            
            <div class="bank-card mb-3">
                <div class="bank-header">
                    <h6 class="bank-name mb-1">
                        <i class="fas fa-building me-2"></i>${namaBank}
                    </h6>
                    <div class="bank-number">
                        <strong>No. Rekening:</strong>
                        <span class="rekening-number">${noRek}</span>
                        <button type="button" class="btn-copy" 
                                data-copy-text="${noRek}"
                                title="Salin nomor rekening">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="bank-detail">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-2">
                                <strong><i class="fas fa-user me-2"></i>Atas Nama:</strong>
                                <br>
                                <span class="bank-account-name">${asNama}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="transfer-instructions mt-3">
                <h6 class="mb-2">
                    <i class="fas fa-list-check me-2"></i>Petunjuk Transfer:
                </h6>
                <ol class="small">
                    <li>Transfer ke rekening <strong>${namaBank}</strong> di atas</li>
                    <li>Jumlah transfer sesuai dengan <strong>Total Harga</strong></li>
                    <li>Pastikan nama penerima adalah <strong>${asNama}</strong></li>
                    <li>Simpan bukti transfer untuk diupload</li>
                    <li>Upload bukti transfer pada form di atas</li>
                    <li>Transfer akan diverifikasi oleh panitia</li>
                </ol>
            </div>
        `;

        bankDetailContainer.innerHTML = bankHTML;
        bankInfoGroup.style.display = 'block';

        // Re-attach copy button event listeners
        attachCopyButtonListeners();
    }

    function attachCopyButtonListeners() {
        document.querySelectorAll('.btn-copy').forEach(button => {
            button.addEventListener('click', function () {
                const textToCopy = this.getAttribute('data-copy-text');

                navigator.clipboard.writeText(textToCopy).then(() => {
                    const originalHTML = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-check"></i>';
                    this.style.background = '#28a745';
                    this.style.color = 'white';
                    this.style.borderColor = '#28a745';

                    showToast('Nomor rekening berhasil disalin!', 'success');

                    setTimeout(() => {
                        this.innerHTML = originalHTML;
                        this.style.background = '';
                        this.style.color = '';
                        this.style.borderColor = '';
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    showToast('Gagal menyalin, coba lagi', 'error');
                });
            });
        });
    }

    // =============================================
    // 4. FORM CALCULATION FUNCTIONALITY
    // =============================================
    const konversiHewan = {
        'sapi': { karkasPersen: 0.50, dagingDariKarkas: 0.75, kepalaPersen: 0.04, kakiPerEkor: 4.5, ekorPersen: 0.007, jeroanPersen: 0.10 },
        'kambing': { karkasPersen: 0.40, dagingDariKarkas: 0.70, kepalaPersen: 0.03, kakiPerEkor: 1.2, ekorPersen: 0.004, jeroanPersen: 0.08 },
        'domba': { karkasPersen: 0.42, dagingDariKarkas: 0.72, kepalaPersen: 0.03, kakiPerEkor: 1.0, ekorPersen: 0.005, jeroanPersen: 0.09 }
    };

    function hitungPerkiraanDaging(beratPerEkor, jumlahHewan, jenisHewan) {
        const totalBeratHidup = beratPerEkor * jumlahHewan;
        const konversi = konversiHewan[jenisHewan.toLowerCase()] || konversiHewan['sapi'];

        const beratKarkas = totalBeratHidup * konversi.karkasPersen;
        const dagingMurni = beratKarkas * konversi.dagingDariKarkas;
        const kepalaTotal = totalBeratHidup * konversi.kepalaPersen;
        const kakiTotal = konversi.kakiPerEkor * jumlahHewan;
        const ekorTotal = totalBeratHidup * konversi.ekorPersen;
        const jeroanTotal = beratKarkas * konversi.jeroanPersen;
        const totalDagingDistribusi = dagingMurni + kepalaTotal + kakiTotal + ekorTotal + jeroanTotal;

        return {
            totalBeratHidup: totalBeratHidup,
            beratKarkas: beratKarkas,
            dagingMurni: dagingMurni,
            kepalaTotal: kepalaTotal,
            kakiTotal: kakiTotal,
            ekorTotal: ekorTotal,
            jeroanTotal: jeroanTotal,
            totalDagingDistribusi: totalDagingDistribusi
        };
    }

    function hitungDanUpdate() {
        if (!tipePendaftaran) return;

        let beratPerEkor = 0;
        let hargaPerEkor = 0;
        let jenisHewan = '';

        // Reset hidden fields
        if (beratHidden) beratHidden.value = '';
        if (dagingHidden) dagingHidden.value = '';
        if (hargaHidden) hargaHidden.value = '';
        if (jenisHidden) jenisHidden.value = '';

        // Ambil data berdasarkan tipe
        if (tipePendaftaran.value === 'transfer' && hewanSelect && hewanSelect.value) {
            const selectedOption = hewanSelect.options[hewanSelect.selectedIndex];
            beratPerEkor = parseFloat(selectedOption.dataset.berat) || 0;
            hargaPerEkor = parseFloat(selectedOption.dataset.harga) || 0;
            jenisHewan = selectedOption.dataset.jenis || 'sapi';
        } else if (tipePendaftaran.value === 'kirim langsung') {
            beratPerEkor = beratKirimInput ? parseFloat(beratKirimInput.value) || 0 : 0;
            hargaPerEkor = 0;
            jenisHewan = jenisHewanInput ? jenisHewanInput.value || 'sapi' : 'sapi';
        }

        // Jika berat per ekor tidak valid, reset semua
        if (beratPerEkor <= 0) {
            if (beratDisplay) beratDisplay.value = '0 kg';
            if (dagingDisplay) dagingDisplay.value = '0 kg';
            if (hargaDisplay) {
                hargaDisplay.value = tipePendaftaran.value === 'transfer' ? 'Rp 0' : 'Tidak ada biaya (Kirim hewan langsung)';
            }
            return;
        }

        const totalHewan = jumlahInput ? parseInt(jumlahInput.value) || 1 : 1;
        const jenisHewanLower = jenisHewan.toLowerCase();

        const hasilPerhitungan = hitungPerkiraanDaging(beratPerEkor, totalHewan, jenisHewanLower);
        const perkiraanDaging = hasilPerhitungan.totalDagingDistribusi;
        const totalBerat = hasilPerhitungan.totalBeratHidup;
        const totalHarga = hargaPerEkor * totalHewan;

        // Update HIDDEN fields
        if (beratHidden) beratHidden.value = beratPerEkor;
        if (dagingHidden) dagingHidden.value = perkiraanDaging.toFixed(2);
        if (hargaHidden) hargaHidden.value = totalHarga;
        if (jenisHidden) jenisHidden.value = jenisHewanLower;

        // Update DISPLAY fields
        if (beratDisplay) beratDisplay.value = totalBerat.toFixed(1) + ' kg';
        if (dagingDisplay) dagingDisplay.value = perkiraanDaging.toFixed(1) + ' kg';

        if (tipePendaftaran.value === 'transfer') {
            if (hargaDisplay) hargaDisplay.value = 'Rp ' + totalHarga.toLocaleString('id-ID');
        } else {
            if (hargaDisplay) hargaDisplay.value = 'Tidak ada biaya (Kirim hewan langsung)';
        }
    }

    // =============================================
    // 5. TOAST NOTIFICATION
    // =============================================
    function showToast(message, type = 'info') {
        const existingToasts = document.querySelectorAll('.custom-toast');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `custom-toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
        `;

        document.body.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 10);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // =============================================
    // 6. EVENT LISTENERS
    // =============================================
    if (tipePendaftaran) {
        tipePendaftaran.addEventListener('change', toggleFormSections);
    }

    if (bankSelect) {
        bankSelect.addEventListener('change', updateBankDetail);
    }

    if (hewanSelect) {
        hewanSelect.addEventListener('change', hitungDanUpdate);
    }

    if (jumlahInput) {
        jumlahInput.addEventListener('input', hitungDanUpdate);
    }

    if (jenisHewanInput) {
        jenisHewanInput.addEventListener('input', hitungDanUpdate);
    }

    if (beratKirimInput) {
        beratKirimInput.addEventListener('input', hitungDanUpdate);
    }

    // =============================================
    // 7. INITIALIZATION
    // =============================================
    function initializeForm() {
        // Toggle form sections berdasarkan nilai awal
        if (tipePendaftaran) {
            toggleFormSections();

            // Jika transfer dan sudah ada pilihan bank, tampilkan detail
            if (tipePendaftaran.value === 'transfer' && bankSelect && bankSelect.value) {
                updateBankDetail();
            }
        }

        // Attach copy button listeners jika ada
        attachCopyButtonListeners();
    }

    // Panggil initialization
    initializeForm();
});