{{--
    Tema gelap/terang. Satu-satunya tempat yang memasang kelas `dark` di <html>;
    dipakai kedua layout (app dan guest) supaya tidak ada dua salinan yang
    perlahan berbeda.

    Harus di <head>, tanpa `defer`: menaruhnya di akhir <body> membuat halaman
    sempat tergambar terang lebih dulu, lalu berkedip jadi gelap.

    ⚠️ Kenapa ada MutationObserver, bukan cukup mendengar `livewire:navigated`.

    Saat wire:navigate, Livewire menyalin atribut <html> dari halaman baru ke
    dokumen yang sedang tampil (replaceHtmlAttributes). HTML dari server tidak
    tahu pilihan tema, jadi salinan itu MENCOPOT `dark`. Perbaikan sebelumnya
    (63c3d9f) memasangnya kembali pada `livewire:navigated`, tetapi event itu
    baru dipicu setelah skrip baru di <head> selesai dimuat. Selama jeda itu
    halaman terang, dan bila sebuah skrip eksternal lambat atau gagal dimuat,
    `dark` tidak pernah kembali: persis gejala "klik logo, tiba-tiba terang".

    Observer bereaksi pada perubahan atribut itu sendiri, sebelum layar sempat
    digambar ulang, siapa pun yang mengubahnya. Tidak bergantung pada urutan
    event apa pun.

    Tidak berebut dengan tombol tema Preline: Preline menyimpan pilihan ke
    localStorage LEBIH DULU, baru mengganti kelas, sehingga saat observer
    membaca, keinginan dan keadaannya sudah sama.
--}}
<script>
    (function () {
        function inginGelap() {
            var tersimpan = null;
            try { tersimpan = localStorage.getItem('hs_theme'); } catch (e) {}
            if (tersimpan === 'dark') return true;
            if (tersimpan === 'light' || tersimpan === 'default') return false;
            return window.matchMedia('(prefers-color-scheme: dark)').matches;
        }

        window.applyTheme = function () {
            var html = document.documentElement;
            var gelap = inginGelap();
            if (html.classList.contains('dark') !== gelap) {
                html.classList.toggle('dark', gelap);
            }
        };

        window.applyTheme();

        new MutationObserver(window.applyTheme)
            .observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

        // Ikuti perubahan tema sistem, selama pengguna belum memilih sendiri.
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', window.applyTheme);
    })();
</script>
