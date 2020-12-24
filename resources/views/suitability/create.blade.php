@extends('layouts.app')

@section('title', 'Nuevo Staff')

@section('content')

@include('suitability.nav')

<h3 class="mb-3">Nueva Solicitud de Evaluación de Idoneidad Psicológica </h3>

<form method="POST" class="form-horizontal" action="">
    @csrf
    @method('POST')

    <div class="form-row align-items-end">
        <fieldset class="form-group col-5 col-sm-4 col-md-4 col-lg-2">
            <label for="for_run">Run</label>
            <input type="number" class="form-control" id="for_run" name="run" autocomplete="off" max="50000000" value="16055586" required>
        </fieldset>

        <fieldset class="form-group col-2 col-sm-2 col-md-1 col-lg-1">
            <label for="for_dv">DV</label>
            <input type="text" class="form-control" id="for_dv" name="dv" autocomplete="off" value="6" readonly>
        </fieldset>

        <fieldset class="form-group col-4">
            <label>Sexo*</label>
            <select class="form-control">
            <option value="">Seleccionar Sexo</option>
            <option value="Masculino">Masculino</option>
            <option value="Femenino">Femenino</option>
            </select>
        </fieldset>

    </div>

    <div class="row">
        <fieldset class="form-group col-6">
            <label for="for_critic_stock">Nombre Completo*</label>
            <input type="text" class="form-control" id="for_critic_stock" placeholder="" name="critic_stock" required="">
        </fieldset>

        <fieldset class="form-group col-6">
            <label for="for_critic_stock">Correo Electrónico*</label>
            <input type="email" class="form-control" id="" placeholder="" name="" required="">
        </fieldset>
    </div>

    <div class="row">
        <fieldset class="form-group col-6">
            <label for="for_critic_stock">Cargo Desempeñado*</label>
            <input type="text" class="form-control" id="for_critic_stock" placeholder="" name="critic_stock" required="">
        </fieldset>

        <fieldset class="form-group col-6">
            <label for="for_critic_stock">Fecha de Ingreso*</label>
            <input type="date" class="form-control" id="for_critic_stock" placeholder="" name="critic_stock" required="">
        </fieldset>
    </div>

    <div class="row">
        <fieldset class="form-group col-6">
            <label for="for_critic_stock">Nacionalidad*</label>
            <input type="text" class="form-control" id="for_critic_stock" placeholder="" name="critic_stock" required="">
        </fieldset>
    </div>


    <label for="forBrand">Presenta Discapacidad</label>
    <fieldset class="form-group">
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="RadioType1" value="desktop" required>
            <label class="form-check-label" for="inlineRadio1">Sí</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="type" id="RadioType2" value="all-in-one" required>
            <label class="form-check-label" for="inlineRadio2">No</label>
        </div>
    </fieldset>






    <button type="submit" class="btn btn-primary">Guardar</button>


</form>

@endsection

@section('custom_js')



<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="{{ asset('js/jquery.rut.chileno.js') }}"></script>
<script type="text/javascript">
    //obtiene digito verificador
    $('input[name=run]').keyup(function(e) {
        var str = $("#for_run").val();
        $('#for_dv').val($.rut.dv(str));
    });
</script>

@endsection