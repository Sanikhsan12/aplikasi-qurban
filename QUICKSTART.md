<!-- Quick Start Guide for Responsive Design & MidTrans Integration -->

# 🚀 Quick Start Guide

## Responsive Design

### Overview
All pages are now fully responsive with automatic CSS media queries for:
- 📱 **Mobile** (<768px)
- 📱 **Tablet** (768-900px)  
- 💻 **Laptop** (900-1200px)
- 🖥️ **Desktop** (1200px+)

### Included Automatically In:
- ✅ Admin Dashboard
- ✅ User Dashboard

### Add to Other Pages:
```html
<link rel="stylesheet" href="{{ asset('css/responsive-improvements.css') }}">
```

### Key Features:
- Touch-friendly buttons (44x44px minimum)
- Mobile sidebar that toggles
- Responsive tables with horizontal scroll
- Optimized for all screen sizes
- Print-friendly layouts

---

## MidTrans Payment Integration

### Installation
```bash
composer require midtrans/midtrans-php
```

### Configuration
Add to `.env`:
```env
MIDTRANS_SERVER_KEY=your_key_here
MIDTRANS_CLIENT_KEY=your_key_here
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false
```

Get credentials from: https://midtrans.com

### Show Payment Page
```blade
<a href="{{ route('payment.show', $order->id) }}" class="btn btn-primary">
    Pay Now
</a>
```

### Show Payment Status
```blade
<x-payment-status-card :order="$order" />
```

### Check if Order Can Be Paid
```php
if ($order->canBePaid()) {
    // Show payment option
}
```

### Order Status Methods
```php
$order->canBePaid()          // Can be paid?
$order->isPaid()             // Is payment received?
$order->isRejected()         // Was payment rejected?
$order->getPaymentStatusLabel()  // Get status text
```

---

## Routes

### User Payment Routes (Protected)
```
GET  /peserta/payment/order/{order}              Show payment form
GET  /peserta/payment/order/{order}/snap-token   Get Snap token
GET  /peserta/payment/verify/{order}             Verify payment status
```

### Public Routes
```
POST /payment/callback                           MidTrans webhook
GET  /payment/finish                             Success redirect
GET  /payment/error                              Error redirect
```

---

## Testing

### Test Payment (Sandbox Mode)
When `MIDTRANS_IS_PRODUCTION=false`, use test credentials:

**Bank Transfer (Permata)**
- Account: 8562000008888888
- PIN: 000000

**Credit Card**
- Number: 5111111111111142
- Expiry: 12/25
- CVV: 123
- OTP: 123456

More test methods: https://docs.midtrans.com/sandbox-test-payment

---

## File Reference

| File | Purpose |
|------|---------|
| `config/midtrans.php` | MidTrans settings |
| `app/Http/Controllers/PaymentController.php` | Payment logic |
| `app/Helpers/PaymentHelper.php` | Payment utilities |
| `resources/views/payment/show.blade.php` | Payment form |
| `resources/views/components/payment-status-card.blade.php` | Status display |
| `public/css/responsive-improvements.css` | Responsive CSS |

---

## Troubleshooting

### Payment token fails
```bash
# Check keys in .env are correct
# Ensure SDK is installed
composer require midtrans/midtrans-php

# Check logs
tail storage/logs/laravel.log
```

### Webhook not working
- Domain must be publicly accessible (not localhost)
- Set webhook URL in MidTrans Dashboard > Settings
- Verify callback route: POST /payment/callback

### Status not updating
```bash
# Clear cache
php artisan cache:clear

# Check database
php artisan tinker
Order::first()->bukti_pembayaran
```

---

## Production Checklist

- [ ] Run: `composer require midtrans/midtrans-php`
- [ ] Get production MidTrans keys
- [ ] Set `MIDTRANS_IS_PRODUCTION=true`
- [ ] Enable HTTPS
- [ ] Test full payment flow
- [ ] Set webhook URL in MidTrans dashboard
- [ ] Deploy to production
- [ ] Monitor payment callbacks in logs

---

## Documentation Files

- 📄 **MIDTRANS_SETUP.md** - Complete setup guide
- 📄 **IMPROVEMENTS.md** - Detailed improvements summary
- 📄 **QUICKSTART.md** - This file

---

## Support

- MidTrans Docs: https://docs.midtrans.com
- Responsive Design: https://developer.mozilla.org/en-US/docs/Web/CSS/Media_Queries
- Laravel Documentation: https://laravel.com/docs

---

**Last Updated**: May 6, 2025
