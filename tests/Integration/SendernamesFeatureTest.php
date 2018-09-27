<?php
declare(strict_types=1);

namespace Smsapi\Client\Tests\Integration;

use Smsapi\Client\Infrastructure\Response\ApiErrorException;
use Smsapi\Client\Feature\Sms\Sendernames\Bag\CreateSendernameBag;
use Smsapi\Client\Feature\Sms\Sendernames\Bag\DeleteSendernameBag;
use Smsapi\Client\Feature\Sms\Sendernames\Bag\FindSendernameBag;
use Smsapi\Client\Feature\Sms\Sendernames\Bag\FindSendernamesBag;
use Smsapi\Client\Feature\Sms\Sendernames\Bag\MakeSendernameDefaultBag;
use Smsapi\Client\Tests\SmsapiClientIntegrationTestCase;

class SendernamesFeatureTest extends SmsapiClientIntegrationTestCase
{
    const SENDERNAME = 'some_sender';

    /**
     * @test
     */
    public function it_should_create_sendername()
    {
        $sendernameFeature = self::$smsapiService->smsFeature()->sendernameFeature();
        $createSendernameBag = new CreateSendernameBag(self::SENDERNAME);

        $result = $sendernameFeature->createSendername($createSendernameBag);

        $this->assertEquals(self::SENDERNAME, $result->sender);
    }

    /**
     * @test
     */
    public function it_should_find_sendernames()
    {
        $sendernameFeature = self::$smsapiService->smsFeature()->sendernameFeature();
        $findSendernamesBag = new FindSendernamesBag();

        $result = $sendernameFeature->findSendernames($findSendernamesBag);

        $senders = array_column(array_map('get_object_vars', $result), 'sender');
        $this->assertContains(self::SENDERNAME, $senders);
    }

    /**
     * @test
     */
    public function it_should_find_sendername()
    {
        $sendernameFeature = self::$smsapiService->smsFeature()->sendernameFeature();
        $findSendernameBag = new FindSendernameBag(self::SENDERNAME);

        $result = $sendernameFeature->findSendername($findSendernameBag);

        $this->assertEquals(self::SENDERNAME, $result->sender);
    }

    /**
     * @test
     */
    public function it_should_delete_sendername()
    {
        $sendernameFeature = self::$smsapiService->smsFeature()->sendernameFeature();
        $deleteSendernameBag = new DeleteSendernameBag(self::SENDERNAME);

        $sendernameFeature->deleteSendername($deleteSendernameBag);

        $this->expectException(ApiErrorException::class);
        $this->expectExceptionMessage('Sendername not exists');
        $sendernameFeature->findSendername(new FindSendernameBag(self::SENDERNAME));
    }

    /**
     * @test
     */
    public function it_should_make_sendername_default()
    {
        $sendernameFeature = self::$smsapiService->smsFeature()->sendernameFeature();
        $sender = '2WAY';
        $makeSenderNameDefaultBag = new MakeSendernameDefaultBag($sender);

        $sendernameFeature->makeSendernameDefault($makeSenderNameDefaultBag);

        $sendername = $sendernameFeature->findSendername(new FindSendernameBag($sender));
        $this->assertTrue($sendername->isDefault);
    }
}
