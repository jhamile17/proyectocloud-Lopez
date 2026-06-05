@extends('layouts.app')
@section('title','Nosotros | PROCAFES')

@section('content')
<style>
  :root{
    --brand-100:#F2DD6C; /* amarillo suave */
    --brand-200:#DAAD29;
    --brand-700:#794515; /* marrón */
    --brand-900:#3E350E; /* marrón oscuro */
    --surface:#EAE9E7;
  }
  /* HERO */
  .hero-about{
    background:
      linear-gradient(180deg, rgba(0,0,0,.35), rgba(0,0,0,.55)),
      url('{{ asset("images/hero1.jpg") }}') center/cover no-repeat;
    color:#fff;
  }
  .accent{ width:56px;height:6px;border-radius:6px;background:var(--brand-200); }
</style>

{{-- HERO --}}
<section class="hero-about py-5">
  <div class="container text-center">
    <span class="badge text-dark" style="background:var(--brand-100)">Nosotros</span>
    <h1 class="fw-bold mt-2 mb-2">Sobre PROCAFES</h1>
    <p class="lead mb-0">Comprometidos con el café peruano y el desarrollo de nuestra región.</p>
  </div>
</section>

{{-- ¿QUIÉNES SOMOS? (imagen más pequeña) --}}
<section class="py-5 bg-white">
  <div class="container">
    <div class="row justify-content-center g-4">
      <div class="col-lg-5 d-flex justify-content-center">
        <img
          src="{{ asset('images/nosotros.jpg') }}"
          alt="Quiénes somos PROCAFES"
          class="img-fluid rounded-4 shadow-sm rounded-2xl shadow-md"
          style="max-width:420px; object-fit:cover;"
        >
      </div>
      <div class="col-lg-6">
        <div class="accent mb-2"></div>
        <h2 class="fw-bold mb-3" style="color:var(--brand-700)">¿Quiénes somos?</h2>
        <p class="fs-5 mb-0">
          Somos una empresa dedicada a la <strong>comercialización y transformación del café, cacao y derivados</strong>,
          trabajando con productores locales de la región Junín. Promovemos el consumo interno del café peruano
          a través de bebidas, desayunos y experiencias que celebran la cultura cafetalera de Pichanaki.
        </p>
      </div>
    </div>
  </div>
</section>

{{-- MISIÓN & VISIÓN (texto, no imagen) --}}
<section class="py-5" style="background:var(--surface)">
  <div class="container">
    <div class="text-center mb-4">
      <div class="accent mx-auto mb-2"></div>
      <h2 class="fw-bold" style="color:var(--brand-700)">Misión y Visión</h2>
    </div>
    <div class="row g-4">
      <div class="col-md-6">
        <div class="bg-white p-4 rounded-4 border rounded-2xl shadow-sm h-100">
          <h3 class="h5 fw-bold text-uppercase mb-2" style="color:var(--brand-900)">Nuestra misión</h3>
          <p class="mb-0">
            Comercializamos <strong>café, cacao y derivados</strong> e incentivamos el consumo interno del café
            mediante bebidas y platos de la zona, comprometidos con cada actor de la cadena de valor del café
            y la calidad de nuestros productos y servicios.
          </p>
        </div>
      </div>
      <div class="col-md-6">
        <div class="bg-white p-4 rounded-4 border rounded-2xl shadow-sm h-100">
          <h3 class="h5 fw-bold text-uppercase mb-2" style="color:var(--brand-900)">Nuestra visión</h3>
          <p class="mb-0">
            Ser líderes en la <strong>Región Junín</strong> en tostado de café comercial y especial y en la
            preparación de bebidas con café 100% peruano, con proyección al mercado nacional e internacional.
          </p>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- NUESTRO EQUIPO (centrado y mejorado) --}}
<section class="py-5 bg-white">
  <div class="container text-center">
    <div class="accent mx-auto mb-2"></div>
    <h2 class="fw-bold mb-3" style="color:var(--brand-700)">Nuestro equipo</h2>
    <p class="text-muted mb-4">
      El alma de PROCAFES está en su gente: baristas, tostadores y productores comprometidos.
    </p>

    {{-- Imagen al centro con ancho controlado --}}
    <div class="d-flex justify-content-center">
      <img
        src="{{ asset('images/equipo.jpg') }}"
        alt="Equipo PROCAFES"
        class="img-fluid rounded-4 shadow-sm rounded-2xl shadow-lg"
        style="max-width:760px; object-fit:cover;"
      >
    </div>
  </div>
</section>
@endsection
