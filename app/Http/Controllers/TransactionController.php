<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;


class TransactionController extends Controller
{

    public function index()
    {
        $transaction = Transaction::orderBy('time', 'DESC')->get();

        $response = [
            'message' => 'List transaction order by time',
            'data' => $transaction
        ];

        return response()->json($response, Response::HTTP_OK);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $transaction = Transaction::create($request->all());
            $response = [
                'message' => 'Transaction created',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }


    public function show($id)
    {
        $transaction = Transaction::findOrFail($id);

        try {
            $response = [
                'message' => 'Transaction detail success',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }



    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $transaction = Transaction::findOrFail($id);
        try {
            $transaction->update($request->all());
            $response = [
                'message' => 'Transaction updated',
                'data' => $transaction
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }


    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);

        try {
            $transaction->delete();
            $response = [
                'message' => 'Transaction deleted',
            ];

            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json([
                'message' => "Failed " . $e->errorInfo
            ]);
        }
    }
}
