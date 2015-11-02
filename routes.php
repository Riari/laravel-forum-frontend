<?php

// Forum index
$r->get('/', ['as' => 'index', 'uses' => "{$controllers['category']}@index"]);

// New/updated threads
$r->get('new', ['as' => 'index-new', 'uses' => "{$controllers['thread']}@indexNew"]);
$r->patch('new', ['as' => 'mark-new', 'uses' => "{$controllers['thread']}@markNew"]);

// Categories
$r->group(['prefix' => '{category}-{category_slug}'], function ($r) use ($controllers)
{
    $r->get('/', ['as' => 'category.index', 'uses' => "{$controllers['category']}@show"]);

    // Threads
    $r->get('{thread}-{thread_slug}', ['as' => 'thread.show', 'uses' => "{$controllers['thread']}@show"]);
    $r->get('thread/create', ['as' => 'thread.create', 'uses' => "{$controllers['thread']}@create"]);

    // Posts
    $r->get('{thread}-{thread_slug}/post/{post}', ['as' => 'post.show', 'uses' => "{$controllers['post']}@show"]);
    $r->get('{thread}-{thread_slug}/reply', ['as' => 'post.create', 'uses' => "{$controllers['post']}@create"]);
    $r->get('{thread}-{thread_slug}/post/{post}/edit', ['as' => 'post.edit', 'uses' => "{$controllers['post']}@edit"]);
});

// Actions
$r->post('category/create', ['as' => 'category.store', 'uses' => "{$controllers['category']}@store"]);
$r->patch('category/{category}', ['as' => 'category.update', 'uses' => "{$controllers['category']}@update"]);
$r->delete('category/{category}', ['as' => 'category.delete', 'uses' => "{$controllers['category']}@destroy"]);
$r->post('category/{category}/thread/create', ['as' => 'thread.store', 'uses' => "{$controllers['thread']}@store"]);
$r->patch('thread/{thread}', ['as' => 'thread.update', 'uses' => "{$controllers['thread']}@update"]);
$r->delete('thread/{thread}', ['as' => 'thread.delete', 'uses' => "{$controllers['thread']}@destroy"]);
$r->post('thread/{thread}/reply', ['as' => 'post.store', 'uses' => "{$controllers['post']}@store"]);
$r->patch('post/{post}', ['as' => 'post.update', 'uses' => "{$controllers['post']}@update"]);
$r->delete('post/{post}', ['as' => 'post.delete', 'uses' => "{$controllers['post']}@destroy"]);

// Bulk actions
$r->group(['prefix' => 'bulk', 'as' => 'bulk.'], function ($r) use ($controllers)
{
    $r->patch('thread', ['as' => 'thread.update', 'uses' => "{$controllers['thread']}@bulkUpdate"]);
    $r->delete('thread', ['as' => 'thread.delete', 'uses' => "{$controllers['thread']}@bulkDestroy"]);
    $r->patch('post', ['as' => 'post.update', 'uses' => "{$controllers['post']}@bulkUpdate"]);
    $r->delete('post', ['as' => 'post.delete', 'uses' => "{$controllers['post']}@bulkDestroy"]);
});
