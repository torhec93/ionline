@extends('layouts.app')

@section('title', 'Requerimientos')

@section('content')

@include('requirements.partials.nav')

<link href="{{ asset('css/animate.css') }}" rel="stylesheet">

<form class="form-inline float-right" method="GET" action="{{ route('requirements.outbox') }}">
	<div class="input-group mb-4">
		<input type="text" name="request_req" class="form-control" placeholder="N°, Asunto.">
		<input type="text" name="request_cat" class="form-control" placeholder="Categorías">
		<input type="text" name="request_usu" class="form-control" placeholder="Usuario involucrado">
		<input type="text" name="request_parte" class="form-control" placeholder="Origen, N°Origen">
		<div class="input-group-append">
			<button class="btn btn-outline-secondary" type="submit">
				<i class="fas fa-search" aria-hidden="true"></i></button>
		</div>
	</div>
</form>

<br><br>

<div class="alert alert-warning alert-dismissible fade show" role="alert">
  <strong>¡Es importante que cierres tus requerimientos!</strong> <br /> Requerimientos abiertos producen demoras en tus tiempos productivos.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

<h3 class="mb-3">Pendientes por atender</h3>

<table class="table table-sm table-bordered small">
    <tr>
        <td class="alert-info text-center">Creados ({{ $legend['creados'] }})</td>
        <td class="alert-light text-center">Recibidos ({{ $legend['recibidos'] }})</td>
        <td class="alert-warning text-center">Respondidos ({{ $legend['respondidos'] }})</td>
        <td class="alert-primary text-center">Derivados ({{ $legend['derivados'] }})</td>
        <td class="alert-success text-center">Cerrados ({{ $legend['cerrados'] }})</td>
        <td class="alert-light text-center">Reabiertos ({{ $legend['reabiertos'] }})</td>
        <td class="alert-secondary text-center">En copia ({{ $legend['en copia'] }})</td>
    </tr>
</table>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>N°</th>
            <th>Asunto</th>
            <th>Unidad Organizacional</th>
			<th>Funcionario</th>
            <th style="width: 77px;">Fecha creación</th>
            <th>Transcurrido</th>
            <th style="width: 80px;">Fecha límite</th>
            <th></th>
						<th></th>
        </tr>
    </thead>
    <tbody>

		@foreach($created_requirements as $created)

            @php $flag = 0; @endphp
            @foreach($created->events as $event)
              @if($event->status == "en copia" && $event->to_user_id == Auth::user()->id)
                @php $flag = 1; break; @endphp
              @endif
            @endforeach

            @if($flag == 1) <tr class="alert-secondary">
            @else
              @switch($created->status)
                  @case('creado')
                      @if($created->user == Auth::user())
                          <tr class="alert-info">
                      @else
                          <tr class="alert-light">
                      @endif
                      @break
                  @case('respondido') <tr class="alert-warning"> @break
                  @case('cerrado') <tr class="alert-success"> @break
                  @case('derivado') <tr class="alert-primary"> @break
                  @case('reabierto') <tr class="alert-light"> @break
              @endswitch
            @endif
        <td class="text-center">{{$created->id}}</td>
				<td class="small">
                    {{$created->subject}}<br>
                    @foreach($created->categories as $category)
                        @if(Auth::user()->id == $category->user_id)
                            <span class='badge badge-primary' style='background-color: #{{$category->color}};'>{{$category->name}}</span>
                        @endif
                    @endforeach
										@if($created->parte)
											<br>
											<small>Parte: <b>{{$created->parte->origin}} - {{$created->parte->number}}</b></small>
										@endif

                </td>
				<td class="small">
                        {{$created->events->last()->to_ou->name}}
                </td>
				<td class="small">
                    @if(($created->status == 'respondido') or ($created->status == 'creado' AND $created->from_user != Auth::user()))
                        @if($created->events->last()->from_user <> null){{$created->events->last()->from_user->getFullNameAttribute()}}@endif
                    @else
                        @if($created->events->last()->to_user <> null){{$created->events->last()->to_user->getFullNameAttribute()}}@endif
                    @endif

                </td>
				<td class="small text-center">{{Carbon\Carbon::parse($created->created_at)->format('Y-m-d H:i')}}</td>
			  <td class="small text-center">{{$created->created_at->diffForHumans() }}</td> <!--Carbon\Carbon::parse($created->created_at)->diffInDays(Carbon\Carbon::now()) -->
        @if($created->limit_at <> NULL)
          @if(Carbon\Carbon::now() >= $created->limit_at)
            <td class="small text-danger"><b>{{Carbon\Carbon::parse($created->limit_at)->format('Y-m-d')}}</b>
          @else
            <td class="small">{{Carbon\Carbon::parse($created->limit_at)->format('Y-m-d')}}
          @endif
        @else
          <td class="small">
        @endif
        </td>
				<!-- <td>{{$created->status}}</td> -->
				<td nowrap>
					<a href="{{ route('requirements.show',$created->id) }}">
						<i class="fas fa-edit"></i>
					</a>
                    &nbsp;
					<a href="{{ route('requirements.archive_requirement',$created) }}">
						<i class="fas fa-arrow-down"></i>
					</a>
				</td>
				<td nowrap>

					@if($created->status_view == "visto")
						<div title="Visto">
							<span style="font-size: 1em; color: #06F606;">
							  <i class="fas fa-check-double"></i>
							</span>
						</div>
					@else
						<div title="Sin revisar">
							<span style="font-size: 1em; color: #C9C8C8;">
							  <i class="fas fa-check-double"></i>
							</span>
					  </div>
					@endif

				</td>
			</tr>
		@endforeach
    </tbody>
</table>

{{ $created_requirements->appends(request()->query())->links() }}


<h3 class="mb-3">Archivados</h3>

<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>N°</th>
            <th>Asunto</th>
            <th>Unidad Organizacional</th>
			<th>Funcionario</th>
            <th style="width: 77px;">Fecha creación</th>
            <th>Transcurrido</th>
            <th style="width: 80px;">Fecha límite</th>
            <th></th>
						<th></th>
        </tr>
    </thead>
    <tbody>
		@foreach($archived_requirements as $archived)
          @php $flag = 0; @endphp
          @foreach($archived->events as $event)
            @if($event->status == "en copia" && $event->to_user_id == Auth::user()->id)
              @php $flag = 1; break; @endphp
            @endif
          @endforeach

          @if($flag == 1) <tr class="alert-secondary">
          @else
            @switch($archived->status)
                @case('creado')
                    @if($archived->user == Auth::user())
                        <tr class="alert-info">
                    @else
                        <tr class="alert-light">
                    @endif
                    @break
                @case('respondido') <tr class="alert-warning"> @break
                @case('cerrado') <tr class="alert-success"> @break
                @case('derivado') <tr class="alert-primary"> @break
                @case('reabierto') <tr class="alert-light"> @break
            @endswitch
          @endif
					<td class="text-center">{{$archived->id}}</td>
					<td class="small">
                        {{$archived->subject}}<br>
                        @foreach($archived->categories as $category)
                            @if(Auth::user()->id == $category->user_id)
                                <span class='badge badge-primary' style='background-color: #{{$category->color}};'>{{$category->name}}</span>
                            @endif
                        @endforeach
												@if($archived->parte)
													<br>
													<small>Parte: <b>{{$archived->parte->origin}} - {{$archived->parte->number}}</b></small>
												@endif
                    </td>
					<td class="small">

                        {{$archived->events->last()->to_ou->name}}
                    </td>
					<td class="small">@if($archived->events->last()->to_user <> null){{$archived->events->last()->to_user->getFullNameAttribute()}}@endif</td>
					<td class="small text-center">{{Carbon\Carbon::parse($archived->created_at)->format('Y-m-d H:i')}}</td>
					<td class="small text-center">{{$archived->created_at->diffForHumans() }}</td> <!--Carbon\Carbon::parse($archived->created_at)->diffInDays(Carbon\Carbon::now()) -->
					<!-- <td class="small" nowrap>@if($archived->limit_at <> NULL){{Carbon\Carbon::parse($archived->limit_at)->format('Y-m-d')}} @endif</td> -->
          @if($archived->limit_at <> NULL)
            @if(Carbon\Carbon::now() >= $archived->limit_at)
              <td class="small text-danger"><b>{{Carbon\Carbon::parse($archived->limit_at)->format('Y-m-d')}}</b>
            @else
              <td class="small">{{Carbon\Carbon::parse($archived->limit_at)->format('Y-m-d')}}
            @endif
          @else
            <td class="small">
          @endif
          </td>
					<!-- <td>{{$archived->status}}</td> -->
					<td nowrap>
						<a href="{{ route('requirements.show',$archived->id) }}">
							<i class="fas fa-edit"></i>
						</a>
                        &nbsp;
						<a href="{{ route('requirements.archive_requirement_delete',$archived) }}">
							<i class="fas fa-arrow-up"></i>
						</a>
					</td>
					<td nowrap>

						@if($archived->status_view == "visto")
							<div title="Visto">
								<span style="font-size: 1em; color: #06F606;">
								  <i class="fas fa-check-double"></i>
								</span>
							</div>
						@else
							<div title="Sin revisar">
								<span style="font-size: 1em; color: #C9C8C8;">
								  <i class="fas fa-check-double"></i>
								</span>
						  </div>
						@endif

					</td>
			</tr>
		@endforeach
    </tbody>
</table>

{{ $archived_requirements->appends(request()->query())->links() }}

@endsection

@section('custom_js')

@endsection