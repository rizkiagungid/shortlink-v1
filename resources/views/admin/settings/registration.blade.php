@section('site_title', formatTitle([__('Registration'), __('Settings'), config('settings.title')]))

@include('shared.breadcrumbs', ['breadcrumbs' => [
    ['url' => route('admin.dashboard'), 'title' => __('Admin')],
    ['title' => __('Settings')],
]])

<h1 class="h2 mb-3 d-inline-block">{{ __('Registration') }}</h1>

<div class="card border-0 shadow-sm">
    <div class="card-header"><div class="font-weight-medium py-1">{{ __('Registration') }}</div></div>
    <div class="card-body">

        @include('shared.message')

        <form action="{{ route('admin.settings', 'registration') }}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group">
                <label for="i-registration">{{ __('Registration') }}</label>
                <select name="registration" id="i-registration" class="custom-select{{ $errors->has('registration') ? ' is-invalid' : '' }}">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if ((old('registration') !== null && old('registration') == $key) || (config('settings.registration') == $key && old('registration') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('registration'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('registration') }}</strong>
                    </span>
                @endif
            </div>

            <div class="form-group">
                <label for="i-registration-verification">{{ __('Email verification') }}</label>
                <select name="registration_verification" id="i-registration-verification" class="custom-select{{ $errors->has('registration_verification') ? ' is-invalid' : '' }}">
                    @foreach([0 => __('Disabled'), 1 => __('Enabled')] as $key => $value)
                        <option value="{{ $key }}" @if ((old('registration_verification') !== null && old('registration_verification') == $key) || (config('settings.registration_verification') == $key && old('registration_verification') == null)) selected @endif>{{ $value }}</option>
                    @endforeach
                </select>
                @if ($errors->has('registration_verification'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('registration_verification') }}</strong>
                    </span>
                @endif
            </div>

            <button type="submit" name="submit" class="btn btn-primary">{{ __('Save') }}</button>
        </form>

    </div>
</div>