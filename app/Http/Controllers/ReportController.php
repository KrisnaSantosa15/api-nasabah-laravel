<?php

namespace App\Http\Controllers;

use App\Models\transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
	public function getReports(Request $request)
	{
		$inputRequest = $request->only(['AccountId', 'StartDate', 'EndDate']);
		$validator = Validator::make($inputRequest, [
			'AccountId' => 'required',
			'StartDate' => 'required'
		]);

		$endDate = empty($inputRequest['EndDate']) ? date('Y-m-d') : date('Y-m-d', strtotime($inputRequest['EndDate']));
		$customerId = $inputRequest['AccountId'];
		$Found = transaction::where('customer_id', $customerId)->get();
		$startDate = date('Y-m-d', strtotime($inputRequest['StartDate']));

		if ($validator->fails()) {
			return ResponseFormatter::error('Pastikan semua field sudah diisi dengan tepat');
		} else {
			if (count($Found) > 0) {
				$transaction = Transaction::where('customer_id', $customerId)->whereBetween('transaction_date', [$startDate, $endDate])->get()->makeHidden(['updated_at', 'created_at', 'id', 'customer_id']);

				$data = [];
				$debit = 0;
				$credit = 0;
				foreach ($transaction as $key => $ts) {
					if ($ts->status == 'C') {
						$credit = $ts->amount;
						$debit = 0;
					} elseif ($ts->status == 'D') {
						$debit = $ts->amount;
						$credit = 0;
					}
					array_push($data, [
						'TransactionDate' => $ts->transaction_date,
						'Description' => $ts->description,
						'Credit' => $credit,
						'Debit' => $debit,
						'amount' => $ts->amount
					]);
				}

				return ResponseFormatter::success($data);
			} else {
				return ResponseFormatter::error("Transaksi dengan AccountId ${customerId} tidak ditemukan", 404);
			}
		}
	}
}
