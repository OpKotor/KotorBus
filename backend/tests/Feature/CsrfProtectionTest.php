<?php

namespace Tests\Feature;

use Tests\TestCase;

class CsrfProtectionTest extends TestCase
{
    public function test_post_route_without_csrf_token()
    {
        // Pokreće sesiju prije testa
        $this->startSession();

        // Simulira POST zahtjev na rutu bez CSRF tokena
        $response = $this->post('/ruta-bez-tokena', []); // Bez CSRF tokena
        $response->assertStatus(419); // Očekuje se greška 419 (Page Expired)
    }

    public function test_post_route_with_csrf_token()
    {
        // Pokreće sesiju prije testa
        $this->startSession();

        // Generiše CSRF token
        $csrfToken = csrf_token();

        // Simulira POST zahtjev na rutu sa CSRF tokenom
        $response = $this->post('/ruta-sa-tokenom', ['_token' => $csrfToken]);
        $response->assertStatus(200); // Očekuje se uspješan odgovor
    }
}