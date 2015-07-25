<?php

use Illuminate\Database\Seeder;
use App\Event;

// composer require laracasts/testdummy
use Laracasts\TestDummy\Factory as TestDummy;

class EventsTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('events')->delete();

        Event::create(array(
            'code' => 'test1',
            'event_name' => 'This is a test event',
            'event_place' => 'Holy Angel University, Angeles City, Philippines',
            'event_date' => '2015-01-01',
            'cert_type' => 'attendance',
            'theme' => 'default',
            'filename_prefix' => 'ca_test_',
            'participants' => '',
            'user_id' => 1,
        ));
    }
}
