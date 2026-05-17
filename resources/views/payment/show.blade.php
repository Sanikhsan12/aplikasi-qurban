<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Pembayaran - Manajemen Kurban</title>
    <meta name="description" content="Pembayaran peserta kurban melalui MidTrans">

    <!-- CSS Links -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/user.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive-improvements.css') }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .payment-container {
            max-width: 600px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .payment-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .payment-header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 0.5rem;
        }

        .payment-header p {
            color: #6b7280;
            font-size: 0.95rem;
        }

        .order-summary {
            background: #f9fafb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1px solid #e5e7eb;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .summary-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .summary-label {
            color: #6b7280;
            font-weight: 500;
        }

        .summary-value {
            color: #111827;
            font-weight: 600;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 1rem;
            border-top: 2px solid #d1d5db;
            margin-top: 1rem;
        }

        .summary-total .summary-label {
            font-size: 1.125rem;
            font-weight: 700;
            color: #111827;
        }

        .summary-total .summary-value {
            font-size: 1.25rem;
            color: #1f2937;
        }

        .payment-methods {
            margin-bottom: 2rem;
        }

        .payment-methods h3 {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #111827;
        }

        .payment-button {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            background: white;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .payment-button:hover {
            border-color: #3b82f6;
            background: #eff6ff;
            color: #1e40af;
        }

        .payment-button.active {
            border-color: #3b82f6;
            background: #3b82f6;
            color: white;
        }

        .payment-button i {
            font-size: 1.5rem;
        }

        .btn-pay {
            width: 100%;
            padding: 1rem;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
        }

        .btn-pay:active {
            transform: translateY(0);
        }

        .btn-pay.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .btn-back {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: white;
            color: #374151;
            font-weight: 500;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background: #f3f4f6;
            border-color: #9ca3af;
        }

        .alert-info {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 1rem;
            color: #1e40af;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-info i {
            margin-right: 0.5rem;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }

        .loading-spinner.show {
            display: block;
        }

        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #3b82f6;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-container {
                padding: 1rem;
                margin: 1rem auto;
            }

            .payment-header h1 {
                font-size: 1.5rem;
            }

            .order-summary {
                padding: 1rem;
            }

            .summary-item {
                margin-bottom: 0.75rem;
                padding-bottom: 0.75rem;
            }

            .btn-pay {
                padding: 0.875rem;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="nav-container">
        <div class="nav-main">
            <div class="nav-content">
                <a href="{{ route('peserta.dashboard') }}" class="nav-logo">
                    <div class="logo-icon">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div class="logo-text">
                        Manajemen <span class="text-gold">Kurban</span>
                    </div>
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="payment-container">
        <!-- Header -->
        <div class="payment-header">
            <h1><i class="fas fa-credit-card"></i> Pembayaran Peserta Kurban</h1>
            <p>Silakan lakukan pembayaran untuk menyelesaikan pendaftaran Anda</p>
        </div>

        <!-- Info Alert -->
        <div class="alert-info">
            <i class="fas fa-info-circle"></i>
            Pembayaran Anda akan diproses dengan aman melalui MidTrans
        </div>

        <!-- Order Summary -->
        <div class="order-summary">
            <div class="summary-item">
                <span class="summary-label">No. Order</span>
                <span class="summary-value">#{{ $order->id }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Jenis Hewan</span>
                <span class="summary-value">{{ ucfirst($order->jenis_hewan) }}</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Jumlah Hewan</span>
                <span class="summary-value">{{ $order->total_hewan }} ekor</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Berat Perkiraan</span>
                <span class="summary-value">{{ number_format($order->berat_hewan, 1, ',', '.') }} kg</span>
            </div>
            <div class="summary-item">
                <span class="summary-label">Daging Perkiraan</span>
                <span class="summary-value">{{ number_format($order->perkiraan_daging, 1, ',', '.') }} kg</span>
            </div>
            <div class="summary-total">
                <span class="summary-label">Total Pembayaran</span>
                <span class="summary-value">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div class="loading-spinner" id="loadingSpinner">
            <div class="spinner"></div>
            <p style="margin-top: 1rem; color: #6b7280;">Memproses pembayaran Anda...</p>
        </div>

        <!-- Payment Form -->
        <form id="paymentForm" method="POST">
            @csrf

            <!-- Payment Button -->
            <button type="button" class="btn-pay" id="payButton" onclick="makePayment()">
                <i class="fas fa-lock"></i>
                Bayar Sekarang dengan MidTrans
            </button>

            <!-- Back Button -->
            <a href="{{ route('peserta.dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </form>
    </main>

    <!-- Scripts -->
    <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}"></script>
    <script>
        async function makePayment() {
            const button = document.getElementById('payButton');
            const spinner = document.getElementById('loadingSpinner');
            const form = document.getElementById('paymentForm');

            try {
                // Show loading state
                button.disabled = true;
                button.classList.add('loading');
                spinner.classList.add('show');

                // Get snap token from server
                const response = await fetch('{{ route("peserta.payment.get-snap-token", $order->id) }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (!response.ok) {
                    throw new Error('Gagal mendapatkan token pembayaran');
                }

                const data = await response.json();
                spinner.classList.remove('show');

                // Trigger Snap modal
                snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        // Handle success
                        window.location.href = '{{ route("payment.finish") }}?order_id={{ $order->id }}';
                    },
                    onPending: function(result) {
                        // Handle pending
                        window.location.href = '{{ route("payment.finish") }}?order_id={{ $order->id }}';
                    },
                    onError: function(result) {
                        // Handle error
                        button.disabled = false;
                        button.classList.remove('loading');
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        // Handle close
                        button.disabled = false;
                        button.classList.remove('loading');
                    }
                });
            } catch (error) {
                button.disabled = false;
                button.classList.remove('loading');
                spinner.classList.remove('show');
                alert('Error: ' + error.message);
                console.error('Payment error:', error);
            }
        }

        // Add CSRF token to meta tag if not present
        if (!document.querySelector('meta[name="csrf-token"]')) {
            const meta = document.createElement('meta');
            meta.name = 'csrf-token';
            meta.content = '{{ csrf_token() }}';
            document.head.appendChild(meta);
        }
    </script>
</body>
</html>
