<?php
declare(strict_types=1);

namespace Smsapi\Client\Tests\Integration;

use Smsapi\Client\Feature\Subusers\Bag\DeleteSubuserBag;
use Smsapi\Client\Tests\Assert\SubuserAssert;
use Smsapi\Client\Tests\Fixture\Subusers\CreateSubuserBagMother;
use Smsapi\Client\Tests\Fixture\Subusers\SubuserMother;
use Smsapi\Client\Tests\SmsapiClientIntegrationTestCase;

class SubusersFeatureTest extends SmsapiClientIntegrationTestCase
{
    /** @var SubuserAssert */
    private $subUserAssert;

    /**
     * @before
     */
    public function given()
    {
        $this->subUserAssert = new SubuserAssert();
    }

    /**
     * @test
     */
    public function it_should_create_subuser(): string
    {
        $subusersFeature = self::$smsapiService->subusersFeature();

        $subuser = SubuserMother::createAnySubuser();
        $createSubuserBag = CreateSubuserBagMother::createWithSubuserName($subuser->username);

        $result = $subusersFeature->createSubuser($createSubuserBag);

        $this->subUserAssert->assertSubuser($subuser, $result);

        return $result->id;
    }

    /**
     * @test
     * @depends it_should_create_subuser
     */
    public function it_should_find_subusers()
    {
        $subusersFeature = self::$smsapiService->subusersFeature();

        $result = $subusersFeature->findSubusers();

        $this->subUserAssert->assertContainsSubuser(SubuserMother::createAnySubuser(), $result);
    }

    /**
     * @test
     * @depends it_should_create_subuser
     */
    public function it_should_delete_subuser(string $subuserId)
    {
        $subusersFeature = self::$smsapiService->subusersFeature();
        $deleteSubuserBag = new DeleteSubuserBag($subuserId);

        $subusersFeature->deleteSubuser($deleteSubuserBag);

        $this->assertTrue(true);
    }
}
