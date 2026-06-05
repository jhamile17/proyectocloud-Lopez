@if ($paginator->hasPages())
    <nav class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-3" role="navigation" aria-label="Paginación">
        {{-- Texto "Mostrando" --}}
        <div class="text-muted small">
            @php
                $from = ($paginator->currentPage() - 1) * $paginator->perPage() + 1;
                $to   = min($paginator->currentPage() * $paginator->perPage(), $paginator->total());
            @endphp
            Mostrando <strong>{{ $from }}</strong> a <strong>{{ $to }}</strong> de <strong>{{ $paginator->total() }}</strong> resultados
        </div>

        {{-- Controles --}}
        <ul class="pagination mb-0">
            {{-- Anterior --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Anterior</span>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Anterior</a>
                </li>
            @endif

            {{-- Números --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span class="page-link">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active" aria-current="page"><span class="page-link">{{ $page }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Siguiente --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Siguiente</a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <span class="page-link">Siguiente</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
