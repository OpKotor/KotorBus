<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Postavljanje APP_KEY za testove
        putenv('APP_KEY=base64:TdWMnAyRudCfTDPnLcUOVjC8VQY0GE+v8C9/bSv1hP0=');
    }

    public function testApplicationReturnsSuccessfulResponse()
    {
        // Simulira GET zahtjev na početnu rutu
        $response = $this->get('/');

        // Provjerava da li je status odgovora 200 (uspješno)
        $response->assertStatus(200);
    }
}