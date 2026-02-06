# ✅ KONFIGURASI YANG DIPERLUKAN DI .env

# 1. Midtrans Configuration
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=false  # Gunakan true untuk production

# 2. APP URL - PENTING! Webhook Midtrans akan kirim ke URL ini
APP_URL=http://127.0.0.1:8000  # atau domain sebenarnya untuk production

# 3. Log Channel untuk debugging
LOG_CHANNEL=single
LOG_LEVEL=debug

# PASTIKAN SUDAH SETTING DI MIDTRANS DASHBOARD:
# 1. Login ke Midtrans Dashboard
# 2. Settings → Notifications → HTTP POST Notification URL
# 3. Set ke: {APP_URL}/api/midtrans-callback
# 4. Atau untuk backward compatibility: {APP_URL}/payment/webhook
