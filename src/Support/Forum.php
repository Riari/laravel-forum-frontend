<?php

namespace Riari\Forum\Frontend\Support;

use ReflectionClass;
use Session;

class Forum
{
    /**
     * Process an alert message to display to the user.
     *
     * @param  string  $type
     * @param  string  $transKey
     * @param  string  $transCount
     * @return void
     */
    public static function alert($type, $transKey, $transCount = 1, $transParameters = [])
    {
        $alerts = [];
        if (Session::has('alerts')) {
            $alerts = Session::get('alerts');
        }

        $message = trans_choice("forum::{$transKey}", $transCount, $transParameters);

        array_push($alerts, compact('type', 'message'));

        Session::flash('alerts', $alerts);
    }

    /**
     * Render the given content.
     *
     * @param  string  $content
     * @return string
     */
    public static function render($content)
    {
        return nl2br(e($content));
    }

    /**
     * Generate a URL to a named forum route.
     *
     * @param  string  $route
     * @param  null|\Illuminate\Database\Eloquent\Model  $model
     * @return string
     */
    public static function route($route, $model = null)
    {
        if (!starts_with($route, 'forum.')) {
            $route = "forum.{$route}";
        }

        $params = [];
        $append = '';

        if ($model) {
            $class = new ReflectionClass($model);
            switch ($class->getShortName()) {
                case 'Category':
                    $params = [
                        'category'      => $model->id,
                        'category_slug' => $model->slug
                    ];
                    break;
                case 'Thread':
                    $params = [
                        'category'      => $model->category->id,
                        'category_slug' => $model->category->slug,
                        'thread'        => $model->id,
                        'thread_slug'   => $model->slug
                    ];
                    break;
                case 'Post':
                    $params = [
                        'category'      => $model->thread->category->id,
                        'category_slug' => $model->thread->category->slug,
                        'thread'        => $model->thread->id,
                        'thread_slug'   => $model->thread->slug
                    ];

                    if ($route == 'forum.thread.show') {
                        // The requested route is for a thread; we need to specify the page number and append a hash for
                        // the post
                        $params['page'] = ceil(($model->thread->posts()->lists('id')->search($model->id) + 1) / $model->getPerPage());
                        $append = "#post-{$model->id}";
                    } else {
                        // Other post routes require the post parameter
                        $params['post'] = $model->id;
                    }
                    break;
            }
        }

        return route($route, $params) . $append;
    }
}
