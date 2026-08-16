<?php
namespace App\Models;

use App\Models\Scopes\HideUnlinkedOrdersScope;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'event_id', 'sale_phase_id', 'ticket_category_id',
        'qty', 'title', 'birth_date', 'gender', 'city', 'payment_method', 'full_name', 'phone_number', 'email',
        'identity_number', 'telegram_username', 'social_media_screenshot', 'telegram_user_id',
        'telegram_chat_id', 'telegram_link_token', 'telegram_linked_at',
        'service_fee_total', 'ticket_price_total',
        'admin_fee', 'grand_total', 'payment_mode', 'payment_status',
        'order_status', 'notes', 'membership_code', 'device_token',
    ];

    protected $casts = [
        'identity_number'    => 'encrypted',
        'telegram_linked_at' => 'datetime',
        'birth_date'         => 'date',
    ];

    protected $appends = ['social_media_screenshot_url'];

    public function getSocialMediaScreenshotUrlAttribute(): ?string
    {
        if (!$this->social_media_screenshot) {
            return null;
        }
        if (str_starts_with($this->social_media_screenshot, 'http://') || str_starts_with($this->social_media_screenshot, 'https://')) {
            return $this->social_media_screenshot;
        }
        return \Illuminate\Support\Facades\Storage::disk(config('filesystems.default'))->url($this->social_media_screenshot);
    }

    /**
     * Daftar gelar yang dipakai oleh platform yang punya field gelar (Fasticket).
     * Key = value yang disimpan, value = label tampilan.
     */
    public const TITLE_OPTIONS = [
        'tuan'   => 'Tuan',
        'nona'   => 'Nona',
        'nyonya' => 'Nyonya',
    ];

    /**
     * Daftar gender yang dipakai oleh platform yang punya field gender (Goers, Fasticket).
     */
    public const GENDER_OPTIONS = [
        'male'   => 'Laki-laki',
        'female' => 'Perempuan',
    ];

    /** Platform yang punya field gelar di form order aslinya. */
    public const PLATFORMS_WITH_TITLE = ['tiketcom'];

    /** Platform yang punya field gender di form order aslinya. */
    public const PLATFORMS_WITH_GENDER = ['goers', 'fasticket'];

    /** Platform yang punya field tanggal lahir di form order aslinya. */
    public const PLATFORMS_WITH_BIRTH_DATE = ['loket', 'goers'];

    /** Platform yang punya field kota di form order aslinya. */
    public const PLATFORMS_WITH_CITY = ['goers'];

    /**
     * Pilihan metode pembayaran khusus platform Fasticket, dikelompokkan
     * sesuai kategori yang ada di form order Fasticket.
     */
    public const PAYMENT_METHOD_GROUPS = [
        'Bank Transfer' => [
            'bca'      => 'BCA',
            'bri'      => 'BRI',
            'bni'      => 'BNI',
            'bsi'      => 'BSI',
            'mandiri'  => 'Mandiri',
            'permata'  => 'Permata',
            'bnc'      => 'BNC',
            'sampoerna'=> 'Sampoerna',
        ],
        'Direct Debit' => [
            'dd_bri'  => 'DD BRI',
            'klikpay' => 'Klikpay',
        ],
        'E-Wallet' => [
            'dana'    => 'DANA',
            'linkaja' => 'LinkAja',
            'jenius'  => 'Jenius',
        ],
        'Lainnya' => [
            'qris'      => 'QRIS',
            'indomaret' => 'Indomaret',
            'cc'        => 'Kartu Kredit',
            'akulaku'   => 'Akulaku',
            'atome'     => 'Atome',
        ],
    ];

    /** Platform yang punya field metode pembayaran di form order aslinya. */
    public const PLATFORMS_WITH_PAYMENT_METHOD = ['fasticket'];

    public function getPaymentMethodLabelAttribute(): ?string
    {
        foreach (self::PAYMENT_METHOD_GROUPS as $options) {
            if (isset($options[$this->payment_method])) {
                return $options[$this->payment_method];
            }
        }

        return null;
    }

    public function getTitleLabelAttribute(): ?string
    {
        return self::TITLE_OPTIONS[$this->title] ?? null;
    }

    public function getGenderLabelAttribute(): ?string
    {
        return self::GENDER_OPTIONS[$this->gender] ?? null;
    }

    protected static function booted(): void
    {
        static::addGlobalScope(new HideUnlinkedOrdersScope());

        static::saved(function ($order) {
            $order->syncEventStatus();
        });

        static::deleted(function ($order) {
            $order->syncEventStatus();
        });
    }

    public function syncEventStatus(): void
    {
        try {
            $event = $this->event;
            if ($event) {
                $available = $event->resolved_available_slots;
                if ($available !== null) {
                    if ($available <= 0 && $event->status === 'upcoming') {
                        $event->update(['status' => 'slot_penuh']);
                    } elseif ($available > 0 && $event->status === 'slot_penuh') {
                        $event->update(['status' => 'upcoming']);
                    }
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('syncEventStatus error: ' . $e->getMessage());
        }
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function salePhase()
    {
        return $this->belongsTo(SalePhase::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function guests()
    {
        return $this->hasMany(OrderGuest::class)->orderBy('ticket_position');
    }

    public function customFieldAnswers()
    {
        return $this->hasMany(OrderCustomField::class);
    }

    public function successLog()
    {
        return $this->hasOne(SuccessLog::class);
    }

    public function paymentLog()
    {
        return $this->hasOne(PaymentLog::class)->latest();
    }

    public function telegramConnection()
    {
        return $this->hasOne(TelegramConnection::class);
    }

    public function categoryChoices()
    {
        return $this->hasMany(OrderCategoryChoice::class)->orderBy('priority');
    }

    /**
     * Bangun teks konfirmasi order yang dikirim ke customer via Telegram,
     * disesuaikan dengan field yang relevan untuk platform event ini
     * (tiket.com pakai gelar, loket pakai tanggal lahir, dst).
     *
     * Method ini butuh relasi event, salePhase, dan categoryChoices.ticketCategory
     * sudah di-load (lihat TelegramLinkController::verify).
     */
    public function buildTelegramConfirmationMessage(): string
    {
        $event    = $this->event;
        $platform = $event->platform_type ?? 'custom';

        $lines   = [];
        $lines[] = '✅ <b>Order Diterima — Warindong</b>';
        $lines[] = '';
        $lines[] = "Halo {$this->full_name}! Order kamu sudah dikonfirmasi.";
        $lines[] = '';
        $lines[] = "🎫 Event: <b>{$event->title}</b>";
        $lines[] = '📍 Phase: ' . ($this->salePhase->name ?? '-');

        $categories = $this->categoryChoices
            ->sortBy('priority')
            ->map(fn ($choice) => $choice->ticketCategory->name ?? '-')
            ->values();

        if ($categories->count() > 1) {
            $lines[] = "🎟️ Kategori Utama: {$categories[0]}";
            $lines[] = '🔁 Kategori Cadangan: ' . $categories->slice(1)->implode(', ');
        } else {
            $lines[] = "🎟️ Kategori: " . ($categories->first() ?? '-');
        }

        $lines[] = "🎫 Qty: {$this->qty}";

        // Field khusus platform yang punya gelar (tiket.com, fasticket)
        if (in_array($platform, self::PLATFORMS_WITH_TITLE, true) && $this->title_label) {
            $lines[] = "👤 Gelar: {$this->title_label}";
        }

        // Field khusus platform yang punya gender (goers)
        if (in_array($platform, self::PLATFORMS_WITH_GENDER, true) && $this->gender_label) {
            $lines[] = "🚻 Gender: {$this->gender_label}";
        }

        // Field khusus platform yang punya tanggal lahir (loket, goers)
        if (in_array($platform, self::PLATFORMS_WITH_BIRTH_DATE, true) && $this->birth_date) {
            $lines[] = '🎂 Tanggal Lahir: ' . $this->birth_date->format('d/m/Y');
        }

        // Field khusus platform yang punya kota (goers)
        if (in_array($platform, self::PLATFORMS_WITH_CITY, true) && $this->city) {
            $lines[] = "🏙️ Kota: {$this->city}";
        }

        // Field khusus platform yang punya metode pembayaran (fasticket)
        if (in_array($platform, self::PLATFORMS_WITH_PAYMENT_METHOD, true) && $this->payment_method_label) {
            $lines[] = "💳 Metode Pembayaran: {$this->payment_method_label}";
        }

        if ($this->membership_code) {
            $lines[] = "🪪 Kode Membership: {$this->membership_code}";
        }

        $lines[] = '💰 Total Fee: Rp ' . number_format($this->grand_total, 0, ',', '.');
        $lines[] = "🔖 Order Code: <code>{$this->order_code}</code>";
        $lines[] = '';
        $lines[] = 'Kami akan proses order kamu sesuai jadwal sale phase.';
        $lines[] = '';

        $adminUsername = \App\Models\Setting::get('telegram_admin_username', 'admin_wartix');
        $lines[]       = "Ada yang tidak sesuai? Hubungi @{$adminUsername}";

        return implode("\n", $lines);
    }
}