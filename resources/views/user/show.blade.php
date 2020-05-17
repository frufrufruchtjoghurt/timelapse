@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Benutzerübersicht</div>

                <div class="card-body">
                  @if (session('success'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                      {{ session('success') }}
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                @elseif (session('failure'))
                  <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('failure') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif
                    <ul class="list-group list-group-flush">
                      @foreach ($users as $user)
                        <li class="list-group-item">
                          <form action="{{ route('user.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            {{ $user->first_name }} {{ $user->last_name }} - {{ $user->email }} - <span class="text-capitalize">{{ $user->role }}</span>
                            @if ($user->role == 'admin' && Auth::user()->role != 'admin')
                              <button class="btn btn-primary ml-auto float-right" disabled>LÖSCHEN</button>
                            @else
                              <button class="btn btn-primary ml-auto float-right">LÖSCHEN</button>
                            @endif
                          </form>
                        </li>
                      @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
