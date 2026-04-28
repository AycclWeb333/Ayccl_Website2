@extends('adminlte::page')
@section('title', __('adminlte::adminlte.statistics') . ' - ' . __('adminlte::adminlte.createNewPost'))

@section('content_header')
    <h1>{{ __('adminlte::adminlte.createNewPost') }}</h1>
@stop

@section('content')
    <div class="card card-olive">
        <div class="card-header">
            <h3 class="card-title">{{ __('adminlte::adminlte.statistics') }}</h3>
        </div>
        <form action="{{ route('statistics.store', ['locale' => app()->getLocale()]) }}" method="POST">
            @csrf
            <div class="card-body">
                <div class="row">
                    {{-- Number --}}
                    <div class="form-group col-md-4">
                        <label for="number">{{ __('adminlte::adminlte.number') }}</label>
                        <input type="number" step="0.01" name="number" id="number" class="form-control @error('number') is-invalid @enderror" value="{{ old('number') }}" required>
                        @error('number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- Unit AR --}}
                    <div class="form-group col-md-4">
                        <label for="unit_ar">{{ __('adminlte::adminlte.unitAR') }}</label>
                        <input type="text" name="unit_ar" id="unit_ar" class="form-control @error('unit_ar') is-invalid @enderror" value="{{ old('unit_ar') }}" required>
                        @error('unit_ar') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>

                    {{-- Unit EN --}}
                    <div class="form-group col-md-4">
                        <label for="unit_en">{{ __('adminlte::adminlte.unitEN') }}</label>
                        <input type="text" name="unit_en" id="unit_en" class="form-control @error('unit_en') is-invalid @enderror" value="{{ old('unit_en') }}" required>
                        @error('unit_en') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="row">
                    {{-- Description AR --}}
                    <div class="form-group col-md-6">
                        <label for="description_ar">{{ __('adminlte::adminlte.descriptionAR') }}</label>
                        <textarea name="description_ar" id="description_ar" class="form-control" rows="3">{{ old('description_ar') }}</textarea>
                    </div>

                    {{-- Description EN --}}
                    <div class="form-group col-md-6">
                        <label for="description_en">{{ __('adminlte::adminlte.descriptionEN') }}</label>
                        <textarea name="description_en" id="description_en" class="form-control" rows="3">{{ old('description_en') }}</textarea>
                    </div>
                </div>

                <div class="row">
                    {{-- Order --}}
                    <div class="form-group col-md-4">
                        <label for="order">{{ __('adminlte::adminlte.order') }}</label>
                        <input type="number" name="order" id="order" class="form-control" value="{{ old('order', 0) }}">
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success">{{ __('adminlte::adminlte.save') }}</button>
                <a href="{{ route('statistics.index', ['locale' => app()->getLocale()]) }}" class="btn btn-default">{{ __('adminlte::adminlte.cancel') }}</a>
            </div>
        </form>
    </div>
@stop
