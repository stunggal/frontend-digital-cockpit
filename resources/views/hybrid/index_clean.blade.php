<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Daftar Pasien — Hybrid (Bersih)</title>
  <style>
    :root{
      --bg:#f7f8fb; --card:#fff; --muted:#6b7280; --primary:#0b76ef; --success:#16a34a;
      --radius:10px; --gap:16px; font-family:Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial;
    }
    *{box-sizing:border-box}
    body{margin:0;background:var(--bg);color:#111}
    .container{max-width:1100px;margin:28px auto;padding:20px}
    .header{display:flex;align-items:center;justify-content:space-between;gap:12px;margin-bottom:18px}
    .title{display:flex;flex-direction:column}
    .title h1{margin:0;font-size:20px}
    .title p{margin:2px 0 0;font-size:13px;color:var(--muted)}

    .controls{display:flex;gap:8px;align-items:center}
    .btn{background:var(--primary);color:#fff;padding:8px 12px;border-radius:8px;border:none;cursor:pointer;font-weight:600}
    .btn.secondary{background:#fff;color:var(--primary);border:1px solid rgba(11,118,239,.15)}
    .btn.ghost{background:transparent;color:var(--primary);border:1px dashed rgba(11,118,239,.15)}

    .panel{background:var(--card);padding:14px;border-radius:var(--radius);box-shadow:0 6px 18px rgba(16,24,40,.06)}

    .filters{display:flex;flex-wrap:wrap;gap:10px;margin-bottom:14px}
    .search{flex:1;min-width:180px}
    input[type="text"], select{width:100%;padding:10px;border-radius:10px;border:1px solid #e6e9ee;background:#fff}

    /* cards grid */
    .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px}
    .card{background:var(--card);padding:12px;border-radius:12px;display:flex;gap:12px;align-items:center;border:1px solid rgba(15,23,42,.03)}
    .avatar{width:48px;height:48px;border-radius:10px;background:linear-gradient(135deg,#e8f2ff,#fff);display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--primary)}
    .meta{flex:1}
    .meta h3{margin:0;font-size:15px}
    .meta p{margin:4px 0 0;font-size:13px;color:var(--muted)}
    .actions{display:flex;gap:8px}
    .badge{padding:6px 8px;border-radius:999px;font-size:12px}
    .badge.success{background:rgba(22,163,74,.12);color:var(--success)}
    .badge.inactive{background:#f1f5f9;color:#475569}

    /* table for larger screens */
    .table-wrap{margin-top:12px;overflow:auto}
    table{width:100%;border-collapse:collapse;background:transparent}
    th,td{padding:12px 10px;text-align:left;border-bottom:1px solid #eef2f7}
    th{color:var(--muted);font-size:13px}
    td .small{color:var(--muted);font-size:13px}

    .empty{padding:28px;text-align:center;color:var(--muted)}

    @media (max-width:720px){
      .header{flex-direction:column;align-items:stretch}
      .controls{justify-content:space-between}
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <div class="title">
        <h1>Daftar Pasien</h1>
        <p>Menampilkan pasien yang dikirim oleh controller (view mandiri, tanpa tema default)</p>
      </div>

      <div class="controls">
        <button class="btn" id="btnSync">Sinkronisasi</button>
        <button class="btn secondary" id="btnAdd">Tambah Pasien</button>
      </div>
    </div>

    <div class="panel">
      <div class="filters">
        <div class="search">
          <input type="text" id="q" placeholder="Cari nama / RM / telepon">
        </div>
        <div style="width:180px">
          <select id="status">
            <option value="">Semua status</option>
            <option value="active">Aktif</option>
            <option value="inactive">Tidak aktif</option>
          </select>
        </div>
        <div style="width:140px">
          <select id="sort">
            <option value="name_asc">Nama A→Z</option>
            <option value="name_desc">Nama Z→A</option>
          </select>
        </div>
      </div>

      {{-- Cards for small/medium screens --}}
      <div id="cards" class="grid" aria-live="polite">
        @forelse(($patients ?? []) as $patient)
          <div class="card" data-name="{{ strtolower($patient->name) }}" data-rm="{{ strtolower($patient->medical_record ?? '') }}" data-phone="{{ strtolower($patient->phone ?? '') }}" data-active="{{ ($patient->active ?? true) ? 'active' : 'inactive' }}">
            <div class="avatar">{{ strtoupper(substr($patient->name,0,1)) }}</div>
            <div class="meta">
              <h3>{{ $patient->name }}</h3>
              <p class="small">RM: {{ $patient->medical_record ?? '-' }} · {{ $patient->phone ?? '-' }}</p>
            </div>
            <div class="actions">
              <div class="badge {{ ($patient->active ?? true) ? 'success' : 'inactive' }}">{{ ($patient->active ?? true) ? 'Aktif' : 'Tidak Aktif' }}</div>
            </div>
          </div>
        @empty
          <div class="empty" style="grid-column:1/-1">
            Belum ada data pasien. Klik "Tambah Pasien" untuk membuat data baru.
          </div>
        @endforelse
      </div>

      {{-- Table view (always available for wider screens) --}}
      <div class="table-wrap" style="margin-top:18px">
        <table id="table">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama</th>
              <th>Rekam Medis</th>
              <th>Telepon</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @php $i = 1; @endphp
            @forelse(($patients ?? []) as $patient)
              <tr data-name="{{ strtolower($patient->name) }}" data-rm="{{ strtolower($patient->medical_record ?? '') }}" data-phone="{{ strtolower($patient->phone ?? '') }}" data-active="{{ ($patient->active ?? true) ? 'active' : 'inactive' }}">
                <td>{{ $i++ }}</td>
                <td>{{ $patient->name }}</td>
                <td class="small">{{ $patient->medical_record ?? '-' }}</td>
                <td class="small">{{ $patient->phone ?? '-' }}</td>
                <td><span class="badge {{ ($patient->active ?? true) ? 'success' : 'inactive' }}">{{ ($patient->active ?? true) ? 'Aktif' : 'Tidak Aktif' }}</span></td>
              </tr>
            @empty
              <tr><td colspan="5" class="empty">Belum ada data pasien.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>

    </div>

  </div>

  <script>
    (function(){
      const q = document.getElementById('q');
      const status = document.getElementById('status');
      const sort = document.getElementById('sort');
      const cardsContainer = document.getElementById('cards');
      const table = document.getElementById('table');

      function normalize(s){return (s||'').toString().toLowerCase();}

      function applyFilters(){
        const qv = normalize(q.value);
        const sv = normalize(status.value);
        // filter cards
        Array.from(cardsContainer.querySelectorAll('.card')).forEach(el=>{
          const name = el.dataset.name || '';
          const rm = el.dataset.rm || '';
          const phone = el.dataset.phone || '';
          const active = el.dataset.active || '';
          const matchesQ = !qv || name.includes(qv) || rm.includes(qv) || phone.includes(qv);
          const matchesS = !sv || active === sv;
          el.style.display = (matchesQ && matchesS) ? '' : 'none';
        });
        // filter table rows
        Array.from(table.tBodies[0].rows).forEach(row=>{
          const name = row.dataset.name || '';
          const rm = row.dataset.rm || '';
          const phone = row.dataset.phone || '';
          const active = row.dataset.active || '';
          const matchesQ = !qv || name.includes(qv) || rm.includes(qv) || phone.includes(qv);
          const matchesS = !sv || active === sv;
          row.style.display = (matchesQ && matchesS) ? '' : 'none';
        });
        applySort();
      }

      function applySort(){
        const s = sort.value;
        if(!s) return;
        const [key, dir] = s.split('_');
        // sort cards DOM
        const cards = Array.from(cardsContainer.querySelectorAll('.card')).filter(c=>c.style.display!=='none');
        cards.sort((a,b)=>{
          const an = a.dataset.name||''; const bn = b.dataset.name||'';
          if(an<bn) return dir==='asc' ? -1:1; if(an>bn) return dir==='asc'?1:-1; return 0;
        });
        cards.forEach(c=>cardsContainer.appendChild(c));
        // note: table sort is visual only (DOM reorder)
        const tbody = table.tBodies[0];
        const rows = Array.from(tbody.rows).filter(r=>r.style.display!=='none');
        rows.sort((a,b)=>{
          const an = a.dataset.name||''; const bn = b.dataset.name||'';
          if(an<bn) return dir==='asc' ? -1:1; if(an>bn) return dir==='asc'?1:-1; return 0;
        });
        rows.forEach(r=>tbody.appendChild(r));
      }

      q.addEventListener('input', applyFilters);
      status.addEventListener('change', applyFilters);
      sort.addEventListener('change', applyFilters);

      document.getElementById('btnSync').addEventListener('click', ()=>{
        const b = document.getElementById('btnSync');
        b.disabled = true; b.textContent = 'Menyinkron...';
        setTimeout(()=>{ b.disabled=false; b.textContent='Sinkronisasi'; alert('Sinkronisasi contoh selesai.'); }, 1100);
      });

      document.getElementById('btnAdd').addEventListener('click', ()=>{
        alert('Implementasikan form tambah pasien di controller / modal sesuai kebutuhan.');
      });

    })();
  </script>
</body>
</html>