<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ConvertData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $alldata = DB::table('hotel')->get();
        if (!$alldata) throw new Exception('content not found');

        foreach ($alldata as $item) {
            $date = str_replace([' de '],['-'],$item->data_review);
            //$data = \Carbon\Carbon::createFromTimeString($date);
            // Hotel
            $hotel = DB::table('hotels')->where('name','=',$item->hotel)->first();
            if (!$hotel) {
                // create hotel
                $hotel_id = DB::table('hotels')->insertGetId([
                    'name' => $item->hotel,
                    'active' => 1
                ]);
                $hotel = DB::table('hotels')->find($hotel_id);
            }

            // Platform
            $platform = DB::table('platforms')->where('name','=',$item->plataforma)->first();
            if (!$platform) {
                // create hotel
                $platform_id = DB::table('platforms')->insertGetId([
                    'name' => $item->plataforma,
                    'active' => 1
                ]);
                $platform = DB::table('platforms')->find($platform_id);
            }

            // create review
            $review_id = DB::table('reviews')->insertGetId([
                'positive_description' => $item->comentario_positivo,
                'negative_description' => $item->comentario_negativo,
                'score' => str_replace([','],['.'],$item->nota),
                'evaluation' => $item->avaliacao,
                'title' => $item->titulo,
                'language' => 'pt-BR',
                'hotel_id' => $hotel->id,
                'platform_id' => $platform->id,
                'created_at' => $item->data
            ]);
        }

        // default user
        $profile = DB::table('profiles')->where('name','administrator')->first();
        if (!$profile) {
            $profile_id = DB::table('profiles')->insertGetId([
                'name' => 'administrator'
            ]);
            $profile = DB::table('profiles')->find($profile_id);
        }


        $user = DB::table('users')->find(1);
        if (!$user) {
            $user_id = DB::table('users')->insertGetId([
                'name' => 'Alfredd Admin',
                'username' => 'alfredd',
                'password' => Hash::make('teste'),
                'cpf' => '00011122233',
                'profile_id' => $profile->id,
                'hotel_id' => 1
            ]);
            $user = DB::table('users')->find($user_id);
        }


    }
}
