{{--
    Logo produk ekosistem Kisara — sistem logo v1 (nawasara-dev
    docs/brand/kisara-logo). Dibangkitkan dari geometri yang sama dengan
    favicon dan ikon di produk lain; jangan menyunting path-nya dengan tangan.

        <x-kisara-mark name="portal-asn" class="size-9" />
--}}
@props(['name' => 'portal-asn'])
<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" {{ $attributes }}>
@switch($name)
    @case('portal-asn')
        <g fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"><line x1="10" y1="38" x2="24" y2="11" stroke="#1f6fb2"/><line x1="24" y1="11" x2="38" y2="38" stroke="#0f8b8d"/><line x1="16" y1="29" x2="32" y2="29" stroke="#e0a82e"/></g>
        @break
    @case('kisara-sso')
        <g fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"><path d="M10.84 19.21A14 14 0 0 1 28.79 10.84" stroke="#1f6fb2"/><path d="M28.79 10.84A14 14 0 0 1 37.16 28.79" stroke="#0f8b8d"/><path d="M37.16 28.79A14 14 0 0 1 19.21 37.16" stroke="#2f9e6b"/><path d="M19.21 37.16A14 14 0 0 1 10.84 19.21" stroke="#e0a82e"/><circle cx="24" cy="21" r="4.2" fill="#1f6fb2" stroke="none"/><line x1="24" y1="22" x2="24" y2="29" stroke="#1f6fb2"/></g>
        @break
    @case('akun-kisara')
        <g fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"><circle cx="24" cy="16" r="7" fill="#1f6fb2" stroke="none"/><path d="M10.84 36.21A14 14 0 0 1 19.21 27.84" stroke="#0f8b8d"/><path d="M19.21 27.84A14 14 0 0 1 28.79 27.84" stroke="#2f9e6b"/><path d="M28.79 27.84A14 14 0 0 1 37.16 36.21" stroke="#e0a82e"/></g>
        @break
    @case('kisara-mail')
        <g fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"><line x1="11" y1="36" x2="11" y2="13" stroke="#1f6fb2"/><line x1="11" y1="13" x2="24" y2="26" stroke="#0f8b8d"/><line x1="24" y1="26" x2="37" y2="13" stroke="#2f9e6b"/><line x1="37" y1="13" x2="37" y2="36" stroke="#e0a82e"/></g>
        @break
    @case('nawasara')
        <g fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"><line x1="9" y1="37" x2="9" y2="18" stroke="#1f6fb2"/><path d="M9 18C9 6 20 5 22 15" stroke="#1f6fb2"/><line x1="22" y1="15" x2="26" y2="33" stroke="#0f8b8d"/><path d="M26 33C28 43 39 42 39 30" stroke="#e0a82e"/><line x1="39" y1="30" x2="39" y2="11" stroke="#e0a82e"/></g>
        @break
    @case('gasta')
        <g fill="none" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"><path d="M33.26 19.50A13 13 0 0 1 22.00 39.00" stroke="#1f6fb2"/><path d="M22.00 39.00A13 13 0 0 1 9.00 26.00" stroke="#0f8b8d"/><path d="M9.00 26.00A13 13 0 0 1 22.00 13.00" stroke="#2f9e6b"/><circle cx="22" cy="26" r="5" fill="#1f6fb2" stroke="none"/><circle cx="37" cy="11" r="4" fill="#e0a82e" stroke="none"/></g>
        @break
@endswitch
</svg>
