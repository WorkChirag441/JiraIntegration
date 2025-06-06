<?php
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class TestUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Chirag Ahuja',
            'email' => 'ahujachirag441@gmail.com',
            'password' => Hash::make('12345678'),
            'jira_email' => 'ahujachirag441@gmail.com',
            'jira_api_token' => Crypt::encryptString('ATATT3xFfGF0ZLfBzeQSfCrIGw9ZF9I_jVhdfhTeclZ91AZBhk5CizT9swwlUbV1iq9h0vxD2OIMa-G9POUDGKRYoqKCV8TLClDJdE3vt7kuy0d6FzmQj4Uk0F59IFOV3xtWuCLhQW1qv5y7pjzzFKLIe-824ouQ-KucKAXJhLsOOKwuDI3m_ik=1173D8F5'),
        ]);
    }
}
