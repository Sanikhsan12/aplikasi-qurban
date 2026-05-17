@props(['order', 'showButton' => true])

@php
    $canBePaid = $order->canBePaid();
    $statusColor = match($order->status) {
        'pending' => 'warning',
        'menunggu_verifikasi' => 'info',
        'disetujui' => 'success',
        'ditolak' => 'danger',
        default => 'secondary'
    };

    $statusIcon = match($order->status) {
        'pending' => 'fa-clock',
        'menunggu_verifikasi' => 'fa-hourglass-half',
        'disetujui' => 'fa-check-circle',
        'ditolak' => 'fa-times-circle',
        default => 'fa-question-circle'
    };
@endphp

<div class="payment-status-card">
    <div class="status-header">
        <div class="status-badge badge-{{ $statusColor }}">
            <i class="fas {{ $statusIcon }}"></i>
            {{ $order->getStatusFormattedAttribute() }}
        </div>
        <div class="status-amount">
            {{ $order->getTotalHargaFormattedAttribute() }}
        </div>
    </div>

    <div class="status-details">
        <div class="detail-row">
            <span class="detail-label">Order ID:</span>
            <span class="detail-value">#{{ $order->id }}</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Hewan:</span>
            <span class="detail-value">{{ ucfirst($order->jenis_hewan) }} ({{ $order->total_hewan }} ekor)</span>
        </div>
        <div class="detail-row">
            <span class="detail-label">Tanggal Order:</span>
            <span class="detail-value">{{ $order->created_at->format('d M Y H:i') }}</span>
        </div>
    </div>

    @if ($showButton && $canBePaid)
        <a href="{{ route('peserta.payment.show', $order->id) }}" class="btn btn-payment">
            <i class="fas fa-credit-card"></i> Bayar Sekarang
        </a>
    @elseif ($showButton && $order->isPaid())
        <button class="btn btn-payment btn-success" disabled>
            <i class="fas fa-check-circle"></i> Sudah Dibayar
        </button>
    @elseif ($showButton && $order->isRejected())
        <a href="{{ route('peserta.order.create') }}" class="btn btn-payment btn-secondary">
            <i class="fas fa-plus"></i> Peserta Baru
        </a>
    @endif

    <style>
        .payment-status-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .status-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f3f4f6;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-badge.badge-warning {
            background: #fef3c7;
            color: #92400e;
        }

        .status-badge.badge-info {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-badge.badge-success {
            background: #dcfce7;
            color: #166534;
        }

        .status-badge.badge-danger {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-amount {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
        }

        .status-details {
            margin-bottom: 1.5rem;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f3f4f6;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            color: #6b7280;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .detail-value {
            color: #1f2937;
            font-weight: 600;
            text-align: right;
        }

        .btn-payment {
            width: 100%;
            padding: 0.875rem;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
        }

        .btn-payment:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(59, 130, 246, 0.3);
            color: white;
            text-decoration: none;
        }

        .btn-payment:disabled {
            background: #10b981;
            cursor: not-allowed;
            transform: none;
        }

        .btn-payment.btn-secondary {
            background: #6b7280;
        }

        .btn-payment.btn-secondary:hover {
            background: #4b5563;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .payment-status-card {
                padding: 1rem;
            }

            .status-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
            }

            .status-amount {
                font-size: 1.25rem;
            }

            .detail-row {
                flex-direction: column;
                gap: 0.25rem;
            }

            .detail-value {
                text-align: left;
            }
        }
    </style>
</div>
