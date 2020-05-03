@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a class="btn btn-primary mr-3" href="/admin/games">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Редактирование игры</h1>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger">
                        {{ $error }}
                </div>
            @endforeach
        @endif

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Игра</h3>
            </div>
            <form role="form" method="POST" action="/admin/game/edit" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $game->id }}">

                <div class="card-body">   
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" value="{{ $game->name }}" name="name" class="form-control" id="name" placeholder="Введите название">
                    </div>
                    <div class="form-group">
                        <label for="type">Тип</label>
                        {!! Form::select('type', ['liner' => 'Линейная', 'storm' => 'Штурмовая'], $game->type, ['class' => 'form-control', 'id' => 'type']) !!}
                    </div>
                    <div class="form-group">
                        <label for="preview">Изображение</label>
                        <div class="custom-file">
                            <input type="file" name="preview" class="custom-file-input" id="preview">
                            <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price">Цена</label>
                        <input type="number" value="{{ $game->price }}" name="price" class="form-control" id="price" placeholder="Введите цену (сом)">
                    </div>
                    <div class="form-group">
                        <label for="area">Территория</label>
                        <input type="number" value="{{ $game->area }}" name="area" class="form-control" id="area" placeholder="Введите территорию (км)">
                    </div>
                    <div class="form-group">
                        <label for="date_start">Дата и время начала</label>
                        <input type="text" placeholder="{{ $game->date_start }}" name="date_start" id="date_start" class="form-control" data-datetimepicker>
                    </div>
                    {{-- <div class="form-group">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox">
                            <label class="form-check-label">Приватная игра</label>
                        </div>
                    </div> --}}
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
        <!-- Create level -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Добавить уровень</h3>
            </div>
            <form role="form" method="POST" action="/admin/level/create" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="game_id" value="{{ $game->id }}">

                <div class="card-body">                    
                    <div class="form-group">
                        <label for="order">Номер уровня</label>
                        <input type="number" name="order" class="form-control" id="order" placeholder="Введите номер">
                    </div>
                    <div class="form-group">
                        <label for="required">Кол-во обязательных ответов</label>
                        <input type="number" name="required" class="form-control" id="required" placeholder="Введите кол-во">
                    </div>
                    <div class="form-group">
                        <label for="jump">Автопереход на след. уровень через (мин)</label>
                        <input type="number" name="jump" class="form-control" id="jump" placeholder="Введите кол-во минут">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
        <!-- Levels list -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Уровни</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Номер</th>
                            <th>Обязательных ответов</th>
                            <th>Авто переход (мин)</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($game->levels as $level)
                            <tr>
                                <td>{{ $level->order }}</td>
                                <td>{{ $level->required }}</td>
                                <td>{{ $level->jump ?? 'Нет' }}</td>
                                <td class="text-right">
                                    <a href="/admin/level/{{ $level->id }}/edit" class="btn btn-success btn-sm m-1">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form class="d-inline" action="/admin/level/{{ $level->id }}/remove" method="POST">
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
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection