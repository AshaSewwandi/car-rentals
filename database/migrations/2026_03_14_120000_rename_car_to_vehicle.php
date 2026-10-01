<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $carIdTables = [
        'rentals',
        'expenses',
        'agreements',
        'gps_logs',
        'rent_requests',
        'bookings',
        'vehicle_maintenances',
    ];

    public function up(): void
    {
        if (Schema::hasTable('cars') && !Schema::hasTable('vehicles')) {
            Schema::rename('cars', 'vehicles');
        }

        if (Schema::hasTable('car_images') && !Schema::hasTable('vehicle_images')) {
            Schema::rename('car_images', 'vehicle_images');
        }

        foreach ([...$this->carIdTables, 'vehicle_images'] as $table) {
            if (Schema::hasColumn($table, 'car_id') && !Schema::hasColumn($table, 'vehicle_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->renameColumn('car_id', 'vehicle_id');
                });
            }
        }

        if (Schema::hasColumn('rent_requests', 'car_name') && !Schema::hasColumn('rent_requests', 'vehicle_name')) {
            Schema::table('rent_requests', function (Blueprint $blueprint) {
                $blueprint->renameColumn('car_name', 'vehicle_name');
            });
        }

        DB::table('role_permissions')->where('permission', 'cars')->update(['permission' => 'vehicles']);

        $oldDir = public_path('uploads/cars');
        $newDir = public_path('uploads/vehicles');
        if (File::isDirectory($oldDir) && !File::isDirectory($newDir)) {
            File::moveDirectory($oldDir, $newDir);
        }

        if (Schema::hasTable('vehicle_images')) {
            DB::table('vehicle_images')
                ->where('path', 'like', 'uploads/cars/%')
                ->get()
                ->each(function ($row) {
                    DB::table('vehicle_images')
                        ->where('id', $row->id)
                        ->update(['path' => preg_replace('#^uploads/cars/#', 'uploads/vehicles/', $row->path)]);
                });
        }

        if (Schema::hasTable('vehicles')) {
            if (!Schema::hasColumn('vehicles', 'available_for_hire')) {
                Schema::table('vehicles', function (Blueprint $blueprint) {
                    $blueprint->boolean('available_for_hire')->default(true)->after('allow_long_term');
                });
            }
            if (!Schema::hasColumn('vehicles', 'available_for_rent')) {
                Schema::table('vehicles', function (Blueprint $blueprint) {
                    $blueprint->boolean('available_for_rent')->default(true)->after('available_for_hire');
                });
            }
        }

        if (Schema::hasTable('bookings') && !Schema::hasColumn('bookings', 'order_type')) {
            Schema::table('bookings', function (Blueprint $blueprint) {
                $blueprint->string('order_type', 10)->nullable()->after('driver_option');
            });

            DB::table('bookings')->whereNull('order_type')->update(['order_type' => 'hire']);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('bookings', 'order_type')) {
            Schema::table('bookings', function (Blueprint $blueprint) {
                $blueprint->dropColumn('order_type');
            });
        }

        if (Schema::hasTable('vehicles')) {
            if (Schema::hasColumn('vehicles', 'available_for_rent')) {
                Schema::table('vehicles', function (Blueprint $blueprint) {
                    $blueprint->dropColumn('available_for_rent');
                });
            }
            if (Schema::hasColumn('vehicles', 'available_for_hire')) {
                Schema::table('vehicles', function (Blueprint $blueprint) {
                    $blueprint->dropColumn('available_for_hire');
                });
            }
        }

        if (Schema::hasTable('vehicle_images')) {
            DB::table('vehicle_images')
                ->where('path', 'like', 'uploads/vehicles/%')
                ->get()
                ->each(function ($row) {
                    DB::table('vehicle_images')
                        ->where('id', $row->id)
                        ->update(['path' => preg_replace('#^uploads/vehicles/#', 'uploads/cars/', $row->path)]);
                });
        }

        $newDir = public_path('uploads/vehicles');
        $oldDir = public_path('uploads/cars');
        if (File::isDirectory($newDir) && !File::isDirectory($oldDir)) {
            File::moveDirectory($newDir, $oldDir);
        }

        DB::table('role_permissions')->where('permission', 'vehicles')->update(['permission' => 'cars']);

        if (Schema::hasColumn('rent_requests', 'vehicle_name') && !Schema::hasColumn('rent_requests', 'car_name')) {
            Schema::table('rent_requests', function (Blueprint $blueprint) {
                $blueprint->renameColumn('vehicle_name', 'car_name');
            });
        }

        foreach ([...$this->carIdTables, 'vehicle_images'] as $table) {
            if (Schema::hasColumn($table, 'vehicle_id') && !Schema::hasColumn($table, 'car_id')) {
                Schema::table($table, function (Blueprint $blueprint) {
                    $blueprint->renameColumn('vehicle_id', 'car_id');
                });
            }
        }

        if (Schema::hasTable('vehicle_images') && !Schema::hasTable('car_images')) {
            Schema::rename('vehicle_images', 'car_images');
        }

        if (Schema::hasTable('vehicles') && !Schema::hasTable('cars')) {
            Schema::rename('vehicles', 'cars');
        }
    }
};
