<?php

namespace App\Http\Controllers;

class ResponseFormatter
{
	protected static $response = [
		'meta' => [
			'message' => null,
			'status' => 'success'
		],
		'data' => null
	];

	public static function success($data = null, $message = null)
	{
		self::$response['meta']['message'] = $message;
		self::$response['data'] = $data;

		return response()->json(self::$response['data'], 200);
	}

	public static function error($message = null, $code = 400)
	{
		self::$response['meta']['status'] = 'error';
		self::$response['meta']['code'] = $code;
		self::$response['meta']['message'] = $message;

		return response()->json(self::$response['meta'], self::$response['meta']['code']);
	}
}
