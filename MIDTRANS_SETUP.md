# MidTrans Payment Integration - Setup Guide

## Overview
This project now includes MidTrans payment gateway integration for processing payments from Kurban program participants.

## Requirements
- PHP 8.2 or higher
- Laravel 12.0+
- MidTrans PHP SDK (already configured in composer.json)

## Installation Steps

### 1. Install MidTrans PHP SDK
Run the following command to install the MidTrans PHP SDK:

```bash
composer require midtrans/midtrans-php
```

### 2. Configure Environment Variables
Add the following variables to your `.env` file:

```env
# MidTrans Payment Gateway Configuration
MIDTRANS_ENABLED=true
MIDTRANS_SERVER_KEY=your_midtrans_server_key
MIDTRANS_CLIENT_KEY=your_midtrans_client_key
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_IS_PRODUCTION=false
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true
```

### 3. Get MidTrans Credentials
1. Sign up for a MidTrans account at https://midtrans.com
2. Log in to the MidTrans Dashboard
3. Go to Settings > API Keys
4. Copy your **Server Key** and **Client Key**
5. Paste them into your `.env` file

### 4. File Structure
The payment integration includes:

```
app/
├── Http/
│   └── Controllers/
│       └── PaymentController.php          # Payment processing logic
├── Helpers/
│   └── PaymentHelper.php                  # Payment utility functions
├── Models/
│   └── Order.php                          # Enhanced with payment methods
config/
└── midtrans.php                           # MidTrans configuration
resources/
└── views/
    └── payment/
        └── show.blade.php                 # Payment form view
routes/
└── web.php                                # Payment routes
public/
└── css/
    ├── responsive-improvements.css        # Enhanced responsive design
    └── user.css                           # User page styles
```

## Usage

### Payment Flow

1. **Initiate Payment**
   - User clicks "Pay Now" button on their order
   - System generates a MidTrans Snap token
   - MidTrans payment modal appears

2. **Process Payment**
   - User selects payment method (Bank Transfer, E-Wallet, Credit Card, etc.)
   - User completes payment on MidTrans platform
   - Payment confirmation is sent to the system

3. **Verify Payment**
   - System receives callback from MidTrans
   - Order status is updated automatically
   - User is redirected to dashboard with confirmation

### API Endpoints

#### Get Payment Page
```
GET /peserta/payment/order/{order_id}
```
Displays the payment form for a specific order.

#### Get Snap Token
```
GET /peserta/payment/order/{order_id}/snap-token
```
Returns the MidTrans Snap token for payment processing.

#### Verify Payment
```
GET /peserta/payment/verify/{order_id}
```
Verifies the payment status with MidTrans.

#### Payment Callback (Public)
```
POST /payment/callback
```
Receives payment status updates from MidTrans.

#### Payment Finish
```
GET /payment/finish
```
Redirect page after successful payment.

#### Payment Error
```
GET /payment/error
```
Redirect page after failed or canceled payment.

## Order Model Methods

New payment-related methods added to the Order model:

```php
// Check if order can be paid
$order->canBePaid(): bool

// Check if order is paid
$order->isPaid(): bool

// Check if order is rejected
$order->isRejected(): bool

// Get payment status label
$order->getPaymentStatusLabel(): string
```

## Security Features

- **3D Secure**: Enabled by default for enhanced security
- **Data Sanitization**: Automatic data sanitization
- **Server Key Validation**: All callbacks are validated with server key
- **Authorization**: Payment pages only accessible to order owner or admin

## Testing

### Test Payment Methods (Sandbox Mode)
When `MIDTRANS_IS_PRODUCTION=false`, use these test credentials:

**Bank Transfer (Permata)**
- Account: 8562000008888888
- PIN: 000000

**BCA Virtual Account**
- Account: 99912345678

**Credit Card (Test)**
- Card Number: 5111111111111142
- Expiry: 12/25
- CVV: 123
- OTP: 123456

For more test credentials, visit: https://docs.midtrans.com/en/technical-reference/sandbox-test-payment

## Troubleshooting

### Issue: "Token pembayaran gagal"
- Check if `MIDTRANS_SERVER_KEY` and `MIDTRANS_CLIENT_KEY` are correctly set in `.env`
- Ensure MidTrans PHP SDK is installed: `composer require midtrans/midtrans-php`
- Check Laravel logs at `storage/logs/`

### Issue: Callback not received
- Verify your server is publicly accessible
- Check MidTrans Dashboard > Settings > HTTP Notification
- Ensure firewall allows incoming requests from MidTrans servers
- Test webhook at: https://docs.midtrans.com/en/technical-reference/api-overview#webhook

### Issue: Payment status not updating
- Clear Laravel cache: `php artisan cache:clear`
- Check database connection
- Verify order exists in database
- Check MidTrans transaction status in dashboard

## Responsive Design

The payment interface includes full responsive design:
- **Desktop (1200px+)**: Full layout with sidebar
- **Laptop (900-1199px)**: Optimized 2-column layout
- **Tablet (768-899px)**: Single column with improved spacing
- **Mobile (<768px)**: Mobile-first design with touch-friendly buttons

### Features:
- Touch targets minimum 44x44px on mobile
- Responsive forms with large input fields
- Proper viewport scaling for all devices
- CSS media queries for smooth transitions

## Production Deployment

Before going live:

1. Set environment to production:
   ```env
   MIDTRANS_IS_PRODUCTION=true
   ```

2. Use production MidTrans keys (not sandbox)

3. Test all payment flows

4. Enable HTTPS (required by MidTrans)

5. Set up proper error logging

6. Configure email notifications for payment updates

## Useful Links

- MidTrans Documentation: https://docs.midtrans.com
- MidTrans Dashboard: https://dashboard.midtrans.com
- Laravel Helpers: https://laravel.com/docs/helpers
- Responsive Design: https://developer.mozilla.org/en-US/docs/Learn/CSS/CSS_layout/Responsive_Design

## Support

For issues or questions:
1. Check MidTrans documentation
2. Review error logs in `storage/logs/`
3. Test in sandbox mode first
4. Contact MidTrans support team

---
Last Updated: 2025-05-06
