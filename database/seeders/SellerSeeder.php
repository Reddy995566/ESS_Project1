<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Seller;

class SellerSeeder extends Seeder
{
    public function run()
    {
        // Seller 1 - Active
        $user1 = User::firstOrCreate(
            ['email' => 'rajesh@example.com'],
            [
                'name' => 'Rajesh Kumar',
                'mobile' => '9876543210',
                'password' => Hash::make('password123'),
            ]
        );

        $seller1 = Seller::firstOrCreate(
            ['user_id' => $user1->id],
            [
                'business_name' => 'Kumar Fashion House',
                'business_type' => 'company',
                'business_registration_number' => 'REG123456',
                'gst_number' => '27AABCU9603R1ZM',
                'pan_number' => 'AABCU9603R',
                'business_email' => 'info@kumarfashion.com',
                'business_phone' => '9876543210',
                'business_address' => '123, MG Road',
                'business_city' => 'Mumbai',
                'business_state' => 'Maharashtra',
                'business_pincode' => '400001',
                'commission_rate' => 15,
                'status' => 'active',
                'is_verified' => true,
            ]
        );

        $seller1->bankDetails()->firstOrCreate(
            ['seller_id' => $seller1->id],
            [
                'account_holder_name' => 'Rajesh Kumar',
                'account_number' => '1234567890123456',
                'ifsc_code' => 'HDFC0001234',
                'bank_name' => 'HDFC Bank',
                'branch_name' => 'MG Road Branch',
                'account_type' => 'current',
                'upi_id' => 'rajesh@paytm',
                'is_verified' => true,
            ]
        );

        // Seller 2 - Pending
        $user2 = User::firstOrCreate(
            ['email' => 'priya@example.com'],
            [
                'name' => 'Priya Sharma',
                'mobile' => '9876543211',
                'password' => Hash::make('password123'),
            ]
        );

        $seller2 = Seller::firstOrCreate(
            ['user_id' => $user2->id],
            [
                'business_name' => 'Sharma Textiles',
                'business_type' => 'partnership',
                'business_registration_number' => 'REG789012',
                'gst_number' => '29AABCS9603R1ZN',
                'pan_number' => 'AABCS9603R',
                'business_email' => 'contact@sharmatextiles.com',
                'business_phone' => '9876543211',
                'business_address' => '456, Brigade Road',
                'business_city' => 'Bangalore',
                'business_state' => 'Karnataka',
                'business_pincode' => '560001',
                'commission_rate' => 15,
                'status' => 'pending',
                'is_verified' => false,
            ]
        );

        $seller2->bankDetails()->firstOrCreate(
            ['seller_id' => $seller2->id],
            [
                'account_holder_name' => 'Priya Sharma',
                'account_number' => '9876543210987654',
                'ifsc_code' => 'ICIC0001234',
                'bank_name' => 'ICICI Bank',
                'branch_name' => 'Brigade Road Branch',
                'account_type' => 'savings',
                'upi_id' => 'priya@phonepe',
                'is_verified' => false,
            ]
        );

        // Seller 3 - Active
        $user3 = User::firstOrCreate(
            ['email' => 'amit@example.com'],
            [
                'name' => 'Amit Patel',
                'mobile' => '9876543212',
                'password' => Hash::make('password123'),
            ]
        );

        $seller3 = Seller::firstOrCreate(
            ['user_id' => $user3->id],
            [
                'business_name' => 'Patel Saree Emporium',
                'business_type' => 'individual',
                'gst_number' => '24AABCP9603R1ZO',
                'pan_number' => 'AABCP9603R',
                'business_email' => 'amit@patelsaree.com',
                'business_phone' => '9876543212',
                'business_address' => '789, CG Road',
                'business_city' => 'Ahmedabad',
                'business_state' => 'Gujarat',
                'business_pincode' => '380001',
                'commission_rate' => 12,
                'status' => 'active',
                'is_verified' => true,
            ]
        );

        $seller3->bankDetails()->firstOrCreate(
            ['seller_id' => $seller3->id],
            [
                'account_holder_name' => 'Amit Patel',
                'account_number' => '5555666677778888',
                'ifsc_code' => 'SBIN0001234',
                'bank_name' => 'State Bank of India',
                'branch_name' => 'CG Road Branch',
                'account_type' => 'current',
                'upi_id' => 'amit@sbi',
                'is_verified' => true,
            ]
        );

        // Seller 4 - Suspended
        $user4 = User::firstOrCreate(
            ['email' => 'sneha@example.com'],
            [
                'name' => 'Sneha Reddy',
                'mobile' => '9876543213',
                'password' => Hash::make('password123'),
            ]
        );

        $seller4 = Seller::firstOrCreate(
            ['user_id' => $user4->id],
            [
                'business_name' => 'Reddy Fashion Store',
                'business_type' => 'company',
                'business_registration_number' => 'REG345678',
                'gst_number' => '36AABCR9603R1ZP',
                'pan_number' => 'AABCR9603R',
                'business_email' => 'info@reddyfashion.com',
                'business_phone' => '9876543213',
                'business_address' => '321, Banjara Hills',
                'business_city' => 'Hyderabad',
                'business_state' => 'Telangana',
                'business_pincode' => '500034',
                'commission_rate' => 15,
                'status' => 'suspended',
                'is_verified' => true,
            ]
        );

        $seller4->bankDetails()->firstOrCreate(
            ['seller_id' => $seller4->id],
            [
                'account_holder_name' => 'Sneha Reddy',
                'account_number' => '1111222233334444',
                'ifsc_code' => 'AXIS0001234',
                'bank_name' => 'Axis Bank',
                'branch_name' => 'Banjara Hills Branch',
                'account_type' => 'current',
                'upi_id' => 'sneha@axisbank',
                'is_verified' => true,
            ]
        );

        // Seller 5 - Rejected
        $user5 = User::firstOrCreate(
            ['email' => 'vikram@example.com'],
            [
                'name' => 'Vikram Singh',
                'mobile' => '9876543214',
                'password' => Hash::make('password123'),
            ]
        );

        $seller5 = Seller::firstOrCreate(
            ['user_id' => $user5->id],
            [
                'business_name' => 'Singh Garments',
                'business_type' => 'individual',
                'pan_number' => 'AABCS9603S',
                'business_email' => 'vikram@singhgarments.com',
                'business_phone' => '9876543214',
                'business_address' => '654, Connaught Place',
                'business_city' => 'New Delhi',
                'business_state' => 'Delhi',
                'business_pincode' => '110001',
                'commission_rate' => 15,
                'status' => 'rejected',
                'rejection_reason' => 'Incomplete documentation provided',
                'is_verified' => false,
            ]
        );

        $seller5->bankDetails()->firstOrCreate(
            ['seller_id' => $seller5->id],
            [
                'account_holder_name' => 'Vikram Singh',
                'account_number' => '9999888877776666',
                'ifsc_code' => 'PUNB0001234',
                'bank_name' => 'Punjab National Bank',
                'branch_name' => 'CP Branch',
                'account_type' => 'savings',
                'is_verified' => false,
            ]
        );

        $this->command->info('5 test sellers created successfully!');
    }
}
