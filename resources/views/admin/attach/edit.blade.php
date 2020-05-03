@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a href="/admin/level/{{ $attach->level_id }}/edit" class="btn btn-primary mr-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Редактирование блока уровня</h1>
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
                <h3 class="card-title">Блок</h3>
            </div>
            <form role="form" method="POST" action="/admin/attach/edit" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $attach->id }}">

                <div class="card-body">   
                    <div class="form-group">
                        <label for="order">Номер блока</label>
                        <input type="number" value="{{ $attach->order }}" name="order" class="form-control" id="order" placeholder="Введите номер">
                    </div>
                    <div class="form-group">
                        <label for="type">Тип</label>
                        {!! Form::select('type', ['text' => 'Текст', 'link' => 'Ссылка', 'img' => 'Изображение'], $attach->type, ['class' => 'form-control', 'id' => 'type']) !!}
                    </div>
                    @if($attach->type == 'text' || $attach->type == 'link')
                        <textarea rows="5" name="value" class="form-control" id="value" placeholder="Введите значение">{{ $attach->value }}</textarea>
                    @else
                        <div class="form-group">
                            <label for="file">Файл</label>
                            <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="file">
                                <label class="custom-file-label" for="exampleInputFile">Выберите файл</label>
                            </div>
                        </div>
                    @endif
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