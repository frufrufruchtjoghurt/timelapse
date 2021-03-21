<?php

namespace App\Orchid\Screens\Project;

use App\Models\Camera;
use App\Models\Project;
use App\Models\Symlink;
use App\Orchid\Layouts\Project\ProjectMovieDeeplinkLayout;
use App\Orchid\Layouts\Project\ProjectPictureDeeplinkLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;

class ProjectDeeplinksScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = '';

    /**
     * Display header description.
     *
     * @var string|null
     */
    public $description = 'Deeplinks';

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

        $this->name = $project->name;

        $userSymlinks = Auth::user()->symlinks()
            ->where('project_id', '=', $project->id)
            ->where('is_persistent', '=', true)->get();
        $picture_deeplinks = array();
        $movie_deeplinks = array();

        foreach ($project->cameras() as $camera) {
            $picture_deeplinks[$camera->id] = '';
            $movie_deeplinks[$camera->id] = '';
        }

        foreach ($userSymlinks as $symlink) {
            if ($symlink->is_movie) {
                $movie_deeplinks[$symlink->camera_id] = url('img/' . $symlink->symlink);
                continue;
            }
            $picture_deeplinks[$symlink->camera_id] = url('img/' . $symlink->symlink);
        }

        return [
            'project' => $project,
            'picture_deeplinks' => $picture_deeplinks,
            'movie_deeplinks' => $movie_deeplinks,
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
            ProjectMovieDeeplinkLayout::class,
//            ProjectPictureDeeplinkLayout::class,
        ];
    }

    public function generate($project_id, $cam_id, $is_movie)
    {
        $project = Project::query()->where('id', '=', $project_id)->get()->first();
        $filename = sprintf('pic%03d.jpg', explode('m', Camera::query()->where('id', '=', $cam_id)->get()->first()->name)[1]);
        $path = base_path(sprintf('../../systems/P%04d-%s/latest/%s', $project->id, $project->name,
            $filename));

        $permalink = new Symlink();
        $permalink->user_id = Auth::user()->id;
        $permalink->project_id = $project_id;
        $permalink->camera_id = $cam_id;
        $permalink->is_movie = $is_movie;
        $permalink->is_latest = false;
        $permalink->is_persistent = true;
        $permalink->symlink = Str::random(50) . ($is_movie ? '.gif' : '.jpg');
        $permalink->save();

        symlink($path, public_path(sprintf('img/%s', $permalink->symlink)));

        Alert::success(__('Deeplink wurde erfolgreich erstellt! Sie können den erstellten Link nun kopieren und
            in ihre Seite einbinden.'));
    }

    public function delete($project_id, $cam_id, $is_movie, Request $request) {
        if ($is_movie) {
            $permalink = $request['cam' . $cam_id]['moviedl'];
        } else {
            $permalink = $request['cam' . $cam_id]['picturedl'];
        }
        $permalink = explode('img/', $permalink)[1];

        Symlink::query()->where('symlink', '=', $permalink)
            ->where('project_id', '=', $project_id)
            ->where('camera_id', '=', $cam_id)
            ->where('is_persistent', '=', true)->delete();

        unlink(public_path(sprintf('img/%s', $permalink)));

        Alert::success(__('Deeplink wurde erfolgreich gelöscht! Sie können diesen Link nun nicht mehr verwenden!'));
    }
}
