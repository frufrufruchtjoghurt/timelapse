@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Firmenübersicht</div>

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
                      @foreach ($companies as $company)
                        <li class="list-group-item">
                          <form action="{{ route('company.destroy', $company->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <p>
                              @foreach ($addresses as $address)
                                @if ($company->aid == $address->id)
                                  {{ $company->name }} - {{ $address->postcode }} {{ $address->city }} - {{ $address->country}}
                                @endif
                              @endforeach
                              <button class="btn btn-primary ml-auto float-right">LÖSCHEN</button>
                            </p>
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
