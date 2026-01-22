<?php

use App\Models\{User, ShortUrl, Role, Company};
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

function createUserWithRole($roleName)
{
    $role = Role::factory()->create(['name' => $roleName]);
    $company = Company::factory()->create();

    return User::factory()->create([
        'role_id' => $role->id,
        'company_id' => $company->id,
    ]);
}

test('superadmin can see all short urls', function () {
    $this->withoutExceptionHandling();
    $user = createUserWithRole('SuperAdmin');

    $response = $this->actingAs($user)->get(route('short-urls.index'));

    $response->assertStatus(200);
});

test('admin sees only company short urls', function () {
    $this->withoutExceptionHandling();
    $admin = createUserWithRole('Admin');

    ShortUrl::factory()->create([
        'company_id' => $admin->company_id,
        'user_id' => $admin->id
    ]);

    ShortUrl::factory()->create();

    $response = $this->actingAs($admin)->get(route('short-urls.index'));

    $response->assertStatus(200);
    $this->assertCount(1, $response->viewData('shortUrls'));
});

test('Admin can create short urls', function () {
    $admin = createUserWithRole('Admin');

    $this->withoutMiddleware();

    $response = $this
        ->actingAs($admin)
        ->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/some/long/url',
        ]);

    $response->assertRedirect(route('short-urls.index'));
    $response->assertSessionHas('success', 'Short URL created successfully.');

    $this->assertDatabaseHas('short_urls', [
        'original_url' => 'https://example.com/some/long/url',
        'company_id'   => $admin->company_id,
    ]);
});

test('Member can create short urls', function () {
    $member = createUserWithRole('Member');

    $this->withoutMiddleware();

    $response = $this
        ->actingAs($member)
        ->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/member/url',
        ]);

    $response->assertRedirect(route('short-urls.index'));
    $response->assertSessionHas('success', 'Short URL created successfully.');

    $this->assertDatabaseHas('short_urls', [
        'original_url' => 'https://example.com/member/url',
        'company_id'   => $member->company_id,
        'user_id'      => $member->id,
    ]);
});

test('SuperAdmin cannot create short urls', function () {
    $superAdmin = createUserWithRole('SuperAdmin');

    $this->withoutMiddleware();

    $response = $this
        ->actingAs($superAdmin)
        ->post(route('short-urls.store'), [
            'original_url' => 'https://example.com/superadmin/url',
        ]);

    $response->assertStatus(403);

    $this->assertDatabaseMissing('short_urls', [
        'original_url' => 'https://example.com/superadmin/url',
    ]);
});

test('Admin can only see short urls created in their own company', function () {
 
    $company1 = Company::factory()->create(['name' => 'Company 1']);
    $company2 = Company::factory()->create(['name' => 'Company 2']);

    $role = Role::factory()->create(['name' => 'Admin']);

    $admin1 = User::factory()->create([
        'company_id' => $company1->id,
        'role_id' => $role->id,
    ]);

    $admin2 = User::factory()->create([
        'company_id' => $company2->id,
        'role_id' => $role->id,
    ]);

    $shortUrl1 = ShortUrl::factory()->create([
        'company_id' => $company1->id,
        'user_id' => $admin1->id,
        'original_url' => 'https://company1.com/url',
    ]);

    $shortUrl2 = ShortUrl::factory()->create([
        'company_id' => $company2->id,
        'user_id' => $admin2->id,
        'original_url' => 'https://company2.com/url',
    ]);

    $response = $this
        ->actingAs($admin1)
        ->get(route('short-urls.index'));

    $response->assertStatus(200);
    
    $response->assertSee($shortUrl1->original_url);
    
    $response->assertDontSee($shortUrl2->original_url);
});

test('Member can only see short urls created by themselves', function () {
    $company = Company::factory()->create(['name' => 'Test Company']);
    $role = Role::factory()->create(['name' => 'Member']);

    $member1 = User::factory()->create([
        'company_id' => $company->id,
        'role_id' => $role->id,
    ]);

    $member2 = User::factory()->create([
        'company_id' => $company->id,
        'role_id' => $role->id,
    ]);
    $shortUrl1 = ShortUrl::factory()->create([
        'company_id' => $company->id,
        'user_id' => $member1->id,
        'original_url' => 'https://member1.com/url',
    ]);

    $shortUrl2 = ShortUrl::factory()->create([
        'company_id' => $company->id,
        'user_id' => $member2->id,
        'original_url' => 'https://member2.com/url',
    ]);

    $response = $this
        ->actingAs($member1)
        ->get(route('short-urls.index'));

    $response->assertStatus(200);
    
    $response->assertSee($shortUrl1->original_url);
    
    $response->assertDontSee($shortUrl2->original_url);
});

test('Short urls are publicly resolvable and redirect to the original url', function () {
    $admin = createUserWithRole('Admin');

    $shortUrl = ShortUrl::factory()->create([
        'company_id' => $admin->company_id,
        'user_id' => $admin->id,
        'original_url' => 'https://example.com/original',
        'short_code' => 'abc123',
    ]);

    $response = $this->get(route('short-urls.show', ['shortUrl' => $shortUrl->short_code]));

    $response->assertRedirect('https://example.com/original');
});