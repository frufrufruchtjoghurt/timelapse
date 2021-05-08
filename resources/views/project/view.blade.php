<div class="col p-0 px-3">
    <legend class="text-black">
        @if (count($moviePaths) > 1)
            {{ $project->name . ' - aktuelle Filme' }}
        @else
            {{ $project->name . ' - aktueller Film' }}
        @endif
        <div class="text-sm">
            der letzten 14 Tage
        </div>
    </legend>
</div>

<div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
    @if(!empty($moviePaths))
      view  @foreach($moviePaths as $moviePath)
            <img src="{{ url($moviePath) }}" alt="Aktuelles Video von {{ $project->name }}">
            @if($moviePath != last($moviePaths))
                <br>
            @endif
        @endforeach
    @else
        <div class="text-center py-5 w-100">
            <h3 class="font-weight-light">
                Leider gibt es keinen aktuellen Film für dieses Projekt!
            </h3>
        </div>
    @endif
</div>

<br>
<div class="col p-0 px-3">
    <legend class="text-black">
        @if (count($picturePaths) > 1)
            {{ $project->name . ' - aktuelle Bilder' }}
        @else
            {{ $project->name . ' - aktuelles Bild' }}
        @endif
        @if ($project->id == 10)
            <div class="text-sm">
                8 Minuten Aufnahmeintervall
            </div>
        @endif
    </legend>
</div>

<div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
    @if(!empty($picturePaths))
        @foreach($picturePaths as $picturePath)
            <img src="{{ url($picturePath) }}" alt="Aktuelles Bild von {{ $project->name }}">
            @if($picturePath != last($picturePaths))
                <br>
            @endif
        @endforeach
    @else
        <div class="text-center py-5 w-100">
            <h3 class="font-weight-light">
                Leider gibt es kein aktuelles Bild für dieses Projekt!
            </h3>
        </div>
    @endif
</div>

<br/>
<div class="col p-0 px-3">
    <legend class="text-black">
        Musiktitel
    </legend>
</div>

@if(count($songs) == 0)
    <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
        <div class="col-auto ml-auto text-right">
            <div class="btn-group command-bar">
                <a href="{{ url('/timelapsesong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                    <x-orchid-icon path="playlist"/> &#8287; Musikauswahl (Zeitrafferfilm)
                </a>
            </div>
        </div>
        @if ($project->has_imagefilm)
            <div class="col-auto ml-auto text-right">
                <div class="btn-group command-bar">
                    <a href="{{ url('/imagefilmsong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                        <x-orchid-icon path="playlist"/> &#8287; Musikauswahl (Imagefilm)
                    </a>
                </div>
            </div>
        @endif
    </div>
@elseif(count($songs) == 1)
    <div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
    @if(!$songs->first()->for_imagefilm)
        <div class="row no-gutters">
            <div class="col">
                <div class="card-body h-full p-4">
                    <div class="row d-flex align-items-center">
                        <div class="col-auto text-center">
                            <h5 class="card-title">
                                Zeitraffer: {{ $songs->first()->title }}
                            </h5>
                        </div>

                        <div class="col-auto ml-auto">
                            <iframe width ="360" height="100" src="https://www.youtube.com/embed/{{ $songs->first()->embed_tag }}" frameborder="0"
                                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>

                        <div class="col-auto ml-auto text-right">
                            <div class="btn-group command-bar">
                                <a href="{{ url('/timelapsesong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                                    <x-orchid-icon path="playlist"/> &#8287; Auswahl ändern
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if ($project->has_imagefilm)
            <div class="col-auto ml-auto text-right">
                <div class="btn-group command-bar">
                    <a href="{{ url('/imagefilmsong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                        <x-orchid-icon path="playlist"/> &#8287; Musikauswahl (Imagefilm)
                    </a>
                </div>
            </div>
        @endif
    @else
        <div class="col-auto ml-auto text-right">
            <div class="btn-group command-bar">
                <a href="{{ url('/timelapsesong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                    <x-orchid-icon path="playlist"/> &#8287; Musikauswahl (Zeitrafferfilm)
                </a>
            </div>
        </div>
        <div class="row no-gutters">
            <div class="col">
                <div class="card-body h-full p-4">
                    <div class="row d-flex align-items-center">
                        <div class="col-auto text-center">
                            <h5 class="card-title">
                                Imagefilm: {{ $songs->first()->title }}
                            </h5>
                        </div>

                        <div class="col-auto ml-auto">
                            <iframe width ="360" height="100" src="https://www.youtube.com/embed/{{ $songs->first()->embed_tag }}" frameborder="0"
                                    allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                        </div>

                        <div class="col-auto ml-auto text-right">
                            <div class="btn-group command-bar">
                                <a href="{{ url('/imagefilmsong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                                    <x-orchid-icon path="playlist"/> &#8287; Auswahl ändern
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>
@else
<div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
    @foreach($songs as $song)
    <div class="row no-gutters">
        <div class="col">
            <div class="card-body h-full p-4">
                <div class="row d-flex align-items-center">
                    <div class="col-auto text-center">
                        <h5 class="card-title">
                            @if($song->for_imagefilm)
                                Imagefilm: {{ $song->title }}
                            @else
                                Zeitraffer: {{ $song->title }}
                            @endif
                        </h5>
                    </div>

                    <div class="col-auto ml-auto">
                        <iframe width ="360" height="100" src="https://www.youtube.com/embed/{{ $song->embed_tag }}" frameborder="0"
                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>

                    <div class="col-auto ml-auto text-right">
                        <div class="btn-group command-bar">
                            @if($song->for_imagefilm)
                                <a href="{{ url('/imagefilmsong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                                    <x-orchid-icon path="playlist"/> &#8287; Auswahl ändern
                                </a>
                            @else
                                <a href="{{ url('/timelapsesong/' . $project->id) }}" class="btn btn-xs btn-success pull-right">
                                    <x-orchid-icon path="playlist"/> &#8287; Auswahl ändern
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
