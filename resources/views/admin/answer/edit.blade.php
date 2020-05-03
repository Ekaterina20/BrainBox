@extends('layouts.admin')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="d-flex">
            <a href="/admin/level/{{ $answer->level_id }}/edit" class="btn btn-primary mr-3">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1>Редактирование ответа</h1>
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
                <h3 class="card-title">Ответ</h3>
            </div>
            <form role="form" method="POST" action="/admin/answer/edit">
                @csrf
                <input type="hidden" name="id" value="{{ $answer->id }}">

                <div class="card-body">   
                    <div class="form-group">
                        <label for="order">Номер ответа</label>
                        <input type="number" value="{{ $answer->order }}" name="order" class="form-control" id="order" placeholder="Введите номер">
                    </div>
                    <div class="form-group">
                        <label for="code">Код ответа</label>
                        <input type="text" value="{{ $answer->code }}" name="code" class="form-control" id="code" placeholder="Введите номер">
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