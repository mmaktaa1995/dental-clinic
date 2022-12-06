<?php

use App\Models\Expense;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Service;
use App\Models\Visit;
use Illuminate\Database\Migrations\Migration;

class CopyDataFromOldDbToNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (defined('PHPUNIT_DENTAL_TESTSUITE') && PHPUNIT_DENTAL_TESTSUITE) {
            return;
        }
        DB::connection('mysql_old')->beginTransaction();
        Schema::disableForeignKeyConstraints();
        $databaseName = DB::getDatabaseName();
        $tables = DB::select("SELECT * FROM information_schema.tables WHERE table_schema = '$databaseName'");
        foreach ($tables as $table) {
            $name = $table->TABLE_NAME;
            //if you don't want to truncate migrations
            if ($name == 'migrations') {
                continue;
            }
            DB::table($name)->truncate();
        }
//        foreach ($tableNames as $name) {
//            //if you don't want to truncate migrations
//            if ($name == 'migrations') {
//                continue;
//            }
//            DB::table($name)->truncate();
//        }
//        $users = DB::connection('mysql_old')->table('users')->get();
//        DB::connection('mysql_new')->table('users')->insert($users->toArray());

        $patients = DB::connection('mysql_old')->table('patients')->whereNull('deleted_at')->get();
        $patients = $patients->map(function ($item) {
            $newItem = new Patient();
            $newItem->id = $item->id;
            $newItem->name = $item->full_name;
            $newItem->age = $item->age;
            $newItem->phone = $item->phone;
            $newItem->mobile = $item->mobile;
            $newItem->file_number = $item->file_number;
            $newItem->image = $item->image;
            $newItem->created_at = date('Y-m-d H:i:s', strtotime($item->created_at));
            return $newItem;
        });

        $patients = array_map(function ($item) {
            $item['created_at'] = date('Y-m-d H:i:s', strtotime($item['created_at']));
            return $item;
        }, $patients->toArray());


        DB::connection('mysql_new')->table('patients')->insert($patients);

        $deletedPatients = DB::connection('mysql_old')->table('patients')->whereNotNull('deleted_at')->get();
        $deletedPatients = $deletedPatients->map(function ($item) {
            $newItem = new Patient();
            $newItem->id = $item->id;
            $newItem->name = $item->full_name;
            $newItem->age = $item->age;
            $newItem->phone = $item->phone;
            $newItem->mobile = $item->mobile;
            $newItem->file_number = $item->file_number;
            $newItem->image = $item->image;
            $newItem->created_at = date('Y-m-d H:i:s', strtotime($item->created_at));
            return $newItem;
        });

        $deletedPatients = array_map(function ($item) {
            $item['created_at'] = date('Y-m-d H:i:s', strtotime($item['created_at']));
            return $item;
        }, $deletedPatients->toArray());
        DB::connection('mysql_new')->table('deleted_patients')->insert($deletedPatients);

        $services = DB::connection('mysql_old')->table('services')->get();
        $services = $services->map(function ($item) {
            return new Service([
                'id' => $item->service_id,
                'name' => $item->name_en,
                'price' => $item->price
            ]);
        });
        DB::connection('mysql_new')->table('services')->insert($services->toArray());

        $ids = collect($deletedPatients)->pluck('id');
        DB::connection('mysql_old')->table('visits')->whereIn('user_id', $ids->toArray())->delete();
        $visits = DB::connection('mysql_old')->table('visits')->whereNull('deleted_at')->get();
        $visits = $visits->map(function ($item) {
            $newItem = new Visit();
            $newItem->id = $item->visit_id;
            $newItem->patient_id = $item->user_id;
            $newItem->date = $item->date;
            $newItem->notes = $item->notes ?? '-';
            $newItem->amount = $item->amount_paid ?? 0;
            return $newItem;
        });
        $mappedVisits = unserialize(serialize($visits));
        $mappedVisits = $mappedVisits->map(function ($visit) {
            unset($visit->amount);
            return $visit;
        });

        DB::connection('mysql_new')->table('visits')->insert($mappedVisits->toArray());

        $payments = $visits->map(function ($item) {
            $newItem = new Payment();
            $newItem->date = $item->date;
            $newItem->amount = $item->amount ?? 0;
            $newItem->patient_id = $item->patient_id;
            $newItem->visit_id = $item->id;
            return $newItem;
        });
        DB::connection('mysql_new')->table('payments')->insert($payments->toArray());

//        $serviceVisits = \DB::connection('mysql_old')->table('service_visits')->get();
//        $serviceVisits = $serviceVisits->map(function ($item) {
//            return new \App\Models\ServiceVisit(
//                [
//                    'service_id' => $item->service_id,
//                    'visit_id' => $item->visit_id,
//                    'date' => date('Y-m-d h:m:s', strtotime($item->date))
//                ]
//            );
//        });
//        \DB::connection('mysql_new')->table('service_visits')->insert($serviceVisits->toArray());

        $financialExpenses = DB::connection('mysql_old')->table('financial_expenses')->get();
        $financialExpenses = $financialExpenses->map(function ($item) {
            $newItem = new Expense();
            $newItem->id = $item->id;
            $newItem->date = $item->date;
            $newItem->description = $item->description ?? 'لا يوجد';
            $newItem->name = $item->laboratory;
            $newItem->amount = $item->value;
            return $newItem;
        });
        DB::connection('mysql_new')->table('financial_expenses')->insert($financialExpenses->toArray());
        Schema::enableForeignKeyConstraints();
        DB::connection('mysql_old')->rollBack();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (defined('PHPUNIT_DENTAL_TESTSUITE') && PHPUNIT_DENTAL_TESTSUITE) {
            return;
        }
        DB::connection('mysql_new')->table('users')->truncate();
        DB::connection('mysql_new')->table('patients')->truncate();
        DB::connection('mysql_new')->table('deleted_patients')->truncate();
        DB::connection('mysql_new')->table('service_visits')->truncate();
        DB::connection('mysql_new')->table('services')->truncate();
        DB::connection('mysql_new')->table('payments')->truncate();
        DB::connection('mysql_new')->table('visits')->truncate();
        DB::connection('mysql_new')->table('financial_expenses')->truncate();
    }
}
