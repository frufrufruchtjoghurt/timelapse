<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use App\Models\Song;
use App\Orchid\Layouts\Project\ProjectSongLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ProjectTimelapseSongScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = ' - Musiktitel';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Musiktitelauswahl für den Zeitraffer-Film';

    public $project_id;

    /**
     * @var array
     */
    public $permission = [
        'admin',
        'manager'
    ];

    /**
     * Query data.
     *
     * @return array
     */
    public function query($id): array
    {
        $project = Auth::user()->projects()->where('projects.id', '=', $id)->get()->first();

        if (Auth::user()->hasAccess('manager') || Auth::user()->hasAccess('admin'))
            $project = Project::query()->where('projects.id', '=', $id)->get()->first();

        $this->name = $project->name . $this->name;

        $this->project_id = $project->id;

        return [
            'songs' => Song::query()->where('for_imagefilm', '=', false)
                        ->filters()
                        ->defaultSort('id')
                        ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            ProjectSongLayout::class,
        ];
    }

    public function selectSong(Request $request)
    {
        $project = Project::query()->findOrFail(explode('/', explode('song/',$request->url())[1])[0]);

        DB::table('project_songs')->updateOrInsert([
            'project_id' => $project->id,
            'is_imagefilm' => $request->for_imagefilm,
            ], [
            'project_id' => $project->id,
            'song_id' => $request->song_id,
            'is_imagefilm' => $request->for_imagefilm,
        ]);

        redirect()->route('platform.view', ['id' => $project->id]);

        Toast::success(__('Musikauswahl wurde gespeichert!'));
    }
}
