@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a href="/admin/game/{{ $level->game_id }}/edit" class="btn btn-primary mr-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Редактирование уровня</h1>
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
                <h3 class="card-title">Уровень</h3>
            </div>
            <form role="form" method="POST" action="/admin/level/edit">
                @csrf
                <input type="hidden" name="id" value="{{ $level->id }}">

                <div class="card-body">
                    <div class="form-group">
                        <label for="order">Номер уровня</label>
                        <input type="number" value="{{ $level->order }}" name="order" class="form-control" id="order" placeholder="Введите номер">
                    </div>
                    <div class="form-group">
                        <label for="required">Кол-во обязательных ответов</label>
                        <input type="number" value="{{ $level->required }}" name="required" class="form-control" id="required" placeholder="Введите кол-во">
                    </div>
                    <div class="form-group">
                        <label for="jump">Автопереход на след. уровень через (мин)</label>
                        <input type="number" value="{{ $level->jump }}" name="jump" class="form-control" id="jump" placeholder="Введите кол-во">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
        <!-- Create attachment -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Добавить блок</h3>
            </div>
            <form role="form" method="POST" action="/admin/attach/create" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="level_id" value="{{ $level->id }}">

                <div class="card-body">
                    <div class="form-group">
                        <label for="order">Номер блока</label>
                        <input type="number" name="order" class="form-control" id="order" placeholder="Введите номер">
                    </div>
                    <div class="form-group">
                        <label for="type">Тип</label>
                        {!! Form::select('type', ['text' => 'Текст', 'link' => 'Ссылка', 'img' => 'Изображение'], null, ['class' => 'form-control', 'id' => 'type']) !!}
                    </div>
                   {{-- <div class="form-group">
                        <label for="value">Контент</label>
                        <textarea rows="5" name="value" class="form-control" id="value" placeholder="Введите контент"></textarea>
                    </div>--}}
                    <div class="form-group">
                        <label for="file">Файл</label>
                        <div class="custom-file">
                            <input type="file" name="file" class="custom-file-input" id="file">
                            <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                        </div>
                    </div>
                </div>

                {{--Add Ckeditor--}}

                <div class="card card-primary">

                    <div class="card-header">
                        <h3 class="card-title">Редактор уровня</h3>
                    </div>

                    <div class="card-body">
                        <div class="form-group">
                            <textarea class="form-control" id="value" name="value"></textarea>
                        </div>
                    </div>

                </div>

                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
        <!-- Blocks list -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Блоки</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Номер</th>
                            <th>Значение</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($level->attachs as $attach)
                            <tr>
                                <td>{{ $attach->order }}</td>
                                <td>
                                    <span class="badge badge-primary">
                                        {{ $attach->type }}
                                    </span>
                                    <span>
                                        {{ $attach->value }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="/admin/attach/{{ $attach->id }}/edit" class="btn btn-success btn-sm m-1">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form class="d-inline" action="/admin/attach/{{ $attach->id }}/remove" method="POST">
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
        <!-- Add Answer -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Добавить ответ</h3>
            </div>
            <form role="form" method="POST" action="/admin/answer/create">
                @csrf
                <input type="hidden" name="level_id" value="{{ $level->id }}">

                <div class="card-body">
                    <div class="form-group">
                        <label for="order">Номер ответа</label>
                        <input type="number" name="order" class="form-control" id="order" placeholder="Введите номер">
                    </div>
                    <div class="form-group">
                        <label for="code">Код ответа</label>
                        <input type="text" name="code" class="form-control" id="code" placeholder="Введите код">
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
        <!-- Answers List -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Ответы</h3>
            </div>
            <div class="card-body p-0">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Номер</th>
                            <th>Код</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($level->answers()->orderBy('order')->get() as $answer)
                            <tr>
                                <td>{{ $answer->order }}</td>
                                <td>
                                    {{ $answer->code }}
                                </td>
                                <td class="text-right">
                                    <a href="/admin/answer/{{ $answer->id }}/edit" class="btn btn-success btn-sm m-1">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form class="d-inline" action="/admin/answer/{{ $answer->id }}/remove" method="POST">
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
        <!-- Add Help -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Добавить подсказку</h3>
            </div>
            <form role="form" method="POST" action="/admin/help/create">
                @csrf
                <input type="hidden" name="level_id" value="{{ $level->id }}">

                <div class="card-body">
                    <div class="form-group">
                        <label for="delay">Доступна через (мин)</label>
                        <input type="number" name="delay" class="form-control" id="delay" placeholder="Введите кол-во минут">
                    </div>
                    <div class="form-group">
                        <label for="text">Текст подсказки</label>
                        <textarea rows="5" name="text" class="form-control" id="text" placeholder="Введите текст"></textarea>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Добавить</button>
                </div>
            </form>
        </div>
        <!-- Helps Table -->
        <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Подсказки</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Доступна через (мин)</th>
                                <th>Текст</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($level->helps()->orderBy('delay')->get() as $help)
                                <tr>
                                    <td>{{ $help->delay }}</td>
                                    <td>{{ $help->getText() }}</td>
                                    <td class="text-right">
                                        <a href="/admin/help/{{ $help->id }}/edit" class="btn btn-success btn-sm m-1">
                                            <i class="fas fa-pen"></i>
                                        </a>
                                        <form class="d-inline" action="/admin/help/{{ $help->id }}/remove" method="POST">
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

    <!--Ckeditor-->

    <script src="{{ asset('ckeditor/ckeditor.js') }}"></script>
    <script>
        CKEDITOR.replace( 'value', {
            language: 'ru',
            uiColor: '#CEF6F5'
        });

    </script>

</section>
<!-- /.content -->

@endsection
