<?php

namespace App\Orchid\Layouts\Project;

use App\Models\Song;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ProjectSongLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'songs';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        return [
            /*TD::make('id', __('ID'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),*/

            TD::make('title', __('Titel'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::make('genre', __('Genre'))
                ->cantHide()
                ->filter(TD::FILTER_TEXT)
                ->sort(),

            TD::make('embed_tag', __('Wiedergabe'))
                ->player(),

            TD::make(__('Aktionen'))
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (Song $song) {
                    return DropDown::make()
                        ->icon('options-vertical')
                        ->list([
                            Button::make(__('Auswählen'))
                                ->method('selectSong')
                                ->confirm(__('Möchten Sie diesen Musiktitel auswählen?'))
                                ->parameters([
                                    'song_id' => $song->id,
                                    'for_imagefilm' => $song->for_imagefilm
                                ])
                                ->icon('check'),
                        ]);
                }),
        ];
    }
}
