<?php

declare(strict_types=1);

namespace  Source\Infra\External\Transfer;

use Exception;
use Source\App\Contracts\CurlRequestInterface;
use Source\App\Contracts\TransferAuthorizer;
use Source\Infra\External\Transfer\Dto\TransferAuthorizerDto;

final class HttpTransferAuthorizer implements TransferAuthorizer
{
    public function __construct(
        private readonly CurlRequestInterface $http
    ) {}

    public function authorize(): bool
    {
        $validateOperation = $this->http->setBaseUrl("https://util.devi.tools/api/v2/authorize")->get();
        return TransferAuthorizerDto::fromArray($validateOperation->getResponse())->isAthorized();
    }
}