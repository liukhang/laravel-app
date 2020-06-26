@extends('layouts.master')
@section('content')
Xin chào: {{ $user->name ?? '' }} <br>
Email: {{ $user->email ?? '' }}

<div class="box box-primary">
    <form action="{{ url('user') }}" method="GET" role="form">
        {{ csrf_field()}}
        <div class="box-body">
            <div class="form-group">
                <div class="col-md-10">
                    <label for="name" class="col-md-2">Nhập user github:</label>
                    <div class="col-md-10">
                        <input type="text" name="name" class="form-control" id="" placeholder="Input field">
                    </div>

                </div>
            </div>
            <div class="box-footer">
                <button type="submit" class="btn btn-info pull-right">Get repo</button>
            </div>
        </div>
    </form>
</div>
@stop