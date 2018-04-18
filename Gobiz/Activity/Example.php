<?php

namespace Gobiz\Activity;

class Example
{
    public static function example()
    {
        // Tạo activity
        $activity = new Activity([
            'creator' => [
                'id' => 111,
                'username' => 'admin',
                'name' => 'Full name',
                'partner_id' => 222,
            ],
            'action' => 'USER.CREATE',
            'objects' => [
                'user' => 333,
            ],
            'description' => 'Admin "admin" tạo thành viên mới "user1"',
            'time' => new \DateTime(), // Nếu không khai báo thì mặc định là now
            'payload' => [
                'new_user' => [
                    'id' => 333,
                    'username' => 'user1',
                ],
            ],
        ]);

        // Push activity vào kafka
        ActivityService::dispatch($activity);

        // Lưu trực tiếp vào elastic search
        ActivityService::log($activity);
    }
}