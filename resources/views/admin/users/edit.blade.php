@extends('layouts.app')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Dashboard</div>

    <div class="panel-body">
      @if( session('notification') )
        <div class="alert alert-success">
          {{ session('notification') }}
        </div>
      @endif
      @if( count($errors) > 0 )
        <div class="alert alert-danger">
          <ul>
            @foreach( $errors->all() as $error )
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <form method="POST" action="">
        {{ csrf_field() }}

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}">
        </div>

        <div class="form-group">
          <label for="name">Nombre</label>
          <input type="text" class="form-control" name="name" value="{{ old('name', $user->name) }}">
        </div>

        <div class="form-group">
          <label for="password">Contraseña <em> Ingresar solo si se desea modificar</em></label>
          <input type="text" class="form-control" name="password" value="{{ old('password') }}">
        </div>

        <div class="form-group">
          <button class="btn btn-primary">Guardar usuario</button>
        </div>
      </form>

      <form action="/proyecto-usuario" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="user_id" value="{{ $user->id }}">
        <div class="row">
          <div class="col-md-4">
            <select name="project_id" class="form-control" id="select-project">
              <option value="">Seleccione proyecto</option>
              @foreach( $projects as $project )
                <option value="{{ $project->id }}">{{ $project->name }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-4">
            <select name="level_id" class="form-control" id="select-level">
              <option value="">Seleccione nivel</option>
            </select>
          </div>
          <div class="col-md-4">
            <button class="btn btn-primary btn-block">Asignar proyecto</button>
          </div>
        </div>
      </form>

      <p>Proyectos asignados</p>
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Proyecto</th>
            <th>Nivel</th>
            <th>Opciones</th>
          </tr>
        </thead>
        <tbody>
          @foreach( $projects_user as $project_user )
            <tr>
              <td>{{ $project_user->project->name }}</td>
              <td>{{ $project_user->level->name }}</td>
              <td>
                <a href="/proyecto-usuario/{{ $project_user->id }}/eliminar" class="btn btn-sm btn-danger" title="Dar de baja">
                  <span class="glyphicon glyphicon-remove"> </span>
                </a>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
  $(function(){
    $('#select-project').on('change', onSelectProjectChange);
  });

  function onSelectProjectChange(){
    var project_id = $(this).val();
    if(!project_id){
      $('#select-level').html('<option value="">Seleccione nivel</option>');
      return;
    }
    $.get('/api/proyecto/'+project_id+'/niveles', function(data){
      var html_select = '<option value="">Seleccione nivel</option>';
      for( var i=0; i<data.length; ++i ){
        //console.log(data[i]);
        html_select += '<option value="'+data[i].id+'">'+data[i].name+'</option>';
      }
      $('#select-level').html(html_select);
    });
  }
</script>
@endsection
