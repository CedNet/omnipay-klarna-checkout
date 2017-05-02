<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\FetchTransactionResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class FetchTransactionRequestTest extends RequestTestCase
{
    /**
     * @var FetchTransactionRequest
     */
    private $fetchTransactionRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->fetchTransactionRequest = new FetchTransactionRequest($this->httpClient, $this->getHttpRequest());
    }

    public function testGetDataReturnsNull()
    {
        $this->fetchTransactionRequest->initialize(['transactionReference' => 'foo']);

        self::assertNull($this->fetchTransactionRequest->getData());
    }

    public function testGetDataThrowsExceptionWhenMissingTransactionReference()
    {
        $this->setExpectedException(InvalidRequestException::class);

        $this->fetchTransactionRequest->initialize([]);
        $this->fetchTransactionRequest->getData();
    }

    public function testSendData()
    {
        $expectedData = ['response-data' => 'yey!'];
        $this->setExpectedGetRequest($expectedData, self::BASE_URL.'/ordermanagement/v1/orders/foo');

        $this->fetchTransactionRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => 'foo',
        ]);

        $fetchResponse = $this->fetchTransactionRequest->sendData([]);

        self::assertInstanceOf(FetchTransactionResponse::class, $fetchResponse);
        self::assertSame($expectedData, $fetchResponse->getData());
    }
}
