@extends('layouts.app')

@section('title', 'Historial de documentos')

@section('content')

@include('documents.partials.nav')

<link href="{{ asset('css/bootstrap-select.min.css') }}" rel="stylesheet" type="text/css" />

<h3>Historial</h3>

<form method="GET" class="form-horizontal" action="{{ route('documents.index') }}">

    <div class="form-row">
        <fieldset class="form-group col-4 col-md-1">
            <label for="for_id">Cód Int</label>
            <input type="text" class="form-control" id="for_id" name="id">
        </fieldset>

        <fieldset class="form-group col-8 col-md-2">
            <label for="for_type">Tipo</label>
            <select name="type" id="for_type" class="form-control">
                <option></option>
                <option value="Memo">Memo</option>
                <option value="Oficio">Oficio</option>
                <!-- <option value="Ordinario">Ordinario</option> -->
                <option value="Reservado">Reservado</option>
                <option value="Circular">Circular</option>
                <option value="Acta de recepción">Acta de recepción</option>
                <option value="Resolución">Resolución</option>
                <option value="Acta de Recepción Obras Menores">Acta de Recepción Obras Menores</option>
            </select>
        </fieldset>

        <fieldset class="form-group col-12 col-md-3">
            <label for="for_subject">Materia</label>
            <input type="text" class="form-control" id="for_subject" name="subject">
        </fieldset>

        <fieldset class="form-group col-4 col-md-1">
            <label for="for_number">Número</label>
            <input type="text" class="form-control" id="for_number" name="number">
        </fieldset>

        <fieldset class="form-group col-8 col-md-4">
            <label for="for_user_id">Usuario</label>
            @livewire('search-select-user')
        </fieldset>

        <fieldset class="form-group col-1 col-md-1">
            <label for="">&nbsp;</label>
            <button type="submit" class="btn btn-primary form-control">
                <i class="fas fa-search"></i>
            </button>
        </fieldset>
        
    </div>

    <fieldset class="form-check form-check-inline mb-3">
        <input class="form-check-input" name="file" type="checkbox" id="for_file">
        <label class="form-check-label" for="for_file">Pendiente</label>
    </fieldset>

</form>




<table class="table table-sm">
    <thead>
        <tr>
            <th>CI</th>
            <th>Tipo</th>
            <th>N°</th>
            <th>Fecha</th>
            <th>Antec</th>
            <!--th>Resp</th-->
            <th>Materia</th>
            <th>De</th>
            <th>Para</th>
            <th class="small" width="70">Creador</th>
            <th nowrap></th>
            <th nowrap></th>
            <th nowrap></th>
            <th nowrap></th>
        </tr>
    </thead>
    <tbody>
        @foreach($ownDocuments as $doc)
        <tr class="small">
            <td>{{ $doc->id }}</td>
            <td>{{ $doc->type }}</td>
            <td>
                {{ $doc->number }}
            </td>
            <td nowrap>{{ $doc->date ? $doc->date->format('d-m-Y'): '' }}</td>
            <td>{{ $doc->antecedent }}</td>
            <!--td nowrap>{!! $doc->responsibleHtml !!}</td-->
            <td>{{ $doc->subject }}</td>
            <td>{!! $doc->fromHtml !!}</td>
            <td>{!! $doc->forHtml !!}</td>
            <td class="small">
                @if($doc->user)
                    @if($doc->user->gravatar )
                        <img src="{{ $doc->user->gravatarUrl }}?s=40&d=mp&r=g" class="border rounded-circle" alt="{{ optional($doc->user)->tinnyName }}" title="{{ optional($doc->user)->tinnyName }}">
                    @else
                        {{ optional($doc->user)->tinnyName }}
                    @endif
                @endif
                <br>
                {{ $doc->created_at }}
            </td>
            <td nowrap>
                @if(!$doc->file)
                    <a href="{{ route('documents.edit', $doc) }}" class="btn btn-sm btn-primary"><i class="fas fa-pen"></i></a>
                @endif

            </td>
            <td nowrap>
                <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-file fa-lg"></i></a>
            </td>
            <td nowrap>
                @if($doc->file)
                <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                    <i class="fas fa-file-pdf fa-lg"></i>
                </a>
                @else
                <form method="POST" action="{{ route('documents.find')}}">
                    @csrf
                    <button name="id" value="{{ $doc->id }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-upload"></i>
                    </button>
                </form>
                @endif
            </td>
            <td nowrap>
                @if($doc->file_to_sign_id === null)
                <a href="{{ route('documents.sendForSignature', $doc) }}" class="btn btn-sm btn-outline-primary">
                    <span class="fas fa-signature" aria-hidden="true" title="Enviar a firma"></span></a>
                @endif

                @if($doc->fileToSign && $doc->fileToSign->hasSignedFlow)
                <a href="{{ route('documents.signedDocumentPdf', $doc->id) }}" class="btn btn-sm @if($doc->fileToSign->hasAllFlowsSigned) btn-outline-success @else btn-outline-primary @endif" target="_blank">
                    <span class="fas fa-file-contract" aria-hidden="true"></span></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<hr>

<h3 class="mb-3">Documentos de la misma unidad organizacional</h3>

<table class="table table-sm">
    <thead>
        <tr>
            <th>CI</th>
            <th>Tipo</th>
            <th>N°</th>
            <th>Fecha</th>
            <th>Antec</th>
            <!--th>Resp</th-->
            <th>Materia</th>
            <th>De</th>
            <th>Para</th>
            <th class="small" width="70">Creador</th>
            <th nowrap></th>
            <th nowrap></th>
            <th nowrap></th>
        </tr>
    </thead>
    <tbody>
        @foreach($otherDocuments as $doc)
        <tr class="small">
            <td>{{ $doc->id }}</td>
            <td>{{ $doc->type }}</td>
            <td>
                {{ $doc->number }}
            </td>
            <td nowrap>{{ $doc->date ? $doc->date->format('d-m-Y'): '' }}</td>
            <td>{{ $doc->antecedent }}</td>
            <!--td nowrap>{!! $doc->responsibleHtml !!}</td-->
            <td>{{ $doc->subject }}</td>
            <td>{!! $doc->fromHtml !!}</td>
            <td>{!! $doc->forHtml !!}</td>
            <td class="small">
                @if($doc->user)
                    @if($doc->user->gravatar )
                        <img src="{{ $doc->user->gravatarUrl }}?s=40&d=mp&r=g" class="border rounded-circle" alt="{{ optional($doc->user)->tinnyName }}" title="{{ optional($doc->user)->tinnyName }}">
                    @else
                        {{ optional($doc->user)->tinnyName }}
                    @endif
                @endif
                <br>
                {{ $doc->created_at }}
            </td>
            <td nowrap>
                <a href="{{ route('documents.show', $doc->id) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-file fa-lg"></i></a>
            </td>
            <td nowrap>
                @if($doc->file)
                <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                    <i class="fas fa-file-pdf fa-lg"></i>
                </a>
                @else
                <form method="POST" action="{{ route('documents.find')}}">
                    @csrf
                    <button name="id" value="{{ $doc->id }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-upload"></i>
                    </button>
                </form>
                @endif
            </td>
            <td nowrap>
                @if($doc->file_to_sign_id === null)
                <a href="{{ route('documents.sendForSignature', $doc) }}" class="btn btn-sm btn-outline-primary">
                    <span class="fas fa-signature" aria-hidden="true" title="Enviar a firma"></span></a>
                @endif

                @if($doc->fileToSign && $doc->fileToSign->hasSignedFlow)
                <a href="{{ route('documents.signedDocumentPdf', $doc->id) }}" class="btn btn-sm @if($doc->fileToSign->hasAllFlowsSigned) btn-outline-success @else btn-outline-primary @endif" target="_blank">
                    <span class="fas fa-file-contract" aria-hidden="true"></span></a>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

{{ $otherDocuments->appends(request()->query())->links() }}

@endsection

@section('custom_js')

<script src="{{ asset('js/bootstrap-select.min.js') }}"></script>

@endsection
