@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <h1>Игры</h1>
            </div>
            <div class="col-sm-6 text-sm-right">
                <a href="/admin/game/create" class="btn btn-primary">Создать игру</a>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-head-fixed mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Доступ</th>
                            <th>Название</th>
                            <th>Начало</th>
                            <th>Конец</th>
                            <th>Тип</th>
                            <th>Команд</th>
                            <th>Цена</th>
                            <th>Территория</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($games as $game)    
                            <tr>
                                <td>{{ $game->id }}</td>
                                <td>
                                    @if ($game->private)
                                        <span class="badge badge-danger">private</span>
                                    @else
                                        <span class="badge badge-success">public</span>
                                    @endif
                                </td>
                                <td>{{ $game->name }}</td>
                                <td>{{ $game->date_start }}</td>
                                <td>
                                    @if($game->date_end)
                                        {{ $game->date_end }}
                                    @else
                                        <form action="/admin/game/{{ $game->id }}/finish" method="POST">
                                            @csrf
                                            @method('put')

                                            <button type="submit" class="btn btn-sm btn-primary"
                                                    onclick="return confirm('Завершить игру?')">
                                                <i class="fas fa-list-ol"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    @switch($game->type)
                                        @case('liner')
                                            <span class="badge badge-success">liner</span>
                                            @break
                                        @case('storm')
                                            <span class="badge badge-warning">storm</span>
                                            @break
                                        @default
                                            <span>---</span>
                                    @endswitch
                                </td>
                                <td>
                                    <span class="badge badge-info">
                                        {{ $game->teams_cnt }}
                                    </span>
                                </td>
                                <td>{{ $game->price }} сом</td>
                                <td>{{ $game->area }} км</td>
                                <td class="text-right">
                                    <a href="/admin/game/{{ $game->id }}/edit" class="btn btn-success btn-sm m-1">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form class="d-inline" action="/admin/game/{{ $game->id }}/remove" method="POST">
                                        @csrf
                                        @method('put')
                                        <button type="submit" class="btn btn-danger btn-sm m-1" onclick="return confirm('Вы уверенны?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer clearfix">
                <div class="float-right">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection