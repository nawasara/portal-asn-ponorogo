<div>

    <livewire:dashboard.section.hero />

    <livewire:dashboard.section.app-list />

    <livewire:dashboard.section.support />

    <livewire:dashboard.section.faq />

    <livewire:dashboard.section.integration />

    <livewire:dashboard.section.footer />

    <livewire:shared-components.modal.session-modal />

    {{-- Popup ajakan daftar Passkey (muncul hanya kalau user belum punya passkey,
         dismissible). Menggantikan popup announcement reset-MFA. --}}
    <livewire:shared-components.passkey-banner />

</div>
