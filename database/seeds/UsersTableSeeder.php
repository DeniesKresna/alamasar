<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('religions')->truncate();
        DB::table('educations')->truncate();
        DB::table('nations')->truncate();
        DB::table('occupations')->truncate();

    	DB::table('users')->insert([[
            'name' => 'Administrator',
            'email' => 'administrator@gmail.com',
            'password' => app('hash')->make('12345'),
            'sex' => 'L',
            'contact' => '081357006001',
            'level' => 1,
            'photo' => '1.jpg',
            'creator_id' => 1,
            'updater_id' => 1,
            'isActive' => 0
        ],
        ]);
        DB::table('religions')->insert([[
            'religion' => 'Islam',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'religion' => 'Kristen',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'religion' => 'Katolik',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'religion' => 'Hindhu',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'religion' => 'Budha',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        ]);
        DB::table('nations')->insert([[
            'nation' => 'Indonesia',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'nation' => 'Singapura',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'nation' => 'Australia',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'nation' => 'Malaysia',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'nation' => 'Filipina',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        ]);

        DB::table('educations')->insert([[
            'education' => 'SMP',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'education' => 'SMA',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'education' => 'D1/D2/D3',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'education' => 'S1',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'education' => 'S2',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        ]);
        DB::table('occupations')->insert([[
            'occupation' => 'Pegawai Negeri Sipil',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'occupation' => 'Karyawan Swasta',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'occupation' => 'Wiraswasta',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'occupation' => 'Kepolisian/Militer',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'occupation' => 'Freelancer',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        ]);
        DB::table('cards')->insert([[
            'card' => 'KTP',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'card' => 'SIM',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        ]);

        DB::table('years')->insert([[
            'year' => '2018/2019 Semester 1',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ],
        [
            'year' => '2018/2019 Semester 2',
            'isActive' => 1,
            'creator_id' => 1,
            'updater_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]
        ]);
    }
}
