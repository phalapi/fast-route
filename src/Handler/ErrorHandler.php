<?php
namespace PhalApi\FastRoute\Handler;

use PhalApi\FastRoute\Handler;
use PhalApi\Response;

class ErrorHandler implements Handler {

	public function excute(Response $response) {
		$response->output();
		exit(0);
	}
}
