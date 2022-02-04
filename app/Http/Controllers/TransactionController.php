<?php

namespace App\Http\Controllers;

use App\Models\customer;
use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
	public function index()
	{
		$transactionData = Transaction::get()->makeHidden(['created_at', 'updated_at']);
		if (!count($transactionData) > 0) {
			return ResponseFormatter::error('Data tidak ditemukan', 404);
		}
		return ResponseFormatter::success($transactionData);
	}

	public function store(Request $request)
	{

		$data = $request->only(['customer_id', 'transaction_date', 'description', 'status', 'amount']);
		$validator = Validator::make($data, [
			'customer_id' => 'required',
			'transaction_date' => 'required',
			'description' => 'required',
			'status' => ['required', Rule::in(['c', 'd', 'C', 'D'])],
			'amount' => 'required',
		]);

		if ($validator->fails()) {
			return ResponseFormatter::error('Pastikan semua field sudah diisi dengan tepat, status hanya bisa diisi C atau D');
		} else {
			$Found = Customer::find($data['customer_id']);
			if ($Found) {
				$data['transaction_date'] = date('Y-m-d', strtotime($data['transaction_date']));
				$data['description'] = ucwords($data['description']);
				$data['status'] = ucwords($data['status']);
				$points = calculatePoints($data['description'], $data['amount']);
				$cstm = Customer::findorfail($data['customer_id']);
				$pointFixed = ($cstm->point + $points);
				$cstm->update(['point' => $pointFixed]);
				Transaction::create($data);
				return ResponseFormatter::success($data);
			} else {
				return ResponseFormatter::error("Customer dengan id ${data['customer_id']} tidak ditemukan", 404);
			}
		}
	}

	public function getAllPoints()
	{
		$cstm = Customer::selectRaw('id as AccountId, name as Name, point as TotalPoint')->get();
		if (count($cstm) > 0) {
			return ResponseFormatter::success($cstm);
		}
		return ResponseFormatter::error("Points Tidak ditemukan", 404);
	}

	public function getPointsById($customer_id)
	{
		$cstm = Customer::selectRaw('id as AccountId, name as Name, point as TotalPoint')->where('id', $customer_id)->get();
		if (count($cstm) > 0) {
			return ResponseFormatter::success($cstm);
		}
		return ResponseFormatter::error("Customer dengan id ${customer_id} tidak ditemukan", 404);
	}
}
