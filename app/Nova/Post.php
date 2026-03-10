<?php

namespace App\Nova;

use Laravel\Nova\Fields\Badge;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class Post extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Post>
     */
    public static $model = \App\Models\Post::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'title';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'title', 'author', 'status', 'category',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Title')
                ->sortable()
                ->rules('required', 'max:255'),

            Slug::make('Slug')
                ->from('Title')
                ->rules('required', 'max:255'),

            Text::make('Author')
                ->sortable()
                ->rules('required', 'max:255'),

            Select::make('Status')
                ->options([
                    'draft'     => 'Draft',
                    'published' => 'Published',
                    'archived'  => 'Archived',
                ])
                ->rules('required')
                ->displayUsingLabels()
                ->sortable(),

            Badge::make('Status')->map([
                'draft'     => 'warning',
                'published' => 'success',
                'archived'  => 'danger',
            ])->onlyOnIndex(),

            Select::make('Category')
                ->options([
                    'technology'  => 'Technology',
                    'business'    => 'Business',
                    'lifestyle'   => 'Lifestyle',
                    'health'      => 'Health',
                    'education'   => 'Education',
                    'travel'      => 'Travel',
                    'other'       => 'Other',
                ])
                ->displayUsingLabels()
                ->rules('required'),

            Boolean::make('Is Featured', 'is_featured')
                ->sortable(),

            Trix::make('Content')
                ->rules('required')
                ->hideFromIndex(),

            Textarea::make('Excerpt')
                ->rows(3)
                ->hideFromIndex(),

            Text::make('Meta Title')
                ->hideFromIndex()
                ->nullable(),

            Textarea::make('Meta Description')
                ->rows(3)
                ->hideFromIndex()
                ->nullable(),

            Number::make('Views')
                ->default(0)
                ->sortable()
                ->nullable(),

            Number::make('Rating')
                ->step(0.1)
                ->min(0)
                ->max(5)
                ->nullable()
                ->hideFromIndex(),

            Number::make('Reading Time')
                ->nullable()
                ->hideFromIndex(),

            Image::make('Featured Image', 'featured_image')
                ->nullable()
                ->hideFromIndex(),

            DateTime::make('Published At')
                ->sortable()
                ->nullable(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
