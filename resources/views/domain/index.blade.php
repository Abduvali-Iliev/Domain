@extends('layouts.app')
<div class="container">
    <h1>Домен</h1>
    <form method="post" action="{{action([\App\Http\Controllers\DomainContraller::class, 'store'])}}" enctype="multipart/form-data">
        @csrf
        <label for="domain" class="d-block">Проверка домена</label>
        <textarea class="form-control d-block" name="domain">{{old('domain')}}</textarea>

        @error('domain')
        <div class="alert alert-danger">{{$message}}</div>
        @enderror

        <button type="submit" class="btn btn-primary mt-3">Принять</button>

    </form>
</div>
