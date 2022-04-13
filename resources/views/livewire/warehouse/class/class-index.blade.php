<div>
    <h5>Clases</h5>
    <p><small>{{ $segment->name }} / {{ $family->name }}</small></p>

    <div class="input-group mb-2">
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
                @forelse($classes as $class)
                <tr>
                    <td class="text-center">
                        <a title="Ver productos"
                            href="{{ route('products.index', ['segment' => $segment, 'family' => $family, 'class' => $class]) }}">
                            {{ $class->code }}
                        </a>
                    </td>
                    <td>{{ $class->name }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $class->status_color }}">
                            {{ $class->status }}
                        </span>
                    </td>
                    <td class="text-center">
                        <a class="btn btn-sm btn-outline-secondary"
                            title="Editar clase"
                            href="{{ route('class.edit', ['segment' => $segment, 'family' => $family, 'class' => $class]) }}">
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
                Total resultados : {{ $classes->total() }}
            </caption>
        </table>

        {{ $classes->links() }}

    </div>
</div>
