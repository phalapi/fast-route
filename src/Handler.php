<?php
namespace PhalApi\FastRoute;

use PhalApi\Response;

interface Handler {

	public function excute(Response $response);
}
