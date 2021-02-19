<div class="col p-0 px-3">
    <legend class="text-black">
        @if (count($moviePaths) > 1)
            {{ $project->name . ' - aktuelle Filme' }}
        @else
            {{ $project->name . ' - aktueller Film' }}
        @endif
    </legend>
</div>

<div class="bg-white rounded shadow-sm p-4 py-4 d-flex flex-column">
    @if(!empty($moviePaths))
        @foreach($moviePaths as $moviePath)
            <video controls controlsList="nodownload" muted loop>
                <source src="{{ url($moviePath) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
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
