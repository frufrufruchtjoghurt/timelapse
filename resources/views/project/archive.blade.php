@foreach($symlinks as $id => $symlink)
    <a href="{{ url($symlink) }}" class="btn btn-xs btn-success pull-left">
        <x-orchid-icon path="camrecorder"/> &#8287; Kamera {{ $id }}
    </a>
@endforeach
