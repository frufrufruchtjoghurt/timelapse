@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    Sie sind eingeloggt als
                    @can ('isAdmin')
                        Administrator.
                    @endcan
                    @can ('isManager')
                        Manager.
                    @endcan
                    @can ('isBasic')
                        Standardnutzer.
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
