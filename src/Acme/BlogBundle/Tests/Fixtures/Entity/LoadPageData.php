<?php

public function testGet()
{
    $fixtures = array('Acme\BlogBundle\Tests\Fixtures\Entity\LoadArticleData');
    $this->customSetUp($fixtures);
    $page = array_pop(LoadPageData::$pages);

    $route =  $this->getUrl('api_1_get_page', array('id' => $page->getId(), '_format' => 'json'));
    $this->client->request('GET', $route);
    $response = $this->client->getResponse();
    $this->assertJsonResponse($response, 200);
    $content = $response->getContent();

    $decoded = json_decode($content, true);
    $this->assertTrue(isset($decoded['id']));
}