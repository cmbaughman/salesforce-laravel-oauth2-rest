<?php

namespace Frankkessler\Salesforce\Responses;

use Frankkessler\Salesforce\DataObjects\BaseObject;
use Frankkessler\Salesforce\DataObjects\SalesforceError;

class SalesforceBaseResponse extends BaseObject
{
    /**
     * @var bool
     */
    public $success;

    /**
     * @var int
     */
    public $http_status_code;

    /**
     * @var string
     */
    public $operation;

    /**
     * @var SalesforceError
     */
    public $error;

    /**
     * @var string
     */
    protected $raw_sfdc_errors;

    /**
     * @var string
     */
    public $api_limits;

    public function __construct($data = [])
    {
        if (isset($data['raw_sfdc_error'])) {
            $this->error = new SalesforceError(json_decode($data['raw_sfdc_error'], true));
        } else {
            $this->error = new SalesforceError([]);
        }

        if (isset($data['http_status'])) {
            $this->http_status_code = (int) $data['http_status'];
            unset($data['http_status']);
        }

        if (isset($data['sforce-limit-info'])) {
            $this->api_limits = $data['sforce-limit-info'];
            unset($data['sforce-limit-info']);
        }

        parent::__construct($data);

        if (!$this->success && $this->http_status_code >= 200 && $this->http_status_code <= 299) {
            $this->success = true;
        }
    }
}
