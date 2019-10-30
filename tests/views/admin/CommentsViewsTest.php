<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\Comment;
use App\User;
use App\Role;

class AdminCommentsViewsTest extends BrowserKitTest
{
    use DatabaseMigrations;

    public function testCommentIndexAuthorLink()
    {
        $admin = factory(User::class)->create();
        $admin->roles()->attach(factory(Role::class)->states('admin')->create());
        $comments = factory(Comment::class, 10)->create();

        $this->actingAs($admin)
            ->visit(route('admin.comments.index'))
            ->click(user_name($comments->first()->author))
            ->seeRouteIs('users.show', $comments->first()->author);
    }

    public function testCommentIndexPostLink()
    {
        $admin = factory(User::class)->create();
        $admin->roles()->attach(factory(Role::class)->states('admin')->create());
        $comments = factory(Comment::class, 10)->create();

        $this->actingAs($admin)
            ->visit(route('admin.comments.index'))
            ->click($comments->first()->post->title)
            ->seeRouteIs('posts.show', $comments->first()->post);
    }

    public function testCommentIndex()
    {
        $admin = factory(User::class)->create();
        $admin->roles()->attach(factory(Role::class)->states('admin')->create());
        $comments = factory(Comment::class, 10)->create();

        $this->actingAs($admin)
            ->visit(route('admin.comments.index'))
            ->see(trans_choice('comments.count', $comments->count()))
            ->see($comments->first()->content);
    }

    public function testCommentEdit()
    {
        $admin = factory(User::class)->create();
        $admin->roles()->attach(factory(Role::class)->states('admin')->create());
        $comment = factory(Comment::class)->create();

        $this->actingAs($admin)
            ->visit(route('admin.comments.edit', $comment))
            ->see($comment->post->title)
            ->see($comment->content)
            ->see(humanize_date($comment->posted_at, 'd/m/Y H:i:s'))
            ->see(__('forms.actions.update'));
    }
}
