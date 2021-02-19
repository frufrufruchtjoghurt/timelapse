<?php

namespace App\Orchid\Layouts;

use Orchid\Screen\Actions\Button;
use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Persona;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ReusableComponentListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target;

    private $name;

    private $is_sim;

    private $is_router;

    public function __construct(string $target, string $name, bool $is_sim = false, bool $is_router = false)
    {
        $this->target = $target;
        $this->name = $name;
        $this->is_sim = $is_sim;
        $this->is_router = $is_router;
    }

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): array
    {
        $layout = [TD::make('model', __($this->name))
            ->sort()
            ->cantHide()
            ->filter(TD::FILTER_TEXT)
            ->render(function ($component) {
                return new Persona($component->presenter());
            })];

        if ($this->is_router) {
            array_push($layout,
                TD::make('id', __('Sim-Karte'))
                    ->render(function ($component) {
                        if ($component->simCard() == null)
                            return __('Keine');
                        $simCard = $component->simCard()->get()->first();
                        return empty($simCard) ? __('Keine') : new Persona($simCard->presenter());
                }),
                TD::make('ssid', __('SSID'))
                    ->filter(TD::FILTER_TEXT)
                    ->render(function ($component) {
                        return $component->ssid;
                    })
            );
        }

        array_push($layout,
            TD::make('purchase_date', __('Kaufdatum'))
                ->sort()
                ->filter(TD::FILTER_DATE)
                ->render(function ($component) {
                    return $component->purchase_date->format('Y-m-d');
                }),

            TD::make('age', __('Alter (Jahre)'))
                ->sort()
                ->render(function ($component) {
                    return $component->age();
                })
         );

        if (!$this->is_sim) {
            $layout[] = TD::make('times_used', __('Verwendungen'))
                ->sort()
                ->render(function ($component) {
                    return $component->times_used;
                });
        }

        array_push($layout,
            TD::make('updated_at', __('Zuletzt Bearbeitet'))
                ->sort()
                ->render(function ($component) {
                    if ($component->updated_at == null)
                    {
                        return __('Nie');
                    }
                    return $component->updated_at->format('Y-m-d H:i:s');
                }),

            TD::make('broken', __('Beschädigt'))
                ->sort()
                ->render(function ($component) {
                    return $component->broken ? __('Ja') : __('Nein');
                })
        );

        if ($this->is_sim) {
            $layout[] = TD::make('id', __('Router'))
                ->render(function ($component) {
                    if ($component->router() == null)
                        return __('Im Lager');
                    $router = $component->router()->get()->first();
                    return empty($router) ? __('Im Lager') : new Persona($router->presenter());
                });
        } else {
            $layout[] = TD::make('id', __('Projekt'))
                ->render(function ($component) {
                    if (!$component->projects()->exists())
                        return __('Im Lager');
                    $project = $component->projects()->where('video_editor_send_date', null)->get()->first();
                    return empty($project) ? __('Im Lager') : $project->id;
                });
        }

        $layout[] = TD::make(__('Aktionen'))
            ->align(TD::ALIGN_CENTER)
            ->width('100px')
            ->render(function ($component) {
                return DropDown::make()
                    ->icon('options-vertical')
                    ->list([

                        Link::make(__('Bearbeiten'))
                            ->route('platform.' . $this->target . '.edit', $component->id)
                            ->icon('pencil'),

                        Button::make(__('Löschen'))
                            ->method('remove')
                            ->confirm(__('Möchten Sie diese ' . $this->name . ' wirklich löschen?'))
                            ->parameters([
                                'id' => $component->id,
                            ])
                            ->icon('trash'),

                        Button::make(__('Beschädigt'))
                            ->method('changeBrokenStatus')
                            ->confirm(__('Möchten Sie den Status auf beschädigt ändern?'))
                            ->parameters([
                                'id' => $component->id,
                            ])
                            ->icon('wrench')
                            ->canSee(!$component->broken),

                        Button::make(__('Unbeschädigt'))
                            ->method('changeBrokenStatus')
                            ->confirm(__('Möchten Sie den Status auf unbeschädigt ändern?'))
                            ->parameters([
                                'id' => $component->id,
                            ])
                            ->icon('check')
                            ->canSee($component->broken),
                    ]);
            });

        return $layout;
    }
}
