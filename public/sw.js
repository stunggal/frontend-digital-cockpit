const CACHE_NAME = 'my-pwa-cache-v7'; // Ganti versi cache saat ada perubahan aset!
const OFFLINE_URL = '/offline.html';

// Daftar aset-aset penting yang akan di-cache saat Service Worker diinstal
// Pastikan semua aset yang direferensikan di offline.html ada di sini
const assetsToCache = [
    OFFLINE_URL,
    '/', // Cache halaman utama untuk akses offline
    '/css/app.css',
    '/js/app.js',
    // Tambahkan ikon, font, atau aset statis lainnya di sini:
    // '/images/logo.png',
    // ...
];

/**
 * 1. Event Install: Menginstal Service Worker dan melakukan pra-caching aset penting
 */
self.addEventListener('install', (event) => {
    // Menunggu hingga proses caching selesai sebelum menginstal SW
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Melakukan pra-caching aset penting:', assetsToCache.length);
                // Menambahkan semua aset penting, termasuk OFFLINE_URL, ke cache
                return cache.addAll(assetsToCache).catch((err) => {
                    // Penting: Jika salah satu aset gagal diunduh, seluruh proses install akan gagal.
                    // Pastikan path di assetsToCache benar.
                    console.error('[SW] Gagal menambah aset ke cache:', err);
                });
            })
    );
    self.skipWaiting(); // Memaksa SW baru untuk segera aktif
});

/**
 * 2. Event Activate: Membersihkan cache lama
 */
self.addEventListener('activate', (event) => {
    const cacheWhitelist = [CACHE_NAME];
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    // Menghapus cache yang bukan merupakan versi saat ini
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        console.log(`[SW] Menghapus cache lama: ${cacheName}`);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
    event.waitUntil(clients.claim()); // Mengambil alih kontrol klien (tab browser)
});

/**
 * 3. Event Fetch: Mencegat permintaan dan menerapkan strategi caching
 */
self.addEventListener('fetch', (event) => {
    const req = event.request;

    // --- STRATEGI NAVIGASI (Permintaan HTML Document) ---
    // Gunakan Network First, lalu Cache, Fallback ke Halaman Offline
    if (req.mode === 'navigate' || (req.method === 'GET' && req.destination === 'document')) {
        event.respondWith((async () => {
            const cache = await caches.open(CACHE_NAME);

            try {
                // 1. Coba ambil dari network
                const networkResponse = await fetch(req);

                // 2. Jika berhasil, simpan ke cache dinamis (hanya respons OK)
                if (networkResponse.ok) {
                    try {
                        // Simpan salinan respons ke cache untuk fallback nanti
                        cache.put(req, networkResponse.clone()).catch(() => { });
                    } catch (err) {
                        // Tangani error caching dengan tenang
                    }
                }

                return networkResponse;
            } catch (err) {
                // 3. JIKA NETWORK GAGAL (OFFLINE):
                console.warn(`[SW] Network gagal untuk ${req.url}. Melayani dari cache atau offline.`);

                // A. Coba ambil versi halaman yang di-cache (jika sudah pernah dikunjungi)
                const cachedResponse = await cache.match(req);
                if (cachedResponse) {
                    return cachedResponse;
                }

                // B. Jika tidak ada di cache, arahkan ke halaman offline yang telah di-cache
                const offlinePage = await cache.match(OFFLINE_URL);
                if (offlinePage) {
                    // Mengembalikan konten offline.html sebagai respons untuk URL yang diminta.
                    return offlinePage;
                }

                // C. Fallback darurat
                return new Response('<h1>Offline</h1><p>Halaman offline gagal dimuat.</p>', { headers: { 'Content-Type': 'text/html' }, status: 503 });
            }
        })());
        return;
    }

    // --- STRATEGI ASET LAIN (CSS, JS, Gambar) ---
    // Gunakan Cache First, lalu Network Fallback (Baik untuk aset statis)
    event.respondWith(caches.match(req)
        .then((response) => {
            // Jika ditemukan di cache, kembalikan dari cache
            if (response) {
                return response;
            }

            // Jika tidak ada di cache, coba ambil dari network
            return fetch(req);
        })
        .catch(() => {
            // Kegagalan pada aset non-HTML tidak perlu menampilkan halaman offline.
            // Cukup kembalikan respons error 504 atau null.
            return new Response(null, { status: 504 });
        })
    );
});