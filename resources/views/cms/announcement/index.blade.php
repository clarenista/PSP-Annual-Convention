@extends('layouts.cms')

@section('title')
Assets
@stop

@section('content')

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active" aria-current="page">Assets</li>
    </ol>
</nav>


<div class="row">
    <div class="col-sm col-lg">
        <form action="{{ route('cms.sponsor.contacts.store') . ( $asset->id ? '/' . $asset->id : '') }}" method="post">
            @csrf
            @isset($asset->id)
            {{ method_field('PUT') }}
            @endisset

            <input type="hidden" name="user_id" value="{{ Auth::id() }}"> <!-- added hidden to get user_id -->
            <input type="hidden" name="type" value="external_link">
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">Name</span>
                    </span>
                    <input type="text" class="form-control" name="name" placeholder="Name" aria-label="Name"
                        value="{{ old('name', $asset->name) }}">
                </div>
            </div>
            <div class="form-group">
                @error('name')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-prepend">
                        <span class="input-group-text">Url</span>
                    </span>
                    <input type="text" class="form-control" name="url" placeholder="Url" aria-label="Url"
                        value="{{ old('url', $asset->url) }}">
                </div>
            </div>
            <div class="form-group">
                @error('url')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <button class="btn btn-info btn-block">&#10004; {{isset($asset->id) ? 'UPDATE CONTACT' : 'ADD CONTACT'}}</button>
            </div>
        </form>
    </div>
</div>

@stop


@section('js')

@stop
