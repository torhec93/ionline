<div>
    <h5>Segmentos</h5>

    <div class="input-group my-2">
        <div class="input-group-prepend">
          <span class="input-group-text">Buscar</span>
        </div>
        <input type="text" class="form-control" wire:model="search">
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th class="text-center">Código</th>
                    <th>Nombre</th>
                    <th class="text-center">Estatus</th>
                    <th class="text-center">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($segments as $segment)
                <tr>
                    <td class="text-center">
                        <a title="Ver familias"
                            href="{{ route('families.index', $segment) }}">
                            {{ $segment->code }}
                        </a>
                    </td>
                    <td>{{ $segment->name }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $segment->status_color }}">
                            {{ $segment->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-sm btn-outline-secondary" title="Editar segmento"
                            href="{{ route('segments.edit', $segment) }}">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr class="text-center">
                    <td colspan="4"><em>No hay resultados</em></td>
                </tr>
                @endforelse
            </tbody>
            <caption>
                Total resultados : {{ $segments->total() }}
            </caption>
        </table>

        {{ $segments->links() }}
    </div>
</div>
