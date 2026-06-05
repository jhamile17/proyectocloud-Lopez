@extends('layouts.app')
@section('title','Ubícanos | PROCAFES')

@section('content')
@php
  // ---- EDITA ESTOS 4 DATOS Y QUEDA LISTO ----
  $brand   = 'PROCAFES';
  $address = 'Av. Principal 123, Pichanaki, Junín, Perú';
  $phone   = '+51 955 236 237';
  $email   = 'sayuridamianrojas04@gmail.com';

  // Para los botones "Cómo llegar"
  $mapsQuery = urlencode("$brand $address"); // usa el nombre + dirección
  $gmapLink  = "https://www.google.com/maps/search/?api=1&query=$mapsQuery";
  $wazeLink  = "https://waze.com/ul?q=$mapsQuery";
@endphp

<style>
  :root{
    --brand-100:#F2DD6C; /* Amarillo */
    --brand-200:#DAAD29;
    --brand-700:#794515; /* Marrón */
    --brand-900:#3E350E; /* Marrón oscuro */
    --surface:#EAE9E7;
  }
  .hero-ubi{
    background:
      linear-gradient(180deg, rgba(0,0,0,.35), rgba(0,0,0,.55)),
      url('{{ asset("images/hero1.jpg") }}') center/cover no-repeat;
    color:#fff;
  }
  .accent{ width:56px;height:6px;border-radius:6px;background:var(--brand-200); }
  .card-lite{ border-radius:14px; border:1px solid rgba(0,0,0,.06); box-shadow:0 2px 10px rgba(0,0,0,.06); }
</style>

{{-- HERO --}}
<section class="hero-ubi py-5">
  <div class="container text-center">
    <span class="badge text-dark" style="background:var(--brand-100)">Visítanos</span>
    <h1 class="fw-bold mt-2 mb-2">Ubícanos</h1>
    <p class="lead mb-0">Estamos listos para tu próxima taza de café.</p>
  </div>
</section>

<section class="py-5 bg-white">
  <div class="container">
    <div class="row g-4">
      {{-- Columna de datos y acciones --}}
      <div class="col-lg-5">
        <div class="card card-lite p-4 h-100">
          <div class="accent mb-2"></div>
          <h2 class="h4 fw-bold" style="color:var(--brand-700)">Nuestra dirección</h2>
          <p class="mb-2" id="addr-text">{{ $address }}</p>
          <div class="d-flex flex-wrap gap-2 mb-3">
            <a href="{{ $gmapLink }}" target="_blank" class="btn btn-dark">
              <i class="bi bi-geo-alt-fill me-1"></i> Cómo llegar (Maps)
            </a>
            <a href="{{ $wazeLink }}" target="_blank" class="btn btn-outline-dark">
              <i class="bi bi-signpost-split me-1"></i> Cómo llegar (Waze)
            </a>
            <button type="button" class="btn btn-outline-secondary" id="copyAddr">
              <i class="bi bi-clipboard me-1"></i> Copiar dirección
            </button>
          </div>

          <hr>

          <h3 class="h5 fw-bold mb-2" style="color:var(--brand-700)">Horarios</h3>
          <ul class="list-group list-group-flush mb-3">
            <li class="list-group-item d-flex justify-content-between">
              <span>Lunes a Viernes</span><strong>08:00 – 20:00</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Sábado</span><strong>09:00 – 20:00</strong>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Domingo</span><strong>09:00 – 18:00</strong>
            </li>
          </ul>

          <div class="alert alert-warning d-flex align-items-center gap-2 mb-0" role="alert" style="background:var(--brand-100);border:0;">
            <i class="bi bi-info-circle"></i>
            <div class="small mb-0">En feriados, revisa nuestras historias de Instagram para horarios especiales.</div>
          </div>
        </div>
      </div>

      {{-- Columna mapa y contacto --}}
      <div class="col-lg-7">
        <div class="ratio ratio-4x3 card-lite overflow-hidden mb-3">
          {{-- Mapa embebido sin API key (usa query) --}}
          <iframe
            src="https://www.google.com/maps?q={{ $mapsQuery }}&output=embed"
            style="border:0;" loading="lazy" allowfullscreen></iframe>
        </div>

        <div class="row g-3">
          <div class="col-md-4">
            <div class="card card-lite h-100">
              <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i class="bi bi-whatsapp fs-4" style="color:#25D366"></i>
                  <h3 class="h6 fw-bold mb-0">WhatsApp</h3>
                </div>
                <p class="mb-2 small">Pedidos y reservas</p>
                <a href="https://wa.me/{{ preg_replace('/\D/','',$phone) }}?text=Hola%20{{ urlencode($brand) }}%2C%20quisiera%20hacer%20un%20pedido"
                   target="_blank" class="btn btn-success w-100">
                  Escribir
                </a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-lite h-100">
              <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i class="bi bi-telephone fs-4" style="color:var(--brand-700)"></i>
                  <h3 class="h6 fw-bold mb-0">Teléfono</h3>
                </div>
                <p class="mb-2 small">{{ $phone }}</p>
                <a href="tel:{{ preg_replace('/\D/','',$phone) }}" class="btn btn-outline-dark w-100">Llamar</a>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="card card-lite h-100">
              <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <i class="bi bi-envelope fs-4" style="color:var(--brand-700)"></i>
                  <h3 class="h6 fw-bold mb-0">Email</h3>
                </div>
                <p class="mb-2 small">{{ $email }}</p>
                <a href="mailto:{{ $email }}?subject=Consulta%20{{ urlencode($brand) }}" class="btn btn-outline-dark w-100">Enviar correo</a>
              </div>
            </div>
          </div>
        </div>

        {{-- CTA reserva/visita --}}
        <div class="card card-lite p-3 mt-3">
          <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div class="d-flex align-items-center gap-2">
              <div class="accent"></div>
              <div>
                <div class="fw-bold" style="color:var(--brand-900)">¿Vienes con amigos o en familia?</div>
                <div class="small text-muted">Reserva tu mesa o solicita tu pedido para llevar.</div>
              </div>
            </div>
            <div class="d-flex gap-2">
              <a href="https://wa.me/{{ preg_replace('/\D/','',$phone) }}?text=Hola%20{{ urlencode($brand) }}%2C%20quiero%20reservar%20una%20mesa"
                 class="btn btn-dark">
                Reservar por WhatsApp
              </a>
              <a href="{{ $gmapLink }}" target="_blank" class="btn btn-outline-dark">Cómo llegar</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

@push('scripts')
<script>
  document.getElementById('copyAddr')?.addEventListener('click', function(){
    const t = document.getElementById('addr-text')?.innerText?.trim() || '';
    navigator.clipboard.writeText(t).then(()=>{
      this.classList.remove('btn-outline-secondary'); this.classList.add('btn-success');
      this.innerHTML = '<i class="bi bi-clipboard-check me-1"></i> ¡Copiado!';
      setTimeout(()=>{ this.classList.add('btn-outline-secondary'); this.classList.remove('btn-success'); this.innerHTML='<i class="bi bi-clipboard me-1"></i> Copiar dirección'; }, 1800);
    });
  });
</script>
@endpush
@endsection
