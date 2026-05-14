@extends('adminlte::page')

@section('title', __('adminlte::adminlte.createNewPost'))

@section('content_header')
    <div class="row">
        <ol class="breadcrumb float-sm-left">
            <li class="breadcrumb-item"><a href="{{ route('about-company-sections.index', ['locale' => app()->getLocale()]) }}">{{ __('adminlte::landingpage.aboutcompany') }} - {{ __('adminlte::adminlte.sections') }}</a></li>
            <li class="breadcrumb-item active">{{ __('adminlte::adminlte.createNewPost') }}</li>
        </ol>
    </div>
@stop

@section('content')
    <form action="{{ route('about-company-sections.store', ['locale' => app()->getLocale()]) }}" method="POST">
        @csrf
        <div class="card card-default">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title" class="text-olive">{{ __('adminlte::adminlte.title(AR)') }}</label>
                            <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                        </div>
                        <div class="form-group">
                            <label for="title_en" class="text-olive">{{ __('adminlte::adminlte.title(EN)') }}</label>
                            <input type="text" name="title_en" class="form-control" value="{{ old('title_en') }}" style="text-align: left; direction: ltr;">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="icon" class="text-olive">{{ __('adminlte::adminlte.icon') }}</label>
                            <select name="icon" class="form-control select2" required>
                                @foreach ($icons as $emoji => $label)
                                    <option value="{{ $emoji }}" {{ old('icon') == $emoji ? 'selected' : '' }}>
                                        {{ $emoji }} {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="order" class="text-olive">{{ __('adminlte::adminlte.order') }}</label>
                            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="content" class="text-olive">{{ __('adminlte::adminlte.contentAR') }}</label>
                            <textarea name="content" class="form-control" rows="4" required>{{ old('content') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="content_en" class="text-olive">{{ __('adminlte::adminlte.contentEN') }}</label>
                            <textarea name="content_en" class="form-control" rows="4" style="text-align: left; direction: ltr;">{{ old('content_en') }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success btn-lg px-5">
                        <i class="fas fa-save"></i> {{ __('adminlte::adminlte.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
@stop

@section('plugins.Select2', true)

@section('adminlte_js')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
            });
        });
    </script>
@stop
