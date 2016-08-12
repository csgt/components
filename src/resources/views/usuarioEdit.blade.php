@extends($template)

@section('content')

	<h3 class="text-primary">{!!$data?'Editar':'Nuevo'!!} Usuario</h3>
	@if(Session::get('message'))
		<div class="alert alert-{!! Session::get('type') !!} alert-dismissable .mrgn-top">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
			{!! Session::get('message') !!}
		</div>
	@endif
	{!! Form::open(array('url' => URL::route('usuarios.store'), 'method' => 'POST', 'class'=>'form-horizontal', 'role'=>'form', 'id'=>'frmUsuario')) !!}
		{!!Form::hidden('id', $data?$id:'') !!}
		@foreach(config('csgtlogin.camposeditaradmin') as $campos)
			<div class="form-group">
				<label for="{!! $campos['campo'] !!}" class="col-sm-2 control-label">{!! $campos['titulo'] !!}</label>
				<div class="col-sm-5">
					<?php $campo = config('csgtlogin.usuario.campo'); ?>
		      <input 
						type         = "text" 
						class        = "form-control"
						autocomplete = "off" 
						id           = "{!! $campos['campo'] !!}" 
						name         = "{!! $campos['campo'] !!}" 
						placeholder  = "{!! $campos['titulo'] !!}"  
						value        = "{!! $data?$data->nombre:'' !!}" 
						autocomplete = "off" 
		      	data-fv-notempty>
				</div>
    	</div>
    @endforeach
		<div class="form-group">
	    <label for="{!! config('csgtlogin.usuario.campo') !!}" class="col-sm-2 control-label">{!! trans('csgtlogin::login.usuario') !!}</label>
	    <div class="col-sm-5">
	    	<?php $campo = config('csgtlogin.usuario.campo'); ?>
	      <input 
					type         = "text" 
					class        = "form-control" 
					id           = "{!! config('csgtlogin.usuario.campo') !!}" 
					name         = "{!! config('csgtlogin.usuario.campo') !!}" 
					placeholder  = "{!! trans('csgtlogin::login.usuario') !!}"  
					value        = "{!! $data?$data->email:'' !!}" 
					autocomplete = "off" 
	      	data-fv-notempty = "true"
	      	<?php if(config('csgtlogin.usuario.tipo')=='email') { ?>
	      	data-fv-emailAddress = "true"
	      	data-fv-emailAddress-message = "Correo inválido"
					<?php } ?>
	      	>
	    </div>
	  </div>
	  <div class="form-group">
      <label for="rolid" class="col-sm-2 control-label">Rol</label>
      
      @if(config('csgtcancerbero.multiplesroles')===true)
      	<div class="col-sm-10">
	      	<select name="rolid[]" class="selectpicker" multiple autocomplete="off">
	      		@foreach ($roles as $rol)
	      			<option value="{{Crypt::encrypt($rol->rolid)}}" {!! (in_array($rol->rolid, $uroles) ? 'selected="selected"':'') !!}>{{$rol->nombre}}</option>
	      		@endforeach
	      	</select>
	      </div>
      @else
      	<div class="col-sm-5">
					<select name="rolid" class="selectpicker" data-width="100%">
						@foreach ($roles as $rol)
							<option value="{!!Crypt::encrypt($rol->rolid)!!}" {!! ($data?($data->rolid==$rol->rolid?'selected="selected"':''):'') !!}>{{$rol->nombre}}</option>
		      	@endforeach
	      	</select>
	      </div>
      @endif
    </div>
	  <div class="form-group">
	    <div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	    	{!!Form::checkbox('activo',1, ($data?($data->activo?true:false):true) )!!} <label for="activo">Activo</label>
	    </div>
	  </div>
	  @if(config('csgtlogin.vencimiento.habilitado'))
	  <div class="form-group">
	    <div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	    	{!!Form::checkbox('vencimiento',1, true)!!} <label for="vencimiento">Usuario debe cambiar la contraseña</label>
	    </div>
	  </div>
	  @endif
    <div class="form-group">
      <label for="password" class="col-sm-2 control-label">{!! trans('csgtlogin::login.contrasena') !!}</label>
      <div class="col-sm-5">
        <input 
					type                         = "password" 
					class                        = "form-control" 
					name                         = "password" 
					id                           = "password" 
					autocomplete								 = "off"
					placeholder                  = "{!! trans('csgtlogin::login.contrasena') !!}" 
					autocomplete                 = "off" 
					data-fv-identical            = "true" 
					data-fv-identical-field      = "password2" 
					data-fv-identical-message    = "Las passwords no concuerdan"
					data-fv-stringlength         = "true"
					data-fv-stringlength-min     = "6"
					data-fv-stringlength-message = "La {!!config('csgtlogin.password.titulo')!!} debe tener al menos 6 caracteres."
					{!!$data?'':'data-fv-notEmpty = "true"'!!}>
      </div>
       <div class="col-sm-5">
        <input 
					type                         = "password" 
					class                        = "form-control" 
					name                         = "password2" 
					autocomplete								 = "off"
					placeholder                  = "{!! trans('csgtlogin::login.repetir') . ' ' . trans('csgtlogin::login.contrasena') !!}" 
					autocomplete                 = "off" 
					data-fv-identical            = "true" 
					data-fv-identical-field      = "password" 
					data-fv-identical-message    = "Las passwords no concuerdan"
					data-fv-stringlength         = "true"
					data-fv-stringlength-min     = "6"
					data-fv-stringlength-message = "La {!!config('csgtlogin.password.titulo')!!} debe tener al menos 6 caracteres.">
      </div>
    </div>
    @if($data)
    <div class="form-group">
	  	<div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	    	<small>* Dejar en blanco para no cambiar {!! config('csgtlogin.password.titulo') !!}.</small>
	   	</div>
	  </div>
	  @endif
    <div class="form-group">
	    <div class="col-sm-2">&nbsp;</div>
	    <div class="col-sm-10">
	      {!! Form::submit('Guardar', array('class' => 'btn btn-primary')) !!}
	    </div>
	  </div>
	{!! Form::close() !!}
	<script type="text/javascript">
		$(function() {
			$('.selectpicker').selectize();

			$('#frmUsuario').formValidation({
        message: 'El campo es requerido',
        live: 'submitted',
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