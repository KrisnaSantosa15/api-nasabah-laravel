<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
	public function index()
	{
		$customers = Customer::get()->makeHidden('point');
		if (count($customers) > 0) {
			return response()->json($customers, 200);
		} else {
			return ResponseFormatter::error('Data tidak ditemukan', 404);
		}
	}

	public function store(Request $request)
	{
		$data = $request->only('name');
		$validator = Validator::make($data, [
			'name' => 'required',
		]);

		if ($validator->fails()) {
			return ResponseFormatter::error('Pastikan field nama sudah diisi');
		} else {
			Customer::create($data);
			$data = Customer::latest()->first()->makeHidden('point');
			return ResponseFormatter::success($data, 'oke');
		}
	}
}
