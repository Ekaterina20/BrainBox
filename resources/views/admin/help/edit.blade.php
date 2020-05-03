@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a href="/admin/level/{{ $help->level_id }}/edit" class="btn btn-primary mr-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Редактирование подсказки</h1>
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
                <h3 class="card-title">Подсказка</h3>
            </div>
            <form role="form" method="POST" action="/admin/help/edit">
                @csrf
                <input type="hidden" name="id" value="{{ $help->id }}">

                <div class="card-body">   
                    <div class="form-group">
                        <label for="delay">Доступна через (мин)</label>
                        <input type="number" value="{{ $help->delay }}" name="delay" class="form-control" id="delay" placeholder="Введите кол-во минут">
                    </div>
                    <div class="form-group">
                        <label for="text">Текст</label>
                        <textarea rows="5" name="text" class="form-control" id="text" placeholder="Введите текст">{{ $help->getText() }}</textarea>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                </div>
            </form>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection