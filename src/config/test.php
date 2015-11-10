<?php

$serializer = new SuperClosure\Serializer(null, 'ni-seed-test');

/**
 * example config
 **/
$position1 =  App::make('MedPosition')->orderBy(DB::raw('random()'))->take(1)->first();
$scienceDegreeList  =  App::make('MedScienceDegree')->all();
$doctorSpecList = App::make('MedSpecialization')->all();

return array(
    'tables' => array(
        'user' => $serializer->serialize(function(){
            Schema::create('user', function(Blueprint $table)
            {
                $table->increments('id');
                $table->string('login');
                $table->string('email');
                $table->string('password');
                $table->timestamp('last_login')->nullable();
                $table->string('first_name')->nullable();
                $table->string('last_name')->nullable();
            });
        }),
    ),
    'data' => array(
        'Med' => array(
            array(
                'data' => array(
                    'id'=>1,
                    'name' => 'Testing Firma Medyczna 1',
                    'city' => 'Warszawa',
                    'street' =>  'ul. Jana Pawła 2',
                    'zip_code' => '03-481',
                    'nip' => '2691440397',
                    'regon' => '751066434',
                    'krs' => '69545',
                    'spokesman' => 'Jan Nowak',
                    'phone' => '+48 505 50 60 71',
                    'email' => 'kotantk@firma.medyczna.pl'
                )
            )
        ),
        'MedFacility' => array(
            array(
                'data' => array(
                    'id' => 1,
                    'med__id' => 1,
                    'name' => 'Placówka 1 Med 1',
                    'city' => 'Warszawa',
                    'street' => 'ul. Jana Pawła 2 100/200',
                    'zip_code' => '03-481',
                    'email' => 'placowka1@med1.pl',
                    'x' => 0,
                    'y' => 0,
                    'phone' => '+48 505 50 60 01',
                )

            )
        ),
        'MedPersonnel' => array(
            array(
                'data'=>array(
                    'id' => 1,
                    'med__id' => 1,
                    'med_position__id' => $position1->id,
                    'pesel' => '96052700739',
                    'phone' => '606 66 77 01',
                    'email' => 'adam.kowal@lekarz.crm.pl',
                    'first_name' => 'Adam',
                    'last_name' => 'Kowal',
                    'birth_date' =>'1980-01-01',
                    'pwz_number' => 100001,
                    'city' => 'Warszawa',
                    'zip_code' => '04-300',
                    'street' => 'ul. Lekarzy 1'
                ),
                'attach' => array(
                    'scienceDegrees' => $scienceDegreeList
                ),
                'create' => array(
                    'MedPersonnelSpecialization' => function() use ($doctorSpecList){
                        $result = array();
                        foreach ($doctorSpecList AS $index=>$spec){
                            $degree  =  App::make('MedDegree')->orderBy(DB::raw('random()'))->take(1)->first();
                            $result[] =  array(
                                'med_specialization__id' => $spec->id,
                                'med_degree__id' => $degree->id
                            );
                        }

                        return $result;
                    }
                )
            )
        )
    )
);
