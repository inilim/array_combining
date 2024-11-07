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
}
