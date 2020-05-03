@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <h1>Создание игры</h1>
            </div>
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
            <!-- form start -->
            <form role="form" method="POST" action="/admin/game/create" enctype="multipart/form-data">

                @csrf

                <div class="card-body">
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" value="{{ old('name') }}" name="name" class="form-control" id="name" placeholder="Введите название">
                    </div>
                    <div class="form-group">
                        <label for="type">Тип</label>
                        {!! Form::select('type', ['liner' => 'Линейная', 'storm' => 'Штурмовая'], null, ['class' => 'form-control', 'id' => 'type']) !!}
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
                        <input type="number" value="{{ old('price') }}" name="price" class="form-control" id="price" placeholder="Введите цену (сом)">
                    </div>
                    <div class="form-group">
                        <label for="area">Территория</label>
                        <input type="number" value="{{ old('area') }}" name="area" class="form-control" id="area" placeholder="Введите территорию (км)">
                    </div>
                    <div class="form-group">
                        <label for="date_start">Дата и время начала</label>
                        <input type="text" name="date_start" id="date_start" class="form-control" data-datetimepicker>
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
                    <button type="submit" class="btn btn-primary">Создать игру</button>
                </div>
            </form>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

@endsection