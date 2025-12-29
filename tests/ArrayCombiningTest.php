<?php

namespace Inilim\Test;

use Inilim\ArrayCombining\ArrayCombining;
use Inilim\Test\TestCase;

final class ArrayCombiningTest extends TestCase
{
    function test_one_to_many()
    {
        $posts    = include __DIR__ . '/Data/Post.php';
        $comments = include __DIR__ . '/Data/Comment.php';
        $complete = include __DIR__ . '/Data/CompletePostAndComment.php';

        $s = new ArrayCombining;

        $result = $s->oneToMany($posts, $comments, 'id', 'postId', 'comments');

        $this->assertSame($result, $complete);
    }

    function test_one_to_one()
    {
        $posts    = include __DIR__ . '/Data/Post.php';
        $users    = include __DIR__ . '/Data/User.php';
        $complete = include __DIR__ . '/Data/CompletePostAndUser.php';

        $s = new ArrayCombining;

        $result = $s->oneToOne($posts, $users, 'userId', 'id', 'user');

        $this->assertSame($result, $complete);
    }
    function test_one_to_many_with_except_keys()
    {
        $posts = [
            ['id' => 1, 'title' => 'Post 1', 'userId' => 1],
            ['id' => 2, 'title' => 'Post 2', 'userId' => 1],
            ['id' => 3, 'title' => 'Post 3', 'userId' => 2],
        ];
        $comments = [
            ['id' => 1, 'postId' => 1, 'body' => 'Comment 1'],
            ['id' => 2, 'postId' => 1, 'body' => 'Comment 2'],
            ['id' => 3, 'postId' => 2, 'body' => 'Comment 3'],
        ];
        $expected = [
            ['id' => 1, 'comments' => [['postId' => 1, 'body' => 'Comment 1'], ['postId' => 1, 'body' => 'Comment 2']]],
            ['id' => 2, 'comments' => [['postId' => 2, 'body' => 'Comment 3']]],
            ['id' => 3, 'comments' => []],
        ];

        $s = new ArrayCombining;

        $result = $s->oneToMany($posts, $comments, 'id', 'postId', 'comments', ['title', 'userId'], ['id']);

        $this->assertSame($result, $expected);
    }

    function test_one_to_one_with_except_keys()
    {
        $posts = [
            ['id' => 1, 'title' => 'Post 1', 'userId' => 1, 'postId' => 1],
            ['id' => 2, 'title' => 'Post 2', 'userId' => 2, 'postId' => 2],
            ['id' => 3, 'title' => 'Post 3', 'userId' => 3, 'postId' => 3],
        ];
        $users = [
            ['id' => 1, 'name' => 'User 1', 'email' => 'user1@example.com'],
            ['id' => 2, 'name' => 'User 2', 'email' => 'user2@example.com'],
            ['id' => 3, 'name' => 'User 3', 'email' => 'user3@example.com'],
        ];
        $expected = [
            ['id' => 1, 'user' => ['name' => 'User 1', 'email' => 'user1@example.com']],
            ['id' => 2, 'user' => ['name' => 'User 2', 'email' => 'user2@example.com']],
            ['id' => 3, 'user' => ['name' => 'User 3', 'email' => 'user3@example.com']],
        ];

        $s = new ArrayCombining;

        $result = $s->oneToOne($posts, $users, 'userId', 'id', 'user', ['title', 'postId', 'userId'], ['id']);

        $this->assertSame($result, $expected);
    }
}
