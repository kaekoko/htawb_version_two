<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            TwoDSeeder::class,
            CitySeeder::class,
            PaymentMethodSeeder::class,
            NumberSeeder::class,
            SectionSeeder::class,
            Section1dSeeder::class,
            Section3dSeeder::class,
            SectionCrypto2dSeeder::class,
            OverAllSettingSeeder::class,
            HotBlock::class,
            HotBlockCrypto2dSeeder::class,
            LiveRecordSeeder::class,
            CustomRecordsSeeder::class,
            CustomRecordC2DSeeder::class,
            RoleSeeder::class,
            CategorySeeder::class,
            ThreeDSeeder::class,
            CryptoTwoDSeeder::class,
            ProviderMinimumAmountSeeder::class,
            SpinWheelSeeder::class,
            SettingSeeder::class,
            AppUpdateSeeder::class,
            SocialLinkSeeder::class,         
            SportGameSeeder::class,
            settingwelcomeseeder::class,
            OneDSeeder::class,
            ShamelessGameCategorySeeder::class,
            ShamelessGameProviderSeeder::class,
            ShamelessGameSeeder::class,
            SectionCrypto1dSeeder::class,
            OtherGameSeeder::class,
            CryptoOneDSeeder::class,
            AppUpdateTwoSeeder::class,
        ]);
    }
}
