<?php

namespace App\Orchid\Layouts\Project;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Field;
use Orchid\Screen\Fields\Group;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class ProjectMovieDeeplinkLayout extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title = 'Film-Deeplinks';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): array
    {
        $rows = array();

        foreach ($this->query->get('movie_deeplinks') as $cam_id => $symlink) {
            $rows[] = Group::make([
                Input::make(sprintf('cam%d.moviedl', $cam_id))
                    ->title(__(sprintf('Kamera Nr. %d - Deeplink', $cam_id + 10)))
                    ->value($symlink)
                    ->type('text'),

                Button::make(sprintf('cam%d.moviegen', $cam_id))
                    ->name(__('Generieren'))
                    ->icon('loading')
                    ->canSee(empty($symlink))
                    ->method('generate')
                    ->parameters([$this->query->get('project')->id, $cam_id, true]),

                Button::make(sprintf('cam%d.moviedel', $cam_id))
                    ->name(__('Löschen'))
                    ->icon('trash')
                    ->canSee(!empty($symlink))
                    ->method('delete')
                    ->parameters([$this->query->get('project')->id, $cam_id, true]),
            ])->alignCenter();
        }
        return $rows;
    }
}
