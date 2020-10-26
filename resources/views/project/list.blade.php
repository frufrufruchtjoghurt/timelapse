@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Systeme</div>

                <div class="card-body">
                  <input class="form-control col-md-3 search-input" type="text" placeholder="Projektnr., Name oder ID suchen..."/>

                  <table class="table table-sortable table-responsive">
                    <thead>
                      <tr>
                        <th scope="col">Projektnr.</th>
                        <th class="searchable" scope="col">Projektname</th>
                        <th class="searchable" scope="col">System</th>
                        <th class="searchable" scope="col">Kamera</th>
                        <th scope="col">Start</th>
                        <th scope="col">Ende</th>
                        <th scope="col">Sichtbarkeit</th>
                        <th class="no-sort" scope="col"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($projects as $project)
                        <tr>
                          <td>
                            {{ $project->project_nr }}
                          </td>
                          <td>
                            {{ $project->name }}
                          </td>
                          <td>
                            {{ $project->system }}
                          </td>
                          <td>
                            {{ $project->camera }}
                          </td>
                          <td>
                            {{ $project->start_date }}
                          </td>
                          <td>
                            @if (!$project->end_date)
                              -
                            @else
                              {{ $project->end_date }}
                            @endif
                          </td>
                          <td>
                            @if ($project->invisible)
                              nur Verwaltung
                            @else
                              alle Projektkunden
                            @endif
                          </td>
                          <td>
                            <form action="{{ route('project.show', ['id' => $project->project_nr]) }}" method="GET">
                                <button class="btn btn-primary">DETAILS</button>
                            </form>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{ asset('js/table.js') }}"></script>
@endsection
