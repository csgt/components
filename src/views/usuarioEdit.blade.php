@extends('template/template')

@section('content')
	<h3 class="text-primary">{{$data?'Editar':'Nuevo'}} Usuario</h3>
	@if(Session::get('message'))
		<div class="alert alert-{{ Session::get('type') }} alert-dismissable .mrgn-top">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			{{ Session::get('message') }}
		</div>
	@endif
	{{ Form::open(array('url' => URL::route('usuarios.store'), 'method' => 'POST', 'class'=>'form-horizontal', 'role'=>'form', 'id'=>'frmUsuario')) }}
		{{Form::hidden('id', $data?$id:'') }}
		@foreach(Config::get('login::camposeditarperfil') as $campos)
			<div class="form-group">
				<label for="password" class="col-sm-2 control-label">{{ $campos['titulo'] }}</label>
				<div class="col-sm-10">
					<?php $campo = Config::get('login::usuario.campo'); ?>
		      <input 
						type         = "text" 
						class        = "form-control" 
						id           = "{{ $campos['campo'] }}" 
						name         = "{{ $campos['campo'] }}" 
						placeholder  = "{{ $campos['titulo'] }}"  
						value        = "{{ $data?$data->nombre:'' }}" 
						autocomplete = "off" 
		      	data-bv-notempty>
				</div>
    	</div>
    @endforeach
		<div class="form-group">
	    <label for="email" class="col-sm-2 control-label">{{ Config::get('login::usuario.titulo') }}</label>
	    <div class="col-sm-10">
	    	<?php $campo = Config::get('login::usuario.campo'); ?>
	      <input 
					type         = "text" 
					class        = "form-control" 
					id           = "{{ Config::get('login::usuario.campo') }}" 
					name         = "{{ Config::get('login::usuario.campo') }}" 
					placeholder  = "{{ Config::get('login::usuario.titulo') }}"  
					value        = "{{ $data?$data->email:'' }}" 
					autocomplete = "off" 
	      	data-bv-notempty = "true"
	      	data-bv-emailAddress = "true"
	      	data-bv-emailAddress-message = "Correo inválido">
	    </div>
	  </div>
	  <div class="form-group">
      <label for="rolid" class="col-sm-2 control-label">Rol</label>
      <div class="col-sm-10">
      @if(Config::get('components::multiplesroles'))
      	<select name="rolid[]" class="selectpicker" data-bv-notempty="true" multiple>
      		@foreach ($roles as $rol)
      			<option value="{{Crypt::encrypt($rol->rolid)}}" {{ (in_array($rol->rolid, $uroles) ? 'selected="selected"':'') }}>{{$rol->nombre}}</option>
      		@endforeach
      	</select>
      @else
				<select name="rolid" class="selectpicker">
					@foreach ($roles as $rol)
						<option value="{{Crypt::encrypt($rol->rolid)}}" {{ ($data?($data->rolid==$rol->rolid?'selected="selected"':''):'') }}>{{$rol->nombre}}</option>
	      	@endforeach
      	</select>
      @endif
      </div>
    </div>
	  <div class="form-group">
	    <div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	    	{{Form::checkbox('activo',1, ($data?($data->activo?true:false):true) )}} <label for="activo">Activo</label>
	    </div>
	  </div>
    <div class="form-group">
      <label for="password" class="col-sm-2 control-label">{{ Config::get('login::password.titulo') }}</label>
      <div class="col-sm-5">
        <input 
					type                         = "password" 
					class                        = "form-control" 
					name                         = "password" 
					id                           = "password" 
					placeholder                  = "{{ Config::get('login::password.titulo')}}" 
					autocomplete                 = "off" 
					data-bv-identical            = "true" 
					data-bv-identical-field      = "password2" 
					data-bv-identical-message    = "Las passwords no concuerdan"
					data-bv-stringlength         = "true"
					data-bv-stringlength-min     = "6"
					data-bv-stringlength-message = "La {{Config::get('login::password.titulo')}} debe tener al menos 6 caracteres."
					{{$data?'':'data-bv-notEmpty = "true"'}}>
      </div>
       <div class="col-sm-5">
        <input 
					type                         = "password" 
					class                        = "form-control" 
					name                         = "password2" 
					placeholder                  = "Repetir {{ Config::get('login::password.titulo')}}" 
					autocomplete                 = "off" 
					data-bv-identical            = "true" 
					data-bv-identical-field      = "password" 
					data-bv-identical-message    = "Las passwords no concuerdan"
					data-bv-stringlength         = "true"
					data-bv-stringlength-min     = "6"
					data-bv-stringlength-message = "La {{Config::get('login::password.titulo')}} debe tener al menos 6 caracteres.">
      </div>
    </div>
    <div class="form-group">
	  	<div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	    	<small>* Dejar en blanco para no cambiar {{ Config::get('login::password.titulo') }}.</small>
	   	</div>
	  </div>
    <div class="form-group">
	    <div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	      {{ Form::submit('Guardar', array('class' => 'btn btn-primary')) }}
	    </div>
	  </div>
	{{ Form::close() }}
	<script type="text/javascript">
		$(function() {
			$('.selectpicker').selectpicker();

			$('#frmUsuario').bootstrapValidator({
        message: 'El campo es requerido',
        excluded: [':disabled'],
        feedbackIcons: {
          valid: 'glyphicon glyphicon-ok',
          invalid: 'glyphicon glyphicon-remove',
          validating: 'glyphicon glyphicon-refresh'
        }
      });
		});
	</script>
@stop