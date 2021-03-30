<?php

namespace App\Orchid\Resources;

use App\Models\Song;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Orchid\Crud\Resource;
use Orchid\Crud\ResourceRequest;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\TD;

class SongResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Song::class;

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return __('Musiktitel');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return __('Musiktitel');
    }

    /**
     * Get the permission key for the resource.
     *
     * @return string|null
     */
    public static function permission(): ?string
    {
        return null;
    }

    /**
     * Get the resource should be displayed in the navigation
     *
     * @return bool
     */
    public static function displayInNavigation(): bool
    {
        return false;
    }

    /**
     * Get the text for the list breadcrumbs.
     *
     * @return string
     */
    public static function listBreadcrumbsMessage(): string
    {
        return static::label();
    }

    /**
     * Get the text for the create breadcrumbs.
     *
     * @return string
     */
    public static function createBreadcrumbsMessage(): string
    {
        return __('Neuer :resource', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the edit breadcrumbs.
     *
     * @return string
     */
    public static function editBreadcrumbsMessage(): string
    {
        return __(':resource bearbeiten', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the create resource button.
     *
     * @return string|null
     */
    public static function createButtonLabel(): string
    {
        return __(':resource eintragen', ['resource' => static::singularLabel()]);
    }

    public static function saveButtonLabel(): string
    {
        return __(':resource speichern', ['resource' => static::singularLabel()]);
    }

    public static function deleteButtonLabel(): string
    {
        return __(':resource löschen', ['resource' => static::singularLabel()]);
    }

    public static function updateButtonLabel(): string
    {
        return __(':resource speichern', ['resource' => static::singularLabel()]);
    }

    public static function restoreButtonLabel(): string
    {
        return __(':resource wiederherstellen', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the create resource toast.
     *
     * @return string
     */
    public static function createToastMessage(): string
    {
        return __('Der :resource wurde erstellt!', ['resource' => static::singularLabel()]);
    }

    public static function updateToastMessage(): string
    {
        return __('Der :resource wurde geändert!', ['resource' => static::singularLabel()]);
    }

    public static function deleteToastMessage(): string
    {
        return __('Der :resource wurde gelöscht!', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('title')
                ->title(__('Titel'))
                ->placeholder(__('Titel des Musikstücks hier eingeben'))
                ->required(),

            Input::make('genre')
                ->title(__('Genre'))
                ->placeholder(__('Genre des Musikstücks hier eingeben'))
                ->required(),

            Input::make('embed_tag')
                ->title(__('YouTube Video-ID'))
                ->placeholder(__('Video-ID hier einfügen'))
                ->help(__('Die Video-ID ist der letzte Teil eines YouTube-Links und steht meist nach einem \'v=\'
                z.B.: https://www.youtube.com/watch?v=lEKtVMbNPnQ (die Video-ID ist \'lEKtVMbNPnQ\').'))
                ->required(),

            CheckBox::make('for_imagefilm')
                ->title(__('Musik für Imagefilm?'))
                ->sendTrueOrFalse(),
        ];
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    public function columns(): array
    {
        return [
            TD::make('id'),

            TD::make('title', __('Titel')),

            TD::make('genre', __('Genre')),

            TD::make('embed_tag', __('YouTube Video-ID')),

            TD::make('for_imagefilm', __('Imagefilm?')),

            TD::make('created_at', __('Erstelldatum'))
                ->render(function ($model) {
                    if ($model->created_at == null)
                        return "Nie";
                    return $model->created_at->toDateTimeString();
                }),

            TD::make('updated_at', __('Änderungsdatum'))
                ->render(function ($model) {
                    if ($model->created_at == null)
                        return "Nie";
                    return $model->updated_at->toDateTimeString();
                }),
        ];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Action to create and update the model
     *
     * @param ResourceRequest $request
     * @param Model           $model
     */
    public function onSave(ResourceRequest $request, Model $model)
    {
        $model->forceFill($request->all())->save();
    }

    /**
     * Action to delete a model
     *
     * @param Model $model
     *
     * @throws Exception
     */
    public function onDelete(Model $model)
    {
        $model->delete();
    }
}
