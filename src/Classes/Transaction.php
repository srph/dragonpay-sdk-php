<?php namespace Coreproc\Dragonpay\Classes;

use Coreproc\Dragonpay\DragonpayClient;

class Transaction
{

    /**
     * @var DragonpayClient
     */
    private $client;

    private $urlGenerator;

    public function __construct(DragonpayClient $client)
    {
        $this->client = $client;
        $this->urlGenerator = new URLGenerator();
    }

    /**
     * Get the generated URL for inquiring a transaction's status.
     *
     * @param $transactionId
     * @return string
     */
    public function getInquiryUrl($transactionId)
    {
        return $this->urlGenerator->generateTransactionQueryUrl(
            $this->client->getMerchantId(),
            $this->client->getMerchantPassword(),
            $transactionId,
            'GETSTATUS'
        );
    }

    /**
     * Get the generated URL for the cancellation of a transaction.
     *
     * @param $transactionId
     * @return string
     */
    public function getCancellationUrl($transactionId)
    {
        return $this->urlGenerator->generateTransactionQueryUrl(
            $this->client->getMerchantId(),
            $this->client->getMerchantPassword(),
            $transactionId,
            'VOID'
        );
    }

    /**
     * Get the status of a transaction.
     *
     * @param $statusCode
     * @return string
     */
    public function getStatus($statusCode)
    {
        $status = '';

        switch ($statusCode) {
            case 'S':
                $status = 'success';
                break;
            case 'F':
                $status = 'failure';
                break;
            case 'P':
                $status = 'pending';
                break;
            case 'U':
                $status = 'unknown';
                break;
            case 'R':
                $status = 'refund';
                break;
            case 'K':
                $status = 'chargeback';
                break;
            case 'V':
                $status = 'void';
                break;
            case 'A':
                $status = 'authorized';
                break;
        }

        return $status;
    }

    /**
     * Get the status of a transaction cancellation.
     *
     * @param $statusCode
     * @return string
     */
    public function getCancellationStatus($statusCode)
    {
        switch ($statusCode) {
            case 0:
                return 'success';
                break;
            default:
                return 'failed';
                break;
        }
    }

    /**
     * Get the transaction error.
     *
     * @param $errorCode
     * @return string
     */
    public function getError($errorCode)
    {
        $error = '';

        switch ($errorCode) {
            case 000:
                $error = 'success';
                break;
            case 101:
                $error = 'invalid payment gateway id';
                break;
            case 102:
                $error = 'incorrect secret key';
                break;
            case 103:
                $error = 'invalid reference number';
                break;
            case 104:
                $error = 'unauthorized access';
                break;
            case 105:
                $error = 'invalid token';
                break;
            case 106:
                $error = 'currency not supported';
                break;
            case 107:
                $error = 'transaction cancelled';
                break;
            case 108:
                $error = 'insufficient funds';
                break;
            case 109:
                $error = 'transaction limit exceeded';
                break;
            case 110:
                $error = 'error in operation';
                break;
            case 111:
                $error = 'invalid parameters';
                break;
            case 201:
                $error = 'invalid merchant id';
                break;
            case 202:
                $error = 'invalid merchant password';
                break;
        }

        return $error;
    }

    /**
     * Determine if transaction is successful.
     *
     * @param $message
     * @param $digest
     * @param $status
     * @return bool
     */
    public function isSuccessful($message, $digest, $status)
    {
        return sha1($message) == $digest && $status == 'success';
    }

}