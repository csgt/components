@extends('template/template')
@section('content')
<h3 class="text-primary">Generador de 7 permisos CRUD</h3>
{!!Form::open()!!}
  <div class="form-group">
    <label for="mpid">MPID Inicial</label>
    <input type="text" class="form-control" name="mpid" id="mpid" placeholder="modulopermisoid inicial" value="{!!$mpid!!}">
  </div>
  <div class="form-group">
    <label for="moduloid">Modulo</label>
    <select name="moduloid" class="form-control">
    	@foreach($modulos as $modulo)
    	<option value="{!!$modulo->moduloid!!}">{!!$modulo->nombre!!}</option>
    	@endforeach
    </select>
  </div>
  <button type="submit" class="btn btn-default">Generar</button>
{!!Form::close()!!}
@stop