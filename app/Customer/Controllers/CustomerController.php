<?php

namespace App\Customer\Controllers;

use App\Customer\Models\Customer;
use App\Customer\Requests\CustomerCreateRequest;
use App\Customer\Requests\CustomerUpdateRequest;
use App\Customer\Resources\CustomerResource;
use App\Customer\Services\CustomerService;
use App\Shared\Controllers\Controller;
use App\Shared\Requests\GetAllRequest;
use App\Shared\Resources\GetAllCollection;
use App\Shared\Services\SharedService;
use Illuminate\Http\JsonResponse;
use DB;

class CustomerController extends Controller
{
    protected CustomerService $customerService;
    protected SharedService $sharedService;

    public function __construct(CustomerService $customerService, SharedService $sharedService)
    {
        $this->customerService = $customerService;
        $this->sharedService = $sharedService;
    }

    public function create(CustomerCreateRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $newCustomer = $this->sharedService->convertCamelToSnake($request->validated());
            $this->customerService->create($newCustomer);
            DB::commit();
            return response()->json(['message' => 'Customer created.'], 201);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function delete(Customer $customer): JsonResponse
    {
        DB::beginTransaction();
        try {
            $customerValidated = $this->customerService->validate($customer, 'Customer');
            $this->customerService->delete($customerValidated);
            DB::commit();
            return response()->json(['message' => 'Customer deleted.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }

    public function get(Customer $customer): JsonResponse
    {
        $customerValidated = $this->customerService->validate($customer, 'Customer');
        return response()->json(new CustomerResource($customerValidated));
    }

    public function getAll(GetAllRequest $request): JsonResponse
    {
        $query = $this->sharedService->query($request, 'Customer', 'Customer', 'name');
        return response()->json(new GetAllCollection(
            CustomerResource::collection($query['collection']),
            $query['total'],
            $query['pages'],
        ));
    }

    public function update(CustomerUpdateRequest $request, Customer $customer): JsonResponse
    {
        DB::beginTransaction();
        try {
            $editCustomer = $this->sharedService->convertCamelToSnake($request->validated());
            $customerValidated = $this->customerService->validate($customer, 'Customer');
            $this->customerService->update($customerValidated, $editCustomer);
            DB::commit();
            return response()->json(['message' => 'Customer updated.']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['error' =>  $e->getMessage()]);
        }
    }
}
