<div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead>
                <tr>
                    <th>Nro.</th>
                    <th>Fecha</th>
                    <th class="text-center">Archivo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($control->invoices as $invoice)
                    <tr>
                        <td>
                            {{ $invoice->number }}
                        </td>
                        <td>
                            {{ $invoice->date->format('Y-m-d') }}
                        </td>
                        <td class="text-center">
                            <a
                                href="https://storage.googleapis.com{{ $invoice->url }}"
                                class="btn btn-sm btn-outline-secondary"
                                target="_blank"
                                title="Ver archivo"
                            >
                                <span class="fas fa-file" aria-hidden="true"></span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>