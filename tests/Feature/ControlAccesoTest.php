<?php

namespace Tests\Feature;

use App\Models\Blacklist;
use App\Models\Ingreso;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ControlAccesoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\SucursalSeeder::class);
    }

    public function test_ingresos_index_requiere_guardia_o_admin(): void
    {
        $admin = User::factory()->administrador()->create(['rut' => '12345678-9']);
        $response = $this->actingAs($admin)->get(route('ingresos.index'));
        $response->assertStatus(200);
    }

    public function test_store_ingreso_peatonal_ok(): void
    {
        $admin = User::factory()->administrador()->create(['rut' => '12345678-9']);
        $response = $this->actingAs($admin)->postJson(route('ingresos.store'), [
            'tipo' => 'peatonal',
            'rut' => '11111111-1',
            'nombre' => 'Juan Pérez',
            '_token' => csrf_token(),
        ]);
        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('ingresos', [
            'rut' => '11111111-1',
            'nombre' => 'Juan Pérez',
            'estado' => 'ingresado',
        ]);
    }

    public function test_store_ingreso_vehicular_ok(): void
    {
        $admin = User::factory()->administrador()->create(['rut' => '12345678-9']);
        $response = $this->actingAs($admin)->postJson(route('ingresos.store'), [
            'tipo' => 'vehicular',
            'patente' => 'ABCD12',
            '_token' => csrf_token(),
        ]);
        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('ingresos', [
            'patente' => 'ABCD12',
            'estado' => 'ingresado',
        ]);
    }

    public function test_salida_registra_fecha_salida(): void
    {
        $admin = User::factory()->administrador()->create(['rut' => '12345678-9']);
        $ingreso = Ingreso::create([
            'tipo' => 'peatonal',
            'rut' => '22222222-2',
            'nombre' => 'Test',
            'patente' => null,
            'guardia_id' => $admin->id,
            'estado' => 'ingresado',
            'alerta_blacklist' => false,
        ]);
        $response = $this->actingAs($admin)->post(route('ingresos.salida', $ingreso->id), [
            '_token' => csrf_token(),
        ]);
        $response->assertRedirect(route('ingresos.index'));
        $ingreso->refresh();
        $this->assertNotNull($ingreso->fecha_salida);
        $this->assertSame('salida', $ingreso->estado);
    }

    public function test_blacklist_bloquea_ingreso(): void
    {
        $admin = User::factory()->administrador()->create(['rut' => '12345678-9']);
        Blacklist::create([
            'rut' => '55555555-5',
            'patente' => null,
            'motivo' => 'No autorizado',
            'fecha_inicio' => now()->toDateString(),
            'activo' => true,
            'created_by' => $admin->id,
        ]);
        $response = $this->actingAs($admin)->postJson(route('ingresos.store'), [
            'tipo' => 'peatonal',
            'rut' => '55555555-5',
            'nombre' => 'Bloqueado',
            '_token' => csrf_token(),
        ]);
        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
        $this->assertDatabaseHas('ingresos', [
            'rut' => '55555555-5',
            'estado' => 'bloqueado',
            'alerta_blacklist' => true,
        ]);
    }
}
